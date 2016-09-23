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

    public function testCompleteRedirectFlowSuccessfullyCreatesTheMandate()
    {
      $request = new Request();
      $user = $this->getUser();
      $request->createFromGlobals();
      $parameters = array();
      $parameters['membership_number'] = $user->getMembershipNumber();
      $parameters['redirect_flow_id'] = 'RE0000779K3ENXF44ZKEF90Z8J48VZ7Q';
      $request->request->set('membership_number', $parameters['membership_number']);
      $request->request->set('redirect_flow_id', $parameters['redirect_flow_id']);

      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['completeReturnFromRedirectFlow']);
      $this->expectCompleteReturnFromRedirectFlowIsCalled($paymentsSetupObserver, $parameters['redirect_flow_id'], true);

      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      $json_response = $paymentsController->completeRedirectFlow($request);
      $this->assertJsonStringEqualsJsonString(
          $json_response->getContent(), json_encode(['message' => 'Mandate Successfully created'])
      );
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCompleteRedirectFailsBecauseMembershipNumberDoNotMatch()
    {
      $request = new Request();
      $user = $this->getUser();
      $request->createFromGlobals();
      $parameters = array();
      $parameters['membership_number'] = 'FAKE_MEMBERSHIP_NUMBER';
      $parameters['redirect_flow_id'] = 'RE0000779K3ENXF44ZKEF90Z8J48VZ7Q';
      $request->request->set('membership_number', $parameters['membership_number']);
      $request->request->set('redirect_flow_id', $parameters['redirect_flow_id']);

      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['completeReturnFromRedirectFlow']);
      $this->expectCompleteReturnFromRedirectFlowIsCalled($paymentsSetupObserver, $parameters['redirect_flow_id'], true);

      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      try{
        $json_response = $paymentsController->completeRedirectFlow($request);
      } catch(HttpException $e)
      {
        $this->assertEquals(401, $e->getStatusCode());
        throw $e;
      }
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCompleteReturns403HttpExceptionCouldNotCreateMandate()
    {
      $request = new Request();
      $user = $this->getUser();
      $request->createFromGlobals();
      $parameters = array();
      $parameters['membership_number'] = $user->getMembershipNumber();
      $parameters['redirect_flow_id'] = 'RE0000779K3ENXF44ZKEF90Z8J48VZ7Q';
      $request->request->set('membership_number', $parameters['membership_number']);
      $request->request->set('redirect_flow_id', $parameters['redirect_flow_id']);

      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['completeReturnFromRedirectFlow']);
      $this->expectCompleteReturnFromRedirectFlowIsCalled($paymentsSetupObserver, $parameters['redirect_flow_id'], new \GoCardlessPro\Core\Exception\InvalidApiUsageException(new \Exception()));

      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      try{
        $json_response = $paymentsController->completeRedirectFlow($request);
      } catch(HttpException $e)
      {
        $this->assertEquals(403, $e->getStatusCode());
        throw $e;
      }
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCompleteReturns409HttpExceptionMandateAlreadyCompleted()
    {
      $request = new Request();
      $user = $this->getUser();
      $request->createFromGlobals();
      $parameters = array();
      $parameters['membership_number'] = $user->getMembershipNumber();
      $parameters['redirect_flow_id'] = 'RE0000779K3ENXF44ZKEF90Z8J48VZ7Q';
      $request->request->set('membership_number', $parameters['membership_number']);
      $request->request->set('redirect_flow_id', $parameters['redirect_flow_id']);

      $paymentsSetupObserver = $this->getObserverForClassAndMethods(PaymentsSetupService::class, ['completeReturnFromRedirectFlow']);
      $this->expectCompleteReturnFromRedirectFlowIsCalled($paymentsSetupObserver, $parameters['redirect_flow_id'], new \GoCardlessPro\Core\Exception\InvalidStateException(new \Exception()));

      $paymentsController = new PaymentsController($paymentsSetupObserver);
      $paymentsController->setUser($user);
      try{
        $json_response = $paymentsController->completeRedirectFlow($request);
      } catch(HttpException $e)
      {
        $this->assertEquals(409, $e->getStatusCode());
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

    public function expectCompleteReturnFromRedirectFlowIsCalled($paymentsSetupObserver, $redirect_flow_id, $returnVal)
    {
      if (isset($returnVal)){
        if (is_string($returnVal)){
          $paymentsSetupObserver->expects($this->once())
                       ->method('completeReturnFromRedirectFlow')
                       ->with(
                           $this->anything(),
                           $this->stringContains($redirect_flow_id)
                       )
                       ->will($this->returnValue($returnVal));
        } else if ($returnVal instanceof \Exception){
          $paymentsSetupObserver->expects($this->once())
                       ->method('completeReturnFromRedirectFlow')
                       ->with(
                           $this->anything(),
                           $this->stringContains($redirect_flow_id)
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
