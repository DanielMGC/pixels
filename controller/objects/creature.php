<?php
class Creature{
 
    // database connection and table name
    private $conn;
    private $table_name = "creature";

    // object properties
    public $id;
    public $name;
    public $approved;
    public $author;
    public $parts;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    function get($id){

        // select all query
        $query = "SELECT c.*,
                        u.username,
                        u.id user_id,
                        u.email email
                    FROM " . $this->table_name . " c
                    INNER JOIN user u
                        ON c.author = u.id
                WHERE c.id = " . $id;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function read($filter = null){

        $where = "";

        if(isset($filter)) {
            if(isset($filter->name)) {
                $where .= " AND c.name = '".$filter->name."' ";
            }
            if(isset($filter->nameLike)) {
                $where .= " AND c.name LIKE '%".$filter->nameLike."%' ";
            }
            if(isset($filter->approved)) {
                $where .= " AND c.approved = ".$filter->approved." ";
            }
            if(isset($filter->notId)) {
                $where .= " AND c.id <> ".$filter->notId." ";
            }
            if(isset($filter->author)) {
                $where .= " AND c.author = ".$filter->author." ";
            }
        }

        // select all query
        $query = "SELECT c.*,
                        u.username,
                        u.id user_id,
                        u.email email
                    FROM " . $this->table_name . " c
                    INNER JOIN user u
                        ON c.author = u.id
                WHERE 1 = 1 " . $where;

        if(isset($filter->maxResults)) {
            $query .= " LIMIT " . $filter->maxResults;
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function create(){

        $query = "INSERT INTO " . $this->table_name . "
                            SET name=:name, 
                                author=:author,
                                approved = 0";
                                

        $stmt = $this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":author", $this->author->id);

        $this->conn->beginTransaction();

        if(!$stmt->execute()){
            $this->conn->rollBack();
            return -1;
        }

        $creatureId = $this->conn->lastInsertId();

        for ($i=0; $i < count($this->parts); $i++) { 
            $part = $this->parts[$i];

            $query = "INSERT INTO part
                        SET creature=:creature,
                            type=:type,
                            pixels=:pixels";
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":creature", $creatureId);
            $stmt->bindParam(":type", $part->type);
            $stmt->bindParam(":pixels", $part->pixels);

            if(!$stmt->execute()){
                $this->conn->rollBack();
                return -1;
            }

            $partId = $this->conn->lastInsertId();
            
            for ($j=0; $j < count($part->anchors); $j++) { 
                $anchor = $part->anchors[$j];
    
                $query = "INSERT INTO anchor
                            SET part=:part,
                                type=:type,
                                col=:col,
                                row=:row";
                
                $stmt = $this->conn->prepare($query);
    
                $stmt->bindParam(":part", $partId);
                $stmt->bindParam(":type", $anchor->type);
                $stmt->bindParam(":col", $anchor->col);
                $stmt->bindParam(":row", $anchor->row);
    
                if(!$stmt->execute()){
                    $arr = $stmt->errorInfo();
                    print_r($arr);

                    $this->conn->rollBack();
                    return -1;
                }
            }
        }

        $this->conn->commit();
        return $creatureId;
        
   }

    function update(){
    
        $query = "UPDATE " . $this->table_name . "
                            SET name=:name,
                                approved=:approved
                            WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":approved", $this->approved);
        $stmt->bindParam(":id", $this->id);

        $this->conn->beginTransaction();

        if(!$stmt->execute()){
            $this->conn->rollBack();
            return false;
        }

        for ($i=0; $i < count($this->parts); $i++) { 
            $part = $this->parts[$i];

            $query = "UPDATE part
                        SET pixels=:pixels
                        WHERE id=:id";
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":pixels", $part->pixels);
            $stmt->bindParam(":id", $part->id);

            if(!$stmt->execute()){
                $this->conn->rollBack();
                return false;
            }

            for ($j=0; $j < count($part->anchors); $j++) { 
                $anchor = $part->anchors[$j];

                $query = "UPDATE anchor
                            SET col=:col,
                                row=:row
                            WHERE id=:id";
                
                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(":col", $anchor->col);
                $stmt->bindParam(":row", $anchor->row);
                $stmt->bindParam(":id", $anchor->id);

                if(!$stmt->execute()){
                    $arr = $stmt->errorInfo();
                    print_r($arr);

                    $this->conn->rollBack();
                    return false;
                }
            }
        }

        $this->conn->commit();
        return true;
       }
}