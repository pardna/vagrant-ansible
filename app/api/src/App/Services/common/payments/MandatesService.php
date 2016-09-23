<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class MandatesService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
    $this->client = $client->getClient();
  }

  public function cancel($mandate_id)
  {
    return $this->client->mandates()->cancel($mandate_id);
  }

  public function get($mandate_id)
  {
    return $this->client->mandates()->get($mandate_id);
  }

  public function processEvent($event){
    $action = $event->getAction();
    if ($action == 'created' || $action == 'submitted' || $action == 'active'){
      $this->processNormalAction($event);
    } else if ($action == 'cancelled' || $action == 'failed'){
      $this->processFailedAction($event);
    }
  }

  private function processNormalAction($event){

  }

  private function processFailedAction($event){
    //If mandate is cancelled check that this will not affect pardna
  }

}
