<?php
/**
* Pardna groups
*
*/
namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controllers\AppController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentEventsController
{
  protected $service;

  public function __construct($service)
  {
      $this->service = $service;
  }

  public function processEvent($event_id)
  {
    $this->service->processEvent($event_id);
    return new JsonResponse(array("success" => true));
  }

}
