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

function getItem($type, $id) {    
    // The URL to send the request to
    $url = setParameters("api/".$type."/".$id);
    
    // Access the database
    return accessDatabase("GET", $url);
}

function getItems($type) {
    // The URL to send the request to
    $url = setParameters("api/".$type."/all");
    
    // Access the database
    return accessDatabase("GET", $url);
}

function getMaps($type, $id) {    
    // The URL to send the request to
    $url = setParameters("api/".$type."/".$id."/maps");
    
    // Access the database
    return accessDatabase("GET", $url);
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
