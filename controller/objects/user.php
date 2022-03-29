<?php
class User{
 
    private $conn;
    private $table_name = "user";
 
    public $id;
    public $username;
    public $password;
    public $email;

    public function __construct($db){
        $this->conn = $db;
    }

    function read($filter = null){

        $where = " WHERE 1 = 1 ";

        if(isset($filter)) {
            if(isset($filter->username)) {
                $where .= " AND username = '".$filter->username."' ";
            }
            if(isset($filter->password)) {
                $where .= " AND password = '".$filter->password."' ";
            }
            if(isset($filter->email)) {
                $where .= " AND email = '".$filter->email."' ";
            }
        }
    
        $query = "SELECT
                    *
                    FROM
                    " . $this->table_name . " u " . $where . "
                ORDER BY
                    username DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
   }

    function create(){
    
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    username=:username, password=:password, email=:email, admin = 0";

        $stmt = $this->conn->prepare($query);

        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }

        return -1;
        
   }
}