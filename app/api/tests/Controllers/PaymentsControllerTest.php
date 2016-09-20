<?php
namespace Tests\Controllers;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Controllers\PaymentsController;
use App\Services\payments\setup\PaymentsSetupService;
use App\Entity\UserEntity;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;

class PaymentsControllerTest extends \PHPUnit_Framework_TestCase
{
    private $paymentsController;

    public function setUp()
    {

    }

    public function testGetGroupPaymentsSubscriptionUrlReturnsUrl()
    {
      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['getRedirectUrl']);
      $request = new Request();
      $request->createFromGlobals();

      $mockUrl = "https://pay-sandbox.gocardless.com/flow/RE000075DWVWSFBVKM1JAKAP9CKS8J4Y";
      $this->expectGetRedirectUrlIsCalled($paymentsSetupObserver, $mockUrl);
      $user = $this->getUser();
      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      $json_response = $paymentsController->getGroupPaymentsSubscriptionUrl($request);
      $this->assertJsonStringEqualsJsonString(
          $json_response->getContent(), json_encode(['payment_url' => $mockUrl])
      );
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testGetGroupPaymentsSubscriptionUrlReturnsHttpException()
    {
      $request = new Request();
      $request->createFromGlobals();
      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['getRedirectUrl']);
      $this->expectGetRedirectUrlIsCalled($paymentsSetupObserver, new \Exception);
      $user = $this->getUser();
      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      $json_response = $paymentsController->getGroupPaymentsSubscriptionUrl($request);
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testGetGroupPaymentsSubscriptionUrlReturns503ServiceUnavailableHttpException()
    {
      $request = new Request();
      $request->createFromGlobals();
      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['getRedirectUrl']);
      $this->expectGetRedirectUrlIsCalled($paymentsSetupObserver, new \Exception);
      $user = $this->getUser();
      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      try{
        $json_response = $paymentsController->getGroupPaymentsSubscriptionUrl($request);
      } catch(HttpException $e)
      {
        $this->assertEquals(503, $e->getStatusCode());
        throw $e;
      }

    }

    public function getObserverForClassAndMethods($class, $methodsArray)
    {
      $observer = $this->getMockBuilder($class)
                        ->disableOriginalConstructor()
                        ->setMethods($methodsArray)
                        ->getMock();
      return $observer;
    }

    public function expectGetRedirectUrlIsCalled($paymentsSetupObserver, $returnVal)
    {
      if (isset($returnVal)){
        if (is_string($returnVal)){
          $paymentsSetupObserver->expects($this->once())
                       ->method('getRedirectUrl')
                       ->with(
                           $this->anything(),
                           $this->anything()
                       )
                       ->will($this->returnValue($returnVal));
        } else if ($returnVal instanceof \Exception){
          $paymentsSetupObserver->expects($this->once())
                       ->method('getRedirectUrl')
                       ->with(
                           $this->anything(),
                           $this->anything()
                       )
                      ->will($this->throwException($returnVal));
        }
      }
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
