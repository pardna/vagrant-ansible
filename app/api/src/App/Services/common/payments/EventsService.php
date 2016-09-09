<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class EventsService extends BaseService
{

  protected $client;
  protected $gc_events = "gocardless_events";
  protected $gc_events_links = "gocardless_events_links";

  public function setGoCardlessProClient($client){
    $this->client = $client->client;
  }

  public function listAll($params)
  {
    return $this->client->events()->list($event_id);
  }

  public function get($event_id)
  {
    return $this->client->events()->get($event_id);
  }

  public function hasEventBeenLogged($event_id){
    return $this->db->fetchAssoc("SELECT * FROM {$this->gc_events} WHERE event_id = ? LIMIT 1", array($event_id));
  }

  public function createEvent($event){
    $this->db->insert($this->gc_events, $event);
    return $this->db->lastInsertId();
  }

  public function createEventLinks($event_links){
    $this->db->insert($this->gc_events_links, $event_links);
    return $this->db->lastInsertId();
  }

}
