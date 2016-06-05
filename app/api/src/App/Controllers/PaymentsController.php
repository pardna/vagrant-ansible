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
        $url = $this->service->createPaymentPlan($group);
        return new JsonResponse(array("payment_url" => $url));
      } else{
        return new JsonResponse(array("message" => "User does not have access to payments for this group" ));
      }
    } catch(\Exception $e) {
      throw new HttpException(409, "Error getting subscription url : " . $e->getMessage());
    }
  }

  public function confirmPayment(Request $request)
  {
    try {
      $data = $request->request->all();
      $urlParams = $this->getUrlParamsFromRequest($data);
      $bill = $this->service->confirmPaymentPlan($urlParams);
      return new JsonResponse($bill);
    } catch(\Exception $e) {
      var_dump($e);
      throw new HttpException(409, "Could not confirm payment : " . $e->getMessage());
    }
  }

  public function getUrlParamsFromRequest($data)
  {
    $urlParams = array();
    $urlParams["resource_id"] = $data["resource_id"];
    if(isset($data["status"]) && $data["status"]) {
      $urlParams["status"] = $data["status"];
    }
    $urlParams["resource_type"] = $data["resource_type"];
    $urlParams["resource_uri"] = $data["resource_uri"];
    $urlParams["signature"] = $data["signature"];
    return $urlParams;
  }


}
