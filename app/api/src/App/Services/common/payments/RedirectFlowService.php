<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class RedirectFlowService extends BaseService
{
    protected $client;
    protected $gc_customersTable = "gocardless_customers";
    protected $gc_mandatesTable = "gocardless_mandates";
    protected $configurationsTable = "configurations";

    public function setGoCardlessProClient($client){
      $this->client = $client->getClient();
    }

    public function getRedirectFlowUrl($params)
    {
      return $this->client->redirectFlows()->create($params);
    }

    public function completeRedirectFlow($redirect_flow_id, $params)
    {
      return $this->client->redirectFlows()->complete($redirect_flow_id, $params);
    }

    public function storeGoCardlessCustomerDetails($details)
    {
      if(!$this->gcCustomerExists($details["gc_customer_id"], $details["user_id"])) {

        $member = array();
        $member["cust_id"] = $details["gc_customer_id"];
        $member["user_id"] = $details["user_id"];

        $mandate = array();
        $mandate["cust_id"] = $details["gc_customer_id"];
        $mandate["mandate_id"] = $details["mandate_id"];
        $mandate["cust_bank_account"] = $details["gc_cust_bank_account"];
        $member = $this->appendCreatedModified($member);
        $this->db->insert($this->gc_customersTable, $member);
        $this->db->insert($this->gc_mandatesTable, $mandate);
        return true;
      }
      return false;
    }

    public function gcCustomerExists($gcCustomerId, $user_id)
    {
      return $this->db->fetchAssoc("SELECT * FROM {$this->gc_customersTable} WHERE cust_id = ? AND user_id = ?  LIMIT 1", array($gcCustomerId, $user_id));
    }

    public function getSuccesssRedirectUrl()
    {
      $res = $this->db->fetchAll("SELECT value FROM {$this->configurationsTable} WHERE name = 'gocardless_success_redirect_url'  LIMIT 1");
      return $res[0]["value"];
    }

}
