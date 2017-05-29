<?php
/**
* Pardna groups
*
*/
namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersController
{
  protected $usersService;

  protected $mandrillMailService;

  protected $notificationService;

  protected $user;

  protected $app;

  public function __construct($service)
  {
    $this->usersService = $service;
  }
  public function getAll()
  {
    return new JsonResponse($this->usersService->getAll());
  }

  public function getMailChimpsService(){
    return $this->mailChimpsService;
  }

  public function setMandrillMailService($mandrillMailService){
		$this->mandrillMailService = $mandrillMailService;
	}

  public function getMandrillMailService(){
		return $this->mandrillMailService;
	}

  public function setNotificationService($notificationService) {
    $this->notificationService = $notificationService;
  }

  public function getNotificationService() {
    return $this->notificationService;
  }

  public function setUser($user) {
    $this->user = $user;
  }

  public function getUser() {
    return $this->user;
  }

  public function setApp($app) {
    $this->app = $app;
  }

  public function getApp() {
    return $this->app;
  }

  private function get($id)
  {
    return new JsonResponse($this->usersService->get($id));
  }

  public function login(Request $request) {
    $vars = $request->request->all();
    if (empty($vars['username']) || empty($vars['password'])) {
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['username']));
    }
    return $this->_login($vars["username"], $vars["password"]);
  }

  public function relationships(Request $request)
  {

      try {

        $data = $request->request->all();
        $user = $this->getUser();
        $service = $this->app['relationship.service'];
        $relationships = $service->getUserRelationships($user->getId());
        return new JsonResponse($relationships);
      } catch(\Exception $e) {
        throw new HttpException(409,"Error getting relationships : " . $e->getMessage());
      }

  }

  private function _login($username, $password) {
    $app = $this->app;
    $vars = array(
      "username" => $username,
      "password" => $password
    );
    try {

        /**
         * @var $user User
         */
        $user = $app['users']->loadUserByUsername($vars['username']);
        if (!$app['users']->authenticate($vars['username'], $vars['password'])) {
          $response = [
              'success' => false,
              'error' => 'Invalid credentials',
              'user' => $vars
          ];
        } else {
            $response = $this->jwtArray($user);
            $this->usersService->incrementLoginCount($user->getId());
        }
    } catch (UsernameNotFoundException $e) {
        $response = [
            'success' => false,
            'error' => 'Invalid credentials',
          //  'user' => $vars
        ];
    }
    $code = $response['success'] == true ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST;
    return new JsonResponse($response, $code);
  }

  protected function getTokenArray() {
    $app = $this->app;
    $user = $app['users']->loadUserByUsername($this->getUser()->getUsername());
    return $this->jwtArray($user);
  }

  public function refreshToken() {

    return new JsonResponse($this->getTokenArray());
  }

  protected function jwtArray($user) {
    $app = $this->app;
     return [
        'success' => true,
        'token' => $app['security.jwt.encoder']->encode([
          'fullname' => $user->getFullName(),
          'firstname' => $user->getFirstname(),
          'lastname' => $user->getLastName(),
          "name" => $user->getEmail(),
          "email" => $user->getEmail(),
          "membership_number" => $user->getMembershipNumber(),
          "login_count" => $user->getLoginCount(),
          "id" => $user->getid(),
          "mobile" => $user->getMobile(),
          "verified" => $user->getVerified()
          ]),
    ];
  }

  public function signup(Request $request)
  {
    $user = $this->getDataFromRequest($request);
    $user["fullname"] = $user["firstname"] . " " . $user["lastname"];
    $userId = $this->usersService->save($user);
    $userEntity = $this->usersService->loadUserById($userId);
    $this->usersService->createAccount($userEntity);

    //subscribe user to mail list
    if (isset($user)){
      $fullname = $user['fullname'];
      $firstname = $user['firstname'];
      $lastname = $user['lastname'];

      $link = $this->usersService->getConfirmEmailLink($userId);
      $this->mandrillMailService->sendEmailConfirmation($firstname, $lastname, $user['email'], $link);
      // $this->mailService->subscribeUserToMailList($user['email'], $firstname, $lastname);

      // $this->mailChimpsService->subscribeUserToMailList($user['email'], $firstname, $lastname);
    }
    return $this->_login($user["email"], $user["password"]);
  }

  public function signin(Request $request)
  {
    $user = $this->getDataFromRequest($request);
    return new JsonResponse(array("id" => $this->usersService->save($user)));
  }

  public function changePassword(Request $request)
  {
    $auth_user = $this->getUser();
    $passwords = $this->getDataFromRequest($request);
    if ($this->usersService->authenticate($auth_user->getEmail(), $passwords["currentPassword"])){
      if (strcmp($passwords["currentPassword"], $passwords["newPassword"]) === 0){
        throw new HttpException(403, "Cannot change to use the same password");
      }
      $success = $this->usersService->changePassword($auth_user->getEmail(), $passwords["newPassword"]);
      if ($success){
        return new JsonResponse(array("message" => "Password successfuly changed"));
      }
    }
  }

  public function forgotPassword(Request $request)
  {
    $email = $request->request->get("email");
    if ($email){
      $user = $this->usersService->getByEmail($email);
      if ($user){
        $reset = $this->usersService->generateResetCodeForEmail($email);
        if (! $reset){
          //Throw some kind of exception here if something happened
          throw new HttpException(500, "Failed to generate reset link");
        }
        $this->mandrillMailService->sendResetPasswordEmailLink($email, $reset["reset_link"]);
        $this->usersService->passwordResetEmailSent($reset["reset_code"]);
        return new JsonResponse(array("message" => "Reset password email has been sent to the email address"));
      } else{
          throw new HttpException(403, "Email not recognized");
      }
    }
    throw new HttpException(400, "Email not provided");
  }

  public function resetPassword(Request $request)
  {
    $this->validateResetPasswordRequest($request);
    $reset_code = $request->request->get("reset_code");
    $password = $request->request->get("password");
    $valid = $this->usersService->validateResetPasswordCode($reset_code);
    if ($valid){
      $success = $this->usersService->changePassword($valid["email"], $password);
      if ($success){
        $this->usersService->passwordResetComplete($reset_code);
        return new JsonResponse(array("message" => "Password successfuly reset"));
      }
    }
    throw new HttpException(401, "Reset code expired or invalid");
  }

  public function validateResetPasswordRequest($request){
    if (! $request->request->get("reset_code")){
      throw new HttpException(400, "Reset code not provided");
    }
    if (! $request->request->get("password")){
      throw new HttpException(400, "Password not provided");
    }
  }

  private function update($id, Request $request)
  {
    $user = $this->getDataFromRequest($request);
    $this->usersService->update($id, $user);
    return new JsonResponse($user);
  }
  private function delete($id)
  {
    return new JsonResponse($this->usersService->delete($id));
  }

  public function verify(Request $request) {
    try {
    $user = $this->getUser();
    $id = $user->getId();

    $mobile =  $request->request->get("mobile");
    $code =  $request->request->get("code");

    $this->usersService->verify($id, $code, $mobile);

    $tokenArray = $this->getTokenArray();

    $message = array(
      "message" => "Your account is verified.",
      "target_type" => "user",
      "target_id" => $id
    );

    $this->notificationService->log($message);

    return new JsonResponse(array("message" => "Account verified", "token" => $tokenArray["token"]));
  } catch (Services_Twilio_RestException $e) {
    throw new HttpException(409,"Cannot verify account : " . $e->getMessage());
  }
  }

  public function notifications(Request $request) {
      try {
        $user = $this->getUser();
      $id = $user->getId();
      $notifications = $this->notificationService->getNotifications($id);
      return new JsonResponse($notifications);
    } catch (Services_Twilio_RestException $e) {
      throw new HttpException(409,"Cannot send token" . $e->getMessage());
    }
  }

  public function sendCode(Request $request) {
    $twillioClient = $this->usersService->getTwillioClient();
    $user = $this->getUser();
    $id = $user->getId();
    try {

      $verificationCode = $this->usersService->getVerificationCode();
      $mobile =  $request->request->get("mobile");
      $message = $twillioClient->account->messages->create(array(
        'To' => $mobile,
        'From' => "+441536609330",
        'Body' => $verificationCode
      ));

      $this->usersService->updateMobile($id, $mobile);
      $this->usersService->updateVerificationCode($id, $verificationCode);

      $tokenArray = $this->getTokenArray();

      $message = array(
        "message" => "Verification code sent",
        "target_type" => "user",
        "target_id" => $id
      );

      $this->notificationService->log($message);

      return new JsonResponse(array("message" => "Token sent to " . $mobile, "token" => $tokenArray["token"]));
    } catch (Services_Twilio_RestException $e) {
      throw new HttpException(409,"Cannot send token" . $e->getMessage());
    }
  }

  public function resendConfirmationEmail(){
    $user = $this->getUser();
    if (! empty($user)){
      if (! $user->getVerified()){
        $link = $this->usersService->getConfirmEmailLink($user->getId());
        $this->mandrillMailService->sendEmailConfirmation($user->getFirstname(), $user->getLastname(), $user->getEmail(), $link);
        return new JsonResponse(array("message" => "Confirmation email sent"));
      } else{
        throw new HttpException(409, "User is already confirmed");
      }
    } else{
      throw new HttpException(401, "User not found");
    }
  }


  public function verifyEmail(Request $request){
    try {
      $data = $request->request->all();
      $response = $this->usersService->verifyEmail($data);
      if ($response['success'] == true){
        return new JsonResponse(array("message" => "Email has been successfully verified" ));
      } else{
        if (array_key_exists("reason",$response)){
          if ($response['reason'] === 'expired'){
            throw new HttpException(403, "Sorry, that confirmation link has expired. Please try again.");
          } else if ($response['reason'] === 'verified'){
            throw new HttpException(410, "Email has already been verified");
          }
        }
        throw new HttpException(401, "Confirmation Link not recognised");
      }
    } catch(\Exception $e) {
      throw new HttpException(409, "Could not verify email : " . $e->getMessage());
    }
  }

  public function getDataFromRequest(Request $request)
  {
    return  $request->request->get("user");
  }

}
