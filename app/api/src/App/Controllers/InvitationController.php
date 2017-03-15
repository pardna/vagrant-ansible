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

        try {
          $service = $this->app['invitation.service'];
          $user = $this->getUser();
          $data = $request->request->all();
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
          throw new HttpException(409,"Cannot seng invitations : " . $e->getMessage());
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

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $service = $this->app['invitation.service'];
          $service->setRelationshipService($this->app['relationship.service']);
          $service->acceptUserInvitation($data["id"], $user);
          return new JsonResponse(array("message" => "Success"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

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
          } else{
            throw new HttpException(409,"All slots have been claimed, cannot join the pardna");
          }
        } else{
          throw new HttpException(401,"Operation not allowed, Invitation Not found");
        }

    }

    public function ignoreUserInvitation(Request $request){
      $data = $request->request->all();
      $service = $this->app['invitation.service'];
      $service->ignoreInvitation($data["id"]);
      return new JsonResponse(array("message" => "Successfully ignored user"));
    }

    public function ignoreGroupInvitation (Request $request){
      $data = $request->request->all();
      $service = $this->app['invitation.service'];
      $service->ignoreInvitation($data["id"]);
      return new JsonResponse(array("message" => "Successfully ignored group"));
    }

}
