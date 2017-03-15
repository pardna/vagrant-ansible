<?php
namespace Tests\Controllers;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Controllers\PaymentsController;
use App\Services\payments\setup\PaymentsSetupService;
use App\Entity\UserEntity;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use App\Services\common\payments\RedirectFlowService;
use App\Services\common\payments\SubscriptionsService;
use App\Services\common\payments\GoCardlessProClient;

class PaymentsSetupServiceTest extends \PHPUnit_Framework_TestCase
{
    private $paymentsSetupService;

    public function setUp()
    {
      $app = new Application();
      $app->register(new DoctrineServiceProvider(), array(
          "db.options" => array(
              "driver" => "pdo_sqlite",
              "memory" => true
          ),
      ));

      $app["db"]->prepare("
      CREATE TABLE `configurations` (
        `id` bigint(20) NOT NULL,
        `name` varchar(50) NOT NULL,
        `value` varchar(250) NOT NULL,
        PRIMARY KEY (`id`)
      );")->execute();
      $app["db"]->prepare("
      INSERT INTO configurations (id, name, value) VALUES
      (1, 'gocardless_success_redirect_url', 'http://192.168.33.99/app/frontend/dist/#/payment/confirm');
      ")->execute();

      //$client = new GoCardlessProClient();
      //$gocardlessProClientObserver = $this->getObserverForClassAndMethods(\GoCardlessPro\Client::class, ['redirectFlows']);
      //$client->setOtherClient($gocardlessProClientObserver);
      //$this->expectCompleteRedirectFlowReturnsSuccessfulResponse($gocardlessProClientObserver);
      $gc_success_url = 'http://192.168.33.99/app/frontend/dist/#/payment/confirm';
      $redirectFlowServiceObserver = $this->getObserverForClassAndMethods(RedirectFlowService::class, ['completeRedirectFlow', 'getSuccesssRedirectUrl']);
      $this->returnsCorrectGCSuccessUrl($redirectFlowServiceObserver, $gc_success_url);
      $this->paymentsSetupService = new PaymentsSetupService($redirectFlowServiceObserver, null);
      $app["db"]->prepare("
      PRAGMA foreign_keys = ON;
      CREATE TABLE `gocardless_customers` (
        `id` int(11) NOT NULL,
        `cust_id` varchar(20) NOT NULL,
        `cust_bank_account` varchar(20) NOT NULL,
        `created` datetime NOT NULL,
        `modified` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `cust_id` (`cust_id`),
        KEY `pardnagroup_member_id` (`pardnagroup_member_id`)
      );")->execute();
      $app["db"]->prepare("
      PRAGMA foreign_keys = ON;
      CREATE TABLE `gocardless_mandates` (
        `id` int(11) NOT NULL,
        `cust_id` varchar(20) NOT NULL,
        `mandate_id` varchar(20) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `cust_id` (`cust_id`),
        KEY `mandate_id` (`mandate_id`)
      );")->execute();
    }

    public function testCompleteRedirectFlowSuccessfullyCreatesRecordInDb()
    {
      //1. Prepare data
      $token = "PRDNA_SESS_testdudidlomfdfddfffffsssd";
      $redirect_flow_id = "RE0000779K3ENXF44ZKEF90Z8J48VZ7Q";
      //2. Prepare expectations
      $redirectFlowServiceObserver = $this->getObserverForClassAndMethods(RedirectFlowService::class, ['completeRedirectFlow']);
      $this->expectCompleteRedirectFlowReturnsSuccessfulResponse($redirectFlowServiceObserver);
      //3. Make call to service
      $this->paymentsSetupService->completeReturnFromRedirectFlow($token, $redirect_flow_id);
      //4. Assert that db has created records
      $this->assertRecordsExistOfMandateAsWellAsGCCustomerRecord($response);
    }

    public function getObserverForClassAndMethods($class, $methodsArray)
    {
      $observer = $this->getMockBuilder($class)
                        ->disableOriginalConstructor()
                        ->setMethods($methodsArray)
                        ->getMock();
      return $observer;
    }

    public function returnsCorrectGCSuccessUrl($observer, $url)
    {
      $observer->expects($this->once())
               ->method('getSuccesssRedirectUrl')
               ->with()
               ->will($this->returnValue($url));
    }


    public function expectCompleteRedirectFlowReturnsSuccessfulResponse($redirectFlowServiceObserver)
    {
      $customer = $this->getCreatedCustomerMandate();
      $redirectFlow = array();
      $redirectFlow['id'] = "RE123";
      $redirectFlow['description'] = "Wine boxes";
      $redirectFlow['session_token'] = "SESS_wSs0uGYMISxzqOBq";
      $redirectFlow['success_redirect_url'] = "SESS_wSs0uGYMISxzqOBq";
      $redirectFlow['created_at'] = "2014-10-22T13:10:06.000Z";
      $links = array();
      $links["creditor"] = "CR123";
      $links["mandate"] = $customer['mandate_id'];
      $links["customer"] = $customer['cust_id'];
      $links["customer_bank_account"] = $customer['cust_bank_account'];
      $redirectFlow['links'] = $links;

      $response = new \GoCardlessPro\Resources\RedirectFlow($redirectFlow);

      $redirectFlowServiceObserver->expects($this->once())
               ->method('completeRedirectFlow')
               ->with()
               ->will($this->returnValue($response));
    }

    public function assertRecordsExistOfMandateAsWellAsGCCustomerRecord($response)
    {
      $cust_id = $response['cust_id'];
      $cust_bank_account = $response['cust_bank_account'];
      $mandate_id = $response['mandate_id'];
      $this->assertTrue($this->db->fetchAssoc("SELECT * FROM 'gocardless_customers' WHERE cust_id = ? AND cust_bank_account = ?  LIMIT 1", array($cust_id, $cust_bank_account)));
      $this->assertTrue($this->db->fetchAssoc("SELECT * FROM 'gocardless_mandates' WHERE cust_id = ? AND mandate_id = ?  LIMIT 1", array($cust_id, $mandate_id)));
    }

    public function getCreatedCustomerMandate(){
      $response = array();
      $response['cust_id'] = "CU123";
      $response['cust_bank_account'] = "BA123";
      $response['mandate_id'] = "MD123";
      return $response;
    }

}
