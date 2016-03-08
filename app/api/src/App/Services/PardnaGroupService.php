<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\Entity\UserEntity;

class PardnaGroupService extends BaseService
{

  protected $table = "pardnagroups";
  protected $memberTable = "pardnagroup_members";
  protected $paymentTable = "pardnagroup_payments";
  protected $invitationService;

  public function setInvitationService(InvitationService $invitationService) {
    $this->invitationService = $invitationService;
    return $this;
  }

  public function getInvitationService() {
    return $this->invitationService;
  }

  function save($data, $user)
  {

    $group = $this->getGroupFromRequest($data, $user);
    $group = $this->appendCreatedModified($group);
    if($this->exists($group)) {
      throw new \Exception("Pardna group already exist");
    } else {
      $this->db->insert($this->table, $group);
      return $this->db->lastInsertId();
    }

  }

  public function addMember($user, $groupId) {
    if(!$this->memberExists($groupId, $user->getId())) {
      $member = array();
      $member["email"] = $user->getEmail();
      $member["user_id"] = $user->getId();
      $member["fullname"] = $user->getFullname();
      $member["mobile"] = $user->getMobile();
      $member["group_id"] = $groupId;
      $member = $this->appendCreatedModified($member);
      $this->db->insert($this->memberTable, $member);
      return $this->db->lastInsertId();
    }
    return false;
  }



  public function saveGroupAndEmails($data, $user) {
    $groupId = $this->save($data, $user);
    $emails = $this->getEmailsFromRequest($data);
    $this->getInvitationService()->saveInvitations($emails, "PARDNAGROUP", $groupId);
    $this->addMember($user, $groupId);
    return true;
  }


  public function getGroupFromRequest($data, $user) {
    $group = array();
    $group["name"] = $data["name"];
    if(isset($data["id"]) && $data["id"]) {
      $group["id"] = $data["id"];
    }
    $group["admin"] = $user->getId();
    $group["amount"] = $data["amount"];
    $group["frequency"] = $data["frequency"];
    $group["startdate"] = date("Y-m-d", strtotime($data["startdate"]));
    return $group;
  }

  public function groupDetailsForUser($user, $groupId) {
    //var_dump($user->getId());
    //var_dump($groupId);
    if($this->memberExists($groupId, $user->getId())) {
      return $this->findById($groupId);
    }
    return false;
  }

  public function details($id) {
    $group = $this->findById($id);
    $group["members"] = $this->getMembers($id);
    $group["payments"] = $this->getPayments($id);
    $group["invitations"] = $this->invitationService->getGroupInvitationsByGroupId($id);
    return $group;
  }

  public function getEmailsFromRequest($data) {
    $emails = array();
    if($data["emails"]) {
      foreach($data["emails"] AS $email) {
        if($email) {
          $emails[] = $email;
        }
      }
    }
    return $emails;
  }

  public function exists($group)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE admin = ? AND name = ?  LIMIT 1", array($group["admin"], $group["name"]));
  }

  public function memberExists($groupId, $userId)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->memberTable} WHERE user_id = ? AND group_id = ?  LIMIT 1", array($userId, $groupId));
  }

  public function findById($id)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE id = ?  LIMIT 1", array($id));
  }

  public function getMembers($id)
  {
    $data = $this->db->fetchAll("SELECT * FROM {$this->memberTable} WHERE group_id = ?  LIMIT 1", array($id));
    return $data ? $data : array();
  }

  public function getPayments($id)
  {
    $data = $this->db->fetchAll("SELECT * FROM {$this->paymentTable} WHERE group_id = ?  LIMIT 1", array($id));
    return $data ? $data : array();
  }

  public function findByMemberId($id)
  {
    $groups = $this->db->fetchAll("SELECT g.*, m.user_id FROM {$this->table} g RIGHT JOIN {$this->memberTable} m ON g.id = m.group_id WHERE m.user_id = ?", array($id));
    foreach ($groups as $key => $value) {
      $groups[$key]["editable"] = $value["admin"] === $value["user_id"] ? true : false;
    }
    return $groups;
  }

}
