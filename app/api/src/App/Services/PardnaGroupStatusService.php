<?php
namespace App\Services;

class PardnaGroupStatusService extends BaseService
{
  protected $pardnaGroupService;

  public function setPardnaGroupService(PardnaGroupService $pardnaGroupService){
    $this->pardnaGroupService = $pardnaGroupService;
    return $this;
  }

  public function getUserRelatedGroupStatus($user, $group_id){
    $response;
    $response["reason"] = array();
    $group = $this->pardnaGroupService->groupDetailsForUser($user, $group_id);
    if ($group){
      $nb_slots = $group["slots"];
      $members = $this->pardnaGroupService->getMembers($group_id);
      if (empty($members)){
        //return exception
        return array();
      } else{
        if (! $this->areAllTheSlotsTaken($nb_slots, $members)){
          $reason = $this->getStatusReason("EMPSL");
          array_push($response["reason"], $this->convertReasonIntoCodeDecodeArray($reason));
        } else {
          $allMembersSetupPayment = true;
          foreach ($members as $member){
            if (! $this->hasUserSetupPayment($group_id, $member["user_id"])){
              $allMembersSetupPayment = false;
            }
          }
        }

        if (isset($allMembersSetupPayment) && $allMembersSetupPayment){
          $response["status"] = $this->convertStatusIntoCodeDecodeArray($this->getStatus("RDSRT"));
        } else{
          $response["status"] = $this->convertStatusIntoCodeDecodeArray($this->getUserRelatedStatus($group, $user->getId()));
        }
      }
    }
    return $response;
  }

  public function getUserRelatedStatus($group, $user_id){
    if ($this->isPardnaStartDateAfterTodaysDate($group['startdate'])){
      if (! $this->hasUserSetupPayment($group["id"], $user_id)){
        $status = $this->getStatus("SETRQ");
      } else{
        $status = $this->getStatus("AWTNG");
      }
    } else{
      $status = $this->getStatus("OHOLD");
    }
    return $status;
  }

  public function areAllTheSlotsTaken($nb_slots, $members){
    if ($nb_slots == sizeof($members)){
        return true;
    } else{
      return false;
    }
  }

  public function convertStatusIntoCodeDecodeArray($status){
    $codeDecode = array();
    $codeDecode['code'] = $status['code'];
    $codeDecode['decode'] = $status['status'];
    return $codeDecode;
  }

  public function convertReasonIntoCodeDecodeArray($status){
    $codeDecode = array();
    $codeDecode['code'] = $status['code'];
    $codeDecode['decode'] = $status['reason'];
    return $codeDecode;
  }

  public function isPardnaStartDateAfterTodaysDate(){
    return true;
  }

  public function getGroupStatus($id){
    $group = $this->db->fetchAll("SELECT groups.*, statuses.code, statuses.status FROM pardnagroups groups LEFT JOIN pardnagroup_status statuses ON groups.group_status_code = statuses.id WHERE groups.id = ?", array($id));
    return $group;
  }

  public function getStatus($status_code){
    $status = $this->db->fetchAssoc("SELECT code, status FROM pardnagroup_status WHERE code = ? LIMIT 1", array($status_code));
    return $status;
  }

  public function getStatusReason($reason_code){
    $reason = $this->db->fetchAssoc("SELECT code, reason FROM pardnagroup_status_reasons WHERE code = ? LIMIT 1", array($reason_code));
    return $reason;
  }

  public function hasUserSetupPayment($group_id, $user_id){
    $member = $this->pardnaGroupService->getMember($group_id, $user_id);
    $mandate_id = $member[0]['dd_mandate_id'];
    if (isset($mandate_id) && ! empty($mandate_id)){
      return true;
    } else{
      return false;
    }
  }


}
