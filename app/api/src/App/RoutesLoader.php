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
            $controller->setMandrillMailService($this->app['mandrill.mail.service']);
            $controller->setApp($this->app);
            if($this->app['security.token_storage']->getToken()->getUser()) {
              $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            return $controller;
        });

        $this->app['invitation.controller'] = $this->app->share(function () {
            $controller = new Controllers\InvitationController($this->app['invitation.service']);
            $controller->setApp($this->app);
            if($this->app['security.token_storage']->getToken()->getUser()) {
              $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            return $controller;
        });

        $this->app['pardna.group.controller'] = $this->app->share(function () {
            $controller = new Controllers\PardnaGroupController($this->app['pardna.group.service']);
            //$controller->setPaymentsService($this->app['gocardless.payments.service']);
            $controller->setPardnaGroupStatusService($this->app['pardna.group.status.service']);
            if($this->app['security.token_storage']->getToken()->getUser()) {
               $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            return $controller;
        });

        $this->app['pardna.payments.controller'] = $this->app->share(function () {
            $controller = new Controllers\PaymentsController($this->app['payments.setup.service']);
            $controller->setPardnaGroupsService($this->app['pardna.group.service']);
            $controller->setPardnaGroupStatusService($this->app['pardna.group.status.service']);
            // $controller->setTrackerService($this->app['payments.tracker.service']);
            // $controller->setManageService($this->app['payments.manage.service']);
            if($this->app['security.token_storage']->getToken()->getUser()) {
               $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            return $controller;
        });

    }

    /**
     *  @SWG\Definition(
     *    @SWG\Xml(name="TokenDefault"),
     *    definition = "TokenDefault",
     * 		required={"success", "token"},
     * 		@SWG\Property(property="success", type="boolean"),
     * 		@SWG\Property(property="token", type="string", description="Json Web Token"),
     *    @SWG\Property(property="stacktrace", type="string", description="stack trace")
     * 	),
     *  @SWG\Definition(
     *    @SWG\Xml(name="ErrorDefault"),
     *    definition = "ErrorDefault",
     * 		required={"statusCode", "message"},
     * 		@SWG\Property(property="statusCode", type="boolean"),
     * 		@SWG\Property(property="message", type="string", description="Message detailing error")
     * 	),
     *  @SWG\Property(
     *    @SWG\Xml(name="group_details"),
     *    definition = "group_details",
     * 		required={"name"},
     * 		@SWG\Property(property="name", type="string"),
     * 		@SWG\Property(property="email_invites", type="array", @SWG\Items("string"), description="An array of all the email of invitees to the pardna group"),
     *    @SWG\Property(property="subscriber_invites", type="array", @SWG\Items("string"), description="An array of all the membership ids of invitees to the pardna group")
     *  ),
     *  @SWG\Definition(
     *    @SWG\Xml(name="pardna_details"),
     *    definition = "pardna_details",
     * 		required={"amount","frequency","paydate"},
     * 		@SWG\Property(property="amount", type="string"),
     *    @SWG\Property(property="currency", type="string"),
     *    @SWG\Property(property="frequency", type="string"),
     *    @SWG\Property(property="paydate", type="string"),
     *    @SWG\Property(property="paytype", type="string"),
     *  ),
     *  @SWG\Definition(
     *    @SWG\Xml(name="User"),
     *    definition = "User",
     * 		required={"email", "fullname","password"},
     * 		@SWG\Property(
     *      property="user",
     *      type="object",
     * 		  required={"email", "fullname","password"},
     * 		  @SWG\Property(property="email", type="string"),
     *      @SWG\Property(property="fullname", type="string"),
     * 		  @SWG\Property(property="password", type="string", description="Password")
     *    )
     * 	)
     */
    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        /**
         *  @SWG\Get(
         *    path="/user/{id}",
         *    tags={"user"},
         *    operationId="getuser",
         *    summary="Gets all the details of a specified user",
         *    description="This service is used to retrieve all the details about the user that we hold in the database.",
         *    consumes={"application/json","application/xml"},
         *    produces={"application/json","application/xml"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required=true,
         *      type="string",
         *      description="UUID"
         *    ),
         *    @SWG\Response(
         *      status=200,
         *      description="success"
         *    )
         *  )
         */
        $api->get('/user/{id}', "users.controller:get");

        /**
         *  @SWG\Post(
         *    path="/signup",
         *    tags={"user"},
         *    operationId="userSignUp",
         *    summary="Registers the user to the pardna site",
         *    description="This service is used to create a Pardna Account for a user. User details are stored in DB and the user is sent a mail  to confirm account using Mailchimp mailing list",
         *    consumes={"application/json", "application/xml"},
         *    produces={"application/json", "application/xml"},
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
         *             "pardna_auth": {"write:pardna", "read:pardna"}
         *         }
         *     }
         *  )
         */
        $api->post('/signup', "users.controller:signup");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="LoginUser"),
         *      definition = "LoginUser",
         * 			required={"username", "password"},
         * 			@SWG\Property(property="username", type="string", description="Username"),
         * 			@SWG\Property(property="password", type="string", description="Password")
         * 	),
         *  @SWG\Post(
         *    path="/login",
         *    tags={"user"},
         *    operationId="userLogIn",
         *    summary="Enables the user to login to the pardna site",
         *    description="This service is used to login to the pardna site",
         *    consumes={"application/json", "application/xml"},
         *    produces={"application/json", "application/xml"},
         *    @SWG\Parameter(
         *      name="User",
         *      in="body",
         *      required = true,
         *      description="User object that needs to be logged in to Pardna",
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
         *             "pardna_auth": {"write:pardna", "read:pardna"}
         *         }
         *     }
         *  )
         */
        $api->post('/login', "users.controller:login");

        $api->get('/login', "users.controller:login");

        /**
         *  @SWG\Post(
         *    path="/refresh-token",
         *    tags={"user"},
         *    operationId="refreshToken",
         *    summary="Refreshes the login token",
         *    description="Refreshes the login token. No request object required",
         *    consumes={"application/json", "application/xml"},
         *    produces={"application/json", "application/xml"},
         *    security={
       *         {
       *             "pardna_auth": {"write:pardna", "read:pardna"}
       *         }
         *    }
         *  )
         */
        $api->post('/refresh-token', "users.controller:refreshToken");

        $api->post('/signin', "users.controller:signin");

        /**
         *  @SWG\Definition(
         *        @SWG\Xml(name="ChangePasswordUser"),
         *        definition = "ChangePasswordUser",
         * 			  @SWG\Property(
         *          property="user",
         *          type="object",
         * 			    required={"email", "currentPassword","newPassword"},
         * 			    @SWG\Property(property="email", type="string"),
         * 			    @SWG\Property(property="currentPassword", type="string", description="currentPassword"),
         * 			    @SWG\Property(property="newPassword", type="string", description="newPassword")
         *        )
         * 	),
         *  @SWG\Post(
         *    path="/user/change-password",
         *    tags={"user"},
         *    operationId="changeUserPassword",
         *    summary="Change user password",
         *    description="This service is used to change a specified user password",
         *    consumes={"application/json", "application/xml"},
         *    produces={"application/json", "application/xml"},
         *    @SWG\Parameter(
         *      name="User",
         *      in="body",
         *      required = true,
         *      description="User object, whose password needs to be changed" ,
         *      @SWG\Schema(ref="#/definitions/ChangePasswordUser")
         *    ),
         *    @SWG\Response(
         *      response="401",
         *      description="Invalid username or password",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="User is successfuly registered",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    )
         *  )
         */
        $api->post('/user/change-password', "users.controller:changePassword");
        $api->put('/user/{id}', "users.controller:update");
        $api->delete('/user/{id}', "users.controller:delete");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="MobilePhoneRequest"),
         *      definition = "MobilePhoneRequest",
         * 			required={"mobile"},
         * 			@SWG\Property(property="mobile", type="string", description="mobile")
         * 	),
         *  @SWG\Post(
         *    path="/user/send-code",
         *    tags={"user"},
         *    operationId="sendCodeForMobilePhoneConfirmation",
         *    summary="Sends code to a user mobile phone",
         *    description="This service is used to send the user a code which will be used to confirm mobile phone registration",
         *    consumes={"application/json", "application/xml"},
         *    produces={"application/json", "application/xml"},
         *    @SWG\Parameter(
         *      name="VerifyUserPhoneNumberRequest",
         *      in="body",
         *      required = true,
         *      description="Verify User PhoneNumber",
         *      @SWG\Schema(ref="#/definitions/MobilePhoneRequest")
         *    ),
         *    @SWG\Response(
         *      response="401",
         *      description="Invalid username or password",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="User is successfuly registered",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    ),
         *    security={
         *         {
         *             "pardna_auth": {"write:pardna", "read:pardna"}
         *         }
         *    }
         *  )
         */
        $api->post('/user/send-code', "users.controller:sendCode");

        $api->post('/user/verify', "users.controller:verify");
        $api->get('/relationships', "users.controller:relationships");

        $api->post('/pardna/group', "pardna.group.controller:save");
        $api->get('/pardna/group', "pardna.group.controller:read");
        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroupDetailsRequest"),
         *      definition = "PardnaGroupDetailsRequest",
         * 			required={"id"},
         * 			@SWG\Property(property="id", type="integer", description="id")
         * 	),
         *  @SWG\Post(
         *    path="/pardna/group/details",
         *    tags={"user"},
         *    operationId="sendCodeForMobilePhoneConfirmation",
         *    summary="Sends code to a user mobile phone",
         *    description="This service returns a parda groups details, members and oayment history",
         *    consumes={"application/json", "application/xml"},
         *    produces={"application/json", "application/xml"},
         *    @SWG\Parameter(
         *      name="VerifyUserPhoneNumberRequest",
         *      in="body",
         *      required = true,
         *      description="Verify User PhoneNumber",
         *      @SWG\Schema(ref="#/definitions/MobilePhoneRequest")
         *    ),
         *    @SWG\Response(
         *      response="401",
         *      description="Invalid username or password",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="User is successfuly registered",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    ),
         *    security={
         *         {
         *             "pardna_auth": {"write:pardna", "read:pardna"}
         *         }
         *    }
         *  )
         */

        $api->get('/pardna/group/details/{id}', "pardna.group.controller:details");
        $api->get('/pardna/group/slots/{id}', "pardna.group.controller:slots");

        $api->post('/invite', "invitation.controller:save");
        $api->get('/invite/group', "invitation.controller:readGroupInvitations");
        $api->get('/invite/user', "invitation.controller:readUserInvitations");

        $api->post('/invite/accept/user', "invitation.controller:acceptUserInvitation");
        $api->post('/invite/accept/group', "invitation.controller:acceptGroupInvitation");

        $api->post('/user/notifications', "users.controller:notifications");

        //Payments set up

        $api->post('/group/payments/getPaymentUrl/{id}', "pardna.payments.controller:getGroupPaymentsSubscriptionUrl");

        $api->post('/group/payments/getGroupStatus/{id}', "pardna.payments.controller:getGroupStatus");

        $api->post('/payments/confirm', "pardna.payments.controller:completeRedirectFlow");

        $api->post('/group/subscriptions/create/{id}', "pardna.payments.controller:createSubscription");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
