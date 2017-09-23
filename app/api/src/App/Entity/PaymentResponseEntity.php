<?php
namespace App\Entity;

class PaymentResponseEntity
{
  public $amount;
  public $currency;
  public $charge_date;
  public $metadata;
  public $reference;
  public $description;
  public $links;
  public $id;

  public function getAmount(){
  	return $this->amount;
  }

  public function setAmount($amount){
  	$this->amount = $amount;
  }

  public function getCurrency(){
  	return $this->currency;
  }

  public function setCurrency($currency){
  	$this->currency = $currency;
  }

  public function getCharge_date(){
  	return $this->charge_date;
  }

  public function setCharge_date($charge_date){
  	$this->charge_date = $charge_date;
  }

  public function getMetadata(){
  	return $this->metadata;
  }

  public function setMetadata($metadata){
  	$this->metadata = $metadata;
  }

  public function getReference(){
  	return $this->reference;
  }

  public function setReference($reference){
  	$this->reference = $reference;
  }

  public function getDescription(){
  	return $this->description;
  }

  public function setDescription($description){
  	$this->description = $description;
  }

  public function getLinks(){
  	return $this->links;
  }

  public function setLinks($links){
  	$this->links = $links;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }

}
