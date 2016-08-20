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

class PaymentsController extends AppController
{
  protected $groupService;

  protected $trackerService;

  protected $manageService;

  protected $pardnaGroupStatusService;

  public function setPardnaGroupStatusService($pardnaGroupStatusService){
    $this->pardnaGroupStatusService = $pardnaGroupStatusService;
  }

  public function setPardnaGroupsService($groupService){
    $this->groupService = $groupService;
  }

  public function setTrackerService($trackerService){
    $this->trackerService = $trackerService;
  }

  public function setManageService($manageService){
    $this->manageService = $manageService;
  }

  public function getGroupPaymentsSubscriptionUrl($id)
  {
    try {
      $user = $this->getUser();
      $group = $this->groupService->groupDetailsForUser($user, $id);
      if ($group){
        $token = $this->getSessionId($user, $id);
        $url = $this->service->getRedirectUrl($token, $user, $group);
        return new JsonResponse(array("payment_url" => $url));
      } else{
        return new JsonResponse(array("message" => "User does not have access to payments for this group" ));
      }
    } catch(\Exception $e) {
      throw new HttpException(409, "Error getting subscription url : " . $e->getMessage());
    }
  }

  public function completeRedirectFlow(Request $request)
  {
    try {
      $data = $request->request->all();
      $user = $this->getUser();
      if (strcmp($user->getMembershipNumber(), $data["membership_number"]) !== 0){
        throw new HttpException(401, "Could not confirm setting up of mandate");
      }
      $token = $this->getSessionId($user, $data["group_id"]);
      $pardnagroup_member = $this->groupService->getMember($data["group_id"], $user->getId());
      $response = $this->service->completeReturnFromRedirectFlow($token, $data["redirect_flow_id"], $pardnagroup_member);
      $this->groupService->dd_mandate_setup_completed($pardnagroup_member[0]["group_id"], $pardnagroup_member[0]["user_id"]);
      return new JsonResponse(array("message" => "Mandate Successfully created" ));
    } catch(\GoCardlessPro\Core\Exception\InvalidApiUsageException $e) {
      throw new HttpException(409, "Could not complete the redirect flow : " . $e->getMessage());
    } catch(\GoCardlessPro\Core\Exception\InvalidStateException $e) {
      throw new HttpException(409, "Could not complete the redirect flow : " . $e->getMessage());
    } catch(\Exception $e) {
      var_dump($e);
      throw new HttpException(409, "Could not confirm payment : " . $e->getMessage());
    }
  }

  public function getGroupStatus($id)
  {
    $user = $this->getUser();
    $status = $this->pardnaGroupStatusService->getPaymentStatusForGroupId($user, $id);
    return new JsonResponse($status);
  }

  public function areAllTheSlotsTaken($nb_slots, $members){
    if ($nb_slots > sizeof($members)){
        return false;
    } else{
      return true;
    }
  }

  protected function getSessionId($user, $group_id){
    return "SESS_" . base64_encode($user->getId() . $user->getFullName() . $user->getMembershipNumber() . $group_id);
  }

}
