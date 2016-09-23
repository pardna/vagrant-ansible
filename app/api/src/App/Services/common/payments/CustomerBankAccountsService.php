<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class CustomerBankAccountsService extends BaseService
{

  protected $client;

  public function setGoCardlessProClient($client){
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

}
