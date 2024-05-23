/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch */

function updateSession(parameters) {
    // The base URL is stored in the body of the page
    // TODO: Check if relative URL is enough
    var base_url = $("body").attr("data-base-url");
    
    // The base URL
    var url = 'src/tools/session.php';
    
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

