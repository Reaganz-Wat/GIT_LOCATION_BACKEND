<?php
require_once './PHP-MySQLi-Database-Class-master/MysqliDb.php';

class BaseDatabaseClass
{
    protected $db;
    protected $simplified_db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->simplified_db = new MysqliDb($db);
    }
}