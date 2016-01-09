<?php
/**
* Pardna groups
*
*/
namespace App\Controllers;
class AppController
{
    protected $user;
    protected $service;
    protected $app;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function setUser($user) {
      $this->user = $user;
      return $this;
    }

    public function getUser() {
      return $this->user;
    }

    public function setApp($app) {
      $this->app = $app;
      return $this;
    }

    public function getApp() {
      return $this->app;
    }

}
