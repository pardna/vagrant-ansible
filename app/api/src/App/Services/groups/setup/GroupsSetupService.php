<?php
namespace App\Services\groups\setup;
use App\Services\common\BaseService;
use App\utils\exceptions\PardnaApiException;

class GroupsSetupService extends BaseService
{

  public function createPardnaGroup($pardnagroup)
  {
    $pardnagroup = $this->appendCreatedModified($pardnagroup);
    $this->db->insert("pardnagroups", $pardnagroup);
    return $this->db->lastInsertId();
  }

  public function addMembersToPardnaGroup($pardnagroupid, $request)
  {
    $created_by = $request['created_by'];
    $members = $request['members'];
    if ($created_by || $members){
      return $this->db->executeQuery($this->getSQLQueryForAddMembersToPardnaGroup($pardnagroupid, $created_by, $members));
    }
  }

  public function addEmailInviteesToTable($pardnagroupid, $inviteeEmails)
  {
    if ($inviteeEmails){
      foreach ($inviteeEmails as $inviteeEmail) {
        $existingInvitee = $this->getInviteeByIdAndGroupId($inviteeEmail, $pardnagroupid);
        if($existingInvitee) {
          //Update the invited_count of the invitee found
          $this->incrementInvitedCount($existingInvitee["id"]);
        } else{
          //Create a new entry into pardnagroup_members_invites table
          $invitee = array();
          $invitee["email"] = $inviteeEmail;
          $invitee['pardnagroup_id'] = $pardnagroupid;
          $invitee['invited_date'] = date('Y-m-d H:i:s');
          $invitee['invited_count'] = 1;
          $invitee['joined'] = 0;
          $this->db->insert("pardnagroup_members_invites", $invitee);
        }
      }
    }
  }

  function incrementInvitedCount($id)
  {
    return $this->db->executeQuery('UPDATE pardnagroup_members_invites set invited_count = invited_count + 1 WHERE id = ?', [$id]);
  }

  function getInviteeByIdAndGroupId($inviteeEmail, $pardnagroupid)
  {
    return $this->db->fetchAssoc("SELECT * FROM pardnagroup_members_invites WHERE email = ? AND pardnagroup_id = ? LIMIT 1", array($inviteeEmail, $pardnagroupid));
  }

  public function getSQLQueryForAddMembersToPardnaGroup($pardnagroupid, $created_by, $membership_numbers){
    $rows = $this->constructAddMembersRequest($pardnagroupid, $created_by, $membership_numbers);
    $aexplosion="";
    foreach($rows as $row)
    {
       $bexplosion = array();
       foreach($row as $value) {
          $bexplosion[] = "'" . $value . "'";
       }
       if ($aexplosion){
         $aexplosion = $aexplosion.",";
       }
       $aexplosion = $aexplosion."(".implode(',', $bexplosion).")";
    }
    return "INSERT INTO pardnagroup_members (`pardnagroup_id`, `membership_number`, `status`, `role_id`, `verified`) VALUES " .$aexplosion;
  }

  private function constructAddMembersRequest($pardnagroupid, $created_by, $membership_numbers){
    $rows=array();
    //Adding Pardna creator to members
    $row = array($pardnagroupid, $created_by, 'created', $this->getRoleId('creator'), 0);
    array_push($rows, $row);
    //Shuffle through the memberships
    if ($membership_numbers){
      foreach($membership_numbers as $membership_number) {
        $row = array($pardnagroupid, $membership_number, 'created', $this->getRoleId('member'), 0);
        array_push($rows, $row);
      }
    }
    return $rows;
  }

  private function getRoleId($role){
    if ($role === 'creator'){
      return 1;
    }
    if ($role === 'member'){
      return 2;
    }
  }
}
