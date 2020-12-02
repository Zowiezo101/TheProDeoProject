/* global dict_Search, dict_Settings, get_settings */

function CleanText(text) {
    // The newlines in the string cause problems..
    text1 = text.replace(/\r\n|\r|\n|\\r\\n|\\r|\\n/g, "<br/>");
    
    // Escape slashes
    text2 = text1.replace("\\", "\\\\");
    
    // Escape apastrophs
    text3 = text2.replace("'", "\\'");
    
    // Escape quotes
    text4 = text3.replace('"', '\\"');
    
    // Escape quotes
    text5 = text4.replace('`', '\\`');
    
    return text5;
}

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

        if ((value !== "") && (typeof value === "string")) {
            params += (params !== "" ? '&' : '?') + 'value=' + value;
        } else if ((value !== "") && (value !== null)) {
            var link = 'http://localhost/web/api/items_read.php';
            params += (params !== "" ? '&' : '?') + 'value=' + "(" + value.join(',') + ")";
        }

        if (column !== "") {
            params += (params !== "" ? '&' : '?') + 'column=' + column;
        }

        if (page !== "") {
            params += (params !== "" ? '&' : '?') + 'offset=' + parseInt(page)*100;
        }

        if (sort !== "") {
            params += (params !== "" ? '&' : '?') + 'sort=' + sort;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data !== null)) {
                        resolve(result.data);
                    } else {
                        // TODO: This need to be handled elsewhere
                        //resolve(dict_Search["no_results"]);
                        resolve(result.data);
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

function getAmountFromDatabase(table, column="", page="") {

    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/item_num.php';
        var params = '';

        if (table !== "") {
            params = '?table=' + table;
        }

        if (column !== "") {
            params += (params !== "" ? '&' : '?') + 'column=' + column;
        }

        if (page !== "") {
            params += (params !== "" ? '&' : '?') + 'offset=' + parseInt(page)*100;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    resolve(result.data[0].num);
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function getMapFromDatabase(table="", value="") {

    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/map_read.php';
        var params = '';

        if (table !== "") {
            params = '?table=' + table;
        }

        if (value !== "") {
            params += (params !== "" ? '&' : '?') + 'value=' + value;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data !== null)) {
                        resolve(result.data);
                    } else {
                        // TODO: This need to be handled elsewhere
                        //resolve(dict_Search["no_results"]);
                        resolve(result.data);
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

function searchDatabase(name, table, joins, options) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/items_search.php';
        var params = '';

        if (table !== "") {
            params = '?table=' + table;
        }

        if (name !== "") {
            params += (params !== "" ? '&' : '?') + 'value=' + name;
        }

        if (options !== "") {
            params += (params !== "" ? '&' : '?') + 'options=' + options;
        }

        if (joins !== "") {
            params += (params !== "" ? '&' : '?') + 'joins=' + joins;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data !== null)) {
                        resolve(result.data);
                    } else {
                        // TODO: This need to be handled elsewhere
                        //resolve(dict_Search["no_results"]);
                        resolve(result.data);
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

function addBlogToDatabase(title, text, user) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/blog_write.php';
        var params = '?type=add';

        if (title !== "") {
            params += '&title=' + CleanText(title);
        }

        if (text !== "") {
            params += '&text=' + CleanText(text);
        }

        if (user !== "") {
            params += '&user=' + user;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data === true)) {
                        resolve("<h1>" + dict_Settings["blog_added"] + "</h1>");
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

function deleteBlogFromDatabase(id) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/blog_write.php';
        var params = '?type=delete';

        if (id !== "") {
            params += '&id=' + id;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data === true)) {
                        resolve("<h1>" + dict_Settings["blog_removed"] + "</h1>");
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

function editBlogFromDatabase(id, title, text) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/blog_write.php';
        var params = '?type=edit';

        if (title !== "") {
            params += '&title=' + CleanText(title);
        }

        if (text !== "") {
            params += '&text=' + CleanText(text);
        }

        if (id !== "") {
            params += '&id=' + id;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data === true)) {
                        resolve("<h1>" + dict_Settings["blog_edited"] + "</h1>");
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

function getQueryPart(search_term, search_table) {
    var result = {
        join: "",
        option: ""
    };
    
    switch(search_term) {
        case "name_changes":
            var name_changes = get_settings["name_changes"].split(";");
            name_changes = name_changes.map(s => s.trim());

            if (search_table === "location") {

                result.option = 
                        " AND location_id IN (" + 
                        " SELECT l1.location2_id FROM locations" + 
                        " LEFT JOIN location_to_location AS l1 ON locations.location_id = l1.location1_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND l1.location2_id IS NOT NULL" + 

                        " UNION" + 

                        " SELECT l2.location1_id FROM locations" + 
                        " LEFT JOIN location_to_location AS l2 ON locations.location_id = l2.location2_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND l2.location1_id IS NOT NULL" + 
                        ")";
            } else if (search_table === "peoples") {
                
                result.option = 
                        " AND people_id IN (" + 
                        " SELECT p1.people2_id FROM peoples" + 
                        " LEFT JOIN people_to_people AS p1 ON peoples.people_id = p1.people1_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND p1.people2_id IS NOT NULL" + 

                        " UNION" + 

                        " SELECT p2.people1_id FROM peoples" + 
                        " LEFT JOIN people_to_people AS p2 ON peoples.people_id = p2.people2_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND p2.people1_id IS NOT NULL" + 
                        ")";
            }
            break;
            
        case "parent":
            var parents = get_settings["parent"].split(";");
            parents = parents.map(s => s.trim());
            
            result.option = 
                    " AND people_id IN (" + 
                    " SELECT p1.people_id FROM peoples" + 
                    " LEFT JOIN people_to_parent AS p1 ON peoples.people_id = p1.parent_id" + 
                    " WHERE name LIKE '" + parents.join("' OR name LIKE '") + "' AND p1.people_id IS NOT NULL" + 
                    ")";
            break;
            
        case "child":
            var children = get_settings["child"].split(";");
            children = children.map(s => s.trim());
            
            result.option = 
                    " AND people_id IN (" + 
                    " SELECT p1.parent_id FROM peoples" + 
                    " LEFT JOIN people_to_parent AS p1 ON peoples.people_id = p1.people_id" + 
                    " WHERE name LIKE '" + children.join("' OR name LIKE '") + "' AND p1.parent_id IS NOT NULL" +
                    ")";
            break;
            
        case "people":
            var peoples = get_settings["people"].split(";");
            peoples = peoples.map(s => s.trim());

            if (search_table === "location") {

                result.option = 
                        " AND location_id IN (" + 
                        " SELECT l1.location2_id FROM locations" + 
                        " LEFT JOIN location_to_location AS l1 ON locations.location_id = l1.location1_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND l1.location2_id IS NOT NULL" + 

                        " UNION" + 

                        " SELECT l2.location1_id FROM locations" + 
                        " LEFT JOIN location_to_location AS l2 ON locations.location_id = l2.location2_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND l2.location1_id IS NOT NULL" + 
                        ")";
            } else if (search_table === "events") {
                
                result.option = 
                        " AND people_id IN (" + 
                        " SELECT p1.people2_id FROM peoples" + 
                        " LEFT JOIN people_to_people AS p1 ON peoples.people_id = p1.people1_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND p1.people2_id IS NOT NULL" + 

                        " UNION" + 

                        " SELECT p2.people1_id FROM peoples" + 
                        " LEFT JOIN people_to_people AS p2 ON peoples.people_id = p2.people2_id" + 
                        " WHERE name LIKE '" + name_changes.join("' OR name LIKE '") + "' AND p2.people1_id IS NOT NULL" + 
                        ")";
            }
            break;
    }
    
    return result;
}