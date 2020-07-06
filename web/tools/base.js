/* global session_settings */

function updateSessionSettings(key, value="") {

    var promiseObj = new Promise(function(resolve, reject) {
        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/session_write.php';
        var params = '?key=' + key;

        if (value !== "") {
            params += '&value=' + value;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                // The PHP variable has been updated
                // Time to update the javascript variable currently used
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

                resolve();
            }
        };
    });

    return promiseObj;
}

//https://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
function uniq(a) {
    var prims = {"boolean":{}, "number":{}, "string":{}}, objs = [];

    return a.filter(function(item) {
        var type = typeof item;
        if(type in prims)
            return prims[type].hasOwnProperty(item) ? false : (prims[type][item] = true);
        else
            return objs.indexOf(item) >= 0 ? false : objs.push(item);
    });
}

window.onerror = function(msg, url, linenumber) {
    alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
    return true;
};