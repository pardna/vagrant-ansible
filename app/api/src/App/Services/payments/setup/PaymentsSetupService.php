<?php
namespace App\Services\payments\setup;
use App\Services\common\BaseService;
use App\Entity\SubscriptionEntity;
use App\Services\common\payments\GoCardlessProService;
use App\utils\GoCardlessProAPIUtils;
use App\Entity\RedirectFlow;

class PaymentsSetupService extends GoCardlessProService
{
    protected $pardnaGroupService;

    protected $goCardlessProAPIUtils;

    public function setPardnagroupsService(PardnaGroupService $pardnaGroupService){
      $this->pardnaGroupService = $pardnaGroupService;
      $this->goCardlessProAPIUtils = new GoCardlessProAPIUtils();
      return $this;
    }
    //This is going to create a payment plan to take payment for many customers at once
    //Returns the link which the customer will use to navigate to payment set up
    public function createPaymentPlan($data){
      $subscription = $this->createSubscriptionRequest($data);
      //var_dump(array_filter($subscription->__toArray()));
      $url = $this->getSubscriptionUrl(array_filter($subscription->__toArray()));
      return $url;
    }

    public function getReflectedValue($key, $obj){
      $rp = new \ReflectionProperty('\GoCardlessPro\Resources\RedirectFlow', $key);
      $rp->setAccessible(true);
      return $rp->getValue($obj);
    }

    public function getRedirectUrl($token, $data){
      $description = "To set up recurring payment for " . $data["name"] . " Pardna.";
      if (! $this->descriptionIsOfCorrectLength($description)){
        $description = "To set up payment for " . $data["name"] . "Pardna.";
      }
      $response = $this->getRedirectFlowUrl([
        "params" => ["description" => $description,
                     "session_token" => $token,
                     "success_redirect_url" => "http://192.168.33.99/app/frontend/dist/#/payment/confirm"]
      ]);

      $this->goCardlessProAPIUtils = new GoCardlessProAPIUtils();
      $redirectFlow = new RedirectFlow();
      $obj_vars = get_class_vars(get_class($redirectFlow));
      foreach ($obj_vars as $key => $value)
      {
          $redirectFlow->$key = $this->getReflectedValue($key, $response);
      }
      return $redirectFlow->getRedirect_url();
    }

    public function descriptionIsOfCorrectLength($description){
      if (strlen($description) < 100)
      {
        return true;
      }
      return false;
    }

    public function confirmPaymentPlan($urlParams){
      $bill = $this->confirmGoCardlessRessource(array_filter($urlParams));
      return $bill;
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
