<?php
namespace App\Services\groups\manage;
use App\Services\common\BaseService;
use App\utils\exceptions\PardnaApiException;
use App\Entity\PardnaGroupEntity;
use App\Entity\PardnaGroupMemberEntity;

class GroupsManageService extends BaseService
{
  public function getPardnaGroup($pardnagroupid){
    $pardnagroup = $this->getGroupById($pardnagroupid);
    if (!$pardnagroup){
      throw new PardnaApiException("Pardna group does not exist", 409, 409);
    }
    return $pardnagroup;
  }

  public function getPardnagroupsAssocWithUser($membernumber){
    $response = array();
    $pardnagroupmembers = $this->getGroupsByMembershipNumber($membernumber);
    if ($pardnagroupmembers){
      foreach ($pardnagroupmembers as $pardnagroupmember) {
        $pardnagroup = $this->getPardnaGroup($pardnagroupmember["pardnagroup_id"]);
        $pardnaGroupEntity = new PardnaGroupEntity();
        $pardnaGroupEntity->setName($pardnagroup["name"]);
        $pardnaGroupEntity->setId($pardnagroup["id"]);
        $response[] = $pardnaGroupEntity;
      }
    }
    return $response;
  }

  public function getPardnaGroupDetails($pardnagroup){
    $pardnaGroupEntity = new PardnaGroupEntity();
    //Step 1. Add name and id for the pardna group.
    $pardnaGroupEntity->setName($pardnagroup["name"]);
    $pardnaGroupEntity->setId($pardnagroup["id"]);
    //Step 2. Set members
    $pardnaGroupEntity->setMembers($this->getPardnaGroupMembers($pardnagroup["id"]));
    //Step 3. Set invitees
    $pardnaGroupEntity->setInvites($this->getPardnaGroupEmailInvites($pardnagroup["id"]));
    //Step 4. Set pardna history
    return $pardnaGroupEntity;
  }

  private function getPardnaGroupEmailInvites($pardnagroupid){
    $response = array();
    $groupEmailInvites = $this->getEmailInvitesByGroupId($pardnagroupid);
    if ($groupEmailInvites){
      foreach ($groupEmailInvites as $invitee) {
        $pardnaGroupMemberEntity = new PardnaGroupMemberEntity();
        $pardnaGroupMemberEntity->setEmail($invitee["email"]);
        $pardnaGroupMemberEntity->setIsMember(false);
        $pardnaGroupMemberEntity->setCanInviteMembers(false);
        if ($invitee["joined"] == "1"){
          $pardnaGroupMemberEntity->setJoinedPardna(true);
        } else if ($invitee["joined"] == "0"){
          $pardnaGroupMemberEntity->setJoinedPardna(false);
        }
        $response[] = $pardnaGroupMemberEntity;
      }
    }
    return $response;
  }

  private function getPardnaGroupMembers($pardnagroupid){
    $response = array();
    $pardnagroupmembers = $this->getPardnaGroupMembersByGroupId($pardnagroupid);
    if ($pardnagroupmembers){
      foreach ($pardnagroupmembers as $pardnagroupmember) {
        $pardnaGroupMemberEntity = new PardnaGroupMemberEntity();
        $pardnaGroupMemberEntity->setMembershipNumber($pardnagroupmember["membership_number"]);
        $pardnaGroupMemberEntity->setStatus($pardnagroupmember["status"]);
        $pardnaGroupMemberEntity->setIsMember(true);
        if ($pardnagroupmember["role_id"] == "1"){
          $pardnaGroupMemberEntity->setCanInviteMembers(true);
        } else if ($pardnagroupmember["role_id"] == "2"){
          $pardnaGroupMemberEntity->setCanInviteMembers(false);
        }
        $response[] = $pardnaGroupMemberEntity;
      }
    }
    return $response;
  }

  private function getEmailInvitesByGroupId($pardnagroupid){
    return $this->db->fetchAll("SELECT * FROM pardnagroup_members_invites WHERE pardnagroup_id = ?", array($pardnagroupid));
  }

  private function getPardnaGroupMembersByGroupId($pardnagroupid){
    return $this->db->fetchAll("SELECT * FROM pardnagroup_members WHERE pardnagroup_id = ?", array($pardnagroupid));
  }

  private function getGroupsByMembershipNumber($membernumber){
    return $this->db->fetchAll("SELECT * FROM pardnagroup_members WHERE membership_number = ?", array($membernumber));
  }

  private function getGroupById($pardnagroupid)
  {
    return $this->db->fetchAssoc("SELECT * FROM pardnagroups WHERE id = ? LIMIT 1", array($pardnagroupid));
  }



}
