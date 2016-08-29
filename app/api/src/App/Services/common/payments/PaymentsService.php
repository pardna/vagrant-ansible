<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class PaymentsService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
    $this->client = $client->client;
  }

  public function create($payment)
  {
    return $this->client->payments()->create($payment);
  }

  public function cancel($payment_id)
  {
    return $this->client->payments()->cancel($payment_id);
  }

  public function get($payment_id)
  {
    return $this->client->payments()->get($payment_id);
  }

  public function retry($payment_id)
  {
    return $this->client->payments()->retry($payment_id);
  }

  public function processEvent($event){
    $action = $event->getAction();
    if ($action == 'created' || $action == 'submitted'){
      $this->processNormalAction($event);
    } else if ($action == 'confirmed'){
      $this->processPaymentsCollected($event);
    } else if ($action == 'cancelled' || $action == 'failed'){
      $this->processFailedAction($event);
    } else if ($action == 'charged_back' || $action == 'chargeback_cancelled' || $action == 'chargeback_settled'){
      $this->processPaymentsChargebacks($event);
    } else if ($action == 'paid_out'){
      $this->processPaymentsPaidOut($event);
    } else if ($action == 'resubmission_requested'){
      $this->processPaymentsResubmission($event);
    }
  }

  private function processNormalAction($event){

  }

  private function processPaymentsCollected($event){
    
  }

  private function processFailedAction($event){

  }

  private function processPaymentsChargebacks($event){

  }

  private function processPaymentsPaidOut($event){

  }

  private function processPaymentsResubmission($event){

  }

}
