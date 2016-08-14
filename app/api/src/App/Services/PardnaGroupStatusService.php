<?php
namespace App\Services;

class PardnaGroupStatusService extends BaseService
{
  protected $pardnaGroupService;

  public function setPardnagroupsService(PardnaGroupService $pardnaGroupService){
    $this->pardnaGroupService = $pardnaGroupService;
    return $this;
  }

  public function getPardnaStatus($id){
    $group = $this->pardnagroupsService->findById($id);
    $group_stat
  }

  public function getGroupStatus($id){{
    $group = $this->db->fetchAll("SELECT groups.*, statuses.status FROM pardnagroups groups LEFT JOIN pardnagroup_status statuses ON groups.group_status_code = statuses.id WHERE groups.id = ?", array($id));
    return $group;
  }

  public function getUserRelatedGroupStatus($group_id, $user_id){
    $group = $this->getGroupStatus($group_id)
    $user_dependant_group_status_codes = array(2,3,4);
    $group_status_code = $group['group_status_code'];
    $group_status_decode = $group['status']
    if (in_array($group_status_code, $user_dependant_group_status_codes)){
      if ($this->hasUserSetupPayment($group_id, $user_id)){
        $group['group_status_code'] = 1;
        $group['status'] = 'Set up required';
      }
    }
    return $group;
  }

  public function hasUserSetupPayment($group_id, $user_id){
    $member = $this->pardnaGroupService->getMember($group_id, $user_id);
    if ($member['dd_mandate_setup'] == 1){
      return true;
    } else{
      return false;
    }
  }


}
