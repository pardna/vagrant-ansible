<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\utils\exceptions\ValidationFailureException;
use App\Entity\PardnaGroupEntity;
use App\Entity\PardnaGroupMemberEntity;
use App\Entity\UserEntity;

class UsersService extends BaseService implements UserProviderInterface
{

  protected $twillioClient;
  protected $accountService;
  protected $emailValidatorService;

  public function __construct($db)
  {
      parent::__construct($db);
      $this->accountService = new AccountService($db);
  }

  public function setTwillioClient($twillioClient) {
    $this->twillioClient = $twillioClient;
  }

  public function getTwillioClient() {
    return $this->twillioClient;
  }

  public function setEmailValidatorService($emailValidatorService) {
    $this->emailValidatorService = $emailValidatorService;
  }

  public function getEmailValidatorService() {
    return $this->emailValidatorService;
  }


  public function createMembershipNumber() {
     $digits = 8;
     $no = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
     $atoZ = range('A','Z');
     $rand = mt_rand(0, count($atoZ)-1);
     $code = $atoZ[$rand] . $no;
     $result = $this->db->fetchAssoc("SELECT membership_number FROM users WHERE membership_number = ? LIMIT 1", array($code));
     if($result) {
       return $this->createMembershipNumber();
     }
     return $code;
  }

  public function getVerificationCode() {
     $digits = 4;
     return str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
  }

  public function validateResetPasswordToken($rPassToken){

  }

  public function getUserDetailsForMembers($pardnagroupdetails){
    foreach ($pardnagroupdetails->getMembers() as $member) {
      $user = $this->getByMembershipNumber($member->getMembershipNumber());
      $member->setName($user["fullname"]);
      $member->setEmail($user["email"]);
    }
  }

  public function loadUserByUsername($username)
  {
    $user = $this->getByEmail($username);

    if(!$user) {

      throw new UsernameNotFoundException("Username not found : " . $username);
    }

    $userEntity = new UserEntity();
    $userEntity->setEmail($user["email"])
    ->setUsername($user["email"])
    ->setSalt($user["salt"])
    ->setIsactive(true)
    ->setMembershipNumber($user["membership_number"])
    ->setLoginCount($user["login_count"])
    ->setFullname($user["fullname"])
    ->setPassword($user["password"])
    ->setMobile($user["mobile"])
    ->setVerified($user["verified"])
    ->setId($user["id"]);

    $userEntity->setFirstname($user["firstname"]);
    $userEntity->setLastname($user["lastname"]);

    return $userEntity;

  }

  public function loadUserById($id)
  {
    $user = $this->get($id);

    if(!$user) {

      throw new UsernameNotFoundException("Username not found : " . $username);
    }

    $userEntity = new UserEntity();
    $userEntity->setEmail($user["email"])
    ->setUsername($user["email"])
    ->setSalt($user["salt"])
    ->setIsactive(true)
    ->setMembershipNumber($user["membership_number"])
    ->setLoginCount($user["login_count"])
    ->setFullname($user["fullname"])
    ->setPassword($user["password"])
    ->setMobile($user["mobile"])
    ->setVerified($user["verified"])
    ->setId($user["id"]);

    return $userEntity;

  }

  public function createAccount($user) {
    $this->accountService->createAccount($user);
  }

  public function refreshUser(UserInterface $user)
  {
    $class = get_class($user);
    if (!$this->supportsClass($class)) {
        throw new UnsupportedUserException(
            sprintf(
                'Instances of "%s" are not supported.',
                $class
            )
        );
    }

    return $this->find($user->getId());
}

public function supportsClass($class)
{
    return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
}

  public function getAll()
  {
    return $this->db->fetchAll("SELECT * FROM users");
  }

  public function get($id)
  {
    return $this->db->fetchAssoc("SELECT * FROM users WHERE id = ? LIMIT 1", array($id));
  }

  public function getByMembershipNumber($membershipNumber)
  {
    return $this->db->fetchAssoc("SELECT * FROM users WHERE membership_number = ? LIMIT 1", array($membershipNumber));
  }

  public function getByEmail($email)
  {
    return $this->db->fetchAssoc("SELECT * FROM users WHERE email = ? LIMIT 1", array($email));
  }

  public function getResetLinkByCode($code)
  {
    return $this->db->fetchAssoc("SELECT * FROM reset_password_links WHERE reset_code = ? LIMIT 1", array($code));
  }


  function save($user)
  {

    $userExist = $this->getByEmail($user["email"]);
    if($userExist) {
      throw new HttpException(409,$user["email"] . " already registered");
    }

    $user = $this->appendPasswordDetails($user);
    $user["membership_number"] = $this->createMembershipNumber();
    $user = $this->appendCreatedModified($user);
    $this->db->insert("users", $user);
    return $this->db->lastInsertId();
  }

