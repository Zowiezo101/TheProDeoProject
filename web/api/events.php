<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$method_type = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

$table = "events";

switch($method_type) {
    case 'GET':
        // The request is using the GET method
        require 'crud/read.php';
        break;
    
    default:
        // Null or some other unsupported method
        echo $method_type.' is not a supported type';
        break;
}
