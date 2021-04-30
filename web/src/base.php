<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setParameters($url) {
    $newUrl = $url;
    
    // TODO: Get languague from window.location.url
    if (filter_input(INPUT_GET, "lang")) {
        $newUrl = "/".filter_input(INPUT_GET, "lang")."/".$url;
    }
    
    return $newUrl;
}