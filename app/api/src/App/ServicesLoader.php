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

      $this->app['mail.service'] = $this->app->share(function (){
        $mailService = new Services\MailService($this->app["db"]);

      });
      $this->app['groups.setup.service'] = $this->app->share(function () {
        $service = new Services\groups\setup\GroupsSetupService($this->app["db"]);
        return $service;
      });

      $this->app['groups.manage.service'] = $this->app->share(function () {
        $service = new Services\groups\manage\GroupsManageService($this->app["db"]);
        return $service;
      });

      $this->app['pardna.setup.service'] = $this->app->share(function () {
        $service = new Services\pardna\setup\PardnaSetupService($this->app["db"]);
        return $service;
      });

      $this->app['pardna.manage.service'] = $this->app->share(function () {
        $service = new Services\pardna\manage\PardnaManageService($this->app["db"]);
        return $service;
      });

      $this->app['email.mailchimps.service'] = $this->app->share(function (){
        $mailService = new Services\common\email\MailChimpsService($this->app["db"]);
        $mailService->setMailChimpsClient($this->app["mailChimps"]["API_KEY"]);
        $mailService->setPardnaAccConfirmListId($this->app["mailChimps"]["pardna_acc_confirm_list_id"]);
        return $mailService;
      });

      $this->app['mandrill.mail.service'] = $this->app->share(function (){
        $mandrillMailService = new Services\common\email\MandrillMailService($this->app["db"]);
        $mandrillMailService->setMandrillMailClient($this->app["mandrill"]["API_KEY"]);
        return $mandrillMailService;
      });

      $this->app['notification.service'] = $this->app->share(function () {
        return new Services\NotificationService($this->app["db"]);
      });

    }
}
