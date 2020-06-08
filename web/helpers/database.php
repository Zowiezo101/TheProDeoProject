<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<script>

   
    function getItemFromDatabase(table="", value="", column="") {
       
       var promiseObj = new Promise(function(resolve, reject) {
       
            // Create a request variable and assign a new XMLHttpRequest object to it.
            var request = new XMLHttpRequest();

            var link = 'http://localhost/web/api/item_read.php';

            if (table === "blog") {
                // Use a different link for blogs
                link = 'http://localhost/web/api/blog_read.php';
                table = "";
            } else if (table !== "") {
                link += '?table=' + table;
            }

            if (value !== "") {
                link += (table !== "" ? '&' : '?') + 'value=' + value;
            }

            if (column !== "") {
                link += ((table !== "" || value !== "") ? '&' : '?') + 'column=' + column;
            }

            // Open a new connection, using the GET request on the URL endpoint
            request.open('GET', link, true);

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
</script>