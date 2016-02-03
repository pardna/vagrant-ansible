<?php
namespace App\Services\common\email;
class MandrillMailService
{
  protected $mandrill;

  public function setMandrillMailClient($mandrillApiKey){
    $this->mandrill = new \Mandrill($mandrillApiKey);
  }

  public function getMandrillMailClient(){
    return $this->$mandrill;
  }

  public function sentResetPasswordEmailLink($email,  $resetLink){
    $template_name = 'forgot-pardna-account-password';
    $message = array(
      'to' => array(array('email' => $email)),
      'merge_vars' => array(array(
          'rcpt' => $email,
          'vars' =>
          array(
            array(
              'name' => 'resetPasswordLink',
              'content' => $resetLink
            )
          )
        )
    ));
    $this->mandrill->messages->sendTemplate($template_name, $template_content, $message);
  }

  public function inviteEmailToPardnaGroup($email, $invitorFullName, $groupName){
    $template_name = 'invite-email-to-pardna-group';
    $message = array(
      'to' => array(array('email' => $email)),
      'subject' => 'You have been invited to join '. $groupName . ' group on Pardna Money',
      'merge_vars' => array(array(
          'rcpt' => $email
        )
    ));
    $template_content = array(
        array(
            'name' => 'invitorFullName',
            'content' => $invitorFullName),
        array(
            'name' => 'groupName',
            'content' => $groupName)
    );
    $this->mandrill->messages->sendTemplate($template_name, $template_content, $message);
  }

  public function sendEmailConfirmation($fName, $lname, $toEmail, $link)
  {
    $template_name = 'user-email-verification';
    $fullname = $fName. " ". $lname;
    $message = array(
      'to' => array(array('email' => $toEmail, 'name' => $fullname)),
      'merge_vars' => array(array(
          'rcpt' => $toEmail,
          'vars' =>
          array(
            array(
              'name' => 'FIRSTNAME',
              'content' => $fName
            ),
            array(
              'name' => 'LASTNAME',
              'content' => $lname
            ),
            array(
              'name' => 'VLINK',
              'content' => $link
            )
          )
        )
    ));
    $template_content = array(
      array(
          'name' => 'verify_link',
          'content' => '<a href="*|VLINK|*">Verify my email address</a>'),
        array(
            'name' => 'fullname',
            'content' => '*|FIRSTNAME|* *|LASTNAME|*'),
        array(
            'name' => 'footer',
            'content' => 'Copyright 2012.')
    );
    $this->mandrill->messages->sendTemplate($template_name, $template_content, $message);
  }
}
