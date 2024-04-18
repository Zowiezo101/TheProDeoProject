<?php

class item {
  
    // database connection and table name
    protected $conn;
    protected $table_name;
    
    // In case an error occurs
    protected $error = "";
    protected $code = 200;
  
    // constructor with $db as database connection
    public function __construct(){
        global $conn;
        $this->conn = $conn;
    }
    
    function check_parameters($required_params, $allowed_params) {
        $result = true;
        $given_params = filter_input_array(INPUT_GET);
        
        // Check if all required params are available
        foreach (array_keys($required_params) as $key) {
            if (array_search($key, array_keys($given_params)) === false) {
                // Parameter not found
                $this->error = "Error: required key '".$key."' is not found";
                $this->code = 400;
                $result = false;
                break;
            } else {
                // Filter the parameter and make sure it's the expected type
                $this->$key = filter_var($given_params[$key], $required_params[$key]);
                
                // Remove the key from the given params, as it has been checked
                unset($given_params[$key]);
            }
        }
        
        if ($result == true) {
            // Check if there are any params that aren't allowed
            foreach ($given_params as $key => $value) {
                if (array_search($key, array_keys($allowed_params)) === false) {
                    // Unknown parameter
                    $this->error = "Error: unknown key '".$key."' in query";
                    $this->code = 400;
                    $result = false;
                    break;
                } else {
                    // Filter the parameter and make sure it's the expected type
                    $this->$key = filter_var($value, $allowed_params[$key]);
                }
            }
        }
                
        return $result;
    }
    
    function check_body($required_params, $allowed_params) {
        $result = true;
        $given_params = (array) json_decode(file_get_contents("php://input"));
        
        // Check if all required params are available
        foreach (array_keys($required_params) as $key) {
            if (array_search($key, array_keys($given_params)) === false) {
                // Parameter not found
                $this->error = "Error: required key '".$key."' is not found";
                $this->code = 400;
                $result = false;
                break;
            } else {
                // Filter the parameter and make sure it's the expected type
                $this->$key = filter_var($given_params[$key], $required_params[$key]);
                
                // Remove the key from the given params, as it has been checked
                unset($given_params[$key]);
            }
        }
        
        if ($result == true) {
            // Check if there are any params that aren't allowed
            foreach ($given_params as $key => $value) {
                if (array_search($key, array_keys($allowed_params)) === false) {
                    // Unknown parameter
                    $this->error = "Error: unknown key '".$key."' in body";
                    $this->code = 400;
                    $result = false;
                    break;
                } else {
                    // Filter the parameter and make sure it's the expected type
                    $this->$key = filter_var($value, $allowed_params[$key]);
                }
            }
        }
                
        return $result;
    }
    
    function access_database($stmt) {

        // Create an array for the data
        // and fill it up if we have any results
        $data = [];
            
        try {
            // execute query
            $stmt->execute();

            // check if more than 0 record found
            $num = $stmt->rowCount();
            if ($num > 0) {

                // retrieve our table contents
                // fetch() is faster than fetchAll()
                // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
                    array_push($data, $row);
                }
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->code = 503;
        }

        return $data;
    }
    
    function prepare_message($data) {
        $message = [
            "data" => [
                "error" => $this->error,
                "records" => []
            ],
            "code" => $this->code 
        ];
        
        // Data is supposed to be an array. 
        // When it is false, something happened while checking the parameters
        if($data !== false){
            $message["data"]["records"] = $data;
        }
        
        return $message;
    }
}
