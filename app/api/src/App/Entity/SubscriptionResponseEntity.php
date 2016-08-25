<?php
namespace App\Entity;

class SubscriptionResponseEntity
{
  public $amount;
  public $count;
  public $created_at;
  public $currency;
  public $day_of_month;
  public $end_date;
  public $id;
  public $interval;
  public $interval_unit;
  public $links;
  public $metadata;
  public $month;
  public $name;
  public $payment_reference;
  public $start_date;
  public $status;
  public $upcoming_payments;

  function setAmount($amount)
  {
  	$this->amount = $amount;
  }

  function getAmount()
  {
  	return $this->amount;
  }

  function setCount($count)
  {
  	$this->count = $count;
  }

  function getCount()
  {
  	return $this->count;
  }

  function setCreated_at($created_at)
  {
  	$this->created_at = $created_at;
  }

  function getCreated_at()
  {
  	return $this->created_at;
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

  function setId($id)
  {
  	$this->id = $id;
  }

  function getId()
  {
  	return $this->id;
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

  function setLinks($links)
  {
  	$this->links = $links;
  }

  function getLinks()
  {
  	return $this->links;
  }

  function setMetadata($metadata)
  {
  	$this->metadata = $metadata;
  }

  function getMetadata()
  {
  	return $this->metadata;
  }

  function setMonth($month)
  {
  	$this->month = $month;
  }

  function getMonth()
  {
  	return $this->month;
  }

  function setName($name)
  {
  	$this->name = $name;
  }

  function getName()
  {
  	return $this->name;
  }

  function setPayment_reference($payment_reference)
  {
  	$this->payment_reference = $payment_reference;
  }

  function getPayment_reference()
  {
  	return $this->payment_reference;
  }

  function setStart_date($start_date)
  {
  	$this->start_date = $start_date;
  }

  function getStart_date()
  {
  	return $this->start_date;
  }

  function setStatus($status)
  {
  	$this->status = $status;
  }

  function getStatus()
  {
  	return $this->status;
  }

  function setUpcoming_payments($upcoming_payments)
  {
  	$this->upcoming_payments = $upcoming_payments;
  }

  function getUpcoming_payments()
  {
  	return $this->upcoming_payments;
  }

}
