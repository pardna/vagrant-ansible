<?php
namespace App\Services\payments\manage;
use App\Services\common\BaseService;

class PaymentsManageService extends BaseService
{
  protected $pardnaGroupService;

  protected $goCardlessProAPIUtils;

  protected $customerBankAccountService;

  protected $mandatesService;

  protected $paymentsService;

  protected $payoutsService;

  protected $redirectFlowService;

  protected $refundsService;

  protected $subscriptionsService;

  public function __construct($redirectFlowService, $subscriptionsService){
      $this->redirectFlowService = $redirectFlowService;
      $this->subscriptionsService = $subscriptionsService;
      $this->goCardlessProAPIUtils = new GoCardlessProAPIUtils();
  }

  public function setPardnagroupsService(PardnaGroupService $pardnaGroupService){
    $this->pardnaGroupService = $pardnaGroupService;
  }

  // This is going to be used to return all active subscriptions associated with the pardna group member
  public function getSubscriptions($group, $member){

  }

  // This is going to be used to cancel a subscription,
  public function cancelASubscription($subscription_id){

  }

  // This is going to be used to return gocardless customer bank accounts associated with a group
  public function getASingleCustomerBankAccounts($group, $member){

  }


}
