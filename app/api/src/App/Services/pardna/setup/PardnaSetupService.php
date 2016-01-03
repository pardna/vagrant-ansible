<?php
namespace App\Services\pardna\setup;
use App\Services\common\BaseService;

class PardnaSetupService extends BaseService
{

  public function setUpPardna($pardnagroup_id, $request){
    $pardna = array();
    $pardna["pardnagroup_id"] = $pardnagroup_id;
    //Need to figure out how we can put the pardna account in.
    //$pardna["pardna_account_id"] = ;
    $pardna["amount"] = $request["amount"];
    $pardna["currency"] = $request["currency"];
    $pardna["frequency"] = $request["frequency"];
    $pardna["paydate"] = $this->getDateTimeObject($request["paydate"])->format('Y-m-d');;
    $pardna["paytype"] = $request["paytype"];
    $this->db->insert("pardna", $pardna);
    return $this->db->lastInsertId();
  }

  private function getDateTimeObject($date1){
    $datetime = new \DateTime();
    $date = explode("/", $date1);
    $datetime->setDate($date[2], $date[1], $date[0]);
    return $datetime;
  }

}
