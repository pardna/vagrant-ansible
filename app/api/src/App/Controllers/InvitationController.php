<?php
/**
* Pardna groups
*
*/
namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controllers\AppController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvitationController extends AppController
{
    public $pardnaGroupService;

    public function setPardnaGroupService($pardnaGroupService){
      $this->pardnaGroupService = $pardnaGroupService;
    }

    public function save(Request $request)
    {
      $user = $this->getUser();
      $data = $request->request->all();
      if ($this->userAuthoriedToInvite($user, $data)){
        try {
          $service = $this->app['invitation.service'];
          $data = $request->request->all();
          if (! isset($data["message"])){
            $data["message"] = "I would like to invite you to join pardna";
          }
          $emails = $service->explodeEmailString($data["emails"]);
          $service->saveInvitations($emails, "USER", $user->getId(), $data["message"], $user->getId());
          if (isset($data["group"])){
            $service->saveInvitations($emails, "PARDNAGROUP", $data["group"]["id"], $data["message"], $user->getId());
            $service->sendInviteEmails($user, $emails, $data["group"]["name"]);
          } else{
            $service->sendInviteEmails($user, $emails, null);
          }
          return new JsonResponse(array("message" => "Invitations sent"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Cannot send invitations : " . $e->getMessage());
        }
      } else{
        throw new HttpException(401,"Cannot send invitations : User is not authorized ");
      }
    }

    public function userAuthoriedToInvite($user, $data){
      if (! isset($data["group"])){
        //User is authorized to invite another user to pardna
        return true;
      } else{
        return $this->pardnaGroupService->isUserAdmin($data["group"]["id"], $user);
      }
    }

    public function readGroupInvitations(Request $request)
    {

        try {
          $user = $this->getUser();
          $service = $this->app['invitation.service'];
          $invitations = $service->getGroupInvitations($user->getEmail());
          foreach ($invitations AS $key => $invitation) {
            $invitations[$key] = $this->pardnaGroupService->getPardnagroupDetailsInvite($invitation);
          }
          return new JsonResponse($invitations);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

    }

    public function readUserInvitations(Request $request)
    {

        try {
          $user = $this->getUser();
          $service = $this->app['invitation.service'];
          $invitations = $service->getUserInvitations($user->getEmail());
          return new JsonResponse($invitations);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

    }

    public function acceptUserInvitation(Request $request)
    {
      $data = $request->request->all();
      $user = $this->getUser();
      $service = $this->app['invitation.service'];
      $service->setRelationshipService($this->app['relationship.service']);
      $invitation = $service->retrieveUserInvitation($data["id"], $user->getEmail());
      if ($invitation == null){
        throw new HttpException(403, "Invitation not found");
      }
      if ($invitation["accepted"]){
        throw new HttpException(409, "Invitation has already been accepted");
      }
      $service->acceptUserInvitation($invitation["id"], $user);
      return new JsonResponse(array("message" => "Successfully accepted user invitation"));
    }

    public function acceptGroupInvitation(Request $request)
    {
        $data = $request->request->all();
        $user = $this->getUser();
        $service = $this->app['invitation.service'];
        $invitation = $service->retrieveGroupInvitation($data["id"], $user->getEmail());
        if ($invitation != null){
          $position = $this->pardnaGroupService->getNextAvailableClaimPosition($invitation["type_id"]);
          if ($position != null){
            $this->pardnaGroupService->claimSlot($invitation["type_id"], $position, $user);
            $service->acceptInvitation($invitation["id"]);
            return new JsonResponse(array("message" => "Success"));
          } else {
            throw new HttpException(409,"All slots have been claimed, cannot join the pardna");
          }
        } else{
          throw new HttpException(403, "Invitation not found");
        }

    }

    public function ignoreUserInvitation(Request $request){
      $data = $request->request->all();
      $user = $this->getUser();
      $service = $this->app['invitation.service'];
      $invitation = $service->retrieveUserInvitation($data["id"], $user->getEmail());
      if ($invitation == null){
        throw new HttpException(403, "Invitation not found");
      }
      if ($invitation["accepted"]){
        throw new HttpException(409, "Invitation has already been accepted");
      }
      if ($invitation["ignored"]){
        throw new HttpException(409, "Invitation has already been ignored");
      }
      $service->ignoreInvitation($data["id"]);
      return new JsonResponse(array("message" => "Successfully ignored user invitation"));
    }

    public function ignoreGroupInvitation (Request $request){
      $data = $request->request->all();
      $user = $this->getUser();
      $service = $this->app['invitation.service'];
      $invitation = $service->retrieveGroupInvitation($data["id"], $user->getEmail());
      if ($invitation == null){
        throw new HttpException(403, "Invitation not found");
      }
      if ($invitation["accepted"]){
        throw new HttpException(409, "Invitation has already been accepted");
      }
      if ($invitation["ignored"]){
        throw new HttpException(409, "Invitation has already been ignored");
      }
      $service->ignoreInvitation($data["id"]);
      return new JsonResponse(array("message" => "Successfully ignored group invitation"));
    }

}
