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

class InvitationService extends BaseService
{

  protected $table = "invitations";
  protected $groupTable = "pardnagroups";
  protected $userTable = "users";

  protected $relationshipService;
  protected $mandrillMailService;
  protected $configService;

  public function setMandrillMailService($mandrillMailService){
		$this->mandrillMailService = $mandrillMailService;
	}

  public function getMandrillMailService(){
		return $this->mandrillMailService;
	}

  public function setConfigurationsService($configService){
    $this->configService = $configService;
  }

  public function getConfigurationsService(){
    return $this->configService;
  }

  public function setRelationshipService($service) {
    $this->relationshipService = $service;
  }

  public function getRelationshipService() {
    return $this->relationshipService;
  }

  public function saveInvitations($emails, $type, $typeId, $message = '', $invitorUserId)
  {
    foreach($emails AS $email) {
      $this->saveInvitation($email, $type, $typeId, $message, $invitorUserId);
    }
  }

  public function explodeEmailString($emailString, $delimeter = ",") {
    $emails = explode($delimeter, $emailString);
    foreach($emails AS $key => $email) {
      $emails[$key] = trim($email);
    }
    return $emails;
  }

  public function sendInviteEmails($user, $emails, $groupName){
    $base_url = $this->configService->getConfigValue('base_url');
    $register_endpt = $this->configService->getConfigValue('register_endpoint');
    $register_url = $base_url . $register_endpt;
    foreach($emails AS $key => $email) {
      if (isset($groupName) && $groupName != null){
        $this->mandrillMailService->inviteEmailToPardnaGroup($email, $user->getFullname(), $register_url, $groupName);
      } else{
        $this->mandrillMailService->inviteEmailToPardnaWebsite($email, $user->getFullname(), $register_url);
      }
    }
  }

  public function saveInvitation($email, $type, $typeId, $message = '', $invitorUserId) {
    $invitation = $this->createInvitationEntity($email, $type, $typeId, $message, $invitorUserId);
    if(!$this->exists($invitation)) {
      $this->db->insert($this->table, $invitation);
      return $this->db->lastInsertId();
    }
    return false;
  }

  public function createInvitationEntity($email, $type, $typeId, $message = '', $invitorUserId) {
    $invitation = array();
    $invitation["email"] = $email;
    $invitation["type"] = $type;
    $invitation["type_id"] = $typeId;
    $invitation["message"] = $message;
    $invitation["sent"] = 0;
    $invitation["invitor_user_id"] = $invitorUserId;
    $invitation = $this->appendCreatedModified($invitation);
    return $invitation;
  }

  public function getUserInvitations($email)
  {
    $invitations = $this->db->fetchAll("SELECT u.fullname, i.type_id, i.id AS invitation_id FROM {$this->table} i RIGHT JOIN {$this->userTable} u ON i.type_id = u.id WHERE i.email = ? AND i.accepted = 0 AND i.ignored = 0 AND i.type = 'USER'", array($email));
    return $invitations;
  }


  public function getGroupInvitations($email)
  {
    $invitations = $this->db->fetchAll("SELECT g.*, i.type_id, i.id AS invitation_id FROM {$this->table} i RIGHT JOIN {$this->groupTable} g ON i.type_id = g.id WHERE i.email = ? AND i.accepted = 0 AND i.ignored = 0 AND i.type = 'PARDNAGROUP'", array($email));
    return $invitations ? $invitations : array();
  }

  public function getGroupInvitationsByGroupId($id)
  {
    $invitations = $this->db->fetchAll("SELECT g.*, i.type_id, i.id AS invitation_id FROM {$this->table} i RIGHT JOIN {$this->groupTable} g ON i.type_id = g.id WHERE g.id = ? AND i.accepted = 0 AND i.ignored = 0 AND i.type = 'PARDNAGROUP'", array($id));
    return $invitations ? $invitations : array();
  }

  public function getInvitesForGroup($id) {
    $invitations = $this->db->fetchAll("SELECT i.id, i.email, i.type, i.type_id FROM {$this->table} i WHERE i.type_id = ? AND i.accepted = 0 AND i.ignored = 0 AND i.type = 'PARDNAGROUP'", array($id));
    return $invitations ? $invitations : array();
  }

  public function retrieveGroupInvitation($invitationId, $email) {
    $invitation = $this->findById($invitationId);
    if($invitation && $invitation["email"] == $email && $invitation["type"] == "PARDNAGROUP") {
      return $invitation;
    }
    return null;
  }

  public function retrieveUserInvitation($invitationId, $email){
    $invitation = $this->findById($invitationId, $email);
    if($invitation && $invitation["email"] == $email && $invitation["type"] == "USER") {
      return $invitation;
    }
    return null;
  }

  public function acceptUserInvitation($invitationId, $user) {
    $invitation = $this->findById($invitationId);
    if($invitation && $invitation["email"] == $user->getEmail() && $invitation["type"] == "USER") {
      $relationship = array(
        "user_1" => $user->getId(),
        "user_2" => $invitation["type_id"],
        "status" => "ACCEPTED"
      );
      $this->getRelationshipService()->save($relationship);
      $this->acceptInvitation($invitationId);
    }
  }

  public function findById($id)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1", array($id));
  }

  public function delete($id)
  {
    return $this->db->delete($this->table, array("id" => $id));
  }

  public function acceptInvitation($id)
  {
    return $this->db->update($this->table, array("accepted" => 1), ['id' => $id]);
  }

  public function ignoreInvitation($id){
    return $this->db->update($this->table, array("ignored" => 1), ['id' => $id]);
  }

  public function exists($invitation)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE email = ? AND type = ? AND type_id = ? LIMIT 1", array($invitation["email"], $invitation["type"], $invitation["type_id"]));
  }

}
