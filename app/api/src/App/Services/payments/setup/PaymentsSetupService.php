<?php
namespace App\Services\payments\setup;
use App\Services\common\BaseService;
use App\Entity\SubscriptionRequestEntity;
use App\Entity\SubscriptionResponseEntity;
use App\utils\GoCardlessProAPIUtils;
use App\Entity\RedirectFlowEntity;
use App\utils\exceptions\PaymentSetupException;

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

    public function __construct($redirectFlowService, $subscriptionsService){
        $this->redirectFlowService = $redirectFlowService;
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

    public function getSubscriptionResponse($response){
      $subscriptionResponseEntity = new SubscriptionResponseEntity();
      $obj_vars = get_class_vars(get_class($subscriptionResponseEntity));
      foreach ($obj_vars as $key => $value)
      {
          $subscriptionResponseEntity->$key = $this->getReflectedValue('\Subscription', $key, $response);
      }
      return $subscriptionResponseEntity;
    }

    public function getRedirectUrl($token, $user, $group){
      $description = "To set up recurring payment for " . $group["name"] . " Pardna.";
      if (! $this->goCardlessProAPIUtils->descriptionIsOfCorrectLength($description)){
        $description = "To set up payment for " . $group["name"] . "Pardna.";
      }
      $membership_number = $user->getMembershipNumber();
      $group_id = $group["id"];
      $success_redirect_url = "http://192.168.33.99/app/frontend/dist/#/payment/confirm?membership_number=" . $membership_number . "&group_id=" . $group_id;
      $response = $this->redirectFlowService->getRedirectFlowUrl([
        "params" => ["description" => $description,
                     "session_token" => $token,
                     "success_redirect_url" => $success_redirect_url]
      ]);

      return $this->getRedirectFlowResponse($response)->getRedirect_url();
    }

    public function completeReturnFromRedirectFlow($token, $redirect_flow_id, $pardnagroup_member)
    {
      $response = $this->redirectFlowService->completeRedirectFlow($redirect_flow_id, [
        "params" => ["session_token" => $token]
      ]);
      $this->storeGoCardlessCustomerInfo($response, $pardnagroup_member);
    }

    public function storeGoCardlessCustomerInfo($response, $pardnagroup_member){
      $redirectFlow = $this->getRedirectFlowResponse($response);
      $details = array();
      $links = $redirectFlow->getLinks();
      $details["gc_customer_id"] = $links->customer;
      $details["pardnagroup_member_id"] = $pardnagroup_member[0]['id'];
      $details["gc_cust_bank_account"] = $links->customer_bank_account;
      $details["mandate_id"] = $links->mandate;
      $this->redirectFlowService->storeGoCardlessCustomerDetails($details);
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
      $gocardless_customer = $this->subscriptionsService->getGoCardlessCustomer($member['id']);

      if ($gocardless_customer){
        $gc_customer = $gocardless_customer[0];
        if ($this->subscriptionsService->mandateHasSubscriptions($gc_customer['mandate_id'])){
          $gocardless_subscriptions = $this->subscriptionsService->getMandatesSubscriptions($gc_customer['mandate_id']);
          foreach ($gocardless_subscriptions as $gocardless_subscription) {
            $response = $this->subscriptionsService->get($gocardless_subscription['subscription_id']);
            $subscription = $this->getSubscriptionResponse($response);
            if ($subscription->getStatus() == 'active'){
              $paymentSetupException = new PaymentSetupException("Active subscription exist", 0, 409);
              throw $paymentSetupException;
            }
          }
        }

        $createSubscriptionRequest = $this->createSubscriptionRequest($group, $gc_customer);
        $createSubscriptionResponse = $this->subscriptionsService->create(["params" => $this->objectToArray($createSubscriptionRequest)]);
        $subscriptionResponse = $this->getSubscriptionResponse($createSubscriptionResponse);

        $getSubscriptionResponse = $this->subscriptionsService->get($subscriptionResponse->getId());
        $subscription = $this->getSubscriptionResponse($getSubscriptionResponse);

        $logSubscription = array();
        $logSubscription['mandate_id'] = $gc_customer['mandate_id'];
        $logSubscription['subscription_id'] = $subscriptionResponse->getId();
        $logSubscription['status'] = $subscription->getStatus();

        return $this->subscriptionsService->logSubscriptionCreation($logSubscription);

      } else{
        $paymentSetupException = new PaymentSetupException("Mandate does not exist", 0, 401);
        throw $paymentSetupException;
      }
    }

    public function createSubscriptionRequest($group, $gc_customer){
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
      $links['mandate'] = $gc_customer['mandate_id'];
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
