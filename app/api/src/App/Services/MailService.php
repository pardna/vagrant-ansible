<?php
namespace App\Services;

class MailService extends BaseService
{
  protected $mc;
  protected $pardnaAccConfirmListId;

  public function setMailChimpsClient($mailChimpsClientApiKey){
    //var_dump($mailChimpsClient);
    //var_dump($this->$mailChimpsClient);
    $this->mc = new \Mailchimp($mailChimpsClientApiKey);
  }

  public function getMailChimpsClient(){
    return $this->mc;
  }

  public function setPardnaAccConfirmListId($pardnaAccConfirmListId){
    $this->pardnaAccConfirmListId = $pardnaAccConfirmListId;
  }

  public function getPardnaAccConfirmListId(){
    return $this->pardnaAccConfirmListId;
  }

  public function subscribeUserToMailList($email, $firstname, $lastname){
    try {
        $subscriber = $this->mc->lists->subscribe(
          $this->pardnaAccConfirmListId,
          array(
            'email'=>$email
          ),
          array(
            'FNAME'=>$firstname, 'LNAME'=>$lastname
          )
        );

        if ( ! empty($subscriber['leid'])) {
            // Success
            //var_dump($subscriber);
        }
    } catch (Mailchimp_Error $e) {
        if ($e->getMessage()) {
            //$this->Session->setFlash($e->getMessage(), 'flash_error');
        } else {
            //$this->Session->setFlash('An unknown error occurred', 'flash_error');
        }
    }
  }
}
