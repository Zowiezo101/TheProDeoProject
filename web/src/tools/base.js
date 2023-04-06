/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global get_settings, dict */

function setParameters(url) {
    var newUrl = url;
    
    // Get languague from window.location.url
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
    
    // Now reload the page
    window.location.href = base + setParameters(uri);
}

function toUpperCaseFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function getLinkToItem(type, id, text, options) {
    var newTab = options && options.hasOwnProperty("openInNewTab") ? options.openInNewTab : false;
    var classes = options && options.hasOwnProperty("classes") ? options.classes : "";
    var panTo = options && options.hasOwnProperty("panToItem") ? options.panToItem : "";
    
    // If any other classes are inserted
    if (typeof classes === "undefined" || classes === "") {
        classes = "font-weight-bold";
    }
    
    var to_table = type;
    var to_item = to_table.substr(0, to_table.length - 1);
    if (["familytree", "timeline"].includes(type)) {
        to_item = "map";
    }
    
    var link = setParameters(to_table + (id !== "-1" ? ("/" + to_item + "/" + id) : ""));
    if (text === "self") {
        text = link.substr(get_settings["lang"] ? 4 : 1);
    }
    if (text === "Global") {
        text = dict["timeline.global"];
    }
    
    if (id === null) {
        link = '#';
    }
    
    if (panTo !== "") {
        link += '?panTo=' + panTo;
    }
    
    if ((type === "worldmap") && id !== "-1") {
        // Use a function to link to the item
        return '<a href="javascript: void(0)" onclick="getLinkToMap(' + id + ')"' + 
            'class="' + classes + '">' + 
                text + 
        '</a>';        
    } else {
        // Use an actual hyhperlink to the item
        return '<a href="' + link + '" ' + (newTab ? 'target="_blank" ' : '') +
            (type === "worldmap" ? 'data-toggle="tooltip" title="' + dict["items.details.worldmap"] + '"' : "") + 
            'class="' + classes + '">' + 
                text + 
        '</a>';
    }
}

function getGenderColor(int) {
    var color = "";
    
    switch(int) {
        case "gender.male":
            color = "lightblue";
            break;
            
        case "gender.female":
            color = "pink";
            break;
            
        case "gender.unknown":
        default:
            color = "lightgrey";
            break;
    }
    
    return color;
}

function getDataColor(object) {
    var color = "lightgrey";
    var colorCode = 0;
    
    if (object.hasOwnProperty("descr") && ![null, "", "-1"].includes(object.descr)) {
        colorCode += 1;
    } if (object.hasOwnProperty("date") && ![null, "", "-1"].includes(object.date)) {
        colorCode += 2;
    } if (object.hasOwnProperty("length") && ![null, "", "-1"].includes(object.length)) {
        colorCode += 4;
    }
    
    switch(colorCode) {
        case 0:
            color = "lightgrey";
            break;
        case 1:
            color = "lightcoral";
            break;
        case 2:
            color = "lightblue";
            break;
        case 3:
            color = "mediumpurple";
            break;
        case 4:
            color = "gold";
            break;
        case 5:
            color = "lightsalmon";
            break;
        case 6:
            color = "lightgreen";
            break;
        case 7:
            color = "peru";
            break;
    }
    
    return color;
}

function getTypeString(int) {
    var str = "";
    
    if (typeof dict[int] !== "undefined") {
        str = dict[int];
    }
    
    return str;
}
