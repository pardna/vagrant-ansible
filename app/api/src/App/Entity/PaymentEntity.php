<?php
namespace App\Entity;

class PaymentEntity
{
  private $amount;
  private $charge_datge;
  private $currency;
  private $description;
  private $metadata;
  private $links;

  function setAmount($amount)
  {
    $this->amount = $amount; 
  }

  function getAmount()
  {
    return $this->amount;
  }

  function setCharge_datge($charge_datge)
  {
    $this->charge_datge = $charge_datge;
  }

  function getCharge_datge()
  {
    return $this->charge_datge;
  }

  function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  function getCurrency()
  {
    return $this->currency;
  }

  function setDescription($description)
  {
    $this->description = $description;
  }

  function getDescription()
  {
    return $this->description;
  }

  function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }

  function getMetadata()
  {
    return $this->metadata;
  }

  function setLinks($links)
  {
    $this->links = $links;
  }

  function getLinks()
  {
    return $this->links;
  }


}