  private function appendPasswordDetails($user) {
    $encodePassword = $this->encodePassword($user["password"]);
    $user['password'] = $encodePassword["hash"];
    $user['salt'] = $encodePassword["salt"];
    return $user;
  }

  function incrementLoginCount($id)
  {
    return $this->db->executeQuery('UPDATE users set login_count = login_count + 1 WHERE id = ?', [$id]);
  }

  function update($id, $user)
  {
    $user = $this->appendModified($user);
    return $this->db->update('users', $user, ['id' => $id]);
  }

  function updateMobile($id, $mobile)
  {
    return $this->db->update('users', array("mobile" => $mobile), ['id' => $id]);
  }

  function updateVerificationCode($id, $code)
  {
    return $this->db->update('users', array("verification_code" => $code), ['id' => $id]);
  }

  function verify($id, $code, $mobile)
  {
    $user = $this->get($id);
    if(!$user) {
     throw new HttpException(401,"Invalid user");
    }
    if($user["verification_code"] === $code && $user["mobile"] === $mobile) {
      return $this->db->update('users', array("verified" => 1), ['id' => $id]);
    }
    throw new HttpException(401,"Invalid verification code");
  }

  function generateResetCodeForEmail($email)
  {
    $reset_link = array();
    $reset_code = $this->getUniqueResetCode();;
    $reset_link["reset_code"] = $reset_code;
    $reset_link["email"] = $email;
    $reset_link["reset_link"] = $this->emailValidatorService->generateResetPasswordLink($reset_code);
    $expires = new \DateTime('NOW');
    $expires->add(new \DateInterval('PT24H')); // 1 hour
    $reset_link['expires'] = $expires->format('Y-m-d H:i:s');
    $this->db->insert("reset_password_links", $reset_link);
    return $reset_link;
  }

  function validateResetPasswordCode($code){
    $reset = $this->getResetLinkByCode($code);
    if ($reset){
      if ($reset["reset_complete"] == 1){
        throw new ValidationFailureException("Reset code has already been used, it can no longer be used", 0, 403);
      }
      $now = new \DateTime('NOW');
      $expires = new \DateTime($reset["expires"]);
      if ($expires < $now){
        throw new ValidationFailureException("Reset code has expired, need to generate a new code", 0, 403);
      }
      return $reset;
    }
    return;
  }

  function getUniqueResetCode()
  {
    $attempt = 1;
    while ($attempt < 100){
      $code = $this->randomKey(30);
      if (! $this->getResetLinkByCode($code)){
        return $code;
      }
      $attempt++;
    }
  }

  public function passwordResetEmailSent($reset_code) {
    return $this->db->update('reset_password_links', array("email_sent" => 1), ['reset_code' => $reset_code]);
  }

  public function passwordResetComplete($code) {
    return $this->db->update('reset_password_links', array("reset_complete" => 1), ['reset_code' => $code]);
  }

  function randomKey($length)
  {
    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
    $key = '';
    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
  }

  function delete($id)
  {
    return $this->db->delete("users", array("id" => $id));
  }

  public function changePassword($email, $password) {
    $user = $this->getByEmail($email);
    if(!$user) {
      throw new HttpException(401,"Invalid user");
    }
    $user["password"] = $password;
    $user = $this->appendPasswordDetails($user);
    return $this->update($user["id"], $user);
  }

  public function activate($id) {
    return $this->db->update('users', array("active" => 1), ['id' => $id]);
  }

  public function deActivate($id) {
    return $this->db->update('users', array("active" => 0), ['id' => $id]);
  }

  public function authenticate($email, $password) {
    $user = $this->getByEmail($email);
    if(!$user) {
      throw new HttpException(401,"Invalid username or password");
    }


    $pToken = $this->prependToken($user['salt'], $password);
    $hash = $this->hash($pToken);

    if($hash !== $user['password']) {
      throw new HttpException(401,"Invalid username or password");
    }
    return true;
  }

  public function prependToken($token, $password) {
    return $token . $password;
  }

  public function encodePassword($password) {
    $token = $this->getToken();
    $password = $this->prependToken($token, $password);
    return array("hash" => $this->hash($password), "salt" => $token);
  }

  public function hash($value) {
    return hash("sha256", $value);
  }

  function cryptoRandSecure($min, $max) {
    $range = $max - $min;
    if ($range < 0) return $min; // not so random...
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
  }

  function getConfirmEmailLink($user_id){
    return $this->emailValidatorService->generateConfirmEmailLink($user_id);
  }

  function verifyEmail($data){
    return $this->emailValidatorService->verifyEmail($data);
  }

  function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
      $token .= $codeAlphabet[$this->cryptoRandSecure(0,strlen($codeAlphabet))];
    }
    return $token;
  }
}
