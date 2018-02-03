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
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class DirectDebitService extends BaseService
{

  protected $table = "directdebits";

  protected $keyString;

  public function setKeyString($keyString) {
    $this->keyString = $keyString;
  }

  public function getKeyString() {
    return $this->keyString;
  }

  public function create($account, $sortCode, UserEntity $user) {
    $key = Key::loadFromAsciiSafeString($this->getKeyString());
    $directDebit = $this->read($user);

    $directDebit["account"] = Crypto::encrypt($account, $key, $raw_binary = false);
    $directDebit["sortcode"] = Crypto::encrypt($sortCode, $key, $raw_binary = false);

    if(isset($directDebit["id"])) {
      $directDebit = $this->appendModified($directDebit);
      $this->update($directDebit);
    } else {
      $directDebit["user_id"] = $user->getId();
      $directDebit = $this->appendCreatedModified($directDebit);
      $this->insert($directDebit);
    }

    //echo $encryptedAccount . "\n";
    // echo $encryptedSortCode . "\n";
    // print_r($this->read($user));
    return $directDebit;
  }

  public function fetch(UserEntity $user) {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE user_id = ? LIMIT 1", array($user->getId()));
  }

  public function update($directDebit) {
    return $this->db->update($this->table, $directDebit, ['user_id' => $directDebit["user_id"]]);
  }

  public function insert($directDebit) {
    return $this->db->insert($this->table, $directDebit);
  }



  public function read(UserEntity $user) {

    $directDebit = $this->fetch($user);

    if($directDebit) {
      $key = Key::loadFromAsciiSafeString($this->getKeyString());
      $directDebit["account"] = Crypto::decrypt($directDebit["account"], $key, $raw_binary = false);
      $directDebit["sortcode"] = Crypto::decrypt($directDebit["sortcode"], $key, $raw_binary = false);
    }

    return $directDebit;
  }


}
