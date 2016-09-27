<?php
namespace App\Entity;

class BankAccountEntity
{
  public $id;
  public $account_number_ending;
  public $account_holder_name;
  public $bank_name;
  public $currency;
  public $country_code;
  //public $customer_id;
  public $metadata;
  public $enabled;
  public $links;

  public function getId(){
    return $this->id;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function getAccount_number_ending(){
    return $this->account_number_ending;
  }

  public function setAccount_number_ending($account_number_ending){
    $this->account_number_ending = $account_number_ending;
  }

  public function getAccount_holder_name(){
    return $this->account_holder_name;
  }

  public function setAccount_holder_name($account_holder_name){
    $this->account_holder_name = $account_holder_name;
  }

  public function getBank_name(){
    return $this->bank_name;
  }

  public function setBank_name($bank_name){
    $this->bank_name = $bank_name;
  }

  public function getCurrency(){
    return $this->currency;
  }

  public function setCurrency($currency){
    $this->currency = $currency;
  }

  public function getCountry_code(){
    return $this->country_code;
  }

  public function setCountry_code($country_code){
    $this->country_code = $country_code;
  }

  // public function getCustomer_id(){
  //   return $this->customer_id;
  // }
  //
  // public function setCustomer_id($customer_id){
  //   $this->customer_id = $customer_id;
  // }

  public function getMetadata(){
    return $this->metadata;
  }

  public function setMetadata($metadata){
    $this->metadata = $metadata;
  }

  public function getEnabled(){
    return $this->enabled;
  }

  public function setEnabled($enabled){
    $this->enabled = $enabled;
  }

  public function getLinks(){
    return $this->links;
  }

  public function setLinks($links){
    $this->links = $links;
  }

}
