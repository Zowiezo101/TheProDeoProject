/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch, session_settings, base_url */

function updateSession(parameters) {
    
    // The base URL
    var url = base_url + '/web/src/tools/session.php';
    
    var query_arr = [];
    for (var key in parameters) {
        query_arr.push(key + "=" + parameters[key]);
    }
    
    var query = "";
    if (query_arr.length > 0) {
        query = "?" + query_arr.join("&");
    }
    
    for (key in parameters) {
        if (session_settings.hasOwnProperty(key)) {
            if ((parameters[key] !== "") && (parameters[key] !== "null")) {
                // Update the value
                session_settings[key] = parameters[key];
            } else {
                // Delete the value
                delete session_settings[key];
            }
        } else if ((parameters[key] !== "") && (parameters[key] !== "null")) {
            session_settings[key] = parameters[key];
        }
    }
    
    fetch(url + query, {
            method: 'GET'
        }
    );
}

