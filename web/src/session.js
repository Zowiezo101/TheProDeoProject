/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch, session_settings */

function updateSession(key, value="") {
    
    var url = 'http://localhost/web/api/session_write.php';
    var query = '?key=' + key;

    if (value !== "") {
        query += '&value=' + value;
    }
    
    fetch(url + query, {
            method: 'GET'
        }
    ).then(response => response.json()).then(function() {
        if (session_settings.hasOwnProperty(key)) {
            if (value !== "") {
                // Update the value
                session_settings[key] = value;
            } else {
                // Delete the value
                delete session_settings[key];
            }
        } else if (value !== "") {
            session_settings[key] = value;
        }
    });
}

