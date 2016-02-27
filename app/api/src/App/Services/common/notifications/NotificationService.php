<?php
namespace App\Services\common\notifications;
use App\Services\common\BaseService;

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
