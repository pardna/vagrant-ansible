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

class PardnaGroupController extends AppController
{
    protected $pardnaGroupStatusService;

    public function setPardnaGroupStatusService($pardnaGroupStatusService)
    {
      $this->pardnaGroupStatusService = $pardnaGroupStatusService;
    }

    public function save(Request $request)
    {

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $this->service->saveGroupAndEmails($data, $user);
          return new JsonResponse(array("message" => "Group saved"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Cannot save group account : " . $e->getMessage());
        }

    }

    public function read(Request $request)
    {
        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $groups = $this->service->findByMemberId($user->getId());
          foreach ($groups as $key => $value) {
            $statusAndReason = $this->pardnaGroupStatusService->getUserRelatedGroupStatus($user, $groups[$key]["id"]);
            $members = $this->service->getMembersIncludingPaymentDetails($groups[$key]["id"]);
            $slots = $this->service->getGroupSlots($groups[$key]["id"]);
            $groups[$key]["members"] = $members;
            $groups[$key]["member_key"] = $this->getMemberKey($members, $user->getId());
            $groups[$key]["status"] = $statusAndReason["status"];
            $groups[$key]["reason"] = $statusAndReason["reason"];
            $enddate = $this->service->calculateEndDate($groups[$key]["startdate"], $groups[$key]["frequency"], $groups[$key]["slots"]);
            $groups[$key]["pardna_confirmed"] = $this->service->pardnaBeenConfirmed($groups[$key]["id"], $groups[$key]["startdate"], $enddate);;
            $groups[$key]["pardna_slots"] = $slots;
            $groups[$key]["enddate"] = $enddate;
            $invites = $this->service->getInvitesForGroup($groups[$key]["id"]);
            $groups[$key]["invites"] = $invites;
            $invitees = array();
            foreach ($invites as $invite) {
              array_push($invitees, $invite["email"]);
            }
            $groups[$key]["invitee_emails"] = $invitees;
          }
          return new JsonResponse($groups);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }
    }

    public function getMemberKey($members, $user_id)
    {
      foreach ($members as $key => $member) {
        if ($member["user_id"] == $user_id){
          return $key;
        }
      }
    }

    public function details($id)
    {

        try {
          // $data = $request->request->all();
          $user = $this->getUser();
          $group = $this->service->details($id);
          return new JsonResponse($group);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting list : " . $e->getMessage());
        }

    }

    public function slots($id)
    {

        try {
          $slots = $this->service->getGroupSlots($id);
          return new JsonResponse($slots);
        } catch(\Exception $e) {
          throw new HttpException(409,"Error getting slots : " . $e->getMessage());
        }

    }

    public function addMember(Request $request)
    {

        try {
          $data = $request->request->all();
          $user = $this->getUser();
          $this->service->saveGroupAndEmails($data, $user);
          return new JsonResponse(array("message" => "Group saved"));
        } catch(\Exception $e) {
          throw new HttpException(409,"Cannot save group account : " . $e->getMessage());
        }

    }

    public function confirmPardna($id)
    {
      try {
        $user = $this->getUser();
        if ($this->service->isUserAdmin($id, $user)){
          $group = $this->service->findById($id);
          $this->service->confirmPardna($group);
          return new JsonResponse(array("message" => "Pardna confirmed"));
        } else{
          throw new HttpException(403,"Cannot confirm pardna : User is not admin");
        }
      } catch(\Exception $e) {
        throw new HttpException(409,"Cannot confirm pardna : " . $e->getMessage());
      }
    }

}
