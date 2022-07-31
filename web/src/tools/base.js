/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global get_settings, dict */

var pageSize = 10;

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
    
    // No reload the page
    window.location.href = base + setParameters(uri);
}

function toUpperCaseFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function getLinkToItem(type, id, text, classes="", panTo="") {
    // If any other classes are inserted
    if (typeof classes === "undefined" || classes === "") {
        classes = "font-weight-bold";
    }
    
    var to_table = type;
    var to_item = to_table.substr(0, to_table.length - 1);
    if (["familytree", "timeline"].includes(type)) {
        to_item = "map";
    }
    
    var link = setParameters(to_table + "/" + to_item + "/" + id);
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
    
    if (type === "worldmap") {
        // Use a function to link to the item
        return '<a href="javascript: void(0)" onclick="getLinkToMap(' + id + ')"' + 
            'class="' + classes + '">' + 
                text + 
        '</a>';        
    } else {
        // Use an actual hyhperlink to the item
        return '<a href="' + link + '" ' + 
            'class="' + classes + '">' + 
                text + 
        '</a>';
    }
}

function getGender(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["gender.unknown"];
            break;
            
        case 1:
        case "1":
            str = dict["gender.male"];
            break;
            
        case 2:
        case "2":
            str = dict["gender.female"];
            break;
    }
    
    return str;
}

function getTribe(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["tribe.ruben"];
            break;
            
        case 1:
        case "1":
            str = dict["tribe.simeon"];
            break;
            
        case 2:
        case "2":
            str = dict["tribe.levi"];
            break;
            
        case 3:
        case "3":
            str = dict["tribe.juda"];
            break;
            
        case 4:
        case "4":
            str = dict["tribe.dan"];
            break;
            
        case 5:
        case "5":
            str = dict["tribe.naftali"];
            break;
            
        case 6:
        case "6":
            str = dict["tribe.gad"];
            break;
            
        case 7:
        case "7":
            str = dict["tribe.aser"];
            break;
            
        case 8:
        case "8":
            str = dict["tribe.issachar"];
            break;
            
        case 9:
        case "9":
            str = dict["tribe.zebulon"];
            break;
            
        case 10:
        case "10":
            str = dict["tribe.jozef"];
            break;
            
        case 11:
        case "11":
            str = dict["tribe.benjamin"];
            break;
            
        case 12:
        case "12":
            str = dict["tribe.unknown"];
            break;
    }
    
    return str;
}

function getTypeLocation(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["type.well"];
            break;
            
        case 1:
        case "1":
            str = dict["type.river"];
            break;
            
        case 2:
        case "2":
            str = dict["type.mountain"];
            break;
            
        case 3:
        case "3":
            str = dict["type.valley"];
            break;
            
        case 4:
        case "4":
            str = dict["type.country"];
            break;
            
        case 5:
        case "5":
            str = dict["type.district"];
            break;
            
        case 6:
        case "6":
            str = dict["type.county"];
            break;
            
        case 7:
        case "7":
            str = dict["type.city"];
            break;
            
        case 8:
        case "8":
            str = dict["type.object"];
            break;
            
        case 9:
        case "9":
            str = dict["type.unknown"];
            break;
    }
    
    return str;
}

function getTypeSpecial(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["type.object"];
            break;
            
        case 1:
        case "1":
            str = dict["type.idol"];
            break;
            
        case 2:
        case "2":
            str = dict["type.grave"];
            break;
            
        case 3:
        case "3":
            str = dict["type.song"];
            break;
            
        case 4:
        case "4":
            str = dict["type.building"];
            break;
            
        case 5:
        case "5":
            str = dict["type.creature"];
            break;
            
        case 6:
        case "6":
            str = dict["type.world"];
            break;
            
        case 7:
        case "7":
            str = dict["type.unknown"];
            break;
    }
    
    return str;
}

function getTypeLink(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = " (" + dict["type.birth"] + ")";
            break;
            
        case 1:
        case "1":
            str = " (" + dict["type.living"] + ")";
            break;
            
        case 2:
        case "2":
            str = " (" + dict["type.death"] + ")";
            break;
            
        case 3:
        case "3":
            str = " (" + dict["type.founder"] + ")";
            break;
            
        case 4:
        case "4":
            str = " (" + dict["type.destroyer"] + ")";
            break;
    }
    
    return str;
}


