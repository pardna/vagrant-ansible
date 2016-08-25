<?php
namespace Tests\Entity;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Services\PardnaGroupService;
use App\Entity\LegacySubscriptionEntity;
class SubscriptionEntityTest extends \PHPUnit_Framework_TestCase
{
    private $userService;
    public function setUp()
    {

    }

    public function testEntityArray()
    {
      $data = array (
        'amount' => '10.00',
        'name' => 'Test',
        'frequency' => 'monthly',
        'startdate' => '2016-04-19T23:00:00.000Z'
      );
      $subscription = new LegacySubscriptionEntity();
      $subscription->setAmount(strval($data["amount"]));
      $subscription->setName("Pardna " . $data["name"]);
      $subscription->setDescription("This is going to run for the Pardna " . $data["name"] . " ." );
      $subscription->setRedirect_uri('http://www.pardna.com/success');
      $subscription->setCancel_uri('http://www.pardna.com/failure');
      $subscription->setState('token="id_9SX5G36"');
      $subscription->setInterval_length(1);
      $subscription->setInterval_unit($data["frequency"]);
      $subscription->setStart_at($data["startdate"]);

      print_r ($subscription->__toArray());

      print_r((array) $subscription);

      print_r(array_filter($subscription->__toArray()));



    }
}
