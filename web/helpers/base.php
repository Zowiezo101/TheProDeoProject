<script>
    
    function updateSessionSettings(key, value="") {
       
        var promiseObj = new Promise(function(resolve, reject) {
            // Create a request variable and assign a new XMLHttpRequest object to it.
            var request = new XMLHttpRequest();

            var link = 'http://localhost/web/api/session_write.php';
            var params = '?key=' + key;

            if (value !== "") {
                params += '&value=' + value;
            }

            // Open a new connection, using the GET request on the URL endpoint
            request.open('GET', link + params, true);

            // Send request
            request.send();

            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                    // The PHP variable has been updated
                    // Time to update the javascript variable currently used
                    if (session_settings.hasOwnProperty(key)) {
                        if (value !== "") {
                            // Update the value
                            session_settings[key] = value;
                        } else {
                            // Delete the value
                            delete session_settings[key];
                        }
                    } else if (value !== "") {
                        session_settings[key] = value;
                    }
                    
                    resolve();
                }
            };
        });
        
        return promiseObj;
    }
    
    function getSortSql(sortStr) {
        var sortSql = "";
            
        // Sorting results by name or ID.
        switch(sortStr) {
            case 'alp':
                // Get new SQL array of items
                sortSql = 'name asc';
                break;

            case 'r-alp':
                // Get new SQL array of items
                sortSql = 'name desc';
                break;

            case 'r-app':
                // Get new SQL array of items
                sortSql = 'book_start_id desc, book_start_chap desc, book_start_vers desc';
                break;

            default:
                // Get new SQL array of items
                sortSql = 'book_start_id asc, book_start_chap asc, book_start_vers asc';
                break;
        }
        
        return sortSql;
    }

    window.onerror = function(msg, url, linenumber) {
        alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
        return true;
    };
</script>