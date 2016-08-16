<?php
namespace App\Services\common\payments;
use App\Services\BaseService;

class GoCardlessProClient
{
    public $client;

    public function __construct($db, $config, $gocardless_env)
    {
        // Initialize GoCardless
        $this->db = $db;
        $gocarless_env = $this->goCardlessEnvironment($gocardless_env['environment']);
        $access_token = $config['access_token'];
        $this->client = new \GoCardlessPro\Client(array(
          'access_token' => $access_token,
          'environment'  => $gocarless_env
        ));
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
