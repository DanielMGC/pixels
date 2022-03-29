<?php
class Anchor{
 
    private $conn;
    private $table_name = "anchor";

    // object properties
    public $id;
    public $type;
    public $col;
    public $row;

    public function __construct($db){
        $this->conn = $db;
    }

    function getFromPart($part){
        
        // select all query
        $query = "SELECT
                    *
                    FROM
                    " . $this->table_name . " a
                WHERE part = " . $part;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}