<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\Entity\PardnaGroupEntity;
use App\Entity\PardnaGroupMemberEntity;
use App\Entity\UserEntity;

class AccountService extends BaseService
{

  protected $table = "pardnaaccounts";
  protected $paymentTable = "pardnaaccount_payments";

  public function createCode($digits) {
    return str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
  }

  public function createAccountNumber() {
     $digits = 4;
     $code = $this->createCode($digits) . "-" . $this->createCode($digits) . "-" . $this->createCode($digits) ;
     $result = $this->db->fetchAssoc("SELECT code FROM {$this->table} WHERE code = ? LIMIT 1", array($code));
     if($result) {
       return $this->createAccountNumber();
     }
     return $code;
  }

  public function createAccount($user) {
    $account = array(
      "name" => "Current Account",
      "code" => $this->createAccountNumber(),
      "owner" => $user->getMembershipNumber(),
      "balance" => 0.00
    );

    $account = $this->appendCreatedModified($account);
    $this->db->insert($this->table, $account);
    return $this->db->lastInsertId();
  }

  public function deposit($user, $data) {
    $inserData = $this->createDataFromRequest($data);
    $account = $this->findByCode($inserData["account_code"]);
    if(!$account) {
      throw new \Exception("Invalid account : " . $inserData["account_code"]);
    }

    $this->db->insert($this->paymentTable, $inserData);
    $this->updateBalance($account);
  }

  public function createDataFromRequest($user, $data) {
    $return = array();
    $return["account_code"] = $data["account_code"];
    $return["reference"] = $data["reference"];
    if($return["amount"] > 0) {
      $return["amount"] = "credit";
    } else {
      $return["amount"] = "debit";
    }
    $return["user"] = $user->getMembershipNumber();
    $return["transaction_date"] = date("Y-m-d H:i:s");
    $return = $this->appendCreatedModified($return);
    return $return;
  }

  public function updateBalance($account) {
    $this->db->update($this->paymentTable, array("balance" => $this->getBalance()), array("id" => $account["id"]));
  }

  public function findByCode($code)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE code = ?  LIMIT 1", array($code));
  }

  public function getBalance($code)
  {
    $data = $this->db->fetchAssoc("SELECT sum(amount) AS amount FROM {$this->paymentTable} WHERE code = ?  LIMIT 1", array($code));
    if($data) {
      return $data["amount"];
    }
    return 0;
  }


}
