<?php

const TYPE_BLOG = "blogs";
const TYPE_BOOK = "books";
const TYPE_EVENT = "events";
const TYPE_LOCATION = "locations";
const TYPE_PEOPLE = "peoples";
const TYPE_SPECIAL = "specials";
const TYPE_TIMELINE = "timeline";
const TYPE_FAMILYTREE = "familytree";
const TYPE_WORLDMAP = "worldmap";

function createItem($type, $data) {
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/new");
    
    // Access the database
    return accessDatabase("POST", $url, $data);
}

function getItem($type, $id, $options=false) {
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/".$id);
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function getItems($type, $options=false) {
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/all");
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function updateItem($type, $id, $data) {
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/".$id);
    
    // Access the database
    return accessDatabase("PUT", $url, $data);

}

function deleteItem($type, $id) {
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/".$id);
    
    // Access the database
    return accessDatabase("DELETE", $url);
}

function getPage($type, $page, $options) {
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/pages/".$page);
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function getMaps($type, $id, $options=false) {
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/".$id."/maps");
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function getSearchOptions($type, $options=false) {
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/search/options");
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function getSearchResults($type, $options=false) {
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = setParameters("api/".$type."/search/results");
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function accessDatabase($method, $url, $data=false) {
    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    if ($method == "POST") {
        // To create new items
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    } else if ($method == "PUT") {
        // To update existing items
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    } else if ($method == "DELETE") {
        // To delete existing items
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    }
    
    $json = curl_exec($curl);
    
    $response = json_decode($json);
    
    curl_close($curl);
    
    return $response;
}

function getQuery($options) {    
    // TODO: Use the htmlbuildquery function
    // The query that is built using the options
    $query = "";
    
    // No options means no query
    if ($options != false) {
        $params = [];
        
        // Create the following syntax for each given option: options=value
        foreach($options as $option => $value) {
            $params[] = $option."=".$value;
        }
        
        // Add it all together to get parameters that can be added to an URL
        $query = "?".implode("&", $params);
    }
    
    return $query;
}
