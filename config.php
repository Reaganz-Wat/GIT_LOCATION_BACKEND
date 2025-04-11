<?php

// require_once "./PHP-MySQLi-Database-Class-master/MysqliDb.php";

class DatabaseConnector {
    private $dbConnection = null;

    public function __construct() {
        
        define('Server', 'localhost');
        define('Username', 'root');
        define('Password', '');
        define('Database', 'traffic_incident_reports');

        $this->dbConnection = new mysqli(Server, Username, Password, Database);
        
        // $this->dbConnection = new MysqliDb(Server, Username, Password, Database);

        if ($this->dbConnection->connect_error) {
            die('Error_' . $this->dbConnection->connect_error);

        }

    }

    function getConnection () {
        return $this->dbConnection;
    }
}


// class DatabaseConnector {
//     private $dbConnection = null;

//     public function __construct() {

//         define('Server', '82.197.80.175');
//         define('Username', 'u754825275_briquettes');
//         define('Password', 'Briquettes123*!');
//         define('Database', 'u754825275_briquettes');

//         $this->dbConnection = new mysqli(Server, Username, Password, Database);
        
//         // $this->dbConnection = new MysqliDb(Server, Username, Password, Database);

//         if ($this->dbConnection->connect_error) {
//             die('Error_' . $this->dbConnection->connect_error);

//         }

//     }

//     function getConnection () {
//         return $this->dbConnection;
//     }
// }




?>