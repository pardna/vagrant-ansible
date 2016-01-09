<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RelationshipService extends BaseService
{

  protected $table = "relationships";

  function save($data)
  {
    $data = $this->sortUser($data);
    $data = $this->appendCreatedModified($data);
    $data["status"] = isset($data["status"]) ? $data["status"] : "pending";
    if(!$this->exists($data)) {
      $user1 = $this->getUser($data["user_1"]);
      $user2 = $this->getUser($data["user_2"]);
      $data["fullname_1"] = $user1["fullname"];
      $data["fullname_2"] = $user2["fullname"];
      $this->db->insert($this->table, $data);
      return $this->db->lastInsertId();
    }
      return false;

  }

  public function getUserRelationships($userId)
  {
    $relationships = $this->db->fetchAll("SELECT * FROM {$this->table} WHERE user_1 = ? OR user_2 = ?", array($userId, $userId));
    $return = array();
    foreach($relationships AS $value) {
      $d = array();
      
      if($value["user_1"] != $userId) {
        $d["id"] = $value["user_1"];
        $d["fullname"] = $value["fullname_1"];
      } else {
        $d["id"] = $value["user_2"];
        $d["fullname"] = $value["fullname_2"];
      }

      $return[] = $d;
    }
    return $return;
  }

  public function sortUser($data) {
    if($data["user_1"] > $data["user_2"]) {
      $u = $data["user_1"];
      $data["user_1"] = $data["user_2"];
      $data["user_2"] = $u;
    }
    return $data;

  }

  public function exists($relationship)
  {
    return $this->db->fetchAssoc("SELECT * FROM {$this->table} WHERE user_1 = ? AND user_2 = ? LIMIT 1", array($relationship["user_1"], $relationship["user_2"]));
  }

  // @todo : use user service
  public function getUser($id)
  {
    return $this->db->fetchAssoc("SELECT * FROM users WHERE id = ? LIMIT 1", array($id));
  }

  public function getAll()
  {

    return $this->db->fetchAll("SELECT * FROM {$this->table}");
  }
}
