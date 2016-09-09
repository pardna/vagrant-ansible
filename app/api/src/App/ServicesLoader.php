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

      $this->app['subscriptions.service'] = $this->app->share(function (){
        $subscriptionsService = new Services\common\payments\SubscriptionsService($this->app["db"]);
        $subscriptionsService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $subscriptionsService;
      });

      $this->app['mandates.service'] = $this->app->share(function (){
        $mandatesService = new Services\common\payments\MandatesService($this->app["db"]);
        $mandatesService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $mandatesService;
      });

      $this->app['payments.service'] = $this->app->share(function (){
        $paymentsService = new Services\common\payments\PaymentsService($this->app["db"]);
        $paymentsService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $paymentsService;
      });

      $this->app['refunds.service'] = $this->app->share(function (){
        $refundsService = new Services\common\payments\RefundsService($this->app["db"]);
        $refundsService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $refundsService;
      });

      $this->app['payouts.service'] = $this->app->share(function (){
        $payoutsService = new Services\common\payments\PayoutsService($this->app["db"]);
        $payoutsService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $payoutsService;
      });

      $this->app['events.service'] = $this->app->share(function (){
        $eventsService = new Services\common\payments\EventsService($this->app["db"]);
        $eventsService->setGoCardlessProClient($this->app['gocardlesspro.client']);
        return $eventsService;
      });

      $this->app['payments.setup.service'] = $this->app->share(function (){
        $setUpService = new Services\payments\setup\PaymentsSetupService($this->app['redirectflow.service'], $this->app['subscriptions.service']);
        return $setUpService;
      });

      $this->app['payments.manage.service'] = $this->app->share(function (){
        $setUpService = new Services\payments\manage\PaymentsManageService($this->app['subscriptions.service']);
        return $setUpService;
      });

      $this->app['payments.events.service'] = $this->app->share(function (){
        $paymentEventsService = new Services\payments\manage\PaymentEventsService($this->app['events.service'], $this->app['mandates.service'], $this->app['payments.service'], $this->app['subscriptions.service'], $this->app['refunds.service'], $this->app['payouts.service']);
        return $paymentEventsService;
      });

      $this->app['notification.service'] = $this->app->share(function () {
        return new Services\common\notifications\NotificationService($this->app["db"]);
      });

    }
}
