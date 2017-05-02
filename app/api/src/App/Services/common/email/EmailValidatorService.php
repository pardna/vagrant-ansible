<?php
namespace App\Services\common\email;
use App\Services\common\BaseService;

class EmailValidatorService extends BaseService
{
  protected $configService;
  protected $accountEmailValidateTable = "account_email_validate";

  public function setConfigurationsService($configService){
    $this->configService = $configService;
  }

  public function getConfigurationsService(){
    return $this->configService;
  }

  function generateResetPasswordLink($code)
  {
    $base_url = $this->configService->getConfigValue('base_url');
    $reset_password_endpt = $this->configService->getConfigValue('reset_password_endpoint');
    $reset_password_url = $base_url . $reset_password_endpt;
    $reset_password_url .= http_build_query([
        'code' => $code
    ]);

    return $reset_password_url;
  }

  function generateConfirmEmailLink($user_id){
    $base_url = $this->configService->getConfigValue('base_url');
    $email_valid_endpt = $this->configService->getConfigValue('email_validate_endpoint');
    $email_validate_url = $base_url . $email_valid_endpt;

    $selector = bin2hex(random_bytes(8));
    $token = bin2hex(random_bytes(32));

    $email_validate_url .= http_build_query([
        'selector' => $selector,
        'validator' => $token
    ]);

    $expires = new \DateTime('NOW');
    $expires->add(new \DateInterval('PT168H')); // 24 hours

    $data = array();
    $data['user_id'] = $user_id;
    $data['selector'] = $selector;
    $data['token'] = $token;
    $data['expires'] = $expires->format('Y-m-d H:i:s');
    $this->storeEmailValidateData($data);

    return $email_validate_url;
  }

  function verifyEmail($data){
    $response = array();
    $selector = $data['selector'];
    $validator = $data['validator'];
    $selection = $this->db->fetchAll("SELECT * FROM {$this->accountEmailValidateTable} WHERE selector = ? LIMIT 1", array($selector));
    if (! empty($selection)){
      $selection = $selection[0];
      $user_id = $selection['user_id'];
      $token = $selection['token'];
      $expires = $selection['expires'];
      if ($this->isDateInThePast($expires)){
        $response['success'] = false;
        $response['reason'] = 'expired';
      }
      if (strcmp($token, $data["validator"]) == 0){
        if (! $this->isUserEmailVerified($user_id)){
          $this->verifyUserEmail($user_id);
          $response['success'] = true;
        } else{
          $response['false'] = true;
          $response['reason'] = 'verified';
        }
      }
      return $response;
    }
    $response['success'] = false;
    return $response;
  }

  private function isDateInThePast($date){
    $now = new \DateTime();
    if($date < $now) {
        return true;
    }
    return false;
  }

  public function isUserEmailVerified($id){
    $response = $this->db->fetchAssoc("SELECT email_verified FROM users WHERE id = ? LIMIT 1", array($id));
    if ($response['email_verified'] == 0){
      return false;
    }
    return true;
  }

  public function verifyUserEmail($id){
    return $this->db->update('users', array("email_verified" => 1), ['id' => $id]);
  }

  private function storeEmailValidateData($data){
    $this->db->insert($this->accountEmailValidateTable, $data);
  }
}
