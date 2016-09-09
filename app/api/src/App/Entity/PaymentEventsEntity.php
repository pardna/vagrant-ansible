<?php
namespace App\Entity;

class PaymentEventsEntity
{
  public $action;
  public $created_at;
  public $details;
  public $id;
  public $links;
  public $metadata;
  public $resource_type;

  public function setAction($action)
  {
  	$this->action = $action;
  }

  public function getAction()
  {
  	return $this->action;
  }

  public function setCreated_at($created_at)
  {
  	$this->created_at = $created_at;
  }

  public function getCreated_at()
  {
  	return $this->created_at;
  }

  public function setDetails($details)
  {
  	$this->details = $details;
  }

  public function getDetails()
  {
  	return $this->details;
  }

  public function setId($id)
  {
  	$this->id = $id;
  }

  public function getId()
  {
  	return $this->id;
  }

  public function setLinks($links)
  {
  	$this->links = $links;
  }

  public function getLinks()
  {
  	return $this->links;
  }

  public function setMetadata($metadata)
  {
  	$this->metadata = $metadata;
  }

  public function getMetadata()
  {
  	return $this->metadata;
  }

  public function setResource_type($resource_type)
  {
  	$this->resource_type = $resource_type;
  }

  public function getResource_type()
  {
  	return $this->resource_type;
  }

}
