<?php
namespace Tests\Services;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Services\RelationshipService;

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
        $this->service = new RelationshipService($app["db"]);
        $this->service->setTable("relationships");
        $stmt = $app["db"]->prepare("
        CREATE TABLE  {$this->service->getTable()} (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_1 VARCHAR NOT NULL,
        user_2 VARCHAR NOT NULL,
        status VARCHAR NOT NULL,
        created DATETIME NOT NULL,
        modified DATETIME NOT NULL
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
        $d1 = array("user_1" => "0991774774", "user_2" => "08789976655");
        $d2 = array("user_1" => "0991774774", "user_2" => "08876666677");
        $d3 = array("user_1" => "0887788866", "user_2" => "0991774774");
        $d4 = array("user_1" => "0991774774", "user_2" => "08878999999");
        $d5 = array("user_1" => "0991774774", "user_2" => "89966999999");

        $id = $this->service->save($d1);
        $this->assertEquals(1, count($this->service->getAll()));
        $id = $this->service->save($d2);
        $id = $this->service->save($d3);
        $id = $this->service->save($d4);
        $this->assertEquals(4, count($this->service->getAll()));
        $id = $this->service->save($d5);
        $this->assertEquals(5, count($this->service->getUserRelationships($d1["user_1"])));


    }

}
