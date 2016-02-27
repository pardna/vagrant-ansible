<?php
namespace App\Services\pardna\manage;
use App\Services\common\BaseService;

class PardnaManageService extends BaseService
{
  public function getPardnaGroupById($id){
    return $this->db->fetchAssoc("SELECT * FROM pardna WHERE id = ? LIMIT 1", array($id));
  }


}
