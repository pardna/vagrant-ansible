<?php
namespace App;
use Silex\Application;
class RoutesLoader
{
    private $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }
    private function instantiateControllers()
    {
        $this->app['users.controller'] = $this->app->share(function () {
            $controller = new Controllers\UsersController($this->app['users.service']);
            $controller->setNotificationService($this->app['notification.service']);
            $controller->setMailService($this->app['mail.service']);
            $controller->setApp($this->app);
            if($this->app['security.token_storage']->getToken()->getUser()) {
              //var_dump($this->app['security.token_storage']->getToken()->getUser());
            //  exit;
              $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            // var_dump($this->app['security.token_storage']->getToken()->getTokenContext());
            // exit;
            return $controller;
        });


    }
    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/user/{id}', "users.controller:get");

        /**
         *  @SWG\Post(
         *    path="/signup",
         *    tags={"user"},
         *    operationId="userSignUp",
         *    summary="Registers the user to the pardna site",
         *    description="This service is used to create a Pardna Account for a user. User details are stored in DB and the user is sent a mail  to confirm account using Mailchimp mailing list",
         *    consumes={"application/xml", "application/json"},
         *    produces={"application/xml", "application/json"},
         *    @SWG\Parameter(
         *      name="User",
         *      in="body",
         *      required = true,
         *      description="User object that needs to be registered to Pardna",
         *      @SWG\Schema(ref="#/definitions/User")
         *    ),
         *    @SWG\Response(
         *         response="409",
         *         description="User is already registered"
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="User is successfuly registered",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    ),
         *    security={
         *         {
         *             "petstore_auth": {"write:pets", "read:pets"}
         *         }
         *     }
         *  )
         */
        $api->post('/signup', "users.controller:signup");

        /**
         *  @SWG\Post(
         *    path="/login",
         *    tags={"user"},
         *    operationId="userLogIn",
         *    summary="Enables the user to login to the pardna site",
         *    description="This service is used to login to the pardna site",
         *    consumes={"application/xml", "application/json"},
         *    produces={"application/xml", "application/json"},
         *    @SWG\Parameter(
         *      name="User",
         *      in="body",
         *      required = true,
         *      description="User object that needs to be registered to Pardna",
         *      @SWG\Schema(ref="#/definitions/LoginUser")
         *    ),
         *    @SWG\Response(
         *      response="404",
         *      description="Invalid Credentials",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="401",
         *      description="Invalid Credentials",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="User is successfuly registered",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    ),
         *    security={
         *         {
         *             "petstore_auth": {"write:pets", "read:pets"}
         *         }
         *     }
         *  )
         */
        $api->post('/login', "users.controller:login");
        $api->get('/login', "users.controller:login");
        $api->post('/refresh-token', "users.controller:refreshToken");

        $api->post('/signin', "users.controller:signin");
        $api->post('/user/change-password', "users.controller:changePassword");
        $api->put('/user/{id}', "users.controller:update");
        $api->delete('/user/{id}', "users.controller:delete");

        $api->post('/user/send-code', "users.controller:sendCode");

        $api->post('/user/verify', "users.controller:verify");

        $api->post('/user/notifications', "users.controller:notifications");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
