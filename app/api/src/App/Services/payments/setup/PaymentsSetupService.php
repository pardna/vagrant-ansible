<?php
namespace App\Services\payments\setup;
use App\Services\common\BaseService;
use App\Entity\SubscriptionEntity;
use App\Services\common\payments\GoCardlessProService;
use App\utils\GoCardlessProAPIUtils;
use App\Entity\RedirectFlowEntity;

class PaymentsSetupService extends GoCardlessProService
{
    protected $pardnaGroupService;

    protected $goCardlessProAPIUtils;

    public function setPardnagroupsService(PardnaGroupService $pardnaGroupService){
      $this->pardnaGroupService = $pardnaGroupService;
      $this->goCardlessProAPIUtils = new GoCardlessProAPIUtils();
      return $this;
    }

    public function getReflectedValue($key, $obj){
      $rp = new \ReflectionProperty('\GoCardlessPro\Resources\RedirectFlow', $key);
      $rp->setAccessible(true);
      return $rp->getValue($obj);
    }

    public function getRedirectFlowResponse($response){
      $redirectFlowEntity = new RedirectFlowEntity();
      $obj_vars = get_class_vars(get_class($redirectFlowEntity));
      foreach ($obj_vars as $key => $value)
      {
          $redirectFlowEntity->$key = $this->getReflectedValue($key, $response);
      }
      return $redirectFlowEntity;
    }

    public function getRedirectUrl($token, $user, $group){
      $description = "To set up recurring payment for " . $group["name"] . " Pardna.";
      if (! $this->descriptionIsOfCorrectLength($description)){
        $description = "To set up payment for " . $group["name"] . "Pardna.";
      }
      $membership_number = $user->getMembershipNumber();
      $group_id = $group["id"];
      $success_redirect_url = "http://192.168.33.99/app/frontend/dist/#/payment/confirm?membership_number=" . $membership_number . "&group_id=" . $group_id;
      $response = $this->getRedirectFlowUrl([
        "params" => ["description" => $description,
                     "session_token" => $token,
                     "success_redirect_url" => $success_redirect_url]
      ]);

      return $this->getRedirectFlowResponse($response)->getRedirect_url();
    }

    public function descriptionIsOfCorrectLength($description){
      if (strlen($description) < 100)
      {
        return true;
      }
      return false;
    }

    public function completeReturnFromRedirectFlow($token, $redirect_flow_id, $pardnagroup_member)
    {
      $response = $this->completeRedirectFlow($redirect_flow_id, [
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
      $this->storeGoCardlessCustomerDetails($details);
    }

    public function createSubscription($data){

    }

    public function createSubscriptionRequest($data){
      $subscription = new SubscriptionEntity();
      $subscription->setAmount(strval($data["amount"]));
      $subscription->setName("Pardna " . $data["name"]);
      $subscription->setDescription("This is going to run for the Pardna " . $data["name"] . "." );
      //$subscription->setRedirect_uri('http://www.pardnermoney.com/index.php');
      $subscription->setRedirect_uri('http://192.168.33.99/app/frontend/dist/#/payment/confirm');
      $subscription->setCancel_uri('http://www.pardnermoney.com/index.php?title=Cancel+PardnerMoney+Send+To+Friends');
      //$subscription->setState('token="id_9SX5G36"');
      $subscription->setInterval_length('1');
      if (strcasecmp($data["frequency"], "monthly") == 0){
        $subscription->setInterval_unit("month");
      } else if (strcasecmp($data["frequency"], "weekly") == 0){
        $subscription->setInterval_unit("week");
      }
      $subscription->setStart_at($data["startdate"]);
      return $subscription;
    }

}
