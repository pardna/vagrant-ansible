<?php
namespace App\Services\payments\setup;
use App\Services\common\BaseService;
use App\Entity\SubscriptionRequestEntity;
use App\Entity\SubscriptionResponseEntity;
use App\utils\GoCardlessProAPIUtils;
use App\Entity\RedirectFlowEntity;
use App\utils\exceptions\PaymentSetupException;
use App\Entity\MandateEntity;

class PaymentsSetupService
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

    protected $configService;

    public function __construct($configService, $redirectFlowService, $subscriptionsService, $mandatesService){
        $this->redirectFlowService = $redirectFlowService;
        $this->configService = $configService;
        $this->subscriptionsService = $subscriptionsService;
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

    public function getRedirectFlowResponse($response){
      $redirectFlowEntity = new RedirectFlowEntity();
      $obj_vars = get_class_vars(get_class($redirectFlowEntity));
      foreach ($obj_vars as $key => $value)
      {
          $redirectFlowEntity->$key = $this->getReflectedValue('\RedirectFlow', $key, $response);
      }
      return $redirectFlowEntity;
    }

    public function objectToArray($object){
      $obj_vars = get_object_vars($object);
      $array = array();
      foreach ($obj_vars as $key => $value)
      {
          if (isset($value)){
            $array[$key] = $value;
          }
      }
      return $array;
    }

    public function getMandateResponse($response){
      $mandate = new MandateEntity();
      $obj_vars = get_class_vars(get_class($mandate));
      foreach ($obj_vars as $key => $value)
      {
          $mandate->$key = $this->getReflectedValue('\Mandate', $key, $response);
      }
      return $mandate;
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

    public function getRedirectUrl($token, $user){
      $description = "This will set up a mandate onto which you will be able to set up payments when you join a group";
      $membership_number = $user->getMembershipNumber();

      $success_redirect_url = $this->configService->getConfigValue('gocardless_success_redirect_url');
      $success_redirect_url .= "?membership_number=" . $membership_number;
      $response = $this->redirectFlowService->getRedirectFlowUrl([
        "params" => ["description" => $description,
                     "session_token" => $token,
                     "success_redirect_url" => $success_redirect_url]
      ]);

      return $this->getRedirectFlowResponse($response)->getRedirect_url();
    }

    public function completeReturnFromRedirectFlow($user, $token, $redirect_flow_id)
    {
      $response = $this->redirectFlowService->completeRedirectFlow($redirect_flow_id, [
        "params" => ["session_token" => $token]
      ]);
      $this->storeGoCardlessCustomerInfo($user, $response);
    }

    public function storeGoCardlessCustomerInfo($user, $response)
    {
      $redirectFlow = $this->getRedirectFlowResponse($response);
      $details = array();
      $links = $redirectFlow->getLinks();
      $details["gc_customer_id"] = $links->customer;
      $details["user_id"] = $user->getId();
      $details["gc_cust_bank_account"] = $links->customer_bank_account;
      $details["mandate_id"] = $links->mandate;
      $this->redirectFlowService->storeGoCardlessCustomerDetails($details);
    }

    public function setUpPayment($member, $bank_account_id)
    {
      $gc_mandate = $this->mandatesService->getMandateAssociatedWithBankAccount($bank_account_id);
      $mandate_id = $gc_mandate["mandate_id"];
      $mandate_response = $this->mandatesService->get($mandate_id);
      $mandate = $this->getMandateResponse($mandate_response);
      $dd_mandate = array();
      $dd_mandate['id'] = $mandate->getId();
      $dd_mandate['status'] = $mandate->getStatus();
      $this->mandatesService->updatePardnaGroupMemberWithMandate($member['id'], $dd_mandate);
    }

    public function triggerPardnaGroupCreateMembersSubscriptions($group, $members){
      //Iterate through each member of the group creating a subscription
      $message = array();
      foreach($members as $member){
        try{
          $this->createSubscription($group, $member);
        } catch(PaymentSetupException $e){
          if ($e->getHttpResponseStatusEquivalentCode() != 409){
            array_push("Could not set up subscription for member " . $member . " because of " . $e);
          }
        }
      }

      if (! empty($message)){
        //Send a message to some sort of log systems
        $paymentSetupException = new PaymentSetupException("Could not setup subscription for the members in the array " . $message , 0, 409);
        throw $paymentSetupException;
      }
    }

    //This is going to create a payment plan/subscription to take payment for many customers at once
    //Returns the link which the customer will use to navigate to payment set up
    public function createSubscription($group, $member){
      $mandate_id = $member["dd_mandate_id"];
      //Need to investigate what to do with status
      $mandate_status = $member["dd_mandate_status"];

      if (isset($mandate_id)){
        if ($this->subscriptionsService->mandateHasSubscriptions($mandate_id)){
          $gocardless_subscriptions = $this->subscriptionsService->getMandatesSubscriptions($mandate_id);
          foreach ($gocardless_subscriptions as $gocardless_subscription) {
            $response = $this->subscriptionsService->get($gocardless_subscription['subscription_id']);
            $subscription = $this->getSubscriptionResponse($response);
            if ($subscription->getStatus() == 'active'){
              $paymentSetupException = new PaymentSetupException("Active subscription exist", 0, 409);
              throw $paymentSetupException;
            }
          }
        }

        $createSubscriptionRequest = $this->createSubscriptionRequest($group, $mandate_id);
        $createSubscriptionResponse = $this->subscriptionsService->create(["params" => $this->objectToArray($createSubscriptionRequest)]);
        $subscriptionResponse = $this->getSubscriptionResponse($createSubscriptionResponse);

        $getSubscriptionResponse = $this->subscriptionsService->get($subscriptionResponse->getId());
        $subscription = $this->getSubscriptionResponse($getSubscriptionResponse);

        $logSubscription = array();
        $logSubscription['mandate_id'] = $mandate_id;
        $logSubscription['subscription_id'] = $subscriptionResponse->getId();
        $logSubscription['status'] = $subscription->getStatus();

        return $this->subscriptionsService->logSubscriptionCreation($logSubscription);

      } else{
        $paymentSetupException = new PaymentSetupException("Mandate does not exist", 0, 401);
        throw $paymentSetupException;
      }
    }

    public function createSubscriptionRequest($group, $mandate_id){
      $subscription = new SubscriptionRequestEntity();
      $amount = $this->convertAmountIntoCentsOrPences($group["amount"]);
      $subscription->setAmount($amount);
      $subscription->setCurrency(strval($group["currency"]));
      $subscription->setDay_of_month($this->getDayOfMonth($group["startdate"]));
      if (strcasecmp($group["frequency"], "monthly") == 0){
        $subscription->setInterval_unit("monthly");
      } else if (strcasecmp($group["frequency"], "weekly") == 0){
        $subscription->setInterval_unit("weekly");
      }
      $subscription->setStart_date($group["startdate"]);
      $subscription->setEnd_date($this->calculateEndDate($group["startdate"], $group["frequency"], $group["slots"]));
      $subscription->setName("Pardna " . $group["name"]);
      $subscription->setInterval(1);
      $links = array();
      $links['mandate'] = $mandate_id;
      $subscription->setLinks($links);
      return $subscription;
    }

    public function getDayOfMonth($startDate){
      return 1;
    }

    public function convertAmountIntoCentsOrPences($amount){
      return bcmul(number_format($amount), 100);
    }

    public function calculateEndDate($startDate, $interval, $slots){
      if (strcasecmp($interval, "monthly") == 0){
        return date('Y-m-d', strtotime($startDate . ' + ' . $slots . ' months'));
      } else if (strcasecmp($interval, "weekly") == 0){
        $numberOfDaysOffset = 7 * intval($slots);
        return date('Y-m-d', strtotime($startDate. ' + ' . $numberOfDaysOffset . ' days'));
      }
      return null;
    }

}
