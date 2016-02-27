<?php
namespace App\Entity;

class PardnaGroupMemberEntity{
  protected $name;
  protected $membershipNumber;
  protected $isMember;
  protected $email;
  protected $joinedPardna;
  protected $status;
  protected $canInviteMembers;

  public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getMembershipNumber(){
		return $this->membershipNumber;
	}

	public function setMembershipNumber($membershipNumber){
		$this->membershipNumber = $membershipNumber;
	}

	public function getIsMember(){
		return $this->isMember;
	}

	public function setIsMember($isMember){
		$this->isMember = $isMember;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getJoinedPardna(){
		return $this->joinedPardna;
	}

	public function setJoinedPardna($joinedPardna){
		$this->joinedPardna = $joinedPardna;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

  public function getCanInviteMembers(){
		return $this->canInviteMembers;
	}

	public function setCanInviteMembers($canInviteMembers){
		$this->canInviteMembers = $canInviteMembers;
	}
}
