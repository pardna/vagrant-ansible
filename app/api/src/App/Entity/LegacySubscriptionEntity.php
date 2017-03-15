<?php
namespace App\Entity;

class LegacySubscriptionEntity extends BillEntity
{
    //required The number of interval_units between payments.
    public $interval_length;
    //required The unit of measurement for the interval. Can be day, week or month.
    public $interval_unit;
    //ISO8601 format.
    public $start_at;
    //ISO8601 format.
    public $expires_at;
    //Calculates the expires_at date based on the number of payments that you would like to collect from the subscription.
    public $interval_count;
    public $setup_fee;

    public $subscription_url;

    public function getInterval_length(){
  		return $this->interval_length;
  	}

  	public function setInterval_length($interval_length){
  		$this->interval_length = $interval_length;
  	}

  	public function getInterval_unit(){
  		return $this->interval_unit;
  	}

  	public function setInterval_unit($interval_unit){
  		$this->interval_unit = $interval_unit;
  	}

  	public function getStart_at(){
  		return $this->start_at;
  	}

  	public function setStart_at($start_at){
  		$this->start_at = $start_at;
  	}

  	public function getExpires_at(){
  		return $this->expires_at;
  	}

  	public function setExpires_at($expires_at){
  		$this->expires_at = $expires_at;
  	}

  	public function getInterval_count(){
  		return $this->interval_count;
  	}

  	public function setInterval_count($interval_count){
  		$this->interval_count = $interval_count;
  	}

  	public function getSetup_fee(){
  		return $this->setup_fee;
  	}

  	public function setSetup_fee($setup_fee){
  		$this->setup_fee = $setup_fee;
  	}

  	public function getSubscription_url(){
  		return $this->subscription_url;
  	}

  	public function setSubscription_url($subscription_url){
  		$this->subscription_url = $subscription_url;
  	}

}
