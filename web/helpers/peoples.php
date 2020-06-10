<script>
    
    async function showPeopleInfo(information) {
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
                TableKey.innerHTML = "<?php echo $dict_PeoplesParams["book_start_vers"]; ?>";
                
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
                TableKey.innerHTML = "<?php echo $dict_PeoplesParams["book_start_vers"]; ?>";
                
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
    
    function showPeopleList(information) {
    
        // The item bar, where all items are shown
        var itemBar = document.getElementById("item_bar");
        
        // Clean it
        itemBar.innerHTML = "";
        
        // If there are results, create the table with the results
        var table = document.createElement("table");
        itemBar.appendChild(table);
        
        for (var itemIdx in information) {
            var item = information[itemIdx];
            
            var tableRow = document.createElement('tr');
            table.appendChild(tableRow);
            
            var tableData = document.createElement('td');
            tableRow.appendChild(tableData);
            
            var button = document.createElement('button');
            button.innerHTML = item["name"];
            button.id = item["people_id"];
            button.addEventListener("click", function() {
                updateSessionSettings("id", this.id).then(getItemFromDatabase("peoples", this.id).then(showPeopleInfo, console.log), console.log);
            });
            tableData.appendChild(button);
            
        }
    }
        
    async function PrevPage() {
        // Get the stored page number
        // If there is no page number, we are already at the first page and don't need to go further back
        if (session_settings.hasOwnProperty("page")) {
            var page = parseInt(session_settings["page"], 10);
            
            if (page - 1 === 0) {
                // Going a page back means going to the first page
                // Remove the page property
                page = "";
            } else {
                page = page - 1;
            }
            
            // Show the new information
            await updateSessionSettings("page", page).then(async function () {
                    updateButtonLeft();
                    updateButtonRight();
                    await getItemFromDatabase("peoples", "", "", page ? page : 0, getSortSql(session_settings["sort"])).then(showPeopleList, console.log);
                }, console.log
            );
        }
    }
    
    async function NextPage() {
        // Get the stored page number
        if (session_settings.hasOwnProperty("page")) {
            var page = parseInt(session_settings["page"], 10);
        } else {
            // No page given, means that we are at the first page
            page = 0;
        }
            
        // Show the new information
        await updateSessionSettings("page", page + 1).then(async function () {
                updateButtonLeft();
                updateButtonRight();
                await getItemFromDatabase("peoples", "", "", page + 1, getSortSql(session_settings["sort"])).then(showPeopleList, console.log);
            }, console.log
        );
    }
    
    async function SortOnAppearance() {
        // Get the stored page number
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            // No sort given, means that we have default sort
            sort = "app";
        }
        // New sort setting
        sort = (sort === "app") ? "r-app" : "app";
            
        // Show the new information
        await updateSessionSettings("sort", sort).then(async function () {
                updateButtonAlp();
                updateButtonApp();
                await getItemFromDatabase("peoples", "", "", 0, getSortSql(sort)).then(showPeopleList, console.log);
            }, console.log
        );
    }
    
    async function SortOnAlphabet() {
        // Get the stored page number
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            // No sort given, means that we have default sort
            sort = "app";
        }
        // New sort setting
        sort = (sort === "alp") ? "r-alp" : "alp";
            
        // Show the new information
        await updateSessionSettings("sort", sort).then(async function () {
                updateButtonAlp();
                updateButtonApp();
                await getItemFromDatabase("peoples", "", "", 0, getSortSql(sort)).then(showPeopleList, console.log);
            }, console.log
        );
    }
    
    function setButtonLeft(parent) {
        // Previous page
        var buttonLeft = document.createElement("button");
        parent.appendChild(buttonLeft);
        
        // Set its attributes
        buttonLeft.id = "button_left";
        buttonLeft.onclick = PrevPage;
        buttonLeft.innerHTML = "←";
        
        updateButtonLeft();
    }
    
    function updateButtonLeft() {
        var buttonLeft = document.getElementById("button_left");
        
        if (session_settings.hasOwnProperty("page")) {
            var page = session_settings["page"];
        } else {
            page = 0;
        }
        
        buttonLeft.disabled = (page === 0) ? true : false;
        buttonLeft.className = ((page === 0) ? "off_" : "") + "button_" + session_settings["theme"];
    }
    
    function setButtonApp(parent) {
        // Sort on Apperance
        var buttonApp = document.createElement("button");
        parent.appendChild(buttonApp);
        
        // Set its attributes
        buttonApp.id = "button_app";
        buttonApp.onclick = SortOnAppearance;
        
        updateButtonApp();
    }
    
    function updateButtonApp() {
        var buttonApp = document.getElementById("button_app");
        
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            sort = "app";
        }
        
        buttonApp.className = (sort === "app") ? "sort_9_1" : "sort_1_9";   
    }
    
    function setButtonAlp(parent) {
        // Sort on Alphabet
        var buttonAlp = document.createElement("button");
        parent.appendChild(buttonAlp);
        
        // Set its attributes
        buttonAlp.id = "button_alp";
        buttonAlp.onclick = SortOnAlphabet;
        
        updateButtonAlp();
    }
    
    function updateButtonAlp() {
        var buttonAlp = document.getElementById("button_alp");
        
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            sort = "app";
        }
        
        buttonAlp.className = (sort === "alp") ? "sort_z_a" : "sort_a_z";   
    }
    
    function setButtonRight(parent) {
        // Next page
        var buttonRight = document.createElement("button");
        parent.appendChild(buttonRight);
        
        // Set its attributes
        buttonRight.id = "button_right";
        buttonRight.onclick = NextPage;
        buttonRight.innerHTML = "→";
        
        updateButtonRight();
    }
    
    function updateButtonRight() {
        var buttonRight = document.getElementById("button_right");
        
        // Check if this is the last page. If so, disable next button.
        // TODO: 
        <?php        
            PrettyPrint("var NrOfItems = ".GetNumberOfItems($id).";");
        ?>
        buttonRight.disabled = (NrOfItems < 101) ? true : false;
        buttonRight.className = ((NrOfItems < 101) ? "off_" : "") + "button_" + session_settings["theme"];
        
    }
</script>
