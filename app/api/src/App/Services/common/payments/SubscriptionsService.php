<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class SubscriptionsService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
    $this->client = $client->client;
  }
  
  public function create($subscription)
  {
    return $this->client->subscriptions()->create($subscription);
  }

  public function cancel($subscription_id)
  {
    return $this->client->subscriptions()->cancel($subscription_id);
  }

  public function get($subscription_id)
  {
    return $this->client->subscriptions()->get($subscription_id);
  }

}
