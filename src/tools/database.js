/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch, base_url */

TYPE_BLOG = "blogs";
TYPE_BOOK = "books";
TYPE_EVENT = "events";
TYPE_FAMILYTREE = "familytree";
TYPE_LOCATION = "locations";
TYPE_PEOPLE = "peoples";
TYPE_SPECIAL = "specials";
TYPE_TIMELINE = "timeline";
TYPE_WORLDMAP = "worldmap";

function createItem(type, data) {    
    // The URL to set the request to
    var url = base_url + "/" + lang + "/api/" + type + "/new";
    
    // Access the database
    return accessDatabase("POST", url, data);
    
}

function getItem(type, id, options=false) {
    // Create the query
    var query = getQuery(options);
    
    // The URL to set the request to
    var url = base_url + "/" + lang + "/api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("GET", url + query);
}

function updateItem(type, id, data) {    
    // The URL to set the request to
    var url = base_url + "/" + lang + "/api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("PUT", url, data);
    
}

function deleteItem(type, id) {
    // The URL to set the request to
    var url = base_url + "/" + lang + "/api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("DELETE", url);    
}

function getPage(options) {
    /*
     * URL: api/[item]/pages/[id]
     * options: 
     * - item_type
     * - id
     * - lang
     * - sort
     * - filter
     */
}

function getSearchResults(options) {
    /*
     * URL: api/[item]/search/results
     * options: 
     * - item_type
     * - lang
     * - filter
     */
}

function accessDatabase(method, url, data) {
    if (method === "GET") {
        var response = fetch(url, {
            method: method
        });
    } else {
        response = fetch(url, {
            method: method,
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(data)
        });
    }
   
    return response.then(
        response => response.text()
    ).then (function (response) {
//        console.log(response);
        return JSON.parse(response);
    });
}

function getQuery(options) {
    // The query that is built using the options
    var query = "";
    
    // No options means no query
    if (options !== false) {
        var params = [];
        
        // Create the following syntax for each given option: options=value
        for (var key in options) {
            params.push(key + "=" + options[key]);
        }
        
        // Add it all together to get parameters that can be added to an URL
        query = "?" + params.join("&");
    }
    
    return query;
}



function postData(table, data) {
    var url = base_url + "/api/" + table + "/create.php";
    var params = getParams({"data": data});
    
    return fetch(url, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(
        response => response.text()
    ).then (function (response) {
//        console.log(response);
        return JSON.parse(response);
    });
}

function putData(table, id, data) {
    var url = base_url + "/api/" + table + "/update.php";
    var params = getParams({"id": id, "data": data});
    
    return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(
        response => response.text()
    ).then (function (response) {
//        console.log(response);
        return JSON.parse(response);
    });
}

function deleteData(table, id) {
    var url = base_url + "/api/" + table + "/delete.php";
    var params = getParams({"id": id});
    
    return fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(
        response => response.text()
    ).then (function (response) {
//        console.log(response);
        return JSON.parse(response);
    });
}
		
