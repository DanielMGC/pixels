<?php
class Part{
 
    private $conn;
    private $table_name = "part";

    // object properties
    public $id;
    public $type;
    public $pixels;
    public $anchors;

    public function __construct($db){
        $this->conn = $db;
    }

    function getFromCreature($creature, $type = null){
        
        // select all query
        $query = "SELECT
                    *
                    FROM
                    " . $this->table_name . " p
                WHERE creature = " . $creature;

        if($type !== null) {
            $query .= " AND type LIKE '%".$type."%'";
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}