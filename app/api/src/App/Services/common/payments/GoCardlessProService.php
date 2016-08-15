<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class GoCardlessProService extends BaseService
{

    // Sandbox is the default - uncomment to change to production
    // GoCardless::$environment = 'production';

    protected $client;

    protected $gc_customersTable = "gocardless_customers";
    protected $gc_mandatesTable = "gocardless_mandates";

    public function __construct($db, $config, $gocardless_env)
    {
        // Initialize GoCardless
        $this->db = $db;
        $gocarless_env = $this->goCardlessEnvironment($gocardless_env['environment']);
        $access_token = $config['access_token'];
        $this->client = new \GoCardlessPro\Client(array(
          'access_token' => $access_token,
          'environment'  => $gocarless_env
        ));
    }

    public function goCardlessEnvironment($environment){
        if (isset($environment) && strcasecmp($environment, "live") == 0){
          return \GoCardlessPro\Environment::LIVE;
        } else {
          return \GoCardlessPro\Environment::SANDBOX;
       }
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
