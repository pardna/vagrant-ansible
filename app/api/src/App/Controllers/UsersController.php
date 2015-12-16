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

class UsersController
{
  protected $usersService;

  protected $mailService;

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

  public function setMailService($mailService){
    $this->mailService = $mailService;
  }

  public function getMailService(){
    return $this->mailService;
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

  public function get($id)
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
    $userId = $this->usersService->save($user);

    //subscribe user to mail list
    if (isset($user)){
      $fullname = $user['fullname'];
      $names = explode(" ", $fullname, 2);
      if (array_key_exists(1, $names)){
        $firstname = $names[0];
        $lastname = $names[1];
      } else{
        $firstname = "";
        $lastname = $names[0];
      }

      $this->mailService->subscribeUserToMailList($user['email'], $firstname, $lastname);
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
    $user = $this->getDataFromRequest($request);
    if ($this->usersService->authenticate($user["email"], $user["currentPassword"])){
      return new JsonResponse(array("id" => $this->usersService->changePassword($user["email"], $user["newPassword"])));
    }
  }

  public function resetPassword($t, Request $request)
  {
    $user = $this->getDataFromRequest($request);
    if ($this->usersService->validateResetPasswordToken($t)){
      return new JsonResponse(array("id" => $this->usersService->changePassword($user["email"], $user["newPassword"])));
    }
  }


  public function update($id, Request $request)
  {
    $user = $this->getDataFromRequest($request);
    $this->usersService->update($id, $user);
    return new JsonResponse($user);
  }
  public function delete($id)
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
      return new JsonResponse($this->notificationService->getNotifications($id));
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

  public function getDataFromRequest(Request $request)
  {
    return  $request->request->get("user");
  }




}
