<?php
namespace App\Services;
// move http exceptiosn to controller
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class NotificationService extends BaseService
{

  public function log($data) {
    $data = $this->appendCreatedModified($data);
    $this->db->insert("notifications", $data);
    return $this->db->lastInsertId();
  }

  public function getNotifications($id)
  {
    return $this->db->fetchAssoc("SELECT * FROM notifications WHERE id = ? LIMIT 30 ORDER BY modified DESC", array($id));
  }


}
