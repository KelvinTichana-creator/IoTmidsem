<?php

class Database
{

   // private $dsn = "sqlsrv:Server=localhost;Database=test";    // Conect with SQLServer
    private $dsn = "mysql:host=localhost;dbname=lab3waterlevel";   // Conect with MySQL
    private $username = "root";
    private $pass = "";
    public $conn;
    
    

    public function __construct()
    {
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->pass);
            // echo "Succesfully Connected!";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function read($id)
    {
        $data = array();
        $sql = "SELECT * FROM main WHERE TankID=:id ORDER BY ID DESC LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data[] = $row;
        }
        return $data;
    }

    public function totalRowCount()
    {
        $sql = "SELECT count(*)  FROM main";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $number_of_rows = $result->fetchColumn();
        return $number_of_rows;
    }

}
$ob = new Database();