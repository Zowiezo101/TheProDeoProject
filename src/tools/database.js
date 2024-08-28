/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch */

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
    var url = "api/" + type + "/new";
    
    // Access the database
    return accessDatabase("POST", url, data);
}

function updateItem(type, id, data) {    
    // The URL to set the request to
    var url = "api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("PUT", url, data);
}

function deleteItem(type, id) {
    // The URL to set the request to
    var url = "api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("DELETE", url);    
}

function getItem(type, id) {    
    // The URL to set the request to
    var url = "api/" + type + "/" + id;
    
    // Access the database
    return accessDatabase("GET", url);
}

function getItems(type) {    
    // The URL to set the request to
    var url = "api/" + type + "/all";
    
    // Access the database
    return accessDatabase("GET", url);
}

function getMaps(type, id) {    
    // The URL to set the request to
    var url = "api/" + type + "/" + id + "/maps";
    
    // Access the database
    return accessDatabase("GET", url);
}

function accessDatabase(method, url, data) {
    // The base URL and page language are stored in the body of the page
    var base_url = $("body").attr("data-base-url");
    
    // Prepend these to the given URL
    url = base_url + url;
    
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