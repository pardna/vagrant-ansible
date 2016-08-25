<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class PayoutsService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
    $this->client = $client->client;
  }

  public function getAll($params)
  {
    return $this->client->payouts()->list($params);
  }

  public function get($payout_id)
  {
    return $this->client->payouts()->get($payout_id);
  }

}
