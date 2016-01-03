<?php
namespace App\Entity;

class PardnaGroupEntity{
  private $id;
  private $name;
  protected $members;
  protected $pardna_history;
  protected $invites;

  public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getMembers(){
		return $this->members;
	}

	public function setMembers($members){
		$this->members = $members;
	}

	public function getPardna_history(){
		return $this->pardna_history;
	}

	public function setPardna_history($pardna_history){
		$this->pardna_history = $pardna_history;
	}

	public function getInvites(){
		return $this->invites;
	}

	public function setInvites($invites){
		$this->invites = $invites;
	}

}
