<?php
namespace App\Entity;

class RefundEntity
{
  private $amount;
  private $metadata;
  private $reference;
  private $total_amount_confirmation;
  private $links;

  function setAmount($amount)
  {
    $this->amount = $amount;
  }

  function getAmount()
  {
    return $this->amount;
  }

  function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }

  function getMetadata()
  {
    return $this->metadata;
  }

  function setReference($reference)
  {
    $this->reference = $reference;
  }

  function getReference()
  {
    return $this->reference;
  }

  function setTotal_amount_confirmation($total_amount_confirmation)
  {
    $this->total_amount_confirmation = $total_amount_confirmation;
  }

  function getTotal_amount_confirmation()
  {
    return $this->total_amount_confirmation;
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
