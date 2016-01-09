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
    public function save(Request $request)
    {

        try {
          $service = $this->app['invitation.service'];
          $user = $this->getUser();
          $data = $request->request->all();
          $emails = $service->explodeEmailString($data["emails"]);
          $service->saveInvitations($emails, "USER", $user->getId(), $data["message"]);
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
        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $service = $this->app['invitation.service'];
          $service->setRelationshipService($this->app['relationship.service']);
          $service->acceptUserInvitation($data["id"], $user->getId());
          return new JsonResponse(array("message" => "Success"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

    }


}
