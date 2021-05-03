/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global get_settings */

var pageSize = 10;

function setParameters(url) {
    var newUrl = url;
    
    // TODO: Get languague from window.location.url
    if (get_settings.hasOwnProperty("lang") && get_settings["lang"]) {
        newUrl = "/" + get_settings["lang"] + (url[0] === "/" ? "" : "/") + url;
    }
    
    return newUrl;
}

function setLanguage(language, base, uri) {    
    // Reload the page in the correct language
    // Check whether the URI already starts with a language
    if (get_settings.hasOwnProperty("lang") && get_settings["lang"]) {
        // The URI already starts with a language
        // Remove the language part
        uri = uri.substr(3);
    }
        
    // And update the language
    get_settings["lang"] = language;
    
    // No reload the page
    window.location.href = base + setParameters(uri);
}


