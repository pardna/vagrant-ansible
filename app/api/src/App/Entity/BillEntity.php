<?php
namespace App\Entity;

class BillEntity
{
    //required The number of interval_units between payments.
    public $amount;
    //required ONLY for one off payments.
    public $merchant_id;
    //Brief description used to identify the payment, displayed to the user alongside the amount. Often useful for an invoice reference.
    public $name;
    public $description;
    public $redirect_uri;
    public $cancel_uri;
    public $state;

    public $bill_url;

    public function getAmount(){
  		return $this->amount;
  	}

  	public function setAmount($amount){
  		$this->amount = $amount;
  	}

  	public function getMerchant_id(){
  		return $this->merchant_id;
  	}

  	public function setMerchant_id($merchant_id){
  		$this->merchant_id = $merchant_id;
  	}

  	public function getName(){
  		return $this->name;
  	}

  	public function setName($name){
  		$this->name = $name;
  	}

  	public function getDescription(){
  		return $this->description;
  	}

  	public function setDescription($description){
  		$this->description = $description;
  	}

  	public function getRedirect_uri(){
  		return $this->redirect_uri;
  	}

  	public function setRedirect_uri($redirect_uri){
  		$this->redirect_uri = $redirect_uri;
  	}

  	public function getCancel_uri(){
  		return $this->cancel_uri;
  	}

  	public function setCancel_uri($cancel_uri){
  		$this->cancel_uri = $cancel_uri;
  	}

  	public function getState(){
  		return $this->state;
  	}

  	public function setState($state){
  		$this->state = $state;
  	}

  	public function getBill_url(){
  		return $this->bill_url;
  	}

  	public function setBill_url($bill_url){
  		$this->bill_url = $bill_url;
  	}

    public function __toArray(){
        return call_user_func('get_object_vars', $this);
    }

}
