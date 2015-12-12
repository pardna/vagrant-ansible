<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\Entity\UserEntity;

class PardnaGroupService extends BaseService
{

  protected $table = "pardnagroups";

  function save($group)
  {
    $this->db->insert($this->table, $group);
    return $this->db->lastInsertId();
  }

  public function getAdminGroups($admin)
  {

    return $this->db->fetchAll("SELECT * FROM {$this->table} WHERE admin = ?", array($admin));
  }

  public function getAll()
  {

    return $this->db->fetchAll("SELECT * FROM {$this->table}");
  }

}
