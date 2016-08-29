<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class RefundsService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
    $this->client = $client->client;
  }

  public function create($refund)
  {
    return $this->client->refunds()->create($refund);
  }

  public function get($refund_id)
  {
    return $this->client->refunds()->get($refund_id);
  }

  public function processEvent($event){

  }

}
