<?php

require_once "../objects/event.php";
require_once "../objects/people.php";
require_once "../objects/location.php";
require_once "../objects/special.php";

class base {
    private $conn;
    
    public $table_activities = "activitys";
    public $table_events = "events";
    public $table_peoples = "peoples";
    public $table_locations = "locations";
    public $table_speciacls = "locations";
    public $table_a2pa = "activity_to_parent";
    public $table_a2e = "activity_to_event";
    public $table_e2pa = "event_to_parent";
    public $table_p2a = "people_to_activity";
    public $table_p2p = "people_to_aka";
    public $table_p2pa = "people_to_parent";
    private $table_p2l = "people_to_location";
    private $table_l2a = "location_to_activity";
    private $table_l2l = "location_to_aka";
    private $table_s2a = "special_to_activity";
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
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
    
    public function getEventToPeoples($id) {

        // select all query
        $query = "SELECT
                    distinct(p2a.people_id) as id, p2a.people_name as name
                FROM
                    " . $this->table_p2a . " p2a
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.activity_id = p2a.activity_id
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

        // select all query
        $query = "SELECT
                    distinct(l2a.location_id) as id, l2a.location_name as name
                FROM
                    " . $this->table_l2a . " l2a
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.activity_id = l2a.activity_id
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

        // select all query
        $query = "SELECT
                    distinct(s2a.special_id) as id, s2a.special_name as name
                FROM
                    " . $this->table_s2a . " s2a
                    LEFT JOIN
                        " . $this->table_a2e . " a2e
                            ON a2e.activity_id = s2a.activity_id
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
        // select all query
        $query = "SELECT
                    distinct(e.id) as id, e.name
                FROM
                    " . $this->table_events . " e
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
    
    public function getEventToParents($id) {
        // select all query
        $query = "SELECT
                    distinct(e.id) as id, e.name
                FROM
                    " . $this->table_events . " e
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
    
    public function getPeopleToEvents($id) {
        // select all query
        $query = "SELECT
                    distinct(e.id), e.name
                FROM
                    " . $this->table_events . " e
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

        // select all query
        $query = "SELECT
                    distinct(l.id), l.name
                FROM
                    " . $this->table_locations . " l
                    LEFT JOIN
                        " . $this->table_p2l . " p2l
                            ON p2l.location_id = l.id
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
        // select all query
        $query = "SELECT
                    p2p.people_id as id, p2p.people_name as name
                FROM
                    " . $this->table_p2p . " p2p
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
        // select all query
        $query = "SELECT
                    distinct(p.id) as id, p.name
                FROM
                    " . $this->table_peoples . " p
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
        // select all query
        $query = "SELECT
                    distinct(p.id) as id, p.name, p.gender, p2p.parent_id
                FROM
                    " . $this->table_peoples . " p
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
        // select all query
        $query = "SELECT
                    distinct(e.id), e.name
                FROM
                    " . $this->table_events . " e
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

        // select all query
        $query = "SELECT
                    distinct(p.id), p.name
                FROM
                    " . $this->table_peoples . " p
                    LEFT JOIN
                        " . $this->table_p2l . " p2l
                            ON p2l.people_id = p.id
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
        // select all query
        $query = "SELECT
                    l2l.location_id as id, l2l.location_name as name
                FROM
                    " . $this->table_l2l . " l2l
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
        // select all query
        $query = "SELECT
                    distinct(e.id), e.name
                FROM
                    " . $this->table_events . " e
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
        // select all query
        $query = "SELECT
                    p.id, p.name, p.meaning_name, p.descr, 
                    p.gender, p2p.parent_id, aka.people_name AS aka,
                    ".$gen." as gen, 0 as X, 0 as Y
                FROM
                    " . $this->table_peoples . " p
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
        if ($gen > 1) {
            // select all query
            $query = "SELECT
                        e.id, e.name as name, e.length, e.date, e2pa.parent_id,
                        ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $this->table_events . " e

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
                        e.id, e.name as name, e.length, e.date, -999 as parent_id,
                        ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $this->table_events . " e

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
        
        if ($gen > 1) {
            // select all query
            $query = "SELECT
                        a.id, a.name, a.descr, a.length, a.date, a2pa.parent_id,
                        a.level, ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $this->table_activities . " a

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
                        a.id, a.name, a.descr, a.length, a.date, -999 as parent_id,
                        a.level, ".$gen." as gen, 0 as X, 0 as Y
                    FROM
                        " . $this->table_activities . " a

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
}