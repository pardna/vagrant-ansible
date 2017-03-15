<?php
namespace App\Entity;

class MandateEntity
{
  public $id;
  public $created_at;
  public $reference;
  public $status;
  public $scheme;
  public $next_possible_charge_date;
  public $metadata;
  public $links;

  public function getId(){
    return $this->id;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function getCreated_at(){
    return $this->created_at;
  }

  public function setCreated_at($created_at){
    $this->created_at = $created_at;
  }

  public function getReference(){
    return $this->reference;
  }

  public function setReference($reference){
    $this->reference = $reference;
  }

  public function getStatus(){
    return $this->status;
  }

  public function setStatus($status){
    $this->status = $status;
  }

  public function getScheme(){
    return $this->scheme;
  }

  public function setScheme($scheme){
    $this->scheme = $scheme;
  }

  public function getNext_possible_charge_date(){
    return $this->next_possible_charge_date;
  }

  public function setNext_possible_charge_date($next_possible_charge_date){
    $this->next_possible_charge_date = $next_possible_charge_date;
  }

  public function getMetadata(){
    return $this->metadata;
  }

  public function setMetadata($metadata){
    $this->metadata = $metadata;
  }

  public function getLinks(){
    return $this->links;
  }

  public function setLinks($links){
    $this->links = $links;
  }

}
