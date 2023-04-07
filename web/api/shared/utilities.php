<?php
class utilities{
    
    public $people_aka = "people_to_aka.people_name";
    public $location_aka = "location_to_aka.location_name";
    private $gender_type = "type_gender";
    private $tribe_type = "type_tribe";
    private $location_type = "type_location";
    private $special_type = "type_special";
  
    public function getPaging($page, $total_rows, $records_per_page, $page_url){
  
        // paging array
        $paging_arr = array();
  
        // button for first page
        $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";
  
        // count all products in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);
  
        // range of links to show
        $range = 2;
  
        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
  
        $paging_arr['pages']=array();
        $page_count=0;
          
        for($x = $initial_num; $x < $condition_limit_num; $x++){
            // be sure '$x is greater than or equal to 0' AND 'less than the $total_pages'
            if(($x >= 0) && ($x < $total_pages)){
                $paging_arr['pages'][$page_count]["page"] = $x;
                $paging_arr['pages'][$page_count]["url"] = "{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
  
                $page_count++;
            }
        }
  
        // button for last page
        $paging_arr["last"] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";
  
        // json format
        return $total_pages;
    }
    
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
            case "peoples":
            case "locations":
            case "specials":
                $item_columns[] = "book_start_id";
                $item_columns[] = "book_start_chap";
                $item_columns[] = "book_start_vers";
                $item_columns[] = "book_end_id";
                $item_columns[] = "book_end_chap";
                $item_columns[] = "book_end_vers";
                $item_columns[] = "id";
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
                    $item_columns[] = "type";
                    
                    if (($json_filters->type == "10") && ($type == "locations")) {
                        // We want all tribes
                        $item_filters[] = "type in (0, 1, 2, 3, 4, 5, 6, 7, 8, 9)";
                        array_pop($item_values);
                    }
                    
                    if (($json_filters->type == "8") && ($type == "specials")) {
                        // We want all tribes
                        $item_filters[] = "type in (0, 1, 2, 3, 4, 5, 6, 7)";
                        array_pop($item_values);
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
  
}