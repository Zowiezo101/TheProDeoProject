<?php

// Setting our own namespace
namespace shared;

class Utilities {
    
    public function getParams($type, $filters, $conn) {
        // The filters to be applied on the database
        $item_columns = array();
        $item_filters = array();
        $item_values = array();
        $item_params["filters"] = array();
        $item_params["values"] = array();
        $item_params["columns"] = array();
        
        // Always have these columns
        $item_columns[] = "name";
        switch($type) {
            case "books":
                $item_columns[] = "num_chapters";
                $item_columns[] = "id";
                break;
            
            case "events":
                $item_columns[] = "min_book_id as book_start_id";
                $item_columns[] = "min_book_chap as book_start_chap";
                $item_columns[] = "min_book_vers as book_start_vers";
                $item_columns[] = "max_book_id as book_end_id";
                $item_columns[] = "max_book_chap as book_end_chap";
                $item_columns[] = "max_book_vers as book_end_vers";
                $item_columns[] = "e.id";
                break;
            
            case "peoples":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "p.id";
                break;
            
            case "locations":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "l.id";
                break;
            
            case "specials":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "s.id";
                break;
        }
        
        $json_filters = json_decode($filters);
        if (json_last_error() === JSON_ERROR_NONE && is_object($json_filters)) {
            if(property_exists($json_filters, 'sliders')) {
                $item_columns = [];
                
                if(in_array('chapters', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "max(num_chapters) as max_num_chapters";
                    $item_columns[] = "min(num_chapters) as min_num_chapters";
                }
                if(in_array('age', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "max(age) as max_age";
                    $item_columns[] = "min(age) as min_age";
                }
                if(in_array('parent_age', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "greatest(max(father_age), max(mother_age)) as max_parent_age";
                    $item_columns[] = "greatest(min(father_age), min(mother_age)) as min_parent_age";
                }
            } 
            
            if(property_exists($json_filters, 'select')) {
                $item_types = [];
                
                if(in_array('gender', $json_filters->select)) {
                    // Get the gender types
                    $item_types[] = "type_gender";
                }
                if(in_array('tribe', $json_filters->select)) {
                    // Get the tribe types
                    $item_types[] = "type_tribe";
                }
                if(in_array('type_location', $json_filters->select)) {
                    // Get the location types
                    $item_types[] = "type_location";
                }
                if(in_array('type_special', $json_filters->select)) {
                    // Get the special types
                    $item_types[] = "type_special";
                }
                $item_params["types"] = $item_types;
            }
        
                if(property_exists($json_filters, 'name')) {
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                    if ($type === "peoples") {
                        // Two extra columns, one for the AKA and one
                        // to let us known wether we have a hit because aka
                        $item_columns[] = "if(".$this->people_aka." LIKE ?, ".$this->people_aka.", '') AS aka";
                        
                        // One updated filter WITH aka
                        $item_filters[] = "(name LIKE ? OR ".$this->people_aka." LIKE ?)";
                        
                        // Three extra values
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                    } elseif ($type === "locations") {
                        // Two extra columns, one for the AKA and one
                        // to let us known wether we have a hit because aka
                        $item_columns[] = "if(".$this->location_aka." LIKE ?, ".$this->location_aka.", '') AS aka";
                        
                        // One updated filter WITH aka
                        $item_filters[] = "(name LIKE ? OR ".$this->location_aka." LIKE ?)";
                        
                        // Three extra values
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                        $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                    } else {
                        $item_filters[] = "name LIKE ?";
                    }
                }
                if(property_exists($json_filters, 'meaning_name')) {
                    $item_filters[] = "meaning_name LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->meaning_name))."%";
                    $item_columns[] = "meaning_name";
                }
                if(property_exists($json_filters, 'descr')) {
                    $item_filters[] = "descr LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->descr))."%";
                    $item_columns[] = "descr";
                }
                if(property_exists($json_filters, 'num_chapters')) {
                    $item_filters[] = "num_chapters BETWEEN ? AND ?";
                    
                    // The two chapters to set between
                    $items = explode('-', htmlspecialchars(strip_tags($json_filters->num_chapters)), 2);
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                }
                if(property_exists($json_filters, 'length')) {
                    $item_filters[] = "length LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->length))."%";
                    $item_columns[] = "length";
                }
                if(property_exists($json_filters, 'date')) {
                    $item_filters[] = "date LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->date))."%";
                    $item_columns[] = "date";
                }
                if(property_exists($json_filters, 'age')) {
                    $item_filters[] = "age BETWEEN ? AND ?";
                    
                    // The two lengths to set between
                    $items = explode('-', htmlspecialchars(strip_tags($json_filters->age)), 2);
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                    $item_columns[] = "age";
                }
                if(property_exists($json_filters, 'parent_age')) {
                    $item_filters[] = "(father_age BETWEEN ? AND ? OR mother_age BETWEEN ? AND ?)";
                    
                    // The two lengths to set between
                    $items = explode('-', htmlspecialchars(strip_tags($json_filters->parent_age)), 2);
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                    $item_values[] = $items[0];
                    $item_values[] = $items[1];
                    $item_columns[] = "father_age";
                    $item_columns[] = "mother_age";
                }
                if(property_exists($json_filters, 'gender')) {
                    $item_filters[] = "gender = ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->gender));
                    $item_columns[] = "g.type_name as gender";
                    
                    $query = "SELECT
                                type_id
                            FROM
                                " .$this->gender_type;
                    
                    // prepare query statement
                    $stmt = $conn->prepare($query);
                    
                    // execute query
                    $stmt->execute();
                    
                    // The amount of results
                    $num = strval($stmt->rowCount());
                    
                    if ($json_filters->gender == $num) {
                        $genders = implode(", ", range(0, $num - 1, 1));

                        // We want all genders
                        array_pop($item_filters);
                        array_pop($item_values);
                        $item_filters[] = "gender in (".$genders.")";
                    }
                }
                if(property_exists($json_filters, 'tribe')) {
                    $item_filters[] = "tribe = ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->tribe));
                    $item_columns[] = "t.type_name as tribe";
                    
                    $query = "SELECT
                                type_id
                            FROM
                                " .$this->tribe_type;
                    
                    // prepare query statement
                    $stmt = $conn->prepare($query);
                    
                    // execute query
                    $stmt->execute();
                    
                    // The amount of results
                    $num = strval($stmt->rowCount());
                    
                    if ($json_filters->tribe == $num) {
                        $tribes = implode(", ", range(0, $num - 1, 1));

                        // We want all tribes
                        array_pop($item_filters);
                        array_pop($item_values);
                        $item_filters[] = "tribe in (".$tribes.")";
                    }
                }
                if(property_exists($json_filters, 'profession')) {
                    $item_filters[] = "profession LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->profession))."%";
                    $item_columns[] = "profession";
                }
                if(property_exists($json_filters, 'nationality')) {
                    $item_filters[] = "nationality LIKE ?";
                    $item_values[] = "%".htmlspecialchars(strip_tags($json_filters->nationality))."%";
                    $item_columns[] = "nationality";
                }
                if(property_exists($json_filters, 'type')) {
                    $item_filters[] = "type = ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->type));
                    $item_columns[] = "it.type_name as type";

                    if ($type == "locations") {
                        $query = "SELECT
                                    type_id
                                FROM
                                    " .$this->location_type;

                        // prepare query statement
                        $stmt = $conn->prepare($query);

                        // execute query
                        $stmt->execute();

                        // The amount of results
                        $num = strval($stmt->rowCount());

                        if ($json_filters->type == $num) {
                            $tribes = implode(", ", range(0, $num - 1, 1));

                            // We want all types
                            array_pop($item_filters);
                            array_pop($item_values);
                            $item_filters[] = "type in (".$tribes.")";
                        }
                    }

                    if ($type == "specials") {
                        $query = "SELECT
                                    type_id
                                FROM
                                    " .$this->special_type;

                        // prepare query statement
                        $stmt = $conn->prepare($query);

                        // execute query
                        $stmt->execute();

                        // The amount of results
                        $num = strval($stmt->rowCount());

                        if ($json_filters->type == $num) {
                            $tribes = implode(", ", range(0, $num - 1, 1));

                            // We want all types
                            array_pop($item_filters);
                            array_pop($item_values);
                            $item_filters[] = "type in (".$tribes.")";
                        }
                    }
                }
            
                if(property_exists($json_filters, 'start_book')) {
                    $item_filters[] = "book_start_id >= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->start_book));
                }
                if(property_exists($json_filters, 'start_chap')) {
                    $item_filters[] = "book_start_chap >= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->start_chap));
                }
                if(property_exists($json_filters, 'end_book')) {
                    $item_filters[] = "book_end_id <= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->end_book));
                }
                if(property_exists($json_filters, 'end_chap')) {
                    $item_filters[] = "book_end_chap <= ?";
                    $item_values[] = htmlspecialchars(strip_tags($json_filters->end_chap));
                }
        }
        
        // Turn these arrays into strings
        $item_params["columns"] = implode(', ', $item_columns);
        $item_params["filters"] = implode(' AND ', $item_filters) ? 
                        "WHERE " . implode(' AND ', $item_filters) : "";
        $item_params["values"] = $item_values;
        
        return $item_params;
    }
    
    public function getTable($table_name) {
        
        // NL is the default language
        $table = $table_name;
        $item_name = substr($table_name, 0, strlen($table_name) - 1);
        
        // Only if we're not using the default level
        if ($this->lang !== "nl") {
            
            switch($table_name) {
                
                case "books":
                    $columns = [
                        "id" => false,
                        "order_id" => false,
                        "name" => true,
                        "num_chapters" => false,
                        "summary" => true,
                    ];
                    break;
                
                case "events":
                    $columns = [
                        "id" => false,
                        "order_id" => false,
                        "name" => true,
                        "descr" => true,
                        "length" => true,
                        "date" => true,
                        "book_start_id" => false,
                        "book_start_chap" => false,
                        "book_start_vers" => false,
                        "book_end_id" => false,
                        "book_end_chap" => false,
                        "book_end_vers" => false,
                    ];
                    break;
                
                case "activitys":
                    $columns = [
                        "id" => false,
                        "order_id" => false,
                        "name" => true,
                        "descr" => true,
                        "length" => true,
                        "date" => true,
                        "level" => false,
                        "book_start_id" => false,
                        "book_start_chap" => false,
                        "book_start_vers" => false,
                        "book_end_id" => false,
                        "book_end_chap" => false,
                        "book_end_vers" => false,
                    ];
                    break;
                
                case "peoples":
                    $columns = [
                        "id" => false,
                        "order_id" => false,
                        "name" => true,
                        "descr" => true,
                        "meaning_name" => true,
                        "father_age" => false,
                        "mother_age" => false,
                        "age" => false,
                        "gender" => false,
                        "tribe" => false,
                        "profession" => true,
                        "nationality" => true,
                        "book_start_id" => false,
                        "book_start_chap" => false,
                        "book_start_vers" => false,
                        "book_end_id" => false,
                        "book_end_chap" => false,
                        "book_end_vers" => false,
                    ];
                    break;
                
                case "locations":
                    $columns = [
                        "id" => false,
                        "order_id" => false,
                        "name" => true,
                        "descr" => true,
                        "meaning_name" => true,
                        "type" => false,
                        "coordinates" => false,
                        "book_start_id" => false,
                        "book_start_chap" => false,
                        "book_start_vers" => false,
                        "book_end_id" => false,
                        "book_end_chap" => false,
                        "book_end_vers" => false,
                    ];
                    break;
                
                case "specials":
                    $columns = [
                        "id" => false,
                        "order_id" => false,
                        "name" => true,
                        "descr" => true,
                        "meaning_name" => true,
                        "type" => false,
                        "book_start_id" => false,
                        "book_start_chap" => false,
                        "book_start_vers" => false,
                        "book_end_id" => false,
                        "book_end_chap" => false,
                        "book_end_vers" => false,
                    ];
                    break;
                
                case "people_to_aka":
                    $columns = [
                        "people_id" => false,
                        "people_name" => true,
                        "meaning_name" => true,
                    ];
                    $item_name = "people";
                    break;
                
                case "location_to_aka":
                    $columns = [
                        "location_id" => false,
                        "location_name" => true,
                        "meaning_name" => true,
                    ];
                    $item_name = "location";
                    break;
                
                case "notes":
                    $columns = [
                        "id" => false,
                        "note" => true,
                        "type" => false,
                    ];
                    break;
            }
        }
        
        return $table;
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
                $array[] = $row;
            }
        }
        return $array;
        
    }
    
    public function getEventToLocations($id) {
        $table = $this->getTable($this->table_locations);

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
        $table = $this->getTable($this->table_specials);

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
    
    public function getActivityToAka($id) {
        // select all query
        $query = "SELECT
                    distinct(a2a.activity_id), a2a.book_start_id,
                    a2a.book_start_chap, a2a.book_start_vers,
                    a2a.book_end_id, a2a.book_end_chap, 
                    a2a.book_end_vers
                FROM
                    " . $this->table_a2a . " a2a
                WHERE
                    a2a.activity_id = ?
                UNION    
                SELECT
                    distinct(a.id), a.book_start_id,
                    a.book_start_chap, a.book_start_vers,
                    a.book_end_id, a.book_end_chap, 
                    a.book_end_vers
                FROM
                    " . $this->table_activities . " a
                WHERE
                    a.id = ?
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
    
    public function getPeopleToEvents($id) {
        $table = $this->getTable($this->table_events);
        
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
        $table = $this->getTable($this->table_locations);

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
    
    public function getPeopleToAka($id) {
        $table = $this->getTable($this->table_p2p);
        
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
        $table = $this->getTable($this->table_peoples);
        
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
        $table = $this->getTable($this->table_peoples);

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
        $table = $this->getTable($this->table_events);
        
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
        $table = $this->getTable($this->table_peoples);

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
    
    public function getLocationToAka($id) {
        $table = $this->getTable($this->table_l2l);
        
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
        $table = $this->getTable($this->table_events);
        
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
  
}
