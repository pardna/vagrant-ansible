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
            $controller->setPardnaGroupService($this->app['pardna.group.service']);
            if($this->app['security.token_storage']->getToken()->getUser()) {
              $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            return $controller;
        });

        $this->app['direct-debit.controller'] = $this->app->share(function () {
            $controller = new Controllers\DirectDebitController($this->app['direct-debit.service']);
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
            $controller->setManageService($this->app['payments.manage.service']);
            // $controller->setTrackerService($this->app['payments.tracker.service']);
            if($this->app['security.token_storage']->getToken()->getUser()) {
               $controller->setUser($this->app['security.token_storage']->getToken()->getUser());
            }
            return $controller;
        });

        $this->app['payments.events.controller'] = $this->app->share(function () {
            $controller = new Controllers\PaymentEventsController($this->app['payments.events.service']);
            return $controller;
        });

    }

    /**
     *  @SWG\Definition(
     *    @SWG\Xml(name="TokenDefault"),
     *    definition = "TokenDefault",
     * 		required={"success", "token"},
     * 		@SWG\Property(property="success", type="boolean"),
     * 		@SWG\Property(property="token", type="string", description="Json Web Token")
     * 	),
     *  @SWG\Definition(
     *    @SWG\Xml(name="MessageDefault"),
     *    definition = "MessageDefault",
     * 		required={"message"},
     * 		@SWG\Property(property="message", type="string", description="The messsage from the backend")
     * 	),
     *  @SWG\Definition(
     *    @SWG\Xml(name="MessageTokenDefault"),
     *    definition = "MessageTokenDefault",
     * 		required={"message", "token"},
     * 		@SWG\Property(property="message", type="string", description="The messsage from the backend"),
     *    @SWG\Property(property="token", type="string", description="The token")
     * 	),
     *  @SWG\Definition(
     *    @SWG\Xml(name="ErrorDefault"),
     *    definition = "ErrorDefault",
     * 		required={"statusCode", "message", "stacktrace"},
     * 		@SWG\Property(property="statusCode", type="boolean"),
     * 		@SWG\Property(property="message", type="string", description="Message detailing error"),
     *    @SWG\Property(property="stacktrace", type="string", description="stack trace")
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
     * 		  required={"email", "firstname", "lastname", "password"},
     * 		  @SWG\Property(property="email", type="string"),
     *      @SWG\Property(property="firstname", type="string"),
     *      @SWG\Property(property="lastname", type="string"),
     * 		  @SWG\Property(property="password", type="string", description="Password")
     *    )
     * 	)
     */
    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        /** These methods pose a security risk. They need to be removed but for now, I have provatised the controller methods so, will throw exceptions if called */
        $api->get('/user/{id}', "users.controller:get");
        $api->put('/user/{id}', "users.controller:update");
        $api->delete('/user/{id}', "users.controller:delete");

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


        $api->post('/direct-debit', "direct-debit.controller:save");

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
         *    tags={"user", "token"},
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
         *      response="401",
         *      description="Invalid Credentials",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="User is successfully logged in",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    )
         *  )
         */
        $api->post('/login', "users.controller:login");

        /* This service should be deprecated */
        $api->get('/login', "users.controller:login");

        /**
         *  @SWG\Post(
         *    path="/refresh-token",
         *    tags={"user", "token"},
         *    operationId="refreshToken",
         *    summary="Refreshes the login token",
         *    description="Refreshes the login token. No request object required",
         *    consumes={"application/json"},
         *    produces={"application/json"}
         *  )
         */
        $api->post('/refresh-token', "users.controller:refreshToken");

        /* This service should be deprecated, I am unable to find a real use for it, because it is doing exactly the same thing as signup */
        $api->post('/signin', "users.controller:signin");

        /**
         *  @SWG\Definition(
         *        @SWG\Xml(name="ChangePasswordUser"),
         *        definition = "ChangePasswordUser",
         * 			  @SWG\Property(
         *          property="user",
         *          type="object",
         * 			    required={"currentPassword","newPassword"},
         * 			    @SWG\Property(property="currentPassword", type="string", description="currentPassword"),
         * 			    @SWG\Property(property="newPassword", type="string", description="newPassword")
         *        )
         * 	),
         *  @SWG\Post(
         *    path="/user/change-password",
         *    tags={"user"},
         *    operationId="changeUserPassword",
         *    summary="Change user password",
         *    description="This service is used to change a logged in user password. The user must be logged in to use this service",
         *    consumes={"application/json"},
         *    produces={"application/json"},
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
         *      response="403",
         *      description="Cannot change to use the same password",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Password successfuly changed",
         *      @SWG\Schema(ref="#/definitions/TokenDefault")
         *    )
         *  )
         */
        $api->post('/user/change-password', "users.controller:changePassword");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="Email"),
         *      definition = "Email",
         * 			required={"email"},
         * 			@SWG\Property(property="email", type="string", description="email")
         * 	),
         *  @SWG\Post(
         *    path="/user/forgot-password",
         *    tags={"user"},
         *    operationId="sendForgotPasswordEmail",
         *    summary="Sends a forgot password email with a reset link",
         *    description="Sends a forgot password email with a reset link which should allow the unauthenticated user to change their password ",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="email",
         *      in="body",
         *      required = true,
         *      description="User object, whose password needs to be changed" ,
         *      @SWG\Schema(ref="#/definitions/Email")
         *    ),
         *    @SWG\Response(
         *      response="400",
         *      description="Email not provided",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="403",
         *      description="Email not recognized",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Email has been sent with reset link",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/user/forgot-password', "users.controller:forgotPassword");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="ResetPassword"),
         *      definition = "ResetPassword",
         * 			required={"reset_code", "password"},
         * 			@SWG\Property(property="reset_code", type="string", description="reset_code"),
         *      @SWG\Property(property="password", type="string", description="password")
         * 	),
         *  @SWG\Post(
         *    path="/user/reset-password",
         *    tags={"user"},
         *    operationId="resetPassword",
         *    summary="Resets a password using a reset code",
         *    description="Resets a password using a reset code",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="RestPassword",
         *      in="body",
         *      required = true,
         *      description="ResetPassword object, using a reset code",
         *      @SWG\Schema(ref="#/definitions/ResetPassword")
         *    ),
         *    @SWG\Response(
         *      response="401",
         *      description="Reset code expired or invalid",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Email has been sent with reset link",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/user/reset-password', "users.controller:resetPassword");

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
         *    summary="Sends a verification code to a user mobile phone",
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
         *      response="409",
         *      description="Cannot send token",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Code has been sent",
         *      @SWG\Schema(ref="#/definitions/MessageTokenDefault")
         *    )
         *  )
         */
        $api->post('/user/send-code', "users.controller:sendCode");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="MobilePhoneVerifyRequest"),
         *      definition = "MobilePhoneVerifyRequest",
         * 			required={"mobile", "code"},
         * 			@SWG\Property(property="mobile", type="string", description="mobile"),
         *      @SWG\Property(property="code", type="string", description="code")
         * 	),
         *  @SWG\Post(
         *    path="/user/verify",
         *    tags={"user"},
         *    operationId="verifyCodeForMobilePhoneConfirmation",
         *    summary="Verifies code sent to a user mobile phone",
         *    description="This service is used to verify the code sent to a user mobile phone",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="MobilePhoneVerifyRequest",
         *      in="body",
         *      required = true,
         *      description="Verify User PhoneNumber",
         *      @SWG\Schema(ref="#/definitions/MobilePhoneVerifyRequest")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Cannot verify account",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Code has been verified",
         *      @SWG\Schema(ref="#/definitions/MessageTokenDefault")
         *    )
         *  )
         */
        $api->post('/user/verify', "users.controller:verify");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="EmailVerifyRequest"),
         *      definition = "EmailVerifyRequest",
         * 			required={"selector", "validator"},
         * 			@SWG\Property(property="selector", type="string", description="selector"),
         *      @SWG\Property(property="validator", type="string", description="validator")
         * 	),
         *  @SWG\Post(
         *    path="/user/verify-email",
         *    tags={"user"},
         *    operationId="verifyEmail",
         *    summary="Verifies user email address",
         *    description="This service is used to verify the user email address",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="EmailVerifyRequest",
         *      in="body",
         *      required = true,
         *      description="Verifies User Email",
         *      @SWG\Schema(ref="#/definitions/EmailVerifyRequest")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Could not verify email",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Code has been verified",
         *      @SWG\Schema(ref="#/definitions/MessageTokenDefault")
         *    )
         *  )
         */
        $api->post('/user/verify-email', "users.controller:verifyEmail");

        /**
         *  @SWG\Get(
         *    path="/user/resend-confirmation-email",
         *    tags={"user"},
         *    operationId="sendConfirmationEmail",
         *    summary="Sends a confirmation email to verify the user",
         *    description="Sends a confirmation email to verify the users' identity. Does nor require anything to be passed in",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="401",
         *      description="User not found",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="403",
         *      description="Email not recognized",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Email has been sent with reset link",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->get('/user/resend-confirmation-email', "users.controller:resendConfirmationEmail");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="Notification"),
         *      definition = "Notification",
         * 			required={"id"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="message", type="string", description="message"),
         * 			@SWG\Property(property="target_type", type="string", description="target_type"),
         *      @SWG\Property(property="target_id", type="string", description="target_id"),
         * 			@SWG\Property(property="deleted", type="string", description="deleted"),
         *      @SWG\Property(property="created", type="string", description="created"),
         * 			@SWG\Property(property="modified", type="string", description="modified")
         * 	),
         *  @SWG\Post(
         *    path="/user/notifications",
         *    tags={"user"},
         *    operationId="sendConfirmationEmail",
         *    summary="Sends a confirmation email to verify the user",
         *    description="Sends a confirmation email to verify the users' identity. Does nor require anything to be passed in",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="200",
         *      description="The notifications array",
         *      @SWG\Schema(property="message", type="array", @SWG\Items(ref="#/definitions/Notification"))
         *    )
         *  )
         */
        $api->post('/user/notifications', "users.controller:notifications");


        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="Relationship"),
         *      definition = "Relationship",
         * 			required={"id", "fullname"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="fullname", type="string", description="fullname")
         * 	),
         *  @SWG\Get(
         *    path="/relationships",
         *    tags={"user"},
         *    operationId="relationships",
         *    summary="Retrieves the logged user relationships",
         *    description="Retrieves all the relationships for the logged user",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting relationships",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="relationships array",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/Relationship"), type="array")
         *    )
         *  )
         */
        $api->get('/relationships', "users.controller:relationships");

        //Pardna Groups

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroupCreateRequest"),
         *      definition = "PardnaGroupCreateRequest",
         * 			required={"slots", "amount", "name", "startdate", "frequency"},
         *      @SWG\Property(property="name", type="string", description="The name for the pardna"),
         * 			@SWG\Property(property="slots", type="string", description="The number of slots for this pardna"),
         * 			@SWG\Property(property="amount", type="integer", description="The amount for the pardna"),
         * 			@SWG\Property(property="frequency", type="string", description="The frequency for the pardna"),
         * 			@SWG\Property(property="startdate", type="string", format="date-time", description="The start date for the pardna"),
         *      @SWG\Property(property="emails", type="array", @SWG\Items(type="string"))
         * 	),
         *  @SWG\Post(
         *    path="/pardna/group",
         *    tags={"groups"},
         *    operationId="createGroup",
         *    summary="Creates a pardna group",
         *    description="This service is used to create a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="PardnaGroupCreateRequest",
         *      in="body",
         *      required = true,
         *      description="creates a pardna group",
         *      @SWG\Schema(ref="#/definitions/PardnaGroupCreateRequest")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Could not verify email",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Code has been verified",
         *      @SWG\Schema(ref="#/definitions/MessageTokenDefault")
         *    )
         *  )
         */
        $api->post('/pardna/group', "pardna.group.controller:save");

        $api->post('/pardna/group/slot/change', "pardna.group.controller:changeSlot");


        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroupEditRequest"),
         *      definition = "PardnaGroupEditRequest",
         *      @SWG\Property(property="name", type="string", description="The name for the pardna"),
         * 			@SWG\Property(property="slots", type="string", description="The number of slots for this pardna"),
         * 			@SWG\Property(property="amount", type="integer", description="The amount for the pardna"),
         * 			@SWG\Property(property="frequency", type="string", description="The frequency for the pardna"),
         * 			@SWG\Property(property="startdate", type="string", format="date-time", description="The start date for the pardna")
         * 	),
         *  @SWG\Post(
         *    path="/pardna/group/edit/{id}",
         *    tags={"groups"},
         *    operationId="editGroup",
         *    summary="Creates a pardna group",
         *    description="This service is used to create a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required = true,
         *      description="The pardna group id",
         *    ),
         *    @SWG\Parameter(
         *      name="PardnaGroupEditRequest",
         *      in="body",
         *      required = true,
         *      description="creates a pardna group",
         *      @SWG\Schema(ref="#/definitions/PardnaGroupEditRequest")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Could not verify email",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Code has been verified",
         *      @SWG\Schema(ref="#/definitions/MessageTokenDefault")
         *    )
         *  )
         */
        $api->post('/pardna/group/edit/{id}', "pardna.group.controller:edit");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroupMember"),
         *      definition = "PardnaGroupMember",
         * 			required={"id", "fullname"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="email", type="string", description="email"),
         * 			@SWG\Property(property="user_id", type="string", description="user_id"),
         *      @SWG\Property(property="fullname", type="string", description="fullname"),
         * 			@SWG\Property(property="mobile", type="string", description="mobile"),
         *      @SWG\Property(property="group_id", type="string", description="group_id"),
         * 			@SWG\Property(property="slot_id", type="string", description="slot_id"),
         *      @SWG\Property(property="created", type="string", description="created"),
         * 			@SWG\Property(property="modified", type="string", description="modified"),
         *      @SWG\Property(property="dd_mandate_id", type="string", description="dd_mandate_id"),
         * 			@SWG\Property(property="dd_mandate_status", type="string", description="dd_mandate_status"),
         *      @SWG\Property(property="allow_choose_payment", type="string", description="allow_choose_payment"),
         * 			@SWG\Property(property="payment_status", type="string", description="payment_status")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="CodeDecodeObject"),
         *      definition = "CodeDecodeObject",
         * 			required={"code", "decode"},
         * 			@SWG\Property(property="code", type="string", description="code"),
         *      @SWG\Property(property="decode", type="string", description="decode")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaSlot"),
         *      definition = "PardnaSlot",
         * 			required={"id", "pardnagroup_id", "position", "claimant", "claimed_date", "pay_date", "total_contribution", "charge_percent", "charge_amount", "pay_amount", "created", "modified", "membership_number", "fullname", "claimed"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="pardnagroup_id", type="string", description="fullname"),
         * 			@SWG\Property(property="position", type="string", description="id"),
         *      @SWG\Property(property="claimant", type="string", description="fullname"),
         * 			@SWG\Property(property="claimed_date", type="string", description="id"),
         *      @SWG\Property(property="pay_date", type="string", description="fullname"),
         * 			@SWG\Property(property="total_contribution", type="string", description="id"),
         *      @SWG\Property(property="charge_percent", type="string", description="fullname"),
         * 			@SWG\Property(property="charge_amount", type="string", description="id"),
         *      @SWG\Property(property="pay_amount", type="string", description="fullname"),
         * 			@SWG\Property(property="modified", type="string", description="id"),
         *      @SWG\Property(property="membership_number", type="string", description="fullname"),
         * 			@SWG\Property(property="fullname", type="string", description="id"),
         *      @SWG\Property(property="claimed", type="boolean", description="fullname")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroupInvite"),
         *      definition = "PardnaGroupInvite",
         * 			required={"id", "email"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="email", type="string", description="email"),
         *      @SWG\Property(property="type", type="string", description="type of invite | group or individual"),
         *      @SWG\Property(property="type_id", type="string", description="type_id of invite")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaConfirmed"),
         *      definition = "PardnaConfirmed",
         * 			required={"id", "pardnagroup_id", "confirmed_on"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="pardnagroup_id", type="string", description="pardnagroup_id"),
         *      @SWG\Property(property="confirmed_on", type="string", description="confirmed_on"),
         *      @SWG\Property(property="startdate", type="string", description="startdate"),
         *      @SWG\Property(property="enddate", type="string", description="enddate")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroup"),
         *      definition = "PardnaGroup",
         * 			required={"id", "name"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="name", type="string", description="the name of the pardna"),
         *      @SWG\Property(property="admin", type="string", description="the group admin id"),
         *      @SWG\Property(property="slots", type="string", description="the number of slots"),
         *      @SWG\Property(property="amount", type="string", description="the pardna amount"),
         *      @SWG\Property(property="frequency", type="string", description="the pardna group frequency"),
         *      @SWG\Property(property="created", type="string", description="the pardna creation date"),
         *      @SWG\Property(property="enddate", type="string", description="the pardna end date"),
         *      @SWG\Property(property="modified", type="string", description="the date when the pardna was last modified"),
         *      @SWG\Property(property="currency", type="string", description="the pardna group currency"),
         *      @SWG\Property(property="user_id", type="string", description="the user id"),
         *      @SWG\Property(property="editable", type="string", description="Denotes startdate, enddate can be edited"),
         *      @SWG\Property(property="members", type="array", @SWG\Items(ref="#/definitions/PardnaGroupMember")),
         *      @SWG\Property(property="member_key", type="string", description="the key denoting the position of the member in the members array"),
         *      @SWG\Property(property="status", ref="#/definitions/CodeDecodeObject", description="the parnda group status"),
         *      @SWG\Property(property="reason", type="array", @SWG\Items(type="string"), description="the reason for the pardna group status"),
         *      @SWG\Property(property="pardna_confirmed", ref="#/definitions/PardnaConfirmed"),
         *      @SWG\Property(property="pardna_slots", type="array", @SWG\Items(ref="#/definitions/PardnaSlot")),
         *      @SWG\Property(property="invites", type="array", @SWG\Items(ref="#/definitions/PardnaGroupInvite")),
         *      @SWG\Property(property="invitee_emails", type="array", @SWG\Items(type="string"))
         * 	),
         *  @SWG\Get(
         *    path="/pardna/group",
         *    tags={"groups"},
         *    operationId="readPardnaGroup",
         *    summary="Retrieves all the user pardna groups",
         *    description="Retrieves all the user pardna groups",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting relationships",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="relationships array",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/PardnaGroup"), type="array")
         *    )
         *  )
         */
        $api->get('/pardna/group', "pardna.group.controller:read");

        /**
         *  @SWG\Get(
         *    path="/pardna/group/details/{id}",
         *    tags={"groups"},
         *    operationId="pardnaGroupDetails",
         *    summary="Retrieves specified pardna group details",
         *    description="Retrieves the details of a specified pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required = true,
         *      description="The pardna group id",
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting list",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Pardna group",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/PardnaGroup"), type="array")
         *    )
         *  )
         */
        $api->get('/pardna/group/details/{id}', "pardna.group.controller:details");

        /**
         *  @SWG\Get(
         *    path="/pardna/group/slots/{id}",
         *    tags={"groups"},
         *    operationId="pardnaGroupSlots",
         *    summary="Retrieves a specified pardna group slots",
         *    description="Retrieves the slots for a specified pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required = true,
         *      description="The pardna group id",
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting relationships",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Error getting slots",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/PardnaSlot"), type="array")
         *    )
         *  )
         */
        $api->get('/pardna/group/slots/{id}', "pardna.group.controller:slots");

        /**
         *  @SWG\Get(
         *    path="/pardna/group/confirm/{id}",
         *    tags={"groups"},
         *    operationId="pardnaGroupConfirm",
         *    summary="Confirm that a specified pardna group",
         *    description="Confirm that a specified pardna group is about to start",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required = true,
         *      description="The pardna group id",
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting relationships",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Error getting slots",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/PardnaSlot"), type="array")
         *    )
         *  )
         */
        $api->get('/pardna/group/confirm/{id}', "pardna.group.controller:confirmPardna");

        /**
         *   @SWG\Definition(
         *      @SWG\Xml(name="GroupInvite"),
         *      definition = "GroupInvite",
         * 			required={"id", "name"},
         *      @SWG\Property(property="id", type="string", description="the pardna group id"),
         *      @SWG\Property(property="name", type="string", description="the pardna group name")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="InviteUserRequest"),
         *      definition = "InviteUserRequest",
         * 			required={"emails"},
         *      @SWG\Property(property="emails", type="string", description="comma separated emails"),
         *      @SWG\Property(property="message", type="string", description="the messsage to send in the email"),
         *      @SWG\Property(property="group", ref="#/definitions/GroupInvite")
         * 	),
         *  @SWG\Post(
         *    path="/invite",
         *    tags={"user","groups"},
         *    operationId="inviteUser",
         *    summary="Invite user/emails to join pardna or a pardna group",
         *    description="Invite user/emails to join pardna or a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="InviteUserRequest",
         *      in="body",
         *      required = true,
         *      description="The request object for invitations",
         *      @SWG\Schema(ref="#/definitions/InviteUserRequest")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Cannot send invitations",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Invitations sent",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/invite', "invitation.controller:save");

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="PardnaGroupInvitation"),
         *      definition = "PardnaGroupInvitation",
         * 			required={"id", "name"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         *      @SWG\Property(property="name", type="string", description="the name of the pardna"),
         *      @SWG\Property(property="admin", type="string", description="the group admin id"),
         *      @SWG\Property(property="slots", type="string", description="the number of slots"),
         *      @SWG\Property(property="amount", type="string", description="the pardna amount"),
         *      @SWG\Property(property="frequency", type="string", description="the pardna group frequency"),
         *      @SWG\Property(property="created", type="string", description="the pardna creation date"),
         *      @SWG\Property(property="enddate", type="string", description="the pardna end date"),
         *      @SWG\Property(property="modified", type="string", description="the date when the pardna was last modified"),
         *      @SWG\Property(property="currency", type="string", description="the pardna group currency"),
         *      @SWG\Property(property="members", type="array", @SWG\Items(ref="#/definitions/PardnaGroupMember"))
         * 	),
         *  @SWG\Get(
         *    path="/invite/group",
         *    tags={"groups"},
         *    operationId="pardnaGroupConfirm",
         *    summary="Retrieve a logged in user pardna groups",
         *    description="Retrieve all pardna group invites for a logged in user",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting list",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Error getting group invites",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/PardnaGroupInvitation"), type="array")
         *    )
         *  )
         */
        $api->get('/invite/group', "invitation.controller:readGroupInvitations");

        /**
         *   @SWG\Definition(
         *      @SWG\Xml(name="UserInvite"),
         *      definition = "UserInvite",
         * 			required={"fullname"},
         *      @SWG\Property(property="fullname", type="string", description="the invitor full name"),
         *      @SWG\Property(property="type_id", type="string", description="the type of invitation id"),
         *      @SWG\Property(property="invitation_id", type="string", description="the invitation id")
         * 	),
         *  @SWG\Get(
         *    path="/invite/user",
         *    tags={"user"},
         *    operationId="getUserInvitations",
         *    summary="Retrieves all user invitations",
         *    description="Retrieves all invitations to be friends",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="409",
         *      description="Error getting relationships",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Error getting user invitations",
         *      @SWG\Schema(@SWG\Items(ref="#/definitions/UserInvite"), type="array")
         *    )
         *  )
         */
        $api->get('/invite/user', "invitation.controller:readUserInvitations");

        /**
         *  @SWG\Definition(
         *    @SWG\Xml(name="InvitationObject"),
         *    definition = "InvitationObject",
         * 	  required={"id"},
         *    @SWG\Property(property="id", type="string", description="the invitation id")
         * 	),
         *  @SWG\Post(
         *    path="/invite/accept/user",
         *    tags={"user"},
         *    operationId="acceptUserInvite",
         *    summary="Accept user/emails invitations to join pardna or a pardna group",
         *    description="Accept user/emails invitations to join pardna or a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="InvitationObject",
         *      in="body",
         *      required = true,
         *      description="The invitation object",
         *      @SWG\Schema(ref="#/definitions/InvitationObject")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Invitation has already been accepted",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="403",
         *      description="Invitation not found",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Successfully accepted user invitation",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/invite/accept/user', "invitation.controller:acceptUserInvitation");

        /**
         *  @SWG\Definition(
         *    @SWG\Xml(name="InvitationObject"),
         *    definition = "InvitationObject",
         * 	  required={"id"},
         *    @SWG\Property(property="id", type="string", description="the invitation id")
         * 	),
         *  @SWG\Post(
         *    path="/invite/accept/group",
         *    tags={"groups"},
         *    operationId="acceptUserInvite",
         *    summary="Accept user/emails invitations to join pardna or a pardna group",
         *    description="Accept user/emails invitations to join pardna or a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="InvitationObject",
         *      in="body",
         *      required = true,
         *      description="The invitation object",
         *      @SWG\Schema(ref="#/definitions/InvitationObject")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="All slots have been claimed, cannot join the pardna",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="403",
         *      description="Invitation not found",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Successfully accepted user invitation",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/invite/accept/group', "invitation.controller:acceptGroupInvitation");

        /**
         *  @SWG\Definition(
         *    @SWG\Xml(name="InvitationObject"),
         *    definition = "InvitationObject",
         * 	  required={"id"},
         *    @SWG\Property(property="id", type="string", description="the invitation id")
         * 	),
         *  @SWG\Post(
         *    path="/invite/ignore/user",
         *    tags={"user"},
         *    operationId="ignoreUserInvite",
         *    summary="Ignore user/emails invitations to join pardna or a pardna group",
         *    description="Ignore user/emails invitations to join pardna or a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="InvitationObject",
         *      in="body",
         *      required = true,
         *      description="The invitation object",
         *      @SWG\Schema(ref="#/definitions/InvitationObject")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="All slots have been claimed, cannot join the pardna",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="403",
         *      description="Invitation not found",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Successfully ignored user invitation",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/invite/ignore/user', "invitation.controller:ignoreUserInvitation");

        /**
         *  @SWG\Definition(
         *    @SWG\Xml(name="InvitationObject"),
         *    definition = "InvitationObject",
         * 	  required={"id"},
         *    @SWG\Property(property="id", type="string", description="the invitation id")
         * 	),
         *  @SWG\Post(
         *    path="/invite/ignore/group",
         *    tags={"groups"},
         *    operationId="ignoreGroupInvite",
         *    summary="Ignore group invitations to join pardna or a pardna group",
         *    description="Ignore group invitations to join pardna or a pardna group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="InvitationObject",
         *      in="body",
         *      required = true,
         *      description="The invitation object",
         *      @SWG\Schema(ref="#/definitions/InvitationObject")
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Invitation has already been dealt with",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="403",
         *      description="Invitation not found",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Successfully ignored group invitation",
         *      @SWG\Schema(ref="#/definitions/MessageDefault")
         *    )
         *  )
         */
        $api->post('/invite/ignore/group', "invitation.controller:ignoreGroupInvitation");

        //Payments set up

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="AngularReturnState"),
         *      definition = "AngularReturnState",
         * 			required={"state_id", "state_name"},
         * 			@SWG\Property(property="state_id", type="string", description="The angular state id"),
         *      @SWG\Property(property="state_name", type="string", description="The angular sate name")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="PaymentSubscriptionUrlRequest"),
         *      definition = "PaymentSubscriptionUrlRequest",
         * 			required={"return_to"},
         * 			@SWG\Property(property="return_to", description="return_to", ref="#/definitions/AngularReturnState")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="PaymentSubscriptionUrlResponse"),
         *      definition = "PaymentSubscriptionUrlResponse",
         * 			required={"payment_url"},
         * 			@SWG\Property(property="payment_url", type="string", description="payment_url")
         * 	),
         *  @SWG\Post(
         *    path="/group/payments/getPaymentUrl",
         *    tags={"payments"},
         *    operationId="getPaymentUrl",
         *    summary="Retrieves the link for gocardless mandate setup",
         *    description="Retrieves the link to set up mandate with gocardless",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="PaymentSubscriptionUrlRequest",
         *      in="body",
         *      required = true,
         *      description="The payment subscription url request",
         *      @SWG\Schema(ref="#/definitions/PaymentSubscriptionUrlRequest")
         *    ),
         *    @SWG\Response(
         *      response="503",
         *      description="Error getting subscription url",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Bank accounts array",
         *      @SWG\Schema(ref="#/definitions/PaymentSubscriptionUrlResponse")
         *    )
         *  )
         */
        $api->post('/group/payments/getPaymentUrl', "pardna.payments.controller:getGroupPaymentsSubscriptionUrl");

        $api->post('/payments/confirm', "pardna.payments.controller:completeRedirectFlow");

        /**
         *  @SWG\Definition(
         *    @SWG\Xml(name="GroupStatus"),
         *    definition = "GroupStatus",
         * 	  required={"id"},
         *    @SWG\Property(property="status", ref="#/definitions/CodeDecodeObject"),
         *    @SWG\Property(property="reason", type="array", @SWG\Items(ref="#/definitions/CodeDecodeObject"))
         * 	),
         *  @SWG\Post(
         *    path="/group/payments/getGroupStatus/{id}",
         *    tags={"groups"},
         *    operationId="ignoreGroupInvite",
         *    summary="Get a specified group payment status",
         *    description="Get the payment status for a specified group",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required = true,
         *      description="The pardna group id",
         *    ),
         *    @SWG\Response(
         *      response="409",
         *      description="Invitation has already been dealt with",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Group status object",
         *      @SWG\Schema(ref="#/definitions/GroupStatus")
         *    )
         *  )
         */
        $api->post('/group/payments/getGroupStatus/{id}', "pardna.payments.controller:getGroupStatus");

        $api->get('/group/user/payments/status/{id}', "pardna.payments.controller:userGroupPaymentStatus");

        $api->get('/group/subscription/get/{id}', "pardna.payments.controller:getSubscription");

        $api->post('/group/payment/setup', "pardna.payments.controller:setUpPayment");

        $api->get('/group/subscriptions/create/{id}', "pardna.payments.controller:triggerMassSubscriptionCreation");

        $api->get('/group/subscription/create/{id}', "pardna.payments.controller:createSubscription");

        $api->get('/group/payment/create/{id}', "pardna.payments.controller:createPayment");

        $api->get('/group/subscription/cancel/{id}', "pardna.payments.controller:cancelSubscription");

        // User Account

        /**
         *  @SWG\Definition(
         *      @SWG\Xml(name="BankAcount"),
         *      definition = "BankAcount",
         * 			required={"id", "fullname"},
         * 			@SWG\Property(property="id", type="string", description="id"),
         * 			@SWG\Property(property="account_number_ending", type="string", description="account_number_ending"),
         * 			@SWG\Property(property="account_holder_name", type="string", description="account_holder_name"),
         * 			@SWG\Property(property="bank_name", type="string", description="bank_name"),
         * 			@SWG\Property(property="currency", type="string", description="currency"),
         * 			@SWG\Property(property="country_code", type="string", description="country_code"),
         * 			@SWG\Property(property="created_at", type="string", description="created_at"),
         * 			@SWG\Property(property="metadata", type="string", description="metadata"),
         * 			@SWG\Property(property="enabled", type="string", description="enabled"),
         * 			@SWG\Property(property="links", type="string", description="links")
         * 	),
         *  @SWG\Definition(
         *      @SWG\Xml(name="GetUserBankAcountResponse"),
         *      definition = "GetUserBankAcountResponse",
         * 			required={"bankaccounts"},
         * 			@SWG\Property(property="bankaccounts", type="array", description="bankaccounts", @SWG\Items(ref="#/definitions/BankAcount"))
         * 	),
         *  @SWG\Get(
         *    path="/user/bankaccounts/{id}",
         *    tags={"payments"},
         *    operationId="usersBankAccounts",
         *    summary="Retrieves the logged user bank account",
         *    description="Retrieves a specified bank account for the logged user",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Parameter(
         *      name="id",
         *      in="path",
         *      required = true,
         *      description="The bank account id",
         *    ),
         *    @SWG\Response(
         *      response="503",
         *      description="Error retrieving bank account",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Bank accounts array",
         *      @SWG\Schema(ref="#/definitions/GetUserBankAcountResponse")
         *    )
         *  )
         */
        $api->get('/user/bankaccounts/{id}', "pardna.payments.controller:getUserBankAccount");

        /**
         *  @SWG\Post(
         *    path="/user/bankaccounts",
         *    tags={"payments"},
         *    operationId="usersBankAccounts",
         *    summary="Retrieves the logged user relationships",
         *    description="Retrieves all the relationships for the logged user",
         *    consumes={"application/json"},
         *    produces={"application/json"},
         *    @SWG\Response(
         *      response="503",
         *      description="Error retrieving bank accounts",
         *      @SWG\Schema(ref="#/definitions/ErrorDefault")
         *    ),
         *    @SWG\Response(
         *      response="200",
         *      description="Bank accounts array",
         *      @SWG\Schema(ref="#/definitions/GetUserBankAcountResponse")
         *    )
         *  )
         */
        $api->post('/user/bankaccounts', "pardna.payments.controller:retrieveAllUserBankAccounts");

        //Payments events

        $api->post('/payments/events/process/{event_id}', "payments.events.controller:processEvent");


        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
