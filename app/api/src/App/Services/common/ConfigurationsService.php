<?php
namespace App\Services\common;
use App\utils\exceptions\ConfigNotFoundException;
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
      if (!$res){
        throw new ConfigNotFoundException("Config $name not found in configuration table", 0, 500);
      }
      return $res[0]["value"];
    }

}
