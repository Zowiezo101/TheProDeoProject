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

function getItem(type, id, options=false) {
    // Create the query
    var query = getQuery(options);
    
    // The URL to set the request to
    var url = base_url + "/api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("GET", url + query);
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

function getQuery() {
    
}


/**
 * getData(table, type, data)
 * @param {String} table
 * @param {String} type
 * @param {Object} data
 *  
 *  @return {Promise}
 * 
 */
function getData(table, type, data) {
    var url = base_url + setParameters("/api/" + table + "/" + type + ".php");
    var query = getQuery(data);
    
    return fetch(url + query, {
            method: 'GET'
        }
    ).then(
        response => response.text()
    ).then (function (response) {
//        console.log(response);
        return JSON.parse(response);
    });
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
		
