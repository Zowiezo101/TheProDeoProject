/* global session_settings, getItemFromDatabase
 * , dict_PeoplesParams, dict_Peoples, updateSessionSettings, dict_NavBar, dict_Search */

var dict_params = null;
var dict = null;
var item_links = null;

switch(session_settings["table"]) {
    case "peoples":
        dict_params = dict_PeoplesParams;
        dict = dict_Peoples;
        item_links = [
            {table: "people_to_activity", column: "people_id", data: "activity_id", descr: "Verwante gebeurtenissen"},
            {table: "people_to_location", column: "people_id", data: "loaction_id", descr: "Verwante locaties"},
            {table: "people_to_parent", column: "people_id", data: "parent_id", descr: "Ouders"},
            {table: "people_to_people", column: "people1_id", data: "people2_id", descr: "Ook bekend als"},
            {table: "people_to_people", column: "people2_id", data: "people1_id", descr: "Ook bekend als"}
        ];
        break;
        
}

async function showItemInfo(information) {
    information = information[0];
    
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
    contentEl.appendChild(table);

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
            TableKey.innerHTML = dict_params[key];

            // Only show two decimals after the comma
            var TableLink = document.createElement("a");
            TableLink.innerHTML = "";

            if (key === "book_start_vers") {
                await getItemFromDatabase("books", information["book_start_id"]).then(function (bookInfo) { 
                    bookInfo = bookInfo[0];
                    
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
                    bookInfo = bookInfo[0];
                    
                    // When the information is retreived:
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
            TableKey.innerHTML = dict_params[key];

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

    for (var idx in item_links) {
        var item_link = item_links[idx];
        
        await getItemFromDatabase(item_link.table, session_settings["id"], item_link.column).then(async function(information2) {
            var names = [];
            var values = [];
            var types = [];

            if (information2 === null) {
                return;
            }

            // For all the available information
            for (var idx2 in information2)
            {
                var item = information2[idx2];
                
                // If a value is set as -1 (unknown), 
                // set it to an emtpty string for human readability
                if (item === null) {
                    continue;
                }
                
                // Column is who we are, we just want to show the linking information
                if (item.hasOwnProperty("type")) {
                    types.push(item["type"]);
                } 
                
                switch(item_link.data) {
                    case "activity_id":
                        var table_name = "activitys";
                        break;

                    case "location_id":
                        table_name = "locations";
                        break;

                    case "parent_id":
                    case "people1_id":
                    case "people2_id":
                        table_name = "peoples";
                        break;
                }
                
                await getItemFromDatabase(table_name, item[item_link.data]).then(function(object) {
                    object = object[0];
                    
                    names.push(object["name"]);
                    values.push(item[item_link.data]);
                } , console.log);
            }

            // Add a new table row
            var TableRow = document.createElement("tr");
            table.appendChild(TableRow);

            // Description of the information shown
            var TableKey = document.createElement("td");
            TableKey.innerHTML = item_link.descr;
            TableRow.appendChild(TableKey);

            // Actual data
            var TableData = document.createElement("td");
            TableRow.appendChild(TableData);
            
            // Create a table with the different names
            var Table2 = document.createElement("table");
            TableData.appendChild(Table2);

            // And for each name, create the amount of rows needed to show
            // all the different linked names    
            for (var idx3 in names) {
                var value = values[idx3];
                var name = names[idx3];
                
                // Table row
                var TableRow2 = document.createElement("tr");
                Table2.appendChild(TableRow2);

                // Table data
                var TableData2 = document.createElement("td");
                TableRow2.appendChild(TableData2);
                
                if (value !== null) {
                    // Table links, the name is the name of the item
                    var TableLink2 = document.createElement("a");
                    TableData2.appendChild(TableLink2);
                    
                    // Set its attributes
                    TableLink2.innerHTML = name;
                    TableLink2.value = value;
                    TableLink2.table_name = table_name;
                    TableLink2.onclick = function() {
                        goToPage(this.table_name + ".php", "", this.value);
                    };

                } else {
                    // When the ID is not given, just give the name..
                    TableData2.innerHTML = name;
                }
                
            }
        }, console.log);

    }

    if (typeof setMaps === "function") {
        setMaps(contentEl);
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

    var defaultText = document.createElement("div");
    right.appendChild(defaultText);

    // Set its attributes
    defaultText.id = "default";
    defaultText.innerHTML = dict["default"];

    // Show the selected person, when someone is selected
    if (session_settings.hasOwnProperty("id")) {
        getItemFromDatabase(session_settings["table"], 
                            session_settings["id"]).then(showItemInfo, console.log);
    }

    return right;
}