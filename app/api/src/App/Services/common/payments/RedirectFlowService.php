<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class RedirectFlowService extends BaseService
{
    protected $client;
    protected $gc_customersTable = "gocardless_customers";
    protected $gc_mandatesTable = "gocardless_mandates";

    public function setGoCardlessProClient($client){
      $this->client = $client->client;
    }

    public function getRedirectFlowUrl($params)
    {
      return $this->client->redirectFlows()->create($params);
    }

    public function completeRedirectFlow($redirect_flow_id, $params)
    {
      return $this->client->redirectFlows()->complete($redirect_flow_id, $params);
    }

    public function storeGoCardlessCustomerDetails($details){
      if(!$this->gcCustomerExists($details["gc_customer_id"], $details["pardnagroup_member_id"])) {

        $member = array();
        $member["cust_id"] = $details["gc_customer_id"];
        $member["pardnagroup_member_id"] = $details["pardnagroup_member_id"];
        $member["cust_bank_account"] = $details["gc_cust_bank_account"];

        $mandate = array();
        $mandate["cust_id"] = $details["gc_customer_id"];
        $mandate["mandate_id"] = $details["mandate_id"];
        $member = $this->appendCreatedModified($member);
        $this->db->insert($this->gc_customersTable, $member);
        $this->db->insert($this->gc_mandatesTable, $mandate);
        return true;
      }
      return false;
    }

    public function gcCustomerExists($gcCustomerId, $pardnaGroupMemberId)
    {
      return $this->db->fetchAssoc("SELECT * FROM {$this->gc_customersTable} WHERE cust_id = ? AND pardnagroup_member_id = ?  LIMIT 1", array($gcCustomerId, $pardnaGroupMemberId));
    }

}
