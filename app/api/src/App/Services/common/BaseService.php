<?php
namespace App\Services\common;
class BaseService
{
    protected $db;
    protected $table;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function appendCreatedModified($data) {
      $data = $this->appendCreated($data);
      $data = $this->appendModified($data);
      return $data;
    }

    public function appendCreated($data) {
      return $this->appendDate("created", $data);
    }

    public function appendModified($data) {
      return $this->appendDate("modified", $data);
    }

    protected function appendDate($key, $data) {
      $data[$key] = date("Y-m-d H:i:s");
      return $data;
    }

    public function setTable($table) {
      $this->table = $table;
      return $this;
    }

    public function getTable() {
      return $this->table;
    }


}
