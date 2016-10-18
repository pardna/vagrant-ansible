<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class GoCardlessProClient
{
    private $client;

    public function setClient($config, $gocardless_env)
    {
        // Initialize GoCardless
        $gocarless_env = $this->goCardlessEnvironment($gocardless_env['environment']);
        $access_token = $config['access_token'];
        $this->client = new \GoCardlessPro\Client(array(
          'access_token' => $access_token,
          'environment'  => $gocarless_env
        ));
    }

    public function setOtherClient($client)
    {
      $this->client = $client;
    }

    public function getClient(){
      return $this->client;
    }

    public function goCardlessEnvironment($environment)
    {
        if (isset($environment) && strcasecmp($environment, "live") == 0){
          return \GoCardlessPro\Environment::LIVE;
        } else {
          return \GoCardlessPro\Environment::SANDBOX;
       }
    }

}
