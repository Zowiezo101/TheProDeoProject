/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch, session_settings, base_url */

function updateSession(parameters) {
    
    // The base URL
    var url = base_url + '/src/tools/session.php';
    
    var query_arr = [];
    for (var key in parameters) {
        query_arr.push(key + "=" + parameters[key]);
    }
    
    var query = "";
    if (query_arr.length > 0) {
        query = "?" + query_arr.join("&");
    }
    
    fetch(url + query, {
            method: 'GET'
        }
    );
}

