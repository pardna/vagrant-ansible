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
use App\utils\exceptions\PaymentSetupException;

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
      //$group = $this->groupService->groupDetailsForUser($user, $id);
      //if ($group){
        $token = $this->getSessionId($user, $id);
        $group = null;
        $url = $this->service->getRedirectUrl($token, $user, $group);
        return new JsonResponse(array("payment_url" => $url));
      //} else{
      //  return new JsonResponse(array("message" => "User does not have access to payments for this group" ));
      //}
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
    $status = $this->pardnaGroupStatusService->getUserRelatedGroupStatus($user, $id);
    return new JsonResponse($status);
  }

  public function triggerMassSubscriptionCreation($id){
    try {
      $user = $this->getUser();
      $status = $this->pardnaGroupStatusService->getUserRelatedGroupStatus($user, $id);
      if ($this->isReadyForSetup($status)){
        $group = $this->groupService->groupDetailsForUser($user, $id);
        $members = $this->groupService->getMembers($id, $user->getId());
        $this->service->triggerPardnaGroupCreateMembersSubscriptions($group, $members);
      } else{
        throw new HttpException(401, "Could not create subscriptions : Some slots are empty");
      }
    } catch(PaymentSetupException $e) {
      throw new HttpException(401, "Could not set up payments for all users in group " . $e->getMessage());
    }
  }

  public function createSubscription($id){
    try{
      $user = $this->getUser();
      $group = $this->groupService->groupDetailsForUser($user, $id);
      $member = $this->groupService->getMember($id, $user->getId());
      if ($group && $member){
        $response =  $this->service->createSubscription($group, $member[0]);
        return new JsonResponse(array("message" => "Successfully created subscription"));
      } else{
        throw new HttpException(401, "User does not have access to payments for this group");
      }
    } catch(PaymentSetupException $e) {
      throw new HttpException($e->getHttpResponseStatusEquivalentCode(), "Could not create subscription : " . $e->getMessage());
    }
  }

  public function cancelSubscription($id){
    $user = $this->getUser();
    $gc_customers = $this->manageService->getGoCardlessCustomerForSubscriptionId($id);
    if (! empty($gc_customers)){
      $gc_customer = $gc_customers[0];
      $pardnamember_id = $gc_customer['pardnagroup_member_id'];
      $group_member = $this->groupService->getMemberByMemberId($pardnamember_id);
      if ($group_member[0]['user_id'] == $user->getId()){
        $response = $this->manageService->cancelSubscription($id);
        return new JsonResponse(array("message" => "Successfully cancelled subscription"));
      } else{
        throw new HttpException(401, "User does not have access to payments for this group");
      }
    } else{
      throw new HttpException(403, "Subscription does not exist");
    }
  }

  public function getSubscription($id){
    try{
      $user = $this->getUser();
      $group = $this->groupService->groupDetailsForUser($user, $id);
      $member = $this->groupService->getMember($id, $user->getId());
      if ($group && $member){
        $response =  $this->manageService->getSubscription($member[0]);
        if (isset($response)){
          return new JsonResponse($response);
        } else{
          return new JsonResponse(array("message" => "Subscription not found"));
        }
      } else{
        throw new HttpException(401, "User does not have access to payments for this group");
      }
    } catch(PaymentSetupException $e) {
      throw new HttpException($e->getHttpResponseStatusEquivalentCode(), "Could not create subscription : " . $e->getMessage());
    }
  }

  public function isReadyForSetup($status_object){
    $reasons = $status_object['reason'];
    $status = $status_object['status'];
    foreach ($reasons as $reason){
      if ($reason['code'] == 'EMPSL'){
        return false;
      }
    }

    if ($status['code'] == 'RDSRT'){
      return true;
    }
    return false;
  }

  protected function getSessionId($user, $group_id){
    return "SESS_" . base64_encode($user->getId() . $user->getFullName() . $user->getMembershipNumber() . $group_id);
  }

}
