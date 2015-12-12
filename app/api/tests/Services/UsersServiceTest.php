<?php
namespace Tests\Services;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Services\UsersService;
class UsersServiceTest extends \PHPUnit_Framework_TestCase
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
        
        $this->userService = new UsersService($app["db"]);
        $stmt = $app["db"]->prepare("
        CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR NOT NULL,
        password VARCHAR NOT NULL,
        fullname VARCHAR NOT NULL,
        salt VARCHAR NOT NULL
        )");
        $stmt->execute();
    }
    public function testGetAll()
    {
        $data = $this->userService->getAll();
        $this->assertNotNull($data);
    }

    function testSave()
    {
        $user = array("email" => "joe@bloggs.com", "password" => "password123", "fullname" => "Joe Bloggs");
        $id = $this->userService->save($user);
        $data = $this->userService->getAll();
        print_r($data);
        $this->assertEquals(1, count($data));
    }

    function testChangePassword()
    {
        $user = array("email" => "joe.change.password@bloggs.com", "password" => "password12345", "fullname" => "Joe Bloggs");
        $id = $this->userService->save($user);
        // print_r($this->userService->get($id));
        $password = "password123456";
        $id = $this->userService->changePassword($user["email"], $password);
        $data = $this->userService->getByEmail($user["email"]);
        // print_r($data);
        $pToken = $this->userService->prependToken($data['salt'], $password);
        $this->assertEquals($data['password'], $this->userService->hash($pToken));
    }

    function testUpdate()
    {
        $user = array("email" => "joe1@bloggs.com", "password" => "password123", "fullname" => "Joe Bloggs");
        $id = $this->userService->save($user);
        $user = array("email" => "joe1@bloggs.com", "password" => "password123", "fullname" => "Joe Bloggsey");
        $this->userService->update($id, $user);
        $data = $this->userService->get($id);
        $this->assertEquals($user["fullname"], $data["fullname"]);
    }

    function testDelete()
    {
        $user = array("email" => "joe2@bloggs.com", "password" => "password123", "fullname" => "Joe Bloggsey");
        $id = $this->userService->save($user);
        $this->userService->delete($id);
        $data = $this->userService->get($id);
        $this->assertEquals(0, false);
    }
}
