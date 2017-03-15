<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class CustomerBankAccountsService extends BaseService
{

  protected $client;
  protected $gc_customersTable = "gocardless_customers";
  protected $gc_mandatesTable = "gocardless_mandates";

  public function setGoCardlessProClient($client)
  {
    $this->client = $client->getClient();
  }

  public function get($bank_account_id)
  {
    return $this->client->customerBankAccounts()->get($bank_account_id);
  }

  public function disable($bank_account_id)
  {
    return $this->client->customerBankAccounts()->disable($bank_account_id);
  }

  public function getGoCardlessCustomerForUserId($user_id)
  {
    $usr_bank_accounts = $this->db->fetchAll("SELECT c.*, m.* FROM {$this->gc_customersTable} c RIGHT JOIN {$this->gc_mandatesTable} m ON c.cust_id = m.cust_id WHERE c.user_id = ?", array($user_id));
    return $usr_bank_accounts;
  }

}
