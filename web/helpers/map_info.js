
/* global session_settings, dict_EventsParams, dict_Timeline, dict_PeoplesParams, dict_Familytree, dict_LocationsParams, dict_Worldmap, getMapFromDatabase */

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
    // Information should contain all items of a certain map
    // These should all be converted to objects and placed in the correct location in the map
    
    Items = [];
    for (var idx in information) {
        var item = information[idx];
        
        Items.push(new CreateItem(item));
    }

    // Create all the connections between parents and children
    setItems();

    // Get the Map and the ID numbers
    globalMapId = (session_settings["map"] === "global_id") ? 1 : Number(session_settings["map"]);
    globalItemId = session_settings["id"] ? Number(session_settings["id"]) : globalMapId;

    prep_SetSVG();
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

    // The default text
    var defaultText = document.createElement("div");
    right.appendChild(defaultText);

    // Show the selected timeline, when someone is selected
    if (session_settings.hasOwnProperty("map")) {                        

        // Set its attributes
        defaultText.id = "default";
        defaultText.innerHTML = dict["loading"];

        // The progress bar
        var progressBar = document.createElement("div");
        defaultText.appendChild(progressBar);

        // Set its attributes
        progressBar.id = "progress_bar";

        // The progress in the progress bar
        var progress = document.createElement("div");
        progressBar.appendChild(progress);

        // Set its attributes
        progress.id = "progress";
        progress.innerHTML = "1%";

        // Show the selected map, when a map is selected
        getMapFromDatabase(session_settings["table"], 
                           session_settings["map"]).then(showMapInfo, console.log);
    } else {
        // Set its attributes
        defaultText.id = "default";
        defaultText.innerHTML = dict["default"];
    }

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

    return right;
}