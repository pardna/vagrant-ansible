<?php
namespace App\Services\common\sms;
// move http exceptiosn to controller
use App\Services\common\BaseService;

class ShortMessageService extends BaseService
{

  protected $twillioClient;

  public function setTwillioClient($twillioClient) {
    $this->twillioClient = $twillioClient;
  }

  public function getTwillioClient() {
    return $this->twillioClient;
  }

  public function sendMessage($mobile, $body){
    $message = $twillioClient->account->messages->create(array(
      'To' => $mobile,
      'From' => "+441536609330",
      'Body' => $body
    ));
  }

}
