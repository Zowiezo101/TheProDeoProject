<?php
class Utilities{
  
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
    
    public function getParams($type, $filters) {
        // The filters to be applied on the database
        $item_columns = array();
        $item_filters = array();
        $item_params = array();
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
                if(in_array('length', $json_filters->sliders)) {
                    // Get the maximum and minimum chapters
                    $item_columns[] = "max(length) as max_length";
                    $item_columns[] = "min(length) as min_length";
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
            } else {
        
                if(property_exists($json_filters, 'name')) {
                    $item_filters[] = "name LIKE ?";
                    $item_params[] = "%".htmlspecialchars(strip_tags($json_filters->name))."%";
                }
                if(property_exists($json_filters, 'num_chapters')) {
                    $item_columns[] = "num_chapters";
                    $item_filters[] = "num_chapters LIKE ?";
                    $item_params[] = "%".htmlspecialchars(strip_tags($json_filters->num_chapters))."%";
                }
            }
        }
        
        // Turn these arrays into strings
        $item_params["columns"] = implode(', ', $item_columns);
        $item_params["filters"] = implode(' AND ', $item_filters) ? 
                        "WHERE " . implode(' AND ', $item_filters) : "";
        
        return $item_params;
    }
  
}
?>