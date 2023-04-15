<?php

include_once '../config/core.php';
require_once "../objects/event.php";
require_once "../objects/people.php";
require_once "../objects/location.php";
require_once "../objects/special.php";

class base {
    private $conn;
    private $utilities;
    
    public $table_books = "books";
    public $table_activities = "activitys";
    public $table_events = "events";
    public $table_peoples = "peoples";
    public $table_locations = "locations";
    public $table_specials = "specials";
    public $table_a2a = "activity_to_aka";
    public $table_a2pa = "activity_to_parent";
    public $table_a2e = "activity_to_event";
    public $table_e2e = "event_to_aka";
    public $table_e2pa = "event_to_parent";
    public $table_p2a = "people_to_activity";
    public $table_p2p = "people_to_aka";
    public $table_l2l = "location_to_aka";
    public $table_p2pa = "people_to_parent";
    private $table_p2l = "people_to_location";
    private $table_l2a = "location_to_activity";
    private $table_s2a = "special_to_activity";
    private $table_notes = "notes";
    private $table_sources = "sources";
    private $table_n2s = "note_to_source";
    private $table_n2i = "note_to_item";
    private $table_tn = "type_note";
    private $table_ti = "type_item";
    private $table_tg = "type_gender";
    private $table_tp = "type_people";
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        $this->utilities = new utilities();
    }
    
    public function getResults($stmt) {

        $num = $stmt->rowCount();
        
        // array
        $array = array();
        
        // check if more than 0 record found
        if ($num > 0) {

            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($array, $row);
            }
        }
        return $array;
        
    }
    
    public function getNestedResults($stmt) {

        $num = $stmt->rowCount();
        
        // array
        $array = array();
        
        // check if more than 0 record found
        if ($num > 0) {

            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $id = $row["type"].$row["id"];
                $note_id = $row["note_id"];
                
                if (!array_key_exists($id, $array)) {
                    // This item isn't yet in the array
                    $array[$id] = array();
                } 
                if (!array_key_exists($note_id, $array[$id])) { 
                    // The note for this item isn't yet in the array                    
                    // Push the entire result in here
                    $array[$id][$note_id] = [
                        "id" => $row["note_id"],
                        "note" => $row["note"],
                        "sources" => array()
                    ];
                } 
                if (!is_null($row["source"])) {
                    // Push the new source in here
                    array_push($array[$id][$note_id]["sources"], $row["source"]);
                }
            }
        }
        return $array;
        
    }
    
    public function getEventToEvents($id) {
        // select all query
        $query = "SELECT
                    distinct(e2e.event_id), e2e.book_start_id,
                    e2e.book_start_chap, e2e.book_start_vers,
                    e2e.book_end_id, e2e.book_end_chap, 
                    e2e.book_end_vers
                FROM
                    " . $this->table_e2e . " e2e
                WHERE
                    e2e.event_id = ?
                UNION    
                SELECT
                    distinct(e.id), e.book_start_id,
                    e.book_start_chap, e.book_start_vers,
                    e.book_end_id, e.book_end_chap, 
                    e.book_end_vers
                FROM
                    " . $this->table_e2e . " e
                WHERE
                    e.id = ?
                ORDER BY
                    book_start_id ASC, book_start_chap ASC, book_start_vers ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getEventToPeoples($id) {
        $table = $this->utilities->getTable($this->table_peoples);

        // select all query
        $query = "SELECT
                    distinct(p2a.people_id) AS id, p.name AS name
                FROM
                    " . $this->table_p2a . " p2a
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.activity_id = p2a.activity_id
                    LEFT JOIN
                        " . $table . " p
                            ON p2a.people_id = p.id
                WHERE
                    a2e.event_id = ?
                ORDER BY
                    p2a.people_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getEventToLocations($id) {
        $table = $this->utilities->getTable($this->table_locations);

        // select all query
        $query = "SELECT
                    distinct(l2a.location_id) AS id, l.name AS name
                FROM
                    " . $this->table_l2a . " l2a
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.activity_id = l2a.activity_id
                    LEFT JOIN
                        " . $table . " l
                            ON l2a.location_id = l.id
                WHERE
                    a2e.event_id = ?
                ORDER BY
                    l2a.location_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getEventToSpecials($id) {
        $table = $this->utilities->getTable($this->table_specials);

        // select all query
        $query = "SELECT
                    distinct(s2a.special_id) AS id, s.name AS name
                FROM
                    " . $this->table_s2a . " s2a
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.activity_id = s2a.activity_id
                    LEFT JOIN
                        " . $table . " s
                            ON s2a.special_id = s.id
                WHERE
                    a2e.event_id = ?
                ORDER BY
                    s2a.special_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getEventToChildren($id) {
        $table = $this->utilities->getTable($this->table_events);
        
        // select all query
        $query = "SELECT
                    distinct(e.id) AS id, e.name
                FROM
                    " . $table . " e
                    LEFT JOIN
                        " . $this->table_e2pa . " e2pa
                            ON e2pa.parent_id = e.id
                WHERE
                    e2pa.event_id = ?
                ORDER BY
                    e.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getEventToParents($id) {
        $table = $this->utilities->getTable($this->table_events);
        
        // select all query
        $query = "SELECT
                    distinct(e.id) AS id, e.name
                FROM
                    " . $table . " e
                    LEFT JOIN
                        " . $this->table_e2pa . " e2pa
                            ON e2pa.event_id = e.id
                WHERE
                    e2pa.parent_id = ?
                ORDER BY
                    e.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getPeopleToEvents($id) {
        $table = $this->utilities->getTable($this->table_events);
        
        // select all query
        $query = "SELECT
                    distinct(e.id), e.name
                FROM
                    " . $table . " e
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.event_id = e.id
                    LEFT JOIN
                        " . $this->table_p2a . " p2a
                            ON p2a.activity_id = a2e.activity_id
                WHERE
                    p2a.people_id = ?
                ORDER BY
                    e.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getPeopleToLocations($id) {
        $table = $this->utilities->getTable($this->table_locations);

        // select all query
        $query = "SELECT
                    distinct(l.id), l.name, t.type_name as type
                FROM
                    " . $table . " l
                    LEFT JOIN
                        " . $this->table_p2l . " p2l
                            ON p2l.location_id = l.id
                    LEFT JOIN
                        " . $this->table_tp . " t
                            ON p2l.type = t.type_id
                WHERE
                    p2l.people_id = ?
                ORDER BY
                    l.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getPeopleToPeoples($id) {
        $table = $this->utilities->getTable($this->table_p2p);
        
        // select all query
        $query = "SELECT
                    p2p.people_id as id, p2p.people_name as name, 
                    p2p.meaning_name as meaning_name
                FROM
                    " . $table . " p2p
                WHERE
                    p2p.people_id = ?
                ORDER BY
                    p2p.people_name ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getPeopleToParents($id) {
        $table = $this->utilities->getTable($this->table_peoples);
        
        // select all query
        $query = "SELECT
                    distinct(p.id) as id, p.name
                FROM
                    " . $table . " p
                    LEFT JOIN
                        " . $this->table_p2pa . " p2p
                            ON p2p.parent_id = p.id
                WHERE
                    p2p.people_id = ?
                ORDER BY
                    p.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getPeopleToChildren($id) {
        $table = $this->utilities->getTable($this->table_peoples);

        // select all query
        $query = "SELECT
                    distinct(p.id) as id, p.name, p.gender, p2p.parent_id
                FROM
                    " . $table . " p
                    LEFT JOIN
                        " . $this->table_p2pa . " p2p
                            ON p2p.people_id = p.id
                WHERE
                    p2p.parent_id = ?
                ORDER BY
                    p.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getLocationToEvents($id) {
        $table = $this->utilities->getTable($this->table_events);
        
        // select all query
        $query = "SELECT
                    distinct(e.id), e.name
                FROM
                    " . $table . " e
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.event_id = e.id
                    LEFT JOIN
                        " . $this->table_l2a . " l2a
                            ON l2a.activity_id = a2e.activity_id
                WHERE
                    l2a.location_id = ?
                ORDER BY
                    e.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getLocationToPeoples($id) {
        $table = $this->utilities->getTable($this->table_peoples);

        // select all query
        $query = "SELECT
                    distinct(p.id), p.name, t.type_name as type
                FROM
                    " . $table . " p
                    LEFT JOIN
                        " . $this->table_p2l . " p2l
                            ON p2l.people_id = p.id
                    LEFT JOIN
                        " . $this->table_tp . " t
                            ON p2l.type = t.type_id
                WHERE
                    p2l.location_id = ?
                ORDER BY
                    p.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getLocationToLocations($id) {
        $table = $this->utilities->getTable($this->table_l2l);
        
        // select all query
        $query = "SELECT
                    l2l.location_id as id, l2l.location_name as name
                FROM
                    " . $table . " l2l
                WHERE
                    l2l.location_id = ?
                ORDER BY
                    l2l.location_name ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getSpecialToEvents($id) {
        $table = $this->utilities->getTable($this->table_events);
        
        // select all query
        $query = "SELECT
                    distinct(e.id), e.name
                FROM
                    " . $table . " e
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.event_id = e.id
                    LEFT JOIN
                        " . $this->table_s2a . " s2a
                            ON s2a.activity_id = a2e.activity_id
                WHERE
                    s2a.special_id = ?
                ORDER BY
                    e.id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getFamilytreeToChildren($ids, $gen) {
        $table = $this->utilities->getTable($this->table_peoples);
        
        // select all query
        $query = "SELECT
                    p.id, p.name, p.meaning_name, p.descr, 
                    t.type_name as gender, p2p.parent_id, aka.people_name AS aka,
                    1 as level, ".$gen." as gen, 0 as X, 0 as Y
                FROM
                    " . $table . " p
                    LEFT JOIN
                        " . $this->table_p2pa . " p2p
                            ON p2p.people_id = p.id
                    LEFT JOIN
                        (SELECT people_id, CONCAT('[', GROUP_CONCAT(
                            CASE
                                WHEN meaning_name IS NOT NULL AND meaning_name != ''
                                    THEN CONCAT('{\"name\": \"', people_name, '\", \"meaning_name\": \"', meaning_name, '\"}')
                                    ELSE CONCAT('{\"name\": \"', people_name, '\"}')
                            END SEPARATOR ', '
                        ), ']') AS people_name FROM people_to_aka) AS aka
                            ON aka.people_id = p.id
                    LEFT JOIN " . $this->table_tg . " AS t 
                        ON p.gender = t.type_id
                WHERE
                    p2p.parent_id in (" . implode(',', array_fill(0, count($ids), '?')) . ")
                ORDER BY
                    p2p.parent_id ASC, p.order_id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        foreach($ids as $idx => $id) {
            $stmt->bindValue($idx + 1, $id, PDO::PARAM_STR);
        }

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getTimelineEvents($ids, $gen) {
        $table = $this->utilities->getTable($this->table_events);
        
        if ($gen > 1) {
            // select all query
            $query = "SELECT
                        e.id, e.name, e.descr, e.length, e.date, e2pa.parent_id,
                        e.book_start_id, e.book_start_chap, e.book_start_vers,
                        e.book_end_id, e.book_end_chap, e.book_end_vers,
                        1 as level, ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $table . " e

                        LEFT JOIN
                            " . $this->table_e2pa . " e2pa
                                ON e2pa.event_id = e.id
                    WHERE
                        e2pa.parent_id in (" . implode(',', array_fill(0, count($ids), '?')) . ")
                    ORDER BY
                        e.id ASC, e.order_id ASC";
        } else {
            // select all query
            $query = "SELECT
                        e.id, e.name, e.descr, e.length, e.date, -999 as parent_id,
                        e.book_start_id, e.book_start_chap, e.book_start_vers,
                        e.book_end_id, e.book_end_chap, e.book_end_vers,
                        1 as level, ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $table . " e

                        LEFT JOIN
                            " . $this->table_e2pa . " e2pa
                                ON e2pa.event_id = e.id
                    WHERE
                        e2pa.parent_id is null
                    ORDER BY
                        e.id ASC, e.order_id ASC";
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        foreach($ids as $idx => $id) {
            $stmt->bindValue($idx + 1, $id, PDO::PARAM_STR);
        }

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getTimelineActivities($ids, $gen) {
        $table = $this->utilities->getTable($this->table_activities);
        
        if ($gen > 1) {
            // select all query
            $query = "SELECT
                        a.id, a.name, a.length, a.date, a2pa.parent_id,
                        a.book_start_id, a.book_start_chap, a.book_start_vers,
                        a.book_end_id, a.book_end_chap, a.book_end_vers,
                        a.level, ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $table . " a

                        LEFT JOIN
                            " . $this->table_a2pa . " a2pa
                                ON a2pa.activity_id = a.id
                    WHERE
                        a2pa.parent_id in (" . implode(',', array_fill(0, count($ids), '?')) . ")
                    ORDER BY
                        a2pa.parent_id ASC, a.order_id ASC";
        } else {
            // select all query
            $query = "SELECT
                        a.id, a.name, a.length, a.date, -999 as parent_id,
                        a.book_start_id, a.book_start_chap, a.book_start_vers,
                        a.book_end_id, a.book_end_chap, a.book_end_vers,
                        a.level, ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $table . " a

                        LEFT JOIN
                            " . $this->table_a2pa . " a2pa
                                ON a2pa.activity_id = a.id
                        LEFT JOIN 
                            " . $this->table_a2e . " a2e
                                ON a2e.activity_id = a.id
                    WHERE
                        a2e.event_id = ? AND a2pa.parent_id is null
                    ORDER BY
                        a2e.event_id ASC, a.order_id ASC";
        }

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        foreach($ids as $idx => $id) {
            $stmt->bindValue($idx + 1, $id, PDO::PARAM_STR);
        }

        // execute query
        $stmt->execute();
        
        return $this->getResults($stmt);
    }
    
    public function getItemToNotes($id, $type) {
        return $this->getItemsToNotes([$id], $type);
    }
    
    public function getItemsToNotes($ids, $type) {
        $table_notes = $this->utilities->getTable($this->table_notes);
        
        $type_name = strtolower($type);        
        switch($type_name) {
            case "book":
                $table = $this->utilities->getTable($this->table_books);
                break;
                
            case "event":
                $table = $this->utilities->getTable($this->table_events);
                break;
                
            case "activity":
                $table = $this->utilities->getTable($this->table_activities);
                break;
            
            case "people":
                $table = $this->utilities->getTable($this->table_peoples);
                break;
            
            case "location":
                $table = $this->utilities->getTable($this->table_locations);
                break;
            
            case "special":
                $table = $this->utilities->getTable($this->table_specials);
                break;
            
            default:
                $table = "";
                break;
        }
        
        // select all query
        $query = "
            SELECT ti.type_name AS type, i.id, i.name, n.note, n.id AS note_id, s.source
                FROM " . $table . " i
                JOIN " . $this->table_ti . " ti
                    ON ti.type_name = '".$type_name."'
                JOIN " . $this->table_n2i . " n2i
                    ON n2i.item_type = ti.type_id AND n2i.item_id = i.id
                JOIN " . $table_notes . " n
                    ON n2i.note_id = n.id
                LEFT JOIN " . $this->table_n2s . " n2s
                    ON n2s.note_id = n.id
                LEFT JOIN " . $this->table_sources . " s
                    ON s.id = n2s.source_id
                WHERE
                    i.id in (".implode(", ", array_fill(0, count($ids), "?")).")
                ORDER BY
                    id ASC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind variable values
        $i = 1;
        foreach ($ids as &$id) {
            $stmt->bindParam($i++, $id);
        }

        // execute query
        $stmt->execute();
        
        return $this->getNestedResults($stmt);
    }
}