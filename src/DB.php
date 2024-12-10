<?php
namespace App;
class DB
{
    public $host = "localhost";
    public $user = "root";
    public $pass = "2005";
    public $db_name = "todo_app_nt";
    public $conn;
    public function __construct(){
        $this->conn = new \PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass);
    }
}