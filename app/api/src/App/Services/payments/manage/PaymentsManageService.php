<?php
namespace App\Services\payments\manage;
use App\Services\common\BaseService;
use App\Entity\SubscriptionResponseEntity;
use App\utils\GoCardlessProAPIUtils;
use App\Entity\BankAccountEntity;

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

  public function __construct($subscriptionsService, $customerBankAccountService, $mandatesService){
      $this->subscriptionsService = $subscriptionsService;
      $this->customerBankAccountService = $customerBankAccountService;
      $this->mandatesService = $mandatesService;
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

  public function getBankAccountResponse($response){
    $bankaccount = new BankAccountEntity();
    $obj_vars = get_class_vars(get_class($bankaccount));
    foreach ($obj_vars as $key => $value)
    {
        $bankaccount->$key = $this->getReflectedValue('\CustomerBankAccount', $key, $response);
    }
    return $bankaccount;
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

  public function getBankAccountAssociatedWithMandate($user, $mandate_id)
  {
    $res = $this->mandatesService->getBankAccountAssociatedWithMandate($mandate_id);
    $bankaccounts = $this->getUserBankAccounts($user, $res["cust_bank_account"], true);
    return $bankaccounts[0];
  }

  public function getUserBankAccounts($user, $id){
    $customer_bank_accounts = array();
    if (isset($id)){
      $response = $this->getBankAccountResponse($this->customerBankAccountService->get($id));
      array_push($customer_bank_accounts, $response);
    } else{
      $gc_customers = $this->customerBankAccountService->getGoCardlessCustomerForUserId($user->getId());
      foreach ($gc_customers as $gc_customer) {
        $cust_bank_account_id = $gc_customer["cust_bank_account"];
        if (isset($cust_bank_account_id) && ! empty($cust_bank_account_id)){
          $response = $this->getBankAccountResponse($this->customerBankAccountService->get($cust_bank_account_id));
          array_push($customer_bank_accounts, $response);
        }
      }
    }
    return $customer_bank_accounts;
  }


}
