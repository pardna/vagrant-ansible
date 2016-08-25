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

}
