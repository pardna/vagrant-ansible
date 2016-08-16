<?php
namespace App;
use Silex\Application;
class ServicesLoader
{
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function bindServicesIntoContainer()
    {
      $this->app['users.service'] = $this->app->share(function () {
        $twillioClient = new \Services_Twilio($this->app["twillio"]["account_sid"], $this->app["twillio"]["account_token"]);
        $service = new Services\UsersService($this->app["db"]);
        $service->setTwillioCLient($twillioClient);

        return $service;
      });

      $this->app['invitation.service'] = $this->app->share(function () {
        $service = new Services\InvitationService($this->app["db"]);
        return $service;
      });

      $this->app['relationship.service'] = $this->app->share(function () {
        $service = new Services\RelationshipService($this->app["db"]);
        return $service;
      });

      $this->app['pardna.group.service'] = $this->app->share(function () {
        $service = new Services\PardnaGroupService($this->app["db"]);
        $service->setInvitationService($this->app['invitation.service']);
        return $service;
      });

      $this->app['email.mailchimps.service'] = $this->app->share(function (){
        $mailChimpsMailService = new Services\common\email\MailChimpsService($this->app["db"]);
        $mailChimpsMailService->setMailChimpsClient($this->app["mailChimps"]["API_KEY"]);
        $mailChimpsMailService->setPardnaAccConfirmListId($this->app["mailChimps"]["pardna_acc_confirm_list_id"]);
        return $mailChimpsMailService;
      });

      $this->app['mandrill.mail.service'] = $this->app->share(function (){
        $mandrillMailService = new Services\common\email\MandrillMailService($this->app["db"]);
        $mandrillMailService->setMandrillMailClient($this->app["mandrill"]["API_KEY"]);
        return $mandrillMailService;
      });

      $this->app['pardna.group.status.service'] = $this->app->share(function (){
        $pardnaGroupStatusService = new Services\PardnaGroupStatusService($this->app["db"]);
        $pardnaGroupStatusService->setPardnaGroupService($this->app['pardna.group.service']);
        return $pardnaGroupStatusService;
      });

      $this->app['gocardlesspro.client'] = $this->app->share(function (){
        $gocardlessProClient = new Services\common\payments\GoCardlessProClient($this->app["db"], $this->app["gocardless_pro"], $this->app['gocardless_env']);
        return $gocardlessProClient;
      });

      $this->app['redirectflow.service'] = $this->app->share(function (){
        $redirectflowService = new Services\common\payments\RedirectFlowService($this->app["db"]);
        $redirectflowService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $redirectflowService;
      });

      $this->app['payments.setup.service'] = $this->app->share(function (){
        $setUpService = new Services\payments\setup\PaymentsSetupService($this->app['redirectflow.service']);
        return $setUpService;
      });

      $this->app['notification.service'] = $this->app->share(function () {
        return new Services\common\notifications\NotificationService($this->app["db"]);
      });

    }
}
