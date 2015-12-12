<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RelationshipService extends BaseService
{

  function save($data)
  {
    $data = $this->sortUser($data);
    $data = $this->appendCreatedModified($data);
    $data["status"] = isset($data["status"]) ? $data["status"] : "pending";

    $this->db->insert($this->table, $data);
    return $this->db->lastInsertId();
  }

  public function getUserRelationships($user)
  {

    return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE user_1 = ? OR user_2 = ?", array($user, $user));
  }

  public function sortUser($data) {
    if($data["user_1"] > $data["user_2"]) {
      $u = $data["user_1"];
      $data["user_1"] = $data["user_2"];
      $data["user_2"] = $u;
    }
    return $data;
  }

  public function getAll()
  {

    return $this->db->fetchAll("SELECT * FROM {$this->table}");
  }
}
