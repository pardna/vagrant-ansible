<?php
namespace App\Services\common;
class ConfigurationsService
{
    protected $db;
    protected $configurationTable = "configurations";

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($config)
    {
      return $this->db->insert($this->configurationTable, $config);
    }

    public function getConfigValue($name)
    {
      $res = $this->db->fetchAll("SELECT * FROM {$this->configurationTable} WHERE name = ? LIMIT 1", array($name));
      return $res[0]["value"];
    }

}
