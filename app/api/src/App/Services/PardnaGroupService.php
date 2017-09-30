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
use App\Services\payments\manage\PaymentsManageService;

class PardnaGroupService extends BaseService
{

  protected $table = "pardnagroups";
  protected $memberTable = "pardnagroup_members";
  protected $paymentTable = "pardnagroup_payments";
  protected $schedulePaymentTable = "pardnaaccount_scheduled_payments";

  protected $slotTable = "pardnagroup_slots";
  protected $confirmedTable = "pardnagroup_confirmed";
  protected $invitationService;
  protected $paymentsManageService;
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

  public function setInvitationService(InvitationService  $invitationService) {
    $this->invitationService = $invitationService;
    return $this;
  }

  public function setPaymentsManageService(PaymentsManageService $paymentsManageService){
    $this->paymentsManageService = $paymentsManageService;
    return $this;
  }

  public function getInvitationService() {
    return $this->invitationService;
  }

  function validateDetails ($data){
    if (isset($data["slots"])){
      if($data["slots"] > count($this->charges)) {
        throw new \Exception("Number of slots is greater than charge slots " . count($this->charges));
      }
      if($data["slots"] > $this->maximumSlots) {
        throw new \Exception("Maximum number of slots is " . $this->maximumSlots);
      }
      if($data["slots"] < $this->minimumSlots) {
        throw new \Exception("Minimum number of slots is " . $this->minimumSlots);
      }
    }
    if (isset($data["amount"])){
      if($data["amount"] > $this->maximumAmount) {
        throw new \Exception("Maximum amount is " . $this->maximumAmount);
      }
      if($data["amount"] < $this->minimumAmount) {
        throw new \Exception("Minimum amount is " . $this->minimumAmount);
      }
    }
    if (isset($data["startdate"]) ){
      $startDate = new \DateTime($data['startdate']);
      if ($startDate < new \DateTime())
      {
        throw new \Exception("Date is in the past");
      }
    }
  }

  function save($data, $user)
  {

    $group = $this->getGroupFromRequest($data, $user);
    $this->validateDetails($group);
    $group = $this->appendCreatedModified($group);
    if($this->exists($group)) {
      throw new \Exception("Pardna group already exist");
    } else {
      $this->db->insert($this->table, $group);
      return $this->findById($this->db->lastInsertId());
    }

  }

  public function releaseUserSLots($slot, $user) {
    return $this->db->update($this->slotTable, array("claimed_date" => null, "claimant" => null), ["claimant" => $user->getMembershipNumber()]);
  }


