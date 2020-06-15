/* global dict_Events, dict_NavBar, session_settings, dict_Search */

function setMaps (parent) {
    // Show a list of maps where this item is included in
    var ItemText = document.createElement("p");
    parent.appendChild(ItemText);
    
    // Set its attributes
    ItemText.innerHTML = dict_Events["map_event"];
    
    // The actual list to be created
    var ItemList = document.createElement("ul");
    parent.appendChild(ItemList); 
    
    // The contents of the list
    // TODO:
//    var ItemListIDs = getMaps(session_settings["id"]);
    var ItemListIDs = [];
    
    if (ItemListIDs.length > 0) {
        // For every map that this item is included in
        for (var i = 0; i < ItemListIDs.length; i++) {

            // Put the list item in the list of maps
            var ItemListItem = document.createElement("li");
            ItemList.appendChild(ItemListItem);

            // Put the link in a list item
            var ItemListLink = document.createElement("a");
            ItemListItem.appendChild(ItemListLink);
            
            // Create a link to the map
            ItemListLink.innerHTML = dict_NavBar["Timeline"] + (Number(ItemListIDs[i]) + 1);
            ItemListLink.id = ItemListIDs[i] + "," + session_settings["id"];
            ItemListLink.onclick = function() {
                goToPage("timeline.php", "", this.id);
            };
        }
    } else {
        // If this item is not in a known map
        // Show a message
        var ItemListItem = document.createElement("li");
        ItemList.appendChild(ItemListItem);
        
        // Set its attributes
        ItemListItem.innerHTML = dict_Search["NoResults"];
    }
}

