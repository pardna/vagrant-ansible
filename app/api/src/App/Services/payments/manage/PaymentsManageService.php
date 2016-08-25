<?php
namespace App\Services\payments\manage;
use App\Services\common\BaseService;
use App\Entity\SubscriptionResponseEntity;
use App\utils\GoCardlessProAPIUtils;

class PaymentsManageService extends BaseService
{
  protected $pardnaGroupService;

  protected $goCardlessProAPIUtils;

  protected $customerBankAccountService;

  protected $mandatesService;

  protected $paymentsService;

  protected $payoutsService;

  protected $refundsService;

  protected $subscriptionsService;

  public function __construct($subscriptionsService){
      $this->subscriptionsService = $subscriptionsService;
      $this->goCardlessProAPIUtils = new GoCardlessProAPIUtils();
  }

  public function setPardnagroupsService(PardnaGroupService $pardnaGroupService){
    $this->pardnaGroupService = $pardnaGroupService;
  }

  public function getReflectedValue($class, $key, $obj){
    $rp = new \ReflectionProperty('\GoCardlessPro\Resources'. $class , $key);
    $rp->setAccessible(true);
    return $rp->getValue($obj);
  }

  public function getSubscriptionResponse($response){
    $subscriptionResponseEntity = new SubscriptionResponseEntity();
    $obj_vars = get_class_vars(get_class($subscriptionResponseEntity));
    foreach ($obj_vars as $key => $value)
    {
        $subscriptionResponseEntity->$key = $this->getReflectedValue('\Subscription', $key, $response);
    }
    return $subscriptionResponseEntity;
  }

  //Returns customer using subscription_id
  public function getGoCardlessCustomerForSubscriptionId($subscription_id){
    return $this->subscriptionsService->getGoCardlessCustomerForSubscriptionId($subscription_id);
  }

  // This is going to be used to return all active subscriptions associated with the pardna group member
  public function getSubscription($member){
    $gc_customers = $this->subscriptionsService->getGoCardlessCustomer($member['id']);
    if (! empty($gc_customers)){
      $subscriptions = $this->subscriptionsService->getMandatesSubscriptions($gc_customers[0]['mandate_id']);
      $response = $this->subscriptionsService->get($subscriptions[0]['subscription_id']);
      $subscriptionResponse = $this->getSubscriptionResponse($response);
      return $subscriptionResponse;
    }
    return null;
  }

  // This is going to be used to cancel a subscription,
  public function cancelSubscription($subscription_id){
    $response = $this->subscriptionsService->cancel($subscription_id);
    $cancelSubscriptionResponse = $this->getSubscriptionResponse($response);
    $this->subscriptionsService->updateStatus($subscription_id, $cancelSubscriptionResponse->getStatus());
  }

  // This is going to be used to return gocardless customer bank accounts associated with a group
  public function getASingleCustomerBankAccounts($group, $member){

  }


}
