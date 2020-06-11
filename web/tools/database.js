function getItemFromDatabase(table="", value="", column="", page="", sort="") {

   var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/item_read.php';
        var params = '';

        if (table === "blog") {
            // Use a different link for blogs
            link = 'http://localhost/web/api/blog_read.php';
            table = "";
        } else if (table !== "") {
            params = '?table=' + table;
        }

        if (value !== "") {
            params += (params !== "" ? '&' : '?') + 'value=' + value;
        }

        if (column !== "") {
            params += (params !== "" ? '&' : '?') + 'column=' + column;
        }

        if (page !== "") {
            params += (params !== "" ? '&' : '?') + 'offset=' + parseInt(page)*100;
        }

        if (sort !== "") {
            params += (sort !== "" ? '&' : '?') + 'sort=' + sort;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.data.length > 1) {

                        resolve(result.data);
                    } else {
                        resolve(result.data[0]);
                    }
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function getAmountFromDatabase() {
/*// Get the numbers of items that are stored in a table for a certain page
// This is to see if it was the last page
function GetNumberOfItems($table) {
    global $conn;
    
    // Check if the page number is set
    if (null === filter_input(INPUT_GET, "page")) {
        $page_nr = 0;
    } else {
        $page_nr = filter_input(INPUT_GET, "page");
    }
    
    // The query to run
    $sql = "SELECT ".substr($table, 0, -1)."_id, name FROM ".$table." WHERE ".substr($table, 0, -1)."_id >= ".($page_nr*100)." LIMIT 101";
    $result = $conn->query($sql);
    
    if (!$result) {
        return 0;
    }
    
    // Return the results
    return $result->num_rows;
}*/
}