<?php
namespace Tests\Controllers;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Controllers\PaymentsController;
use App\Services\payments\setup\PaymentsSetupService;
use App\Entity\UserEntity;

class PaymentsControllerTest extends \PHPUnit_Framework_TestCase
{
    private $paymentsController;

    public function setUp()
    {

    }

    public function testGetGroupPaymentsSubscriptionUrlReturnsUrl()
    {
      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['getRedirectUrl']);
      $mockUrl = "https://pay-sandbox.gocardless.com/flow/RE000075DWVWSFBVKM1JAKAP9CKS8J4Y";
      $this->expectGetRedirectUrlIsCalled($paymentsSetupObserver, $mockUrl);
      $user = $this->getUser();
      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      $json_response = $paymentsController->getGroupPaymentsSubscriptionUrl("2");
      $this->assertJsonStringEqualsJsonString(
          $json_response->getContent(), json_encode(['payment_url' => $mockUrl])
      );
    }

    public function getObserverForClassAndMethods($class, $methodsArray)
    {
      $observer = $this->getMockBuilder($class)
                        ->disableOriginalConstructor()
                        ->setMethods($methodsArray)
                        ->getMock();
      return $observer;
    }

    public function expectGetRedirectUrlIsCalled($paymentsSetupObserver, $mockReturnUrl)
    {
      $paymentsSetupObserver->expects($this->once())
                       ->method('getRedirectUrl')
                       ->with(
                           $this->anything(),
                           $this->anything(),
                           $this->anything()
                       )
                       ->will($this->returnValue($mockReturnUrl));
    }

    public function getUser()
    {
      $user = new UserEntity();
      $user->setId('1');
      $user->setFullName('Test User');
      $user->setMembershipNumber('TSTUSR1233');
      return $user;
    }
}
