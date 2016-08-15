<?php
namespace App\Services;

class PardnaGroupStatusService extends BaseService
{
  protected $pardnaGroupService;

  public function setPardnaGroupService(PardnaGroupService $pardnaGroupService){
    $this->pardnaGroupService = $pardnaGroupService;
    return $this;
  }

  public function getUserRelatedGroupStatus($group_id, $user_id){
    $groups = $this->getGroupStatus($group_id);
    $user_dependant_statuses = array(2,3,4);
    $group = $groups[0];
    $status["code"] = $group['group_status_code'];
    $status["decode"] = $group['status'];
    if (in_array($status["code"], $user_dependant_statuses)){
      if (! $this->hasUserSetupPayment($group_id, $user_id)){
        $status['code'] = 1;
        $status['decode'] = 'Set up required';
      }
    }
    return $status;
  }

  public function getGroupStatus($id){
    $group = $this->db->fetchAll("SELECT groups.*, statuses.status FROM pardnagroups groups LEFT JOIN pardnagroup_status statuses ON groups.group_status_code = statuses.id WHERE groups.id = ?", array($id));
    return $group;
  }

  public function hasUserSetupPayment($group_id, $user_id){
    $member = $this->pardnaGroupService->getMember($group_id, $user_id);
    if ($member[0]['dd_mandate_setup'] == 1){
      return true;
    } else{
      return false;
    }
  }


}
