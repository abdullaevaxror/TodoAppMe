<?php
namespace App;
class DB
{
    public $host = "localhost";
    public $user = "axror";
    public $pass = "Xc0~t05VF\"`_";
    public $db_name = "todo_app_a";
    public $conn;
    public function __construct(){
        $this->conn = new \PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass);
    }
}