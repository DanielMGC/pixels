<?php
class Database{
 
    // specify your own database credentials

    private $host = "pixels.mysql.uhserver.com";
    //private $db_name = "pixels";
    //private $username = "pixels";
    //private $password = "2907.Pixels";
    //private $host = "34.130.36.202";
    private $db_name = "pixels";
    private $username = "pixels";
    private $password = "2907.Pixels";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>