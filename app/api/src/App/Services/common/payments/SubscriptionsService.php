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
    $this->client = $client->getClient();
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

  public function getGoCardlessCustomerForSubscriptionId($subscription_id)
  {
    $gcMember = $this->db->fetchAll("SELECT g.*, m.mandate_id FROM {$this->gc_customersTable} g RIGHT JOIN {$this->gc_mandatesTable} m ON g.cust_id = m.cust_id RIGHT JOIN {$this->subscriptionsTable} s ON m.mandate_id = s.mandate_id WHERE s.subscription_id = ?", array($subscription_id));
    return $gcMember;
  }

  public function mandateHasSubscriptions($mandate_id){
    return $this->db->fetchAssoc("SELECT subscription_id FROM {$this->subscriptionsTable} WHERE mandate_id = ? LIMIT 1", array($mandate_id));
  }

  public function getMandatesSubscriptions($mandate_id){
    return $this->db->fetchAll("SELECT * FROM {$this->subscriptionsTable} WHERE mandate_id = ? LIMIT 1", array($mandate_id));
  }

  public function updateStatus($subscription_id, $status) {
    return $this->db->update($this->subscriptionsTable, array("status" => $status), ['subscription_id' => $subscription_id]);
  }

  public function logSubscriptionCreation($subscription){
    $subscription = $this->appendCreatedModified($subscription);
    return $this->db->insert($this->subscriptionsTable, $subscription);
  }

  public function processEvent($event){
    $action = $event->getAction();
    if ($action == 'created' || $action == 'payment_created' || $action == 'finished'){
      $this->processNormalAction($event);
    } else if ($action == 'cancelled'){
      $this->processFailedAction($event);
    }
  }

  private function processNormalAction($event){

  }

  private function processFailedAction($event){
    //If mandate is cancelled check that this will not affect pardna
  }

}