  public function changeSlot($slot, $user) {
      $slot = $this->getSlot($slot["id"]);
      if($slot['claimant'] != null) {
        throw new \Exception("Slot is already claimed");
      }

      $this->addMember($user, $slot['pardnagroup_id']);
      $this->releaseUserSLots($slot, $user);
      return $this->db->update($this->slotTable, array("claimant" => $user->getMembershipNumber(), "claimed_date" => date("Y-m-d H:i:s")), ['id' => $slot["id"]]);
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

  public function getNextAvailableClaimPosition($groupId)
  {
    $groupSlots = $this->getGroupSlots($groupId);
    foreach ($groupSlots as $groupSlot) {
      if (! $groupSlot["claimed"]){
        return $groupSlot["position"];
      }
    }
    return null;
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
      //Need to make a call here to get the mandate status
      $status = $this->paymentsManageService->getMandateStatus($member["dd_mandate_id"]);
      if (! empty($status)){
        $response["status"] = strtoupper(str_replace("_", " ", $status));
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
        $status = $this->paymentsManageService->getMandateStatus($member["dd_mandate_id"]);
        if (! empty($status)){
          $members[$key]["payment_status"] = strtoupper(str_replace("_", " ", $status));
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

  public function modifySlots($id, $group){
    //Need to get the pardnagroup_slots for pardnagroup_id $id
    $slots = $this->getSlots($id);
    if ($slots && ! empty($slots)){
      //Need to only modify the pay_date
      foreach ($slots as $slot){
        $startDate = new \DateTime($group['startdate']);
        if($group["frequency"]  === "weekly") {
          $startDate->modify('+' . $slot["position"] . ' week');
        } else {
          $startDate->modify('+' . $slot["position"] . ' month');
        }
        $this->db->update($this->slotTable, array("pay_date" => $startDate->format('Y-m-d')), ['pardnagroup_id' => $id, 'position' => $slot["position"], 'claimant' => $slot["claimant"]]);
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

  public function getPardnagroupDetailsInvite($invitation){
    $members = $this->getMembersIncludingPaymentDetails($invitation["id"]);
    $invitation["members"] = $members;
    $invitation["enddate"] = $this->calculateEndDate($invitation["startdate"], $invitation["frequency"], $invitation["slots"]);
    return $invitation;
  }

  public function calculateEndDate($startDate, $interval, $slots){
    if (strcasecmp($interval, "monthly") == 0){
      return date('Y-m-d', strtotime($startDate . ' + ' . $slots . ' months'));
    } else if (strcasecmp($interval, "weekly") == 0){
      $numberOfDaysOffset = 7 * intval($slots);
      return date('Y-m-d', strtotime($startDate. ' + ' . $numberOfDaysOffset . ' days'));
    }
    return null;
  }

  public function saveGroupAndEmails($data, $user) {

    $group = $this->save($data, $user);
    $groupId = $group["id"];
    $emails = $this->getEmailsFromRequest($data);
    $this->getInvitationService()->saveInvitations($emails, "PARDNAGROUP", $groupId, "", $user->getId());
    $this->getInvitationService()->sendInviteEmails($user, $emails, $group["name"]);
    $this->createSlots($data["slots"], $group);
    $this->claimSlot($groupId, 1, $user);

    // $this->addMember($user, $groupId);
    return true;
  }

  public function modifyPardna($id, $data){
    //Check that all the details are valid
    $this->validateDetails($data);

    $group = $this->findById($id);
    $modified = false;
    $startdatemodified = false;

    if (isset($data["name"]) && ! empty($data["name"])){
      $group["name"] = $data["name"];
      $modified = true;
    }
    if (isset($data["slots"]) && ! empty($data["slots"])){
      $group["slots"] = $data["slots"];
      $modified = true;
    }
    if (isset($data["amount"]) && ! empty($data["amount"])){
      $group["amount"] = $data["amount"];
      $modified = true;
    }
    if (isset($data["frequency"]) && ! empty($data["frequency"])){
      $group["frequency"] = $data["frequency"];
      $modified = true;
    }
    if (isset($data["frequency"]) && ! empty($data["frequency"])){
      $group["frequency"] = $data["frequency"];
      $modified = true;
    }
    if (isset($data["startdate"]) && ! empty($data["startdate"])){
      $group["startdate"] = date("Y-m-d", strtotime($data["startdate"]));
      $modified = true;
      $startdatemodified = true;
    }

    if ($modified){
      $group = $this->appendModified($group);
      if ($startdatemodified){
        $this->modifySlots($id, $group);
      }
      return $this->db->update($this->table, $group, ['id' => $id]);
    }
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

  public function getInvitesForGroup($id)
  {
    return $this->invitationService->getInvitesForGroup($id);
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

  public function confirmPardna($pardna)
  {
    $paymentsetup = $this->userSetupPayment($pardna["id"]);
    if ($paymentsetup){
      $enddate = $this->calculateEndDate($pardna["startdate"], $pardna["frequency"], $pardna["slots"]);
      if (! $this->pardnaBeenConfirmed($pardna["id"], $pardna["startdate"], $enddate) && ! $this->doesAnotherPardnaExist($pardna["id"], $enddate)){
        $confirmreq = array(
          "pardnagroup_id" => $pardna["id"],
          "startdate" => $pardna["startdate"],
          "enddate" => $enddate
        );
        return $this->confirm($confirmreq);
      } else{
        throw new \Exception("Cannot create pardna at this time as it already exist and confirmed");
      }
    } else{
      throw new \Exception("Cannot create pardna at this time : Not all users have set up payments");
    }
  }

  public function userSetupPayment($id){
    //Need to make sure that all users have setup payment
    $members = $this->getMembers($id);
    foreach ($members as $key => $member) {
      if (empty($member["dd_mandate_id"])){
        return false;
      }
    }
    return true;
  }

  public function isUserAdmin($id, $user)
  {
    $group = $this->findById($id);
    if (! $group){
      throw new \Exception("Group not found");
    }
    if ($user->getId() == $group["admin"]) {
      return true;
    } else {
      return false;
    }
  }

  public function confirm($pardna)
  {
    $this->db->insert($this->confirmedTable, $pardna);
    return $this->findById($this->db->lastInsertId());
  }

  public function pardnaBeenConfirmed($id, $startdate, $enddate)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->confirmedTable} WHERE pardnagroup_id = ? AND startdate = ? AND enddate = ? LIMIT 1", array($id, $startdate, $enddate));
  }

  public function doesAnotherPardnaExist($id, $enddate)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->confirmedTable} WHERE pardnagroup_id = ? AND enddate < ? LIMIT 1", array($id, $enddate));
  }

  public function getDueScheduledPayments($date, $limit) {
      return $this->db->fetchAll("SELECT * FROM {$this->schedulePaymentTable} WHERE status = ? AND scheduled_date <= ? LIMIT " . $limit, array('SCHEDULED', $date));
  }

  public function getFailedDueScheduledPayments($date, $limit) {
      return $this->db->fetchAll("SELECT * FROM {$this->schedulePaymentTable} WHERE status = ? AND scheduled_date <= ? LIMIT " . $limit, array('FAILED', $date));
  }

  public function updateSuccessScheduledPayment($id, $reference, $response, $attempts) {
    return $this->db->update($this->schedulePaymentTable, array('attempts' => $attempts, "status" => "SUCCESS", 'reference' => $reference, 'response' => $response, 'payment_date' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")), ['id' => $id]);
  }

  public function updateFailedScheduledPayment($id, $attempts) {
    return $this->db->update($this->schedulePaymentTable, array('attempts' => $attempts, "status" => "FAILED", 'payment_date' => date("Y-m-d H:i:s"), 'modified' => date("Y-m-d H:i:s")), ['id' => $id]);
  }


  public function fetchAllRunningPardnas($limit)
  {
    return $this->db->fetchAll("SELECT * FROM {$this->table} LIMIT " . $limit);
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

  public function getSlot($id)
  {
    $data = $this->db->fetchAssoc("SELECT * FROM {$this->slotTable} WHERE id = ?", array($id));
    return $data ? $data : array();
  }

  public function getMember($groupId, $userId)
  {
    return $this->db->fetchAll("SELECT * FROM {$this->memberTable} WHERE user_id = ? AND group_id = ?  LIMIT 1", array($userId, $groupId));
  }



  public function getMemberByMemberId($member_id)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->memberTable} WHERE id = ?  LIMIT 1", array($member_id));
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
