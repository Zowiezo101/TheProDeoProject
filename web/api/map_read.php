<?php    
require "../../login_data.php";

class result {
    public $data;
    public $error;
    public $query;
};

$result = new result();

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    $result->error = "Connection failed: " . $conn->connect_error;
} else {
    if (filter_input(INPUT_GET, 'table') !== null) {
        // Get the table and the ID that we want to read
        $table = filter_input(INPUT_GET, 'table');
        
        switch($table) {
            case "timeline":
                $sql = "select event_id as id, name from events";
        
                if (filter_input(INPUT_GET, 'value') !== null) {
                    $value = filter_input(INPUT_GET, 'value');
                    
                    // Collect all the items belonging to this timeline
                    if ($value == "global_id") {
                        // This is the global timeline, using events
                        $sql = "select events.event_id as id, name, descr, length, date as data, e.event1_id as parent_id from events
                                    left join event_to_event as e on events.event_id = e.event2_id";
                    } else {
                        // These are the more specific timelines, using activities
                        $sql = "select activitys.activity_id as id, descr as name, null as descr, length, date as data, a2.activity1_id as parent_id from activitys 
                                    left join activity_to_event as a1 on activitys.activity_id = a1.activity_id 
                                    left join activity_to_activity as a2 on activitys.activity_id = a2.activity2_id
                                    where event_id = ".$value;
                    }
                }
                break;
            
            case "familytree":
                break;
            
            case "worldmap":
                // Always all
                $sql = "select location_id as id, name, coordinates from locations
                            where coordinates is not null and coordinates != ''";
                break;
            
            default:
                $result->error = "Invalid table selected";
                break;
        }

        // excecute SQL statement
        $result->query = $sql;
        $results = mysqli_query($conn, $sql);

        // die if SQL statement failed
        if (!$results) {
            $result->error = mysqli_error($conn);
        }
        
        if (!$result->error && (mysqli_num_rows($results) > 0)) {
            // Put the results in the arrau
            $result->data = Array();
            for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                $result->data[] = mysqli_fetch_object($results);
            }
        }
    } else {
        $result->error = "No table selected";
    }



    // close mysql connection
    mysqli_close($conn);
}

echo json_encode($result);

// https://www.leaseweb.com/labs/2015/10/creating-a-simple-rest-api-in-php/
// https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html