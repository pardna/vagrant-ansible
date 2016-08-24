<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class SubscriptionsService extends BaseService
{

  protected $client;
  protected $gc_customersTable = "gocardless_customers";
  protected $gc_mandatesTable = "gocardless_mandates";
  protected $subscriptionsTable = "gocardless_subscriptions";

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

  public function getGoCardlessCustomer($member_id)
  {
    $gcMember = $this->db->fetchAll("SELECT g.*, m.mandate_id FROM {$this->gc_customersTable} g RIGHT JOIN {$this->gc_mandatesTable} m ON g.cust_id = m.cust_id WHERE g.pardnagroup_member_id = ?", array($member_id));
    return $gcMember;
  }

  public function mandateHasSubscriptions($mandate_id){
    return $this->db->fetchAssoc("SELECT subscription_id FROM {$this->subscriptionsTable} WHERE mandate_id = ? LIMIT 1", array($mandate_id));
  }

  public function getMandatesSubscriptions($mandate_id){
    return $this->db->fetchAll("SELECT * FROM {$this->subscriptionsTable} WHERE mandate_id = ? LIMIT 1", array($mandate_id));
  }

  public function logSubscriptionCreation($subscription){
    $this->appendCreatedModified($subscription);
    return $this->db->insert($this->subscriptionsTable, $subscription);
  }

}
