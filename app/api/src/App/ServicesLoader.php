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

      $this->app['mail.service'] = $this->app->share(function (){
        $mailService = new Services\MailService($this->app["db"]);
        $mailService->setMailChimpsClient($this->app["mailChimps"]["API_KEY"]);
        $mailService->setPardnaAccConfirmListId($this->app["mailChimps"]["pardna_acc_confirm_list_id"]);
        //$mailChimpsClient->lists->subscribe("testid", array('email'=>"testemail"));
        //var_dump($mailChimpsClient);
        //var_dump($mailService);
        return $mailService;
      });

      $this->app['notification.service'] = $this->app->share(function () {
        return new Services\NotificationService($this->app["db"]);
      });

    }
}
