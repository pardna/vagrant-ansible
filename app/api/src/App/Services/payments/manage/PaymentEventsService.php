<?php
namespace App\Services\payments\manage;
use App\Services\BaseService;
use App\Entity\PaymentEventsEntity;
use App\utils\GoCardlessProAPIUtils;

class PaymentEventsService extends BaseService
{
  protected $eventsService;
  protected $mandatesService;
  protected $paymentsService;
  protected $subscriptionsService;
  protected $refundsService;
  protected $payoutsService;
  protected $goCardlessProAPIUtils;

  public function __construct($eventsService, $mandatesService, $paymentsService, $subscriptionsService, $refundsService, $payoutsService){
      $this->eventsService = $eventsService;
      $this->mandatesService = $mandatesService;
      $this->paymentsService = $paymentsService;
      $this->subscriptionsService = $subscriptionsService;
      $this->refundsService = $refundsService;
      $this->payoutsService = $payoutsService;
      $this->goCardlessProAPIUtils = new GoCardlessProAPIUtils();
  }

  public function getReflectedValue($class, $key, $obj){
    $rp = new \ReflectionProperty('\GoCardlessPro\Resources'. $class , $key);
    $rp->setAccessible(true);
    return $rp->getValue($obj);
  }

  public function getPaymentEventsResponse($response){
    $paymentEventsResponseEntity = new PaymentEventsEntity();
    $obj_vars = get_class_vars(get_class($paymentEventsResponseEntity));
    foreach ($obj_vars as $key => $value)
    {
        $paymentEventsResponseEntity->$key = $this->getReflectedValue('\Event', $key, $response);
    }
    return $paymentEventsResponseEntity;
  }

  public function hasEventBeenLogged($event_id){
    return $this->eventsService->hasEventBeenLogged($event_id);
  }

  public function processEvent($event_id){
    //Get event from GoCardless
    if (! $this->hasEventBeenLogged($event_id)){
      $response = $this->eventsService->get($event_id);
      $event = $this->getPaymentEventsResponse($response);
      $this->processResource($event);
      $this->eventsService->createEvent($this->getEventArray($event));
      $eventsLinks = $this->getEventLinks($event);
      foreach ($eventsLinks as $eventsLink) {
        $this->eventsService->createEventLinks($eventsLink);
      }
    }
  }

  private function processResource($event){
    $resource_type = $event->getResource_type();
    if ($resource_type == 'mandates'){
      $this->mandatesService->processEvent($event);
    } else if ($resource_type == 'payments'){
      $this->paymentsService->processEvent($event);
    } else if ($resource_type == 'subscriptions'){
      $this->subscriptionsService->processEvent($event);
    } else if ($resource_type == 'refunds'){
      $this->refundsService->processEvent($event);
    } else if ($resource_type == 'payouts'){
      $this->payoutsService->processEvent($event);
    }
  }

  private function getEventArray($event){
    $eventArray = array();
    $eventArray['event_id'] = $event->getId();
    $eventArray['created_at'] = $event->getCreated_at();
    $eventArray['action'] = $event->getAction();
    $eventArray['resource_type'] = $event->getResource_type();
    $eventArray['cause'] = $event->getDetails()->cause;
    $eventArray['details'] = $event->getDetails()->description;
    return $eventArray;
  }

  public function getEventLinks($event){
    $links = $event->getLinks();
    $linksArray = array();
    foreach ($links as $key => $value) {
      $link = array();
      $link['event_id'] = $event->getId();
      $link['link_type'] = $key;
      $link['link_value'] = $value;
      array_push($linksArray, $link);
    }
    return $linksArray;
  }
}
