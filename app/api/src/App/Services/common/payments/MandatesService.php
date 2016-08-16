<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class MandatesService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
    $this->client = $client->client;
  }

  public function cancel($mandate_id)
  {
    return $this->client->mandates()->cancel($mandate_id);
  }

  public function get($mandate_id)
  {
    return $this->client->mandates()->get($mandate_id);
  }

}
