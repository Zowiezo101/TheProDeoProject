
/* global session_settings, dict_EventsParams, dict_Timeline, dict_PeoplesParams, dict_Familytree, dict_LocationsParams, dict_Worldmap, getMapFromDatabase, dict_Search, Items, getItemFromDatabase */

var dict_params = null;
var dict = null;
var item_links = null;

switch(session_settings["table"]) {
    case "timeline":
        dict_params = dict_EventsParams;
        dict = dict_Timeline;
        break;
        
    case "familytree":
        dict_params = dict_PeoplesParams;
        dict = dict_Familytree;
        break;
        
    case "worldmap":
        dict_params = dict_LocationsParams;
        dict = dict_Worldmap;
        break;
}

async function showMapInfo(information) {
    // Google JS API has loaded a worldmap div, so do not delete that
    if (session_settings["table"] !== "worldmap") {
        // The map info
        var right = document.getElementById("item_info");
        right.innerHTML = "";

        // The default text
        var defaultText = document.createElement("div");
        right.appendChild(defaultText);        

        // Set its attributes
        defaultText.id = "default";
        defaultText.innerHTML = dict_Search["no_results"];
    }
    
    if ((Items.length === 0) || (session_settings["table"] !== "worldmap")) {
        Items = [];

        if ((session_settings["table"] === "timeline") && (session_settings["map"] !== "global_id")) {
            
            await getItemFromDatabase("events", session_settings["map"]).then(function(event) {
                // Global item
                var item = {
                    "id": "-999",
                    "name": event[0]["name"],
                    "descr": event[0]["descr"],
                    "length": event[0]["length"],
                    "data": event[0]["data"],
                    "parent_id": ""
                };
                Items.push(new CreateItem(item));
            }, console.log);
        } else if (session_settings["table"] === "timeline") {
            // Global item
            var item = {
                "id": "-999",
                "name": dict_Timeline["global"],
                "descr": "",
                "length": "",
                "data": "",
                "parent_id": ""
            };
            Items.push(new CreateItem(item));
        }
        
        // This is done for each new map
        // The worldmap only uses one global map
        for (var idx in information) {
            var item = information[idx];
            item.parent_id  = session_settings["table"] === "timeline" ? ((item.parent_id === "" || (item.parent_id === null)) ? -999 : item.parent_id) : item.parent_id;

            Items.push(new CreateItem(item));
        }
    
        // Create all the connections between parents and children
        // Or in case of worldmap, get all the coordinates
        setItems();
    }
    
    if (Items.length > 0) {

        // Get the Map and the ID numbers
        if (session_settings["table"] === "timeline") {
            globalMapId = -999;
        } else {
            globalMapId = Number(session_settings["map"]);
        }
        globalItemId = session_settings["id"] ? Number(session_settings["id"]) : globalMapId;

        showMap();
    }
}
    
function setRightSide(parent) {
    /* Right column. This is where the item info will be displayed
       when an item is clicked from the item bar. When no item is
       clicked yet, show default text with instructions. */
    var right = document.createElement("div");
    parent.appendChild(right);

    // Set its attributes
    right.id = "item_info";
    right.className = "contents_right";

    // Show the selected timeline, when someone is selected
    if (session_settings.hasOwnProperty("map")) {
        // Show the selected map, when a map is selected
        getMapFromDatabase(session_settings["table"], 
                           session_settings["map"]).then(showMapInfo, console.log);
    } else {
        // The default text
        var defaultText = document.createElement("div");
        right.appendChild(defaultText);
    
        // Set its attributes
        defaultText.id = "default";
        defaultText.innerHTML = dict["default"];
    }

    if (session_settings["table"] !== "worldmap") {
        // A SVG canvas to save the SVG
        var hidden_div = document.createElement("div");
        right.appendChild(hidden_div);

        hidden_div.id = "hidden_div";
        hidden_div.style = "display: none";

        // The SVG, canvas and link inside it
        var hidden_svg = document.createElement("svg");
        var hidden_canvas = document.createElement("canvas");
        var hidden_a = document.createElement("a");

        hidden_div.appendChild(hidden_svg);
        hidden_div.appendChild(hidden_canvas);
        hidden_div.appendChild(hidden_a);

        hidden_svg.id = "hidden_svg";
        hidden_canvas.id = "hidden_canvas";
        hidden_a.id = "hidden_a";
    }

    return right;
}