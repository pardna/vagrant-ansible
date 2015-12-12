<?php
namespace App\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEntity implements UserInterface {
  protected $email;
  protected $username;
  protected $password;
  protected $fullname;
  protected $salt;
  protected $isActive;
  protected $verified;
  protected $mobile;
  protected $membershipNumber;
  protected $loginCount;
  protected $roles  =  array('ROLE_USER');

  public function getUserapidetails() {
    return $this->userapidetails;
  }

  // public function setUserapidetails($userapidetails) {
  //    $this->userapi=$userapidetails;

  //    return $this;
  // }

  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  /**
  * Get id
  *
  * @return integer
  */
  public function getId()
  {
    return $this->id;
  }

  public function setVerified($verified) {
    $this->verified = $verified;
    return $this;
  }

  public function getVerified() {
    return $this->verified;
  }

  public function setMobile($mobile) {
    $this->mobile = $mobile;
    return $this;
  }

  public function getMobile() {
    return $this->mobile;
  }

  /**
  * Set password
  *
  * @param string $password
  * @return User
  */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
  * Get password
  *
  * @return string
  */
  public function getPassword()
  {
    return $this->password;
  }

  /**
  * Set name
  *
  * @param string $name
  * @return User
  */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
  * Get name
  *
  * @return string
  */
  public function getName()
  {
    return $this->name;
  }

  /**
  * Set username
  *
  * @param string $username
  * @return User
  */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
  * Get username
  *
  * @return string
  */
  public function getUsername()
  {
    return $this->username;
  }
  /**
  * @var string
  */
  private $isactive;


  /**
  * Set email
  *
  * @param string $email
  * @return User
  */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
  * Get email
  *
  * @return string
  */
  public function getEmail()
  {
    return $this->email;
  }

  /**
  * Set salt
  *
  * @param string $salt
  * @return User
  */
  public function setSalt($salt)
  {
    $this->salt = $salt;

    return $this;
  }

  /**
  * Get salt
  *
  * @return string
  */
  public function getSalt()
  {
    return $this->salt;
  }

  /**
  * Set isactive
  *
  * @param string $isactive
  * @return User
  */
  public function setIsactive($isactive)
  {
    $this->isactive = $isactive;

    return $this;
  }

  /**
  * Get isactive
  *
  * @return string
  */
  public function getIsactive()
  {
    return $this->isactive;
  }

  /**
  * Set fullname
  *
  * @param string $fullname
  * @return User
  */
  public function setFullname($fullname)
  {
    $this->fullname = $fullname;

    return $this;
  }



  /**
  * @inheritDoc
  */
  public function getFullname()
  {
    return $this->fullname;
  }

  /**
  * Set roles
  *
  * @param string $roles
  * @return User
  */
  public function setRoles($roles)
  {
    $this->roles = $roles;

    return $this;
  }



  /**
  * @inheritDoc
  */
  public function getRoles()
  {
    return $this->roles;
  }

  /**
  * @inheritDoc
  */
  public function eraseCredentials()
  {
  }

  public function setMembershipNumber($membershipNumber) {
    $this->membershipNumber = $membershipNumber;
    return $this;
  }

  public function getMembershipNumber() {
    return $this->membershipNumber;
  }

  public function setLoginCount($loginCount) {
    $this->loginCount = $loginCount;
    return $this;
  }

  public function getLoginCount() {
    return $this->loginCount;
  }

  /**
  * @see \Serializable::serialize()
  */
  public function serialize()
  {
    return serialize(array(
      $this->id,
      $this->username,
      $this->fullname
    ));
  }

  /**
  * @see \Serializable::unserialize()
  */
  public function unserialize($serialized)
  {
    list (
    $this->id,
    $this->username,
    $this->fullname,
    ) = unserialize($serialized);
  }


}
