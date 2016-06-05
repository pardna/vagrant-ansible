<?php
namespace App\Services\payments\setup;
use App\Services\common\BaseService;
use App\Entity\SubscriptionEntity;
use App\Services\common\payments\GoCardlessService;

class PaymentsSetupService extends GoCardlessService
{
    //This is going to create a payment plan to take payment for many customers at once
    //Returns the link which the customer will use to navigate to payment set up
    public function createPaymentPlan($data){
      $subscription = $this->createSubscriptionRequest($data);
      //var_dump(array_filter($subscription->__toArray()));
      $url = $this->getSubscriptionUrl(array_filter($subscription->__toArray()));
      return $url;
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
      if ($data["frequency"] == "monthly"){
        $subscription->setInterval_unit("month");
      } else if ($data["frequency"] == "weekly"){
        $subscription->setInterval_unit("week");
      }
      $subscription->setStart_at($data["startdate"]);
      return $subscription;
    }

}
