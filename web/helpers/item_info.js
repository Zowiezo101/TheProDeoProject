/* global session_settings, getItemFromDatabase */

async function showItemInfo(information) {
    // Grab the right part of the information window
    var contentEl = document.getElementById("item_info");

    // Clean it
    contentEl.innerHTML = "";

    // Add the name of the current person as a header
    var Name = document.createElement("h1");
    Name.innerHTML = information["name"];
    contentEl.appendChild(Name);

    // Create a Table
    var table = document.createElement("table");

    // For all the available information
    for (var key in information)
    {
        var value = information[key];

        // If a value is set as -1 (unknown), 
        // set it to an emtpty string for human readability
        if (value === -1) {
            value = " ";
        } 

        // Name is already shown. 
        // ID number might just confuse the reader, so hide it.
        if ((key === "name") || (key === "people_id") || (key === "order_id")) {
            continue;
        }

        // We'll use these when we get to book_start_vers
        if ((key === "book_start_id") || (key === "book_start_chap")) {
            continue;
        }

        // We'll use these when we get to book_end_vers
        if ((key === "book_end_id") || (key === "book_end_chap")) {
            continue;
        }

        if (((key === "book_start_vers") ||
             (key === "book_end_vers")) && (value !== "")) {
            // Create a link to the EO jongerenbijbel website or an english bible website!
            // This website should correspond to the translation used for the database!

            var TableKey = document.createElement("td");
            // TODO: We need javascript dicts
            TableKey.innerHTML = dict_PeoplesParams[key];

            // Only show two decimals after the comma
            var TableLink = document.createElement("a");
            TableLink.innerHTML = "";

            if (key === "book_start_vers") {
                await getItemFromDatabase("books", information["book_start_id"]).then(function (bookInfo) { 
                    // When the information is retreived:
                    TableLink.innerHTML = convertBibleVerseText(bookInfo["name"],
                                                                information["book_start_chap"],
                                                                value);
                    TableLink.href = convertBibleVerseLink(bookInfo["name"],
                                                           information["book_start_id"], 
                                                           information["book_start_chap"], 
                                                           value);
                }, console.log);
            } else {
                await getItemFromDatabase("books", information["book_end_id"]).then(function (bookInfo) { 
                    TableLink.innerHTML = convertBibleVerseText(bookInfo["name"],
                                                                information["book_end_chap"], 
                                                                value);
                    TableLink.href = convertBibleVerseLink(bookInfo["name"],
                                                           information["book_end_id"],
                                                           information["book_end_chap"],
                                                           value);
                }, console.log);
            }
            TableLink.target = "_blank";

            var TableData = document.createElement("td");
            TableData.appendChild(TableLink);


            // Left is key names
            // right is value names
            var TableRow = document.createElement("tr");
            TableRow.appendChild(TableKey);
            TableRow.appendChild(TableData);

            table.appendChild(TableRow);
        } else {
            // Add a new table row
            var TableKey = document.createElement("td");
            // TODO
            TableKey.innerHTML = dict_PeoplesParams[key];

            var TableData = document.createElement("td");
            TableData.innerHTML = value;

            // Left is key names
            // right is value names
            var TableRow = document.createElement("tr");
            TableRow.appendChild(TableKey);
            TableRow.appendChild(TableData);

            table.appendChild(TableRow);
        }
    }

//        // Parts that come from other tables
//        $peopleLinks = array(
//            "people_to_activity" => "people_id",
//            "people_to_location" => "people_id",
//            "people_to_parent" => "people_id",
//            "people_to_people" => "people1_id",
//            "people_to_people" => "people2_id"
//        );
//        
//        foreach ($peopleLinks as $table => $column) {
//            // TODO:
//            $information = GetItemInfo($table, filter_input(INPUT_GET, 'id'), $column);
//            
//            $names = "";
//            $values = "";
//            $types = "";
//            
//            if ($information == null) {
//                continue;
//            }
//            
//            // For all the available information
//            foreach ($information as $key => $value)
//            {
//                // If a value is set as -1 (unknown), 
//                // set it to an emtpty string for human readability
//                if ($value == -1) {
//                    $value = " ";
//                } 
//
//                // Column is who we are, we just want to show the linking information
//                if ($key == $column) {
//                    continue;
//                } else if ($key == "type") {
//                    $types += ($value + ",");
//                } else {
//                    switch($key) {
//                        case "activity_id":
//                            $object = GetItemInfo("activitys", $value);
//                            break;
//                        
//                        case "location_id":
//                            $object = GetItemInfo("locations", $value);
//                            break;
//                        
//                        case "parent_id":
//                        case "people1_id":
//                        case "people2_id":
//                            $object = GetItemInfo("peoples", $value);
//                            break;
//                    }
//                            
//                    $names = ($names.",".$object["name"]);
//                    $values = ($values.",".$value);
//                }
//            }
//            
//                            // Add a new table row
//            PrettyPrint('    var TableKey = document.createElement("td"); ');
//            PrettyPrint('    TableKey.innerHTML = "'.$table.'"; ');
//            PrettyPrint('');
//            PrettyPrint('    var TableData = document.createElement("td"); ');
//            PrettyPrint('    TableData.innerHTML = "'.$value.'"; ');
//            PrettyPrint('');
//
//                            // Left is key names
//                            // right is value names
//            PrettyPrint('    var TableRow = document.createElement("tr"); ');
//            PrettyPrint('    TableRow.appendChild(TableKey); ');
//            PrettyPrint('    TableRow.appendChild(TableData); ');
//            PrettyPrint('');
//            PrettyPrint('    table.appendChild(TableRow); ');
//            PrettyPrint('');
//            PrettyPrint('');
//
//            // Only if the value of this key is actually set, 
//            // otherwise we might run into some errors..
//            if ($names != "") {
//                PrettyPrint('   var value = "'.$values.'"');
//                PrettyPrint('   var names = "'.$names.'"');
//
//                // There might be multiple IDs linked to this item.
//                // The different IDs are separated by comma's
//                PrettyPrint('    var linkParts = value.split(","); ');
//                PrettyPrint('');    
//                        // Get the names they refer to
//                PrettyPrint('    var nameParts = names.split(","); ');
//                PrettyPrint('');
//                        // Create a table with the different names
//                PrettyPrint('    Table2 = document.createElement("table"); ');
//                PrettyPrint('');
//                        // And for each name, create the amount of rows needed to show
//                        // all the different linked names
//                PrettyPrint('    for (var types = 0; types < nameParts.length; types++) { ');
//
//                            // Table data
//                PrettyPrint('        TableData2 = document.createElement("td"); ');
//                PrettyPrint('');
//                            // Not every linked name has an ID given..
//                            // When the ID is given, refer to the item with the same ID.
//                            // If not, just place the name
//                PrettyPrint('        if (types < linkParts.length) { ');
//                                // Table links, the name is the name of the item
//                PrettyPrint('            TableLink2 = document.createElement("a"); ');
//                PrettyPrint('            TableLink2.innerHTML = nameParts[types]; ');
//                PrettyPrint('');        
//                                // The link itself is linked to the item it is referring to
//                PrettyPrint('            currentHref = window.location.href; ');
//                PrettyPrint('            TableLink2.href = updateURLParameter("peoples.php", "id", linkParts[types]); ');
//                PrettyPrint('');            
//                                // Add it to the table with linked items
//                PrettyPrint('            TableData2.appendChild(TableLink2); ');
//                PrettyPrint('        } else { ');
//                                // When the ID is not given, just give the name..
//                PrettyPrint('            TableData2.innerHTML = nameParts[types]; ');
//                PrettyPrint('        } ');
//                PrettyPrint('');        
//                            // Table row
//                PrettyPrint('        TableRow2 = document.createElement("tr"); ');
//                PrettyPrint('        TableRow2.appendChild(TableData2); ');
//                PrettyPrint('');        
//                            // Little table inside of table
//                PrettyPrint('        Table2.appendChild(TableRow2); ');                    
//                PrettyPrint('    } ');
//                PrettyPrint('');
//                        // Update the previous table cell with links to the IDs
//                PrettyPrint('    TableData.innerHTML = ""; ');
//                PrettyPrint('    TableData.appendChild(Table2); ');
//                PrettyPrint('');
//                PrettyPrint('');
//
//            }
//
//        }

    contentEl.appendChild(table);

//        PrettyPrint('');
//        PrettyPrint('    // Show a list of maps where this item is included in');
//        PrettyPrint('    var ItemText = document.createElement("p"); ');
//        PrettyPrint('    ItemText.innerHTML = "'.$dict_Peoples["map_people"].'"; ');
//        PrettyPrint('    contentEl.appendChild(ItemText); ');
//        PrettyPrint('');
//        PrettyPrint('    // The actual list to be created');
//        PrettyPrint('    var ItemList = document.createElement("ul"); ');
//        PrettyPrint('');
//        PrettyPrint('    // The contents of the list');
//        PrettyPrint('    var ItemListIDs = getMaps('.filter_input(INPUT_GET, 'id').'); ');
//        PrettyPrint('');
//        PrettyPrint('    if (ItemListIDs.length > 0) { ');
//        PrettyPrint('        // For every map that this item is included in');
//        PrettyPrint('        for (var i = 0; i < ItemListIDs.length; i++) { ');
//        PrettyPrint('            // Create a link to the map');
//        PrettyPrint('            var ItemListLink = document.createElement("a"); ');
//        PrettyPrint('            ItemListLink.innerHTML = "'.$dict_NavBar["Familytree"].' " + (Number(ItemListIDs[i]) + 1); ');
//        PrettyPrint('            ItemListLink.href = updateURLParameter("familytree.php", "id", "" + ItemListIDs[i] + "," + '.filter_input(INPUT_GET, 'id').'); ');
//        PrettyPrint('');
//        PrettyPrint('            // Put the link in a list item');
//        PrettyPrint('            var ItemListItem = document.createElement("li"); ');
//        PrettyPrint('            ItemListItem.appendChild(ItemListLink); ');
//        PrettyPrint('');
//        PrettyPrint('            // Put the list item in the list of maps');
//        PrettyPrint('            ItemList.appendChild(ItemListItem); ');
//        PrettyPrint('        } ');
//        PrettyPrint('    } else { ');
//        PrettyPrint('        // If this item is not in a known map');
//        PrettyPrint('        // Show a message');
//        PrettyPrint('        var ItemListItem = document.createElement("li"); ');
//        PrettyPrint('        ItemListItem.innerHTML = "'.$dict_Search["NoResults"].'"; ');
//        PrettyPrint('        ItemList.appendChild(ItemListItem); ');
//        PrettyPrint('    } ');
//        PrettyPrint('');
//        PrettyPrint('    contentEl.appendChild(ItemList); ');
}


function setRightSide(parent, default_text) {
    /* Right column. This is where the item info will be displayed
       when an item is clicked from the item bar. When no item is
       clicked yet, show default text with instructions. */
    var right = document.createElement("div");
    parent.appendChild(right);

    // Set its attributes
    right.id = "item_info";
    right.className = "contents_right";

    var defaultText = document.createElement("div");
    right.appendChild(defaultText);

    // Set its attributes
    defaultText.id = "default";
    defaultText.innerHTML = default_text;

    // Show the selected person, when someone is selected
    if (session_settings.hasOwnProperty("id")) {
        getItemFromDatabase(session_settings["table"], 
                            session_settings["id"]).then(showItemInfo, console.log);
    }

    return right;
}