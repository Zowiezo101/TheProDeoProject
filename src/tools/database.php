<?php

$TYPE_BLOG = "blogs";
$TYPE_BOOK = "books";
$TYPE_EVENT = "events";
$TYPE_FAMILYTREE = "familytree";
$TYPE_LOCATION = "locations";
$TYPE_PEOPLE = "peoples";
$TYPE_SPECIAL = "specials";
$TYPE_TIMELINE = "timeline";
$TYPE_WORLDMAP = "worldmap";

function createItem($type, $data) {

}

function getItem($type, $options) {

}

function getItems($type, $options=false) {    
    global $base_url;
    
    // Create the query
    $query = getQuery($options);
    
    // The URL to send the request to
    $url = $base_url."/api/".$type."/all";
    
    // Access the database
    return accessDatabase("GET", $url.$query);
}

function updateItem($type, $data) {

}

function deleteItem($type, $data) {

}

function getPage($type, $options) {

}

function getMap($type, $options) {

}

function getMaps($type, $options) {

}

function getSearchOptions($type, $options) {

}

function getSearchResults($type, $options) {

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
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    } else if ($method == "DELETE") {
        // To delete existing items
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DETE");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    }
    
    $json = curl_exec($curl);
    
    $response = json_decode($json);
    
    curl_close($curl);
    
    return $response;
}

function getQuery($options) {    
    // The query that is built using the options
    $query = "";
    if ($options != false) {
        
    }
    return $query;
}

function getBody($data) {
    $body = "";
    return $body;
}
?>



