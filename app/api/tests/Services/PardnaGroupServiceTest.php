<?php
namespace Tests\Services;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Services\PardnaGroupService;
class PardnaGroupServiceTest extends \PHPUnit_Framework_TestCase
{
    private $userService;
    public function setUp()
    {
        $app = new Application();
        $app->register(new DoctrineServiceProvider(), array(
            "db.options" => array(
                "driver" => "pdo_sqlite",
                "memory" => true
            ),
        ));
        $this->service = new PardnaGroupService($app["db"]);
        $stmt = $app["db"]->prepare("
        CREATE TABLE pardnagroups (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR NOT NULL,
        start_date date NOT NULL,
        amount decimal(10, 2) NOT NULL,
        frequency VARCHAR NOT NULL,
        admin VARCHAR NOT NULL,
        status VARCHAR NOT NULL
        )");
        $stmt->execute();
    }

    public function testGetAll()
    {
        $data = $this->service->getAll();
        $this->assertNotNull($data);
    }


    function testSave()
    {
        $group = array("name" => "Holiday Fund", "start_date" => "2015-12-15", "amount" => 100, "frequency" => "monthly", "status" => "pending", "admin" => "009009877777");
        $id = $this->service->save($group);
        $data = $this->service->getAdminGroups($group["admin"]);
        $this->assertEquals(1, count($data));
    }

}
