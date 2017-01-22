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
  protected $slotTable = "pardnagroup_slots";
  protected $invitationService;
  protected $maximumSlots = 12;
  protected $minimumSlots = 4;
  protected $minimumAmount = 10;
  protected $maximumAmount = 500;
  protected $charges = array(
    1 => 6,
    2 => 5.5,
    3 => 5.0,
    4 => 4.5,
    5 => 4.0,
    6 => 3.5,
    7 => 3.0,
    8 => 2.5,
    9 => 2.0,
    10 => 1.5,
    11 => 1.0,
    12 => 0
  );

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
    if($group["slots"] > count($this->charges)) {
      throw new \Exception("Number of slots is greater than charge slots " . count($this->charges));
    }
    if($group["slots"] > $this->maximumSlots) {
      throw new \Exception("Maximum number of slots is " . $this->maximumSlots);
    }
    if($group["slots"] < $this->minimumSlots) {
      throw new \Exception("Minimum number of slots is " . $this->minimumSlots);
    }
    if($group["amount"] > $this->maximumAmount) {
      throw new \Exception("Maximum amount is " . $this->maximumAmount);
    }
    if($group["amount"] < $this->minimumAmount) {
      throw new \Exception("Minimum amount is " . $this->minimumAmount);
    }
    $startDate = new \DateTime($group['startdate']);
    if ($startDate < new \DateTime())
    {
      throw new \Exception("Date is in the past");
    }
    $group = $this->appendCreatedModified($group);
    if($this->exists($group)) {
      throw new \Exception("Pardna group already exist");
    } else {
      $this->db->insert($this->table, $group);
      return $this->findById($this->db->lastInsertId());
    }

  }

  public function claimSlot($groupId, $position, $user) {
      $slots = $this->getSlots($groupId);
      if($this->userHasClaimed($slots, $user)) {
        throw new \Exception("User already claimed a slot");
      }

      $slot = $this->getUnclaimedSlot($slots, $position);

      $this->addMember($user, $groupId);
      return $this->db->update($this->slotTable, array("claimant" => $user->getMembershipNumber(), "claimed_date" => date("Y-m-d H:i:s")), ['id' => $slot["id"]]);
  }

  public function userHasClaimed($slots, $user) {
    foreach ($slots as $key => $slot) {
      if($slot["claimant"] === $user->getMembershipNumber()) {
        return true;
      }
    }
    return false;
  }

  public function slotClaimed($slots, $position) {
    foreach ($slots as $key => $slot) {
      if($slot["position"] === $position && $slot["claimant"]) {
        return true;
      }
    }
    return false;
  }

  public function getGroupMemberIncludingPaymentDetails($user, $groupId)
  {
    $response;
    $member = $this->getMember($groupId, $user->getId())[0];
    if (empty($member["dd_mandate_id"])){
      $response["setup_completed"] = false;
    } else{
      $response["setup_completed"] = true;
      $response["mandate_id"] = $member["dd_mandate_id"];
      $response["allow_edit_payment"] = true;
      if (! empty($member["dd_mandate_status"])){
        $response["status"] = strtoupper(str_replace("_", " ", $member["dd_mandate_status"]));
      } else {
        $response["status"] = "AWAITING CONFIRM FROM PAYMENT PROVIDER";
      }
    }
    return $response;
  }

  public function getMembersIncludingPaymentDetails($id)
  {
    $members = $this->getMembers($id);
    foreach ($members as $key => $member) {
      if (empty($member["dd_mandate_id"])){
        $members[$key]["allow_choose_payment"] = true;
        $members[$key]["payment_status"] = "SETUP REQUIRED";
      } else{
        $members[$key]["allow_edit_payment"] = true;
        if (! empty($member["dd_mandate_status"])){
          $members[$key]["payment_status"] = strtoupper(str_replace("_", " ", $member["dd_mandate_status"]));
        } else {
          $members[$key]["payment_status"] = "AWAITING CONFIRM FROM PAYMENT PROVIDER";
        }
      }
    }
    return $members;
  }

  public function getUnclaimedSlot($slots, $position) {
    foreach ($slots as $key => $slot) {
      if($slot["position"] == $position) {
        if($slot["claimant"]) {
          throw new \Exception("Position already claimed");
        }
        return $slot;
      }
    }
    throw new \Exception("Position does not exist in slot");;
  }

  public function getChargePercent($position, $numberOfSlots) {
    $chargeSlots = count($this->charges);
    $key = ($chargeSlots+$position)-($numberOfSlots);
    if(!isset($this->charges[$key])) {
      throw new \Exception("Charge percentage slot " . $key . " does not exist");
    }
    return $this->charges[$key];
  }

  public function createSlots($numberOfSlots, $group) {
    if($numberOfSlots > 24) {
      $numberOfSlots = 24;
    }
    $startDate = new \DateTime($group['startdate']);
    if($numberOfSlots > 0) {


      for($i = 1; $i <= $numberOfSlots; $i++) {
        if($group["frequency"]  === "weekly") {
          $startDate->modify('+1 week');
        } else {
          $startDate->modify('+1 month');
        }
        $slot = array(
          "pardnagroup_id" => $group["id"],
          "position" => $i,
          "pay_date" => $startDate->format('Y-m-d'),
          "total_contribution" => $numberOfSlots*$group["amount"],
          "charge_percent" => $this->getChargePercent($i, $numberOfSlots),
        );

        $slot["charge_amount"] = ($slot["total_contribution"]*$slot["charge_percent"])/100;
        $slot["pay_amount"] = $slot["total_contribution"]-$slot["charge_amount"];

        $slot = $this->appendCreatedModified($slot);
        $this->db->insert($this->slotTable, $slot);

      }
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

    $group = $this->save($data, $user);
    $groupId = $group["id"];
    $emails = $this->getEmailsFromRequest($data);
    $this->getInvitationService()->saveInvitations($emails, "PARDNAGROUP", $groupId);
    $this->createSlots($data["slots"], $group);
    $this->claimSlot($groupId, 1, $user);
    // $this->addMember($user, $groupId);
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
    $group["slots"] = $data["slots"];
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

  public function getUser($id)
  {
    return $this->db->fetchAssoc("SELECT * FROM users WHERE id = ? LIMIT 1", array($id));
  }

  public function getSlots($groupId)
  {
    $data = $this->db->fetchAll("SELECT * FROM {$this->slotTable} WHERE pardnagroup_id = ?", array($groupId));
    return $data ? $data : array();
  }

  public function getMember($groupId, $userId)
  {
    return $this->db->fetchAll("SELECT * FROM {$this->memberTable} WHERE user_id = ? AND group_id = ?  LIMIT 1", array($userId, $groupId));
  }

  public function getMemberByMemberId($member_id)
  {
    return $this->db->fetchAll("SELECT * FROM {$this->memberTable} WHERE id = ?  LIMIT 1", array($member_id));
  }


  public function getMembers($id)
  {
    $data = $this->db->fetchAll("SELECT * FROM {$this->memberTable} WHERE group_id = ?", array($id));
    return $data ? $data : array();
  }

  public function getPayments($id)
  {
    $data = $this->db->fetchAll("SELECT * FROM {$this->paymentTable} WHERE group_id = ?", array($id));
    return $data ? $data : array();
  }

  function dd_mandate_setup_completed($group_id, $user_id)
  {
    if($this->memberExists($group_id, $user_id)) {
      return $this->db->update($this->memberTable, array("dd_mandate_setup" => 1), ['user_id' => $user_id, 'group_id' => $group_id]);
    } else{
      throw new HttpException(401,"User is not authorized to complete this operation");
    }
  }

  public function findByMemberId($id)
  {
    $groups = $this->db->fetchAll("SELECT g.*, m.user_id FROM {$this->table} g RIGHT JOIN {$this->memberTable} m ON g.id = m.group_id WHERE m.user_id = ?", array($id));
    foreach ($groups as $key => $value) {
      $groups[$key]["editable"] = $value["admin"] === $value["user_id"] ? true : false;
    }
    return $groups;
  }

  public function getGroupSlots($id)
  {
    $slots = $this->db->fetchAll("SELECT s.*, u.membership_number, u.fullname FROM {$this->slotTable} s LEFT JOIN users u ON s.claimant = u.membership_number WHERE s.pardnagroup_id = ?", array($id));
    foreach ($slots as $key => $value) {
      $slots[$key]["claimed"] = $value["claimant"] ? true : false;
    }
    return $slots;
  }


}
