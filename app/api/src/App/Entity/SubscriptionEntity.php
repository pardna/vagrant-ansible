<?php
namespace App\Entity;

class SubscriptionEntity
{
  private $amount;

  private $currency;

  private $day_of_month;

  private $end_date;

  private $interval;

  private $interval_unit;

  private $metadata;

  private $name;

  private $start_date;

  private $links;

  function setAmount($amount)
  {
    $this->amount = $amount;
  }

  function getAmount()
  {
    return $this->amount;
  }

  function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  function getCurrency()
  {
    return $this->currency;
  }

  function setDay_of_month($day_of_month)
  {
    $this->day_of_month = $day_of_month;
  }

  function getDay_of_month()
  {
    return $this->day_of_month;
  }

  function setEnd_date($end_date)
  {
    $this->end_date = $end_date;
  }

  function getEnd_date()
  {
    return $this->end_date;
  }

  function setInterval($interval)
  {
    $this->interval = $interval;
  }

  function getInterval()
  {
    return $this->interval;
  }

  function setInterval_unit($interval_unit)
  {
    $this->interval_unit = $interval_unit;
  }

  function getInterval_unit()
  {
    return $this->interval_unit;
  }

  function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }

  function getMetadata()
  {
    return $this->metadata;
  }

  function setName($name)
  {
    $this->name = $name;
  }

  function getName()
  {
    return $this->name;
  }

  function setStart_date($start_date)
  {
    $this->start_date = $start_date;
  }

  function getStart_date()
  {
    return $this->start_date;
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
