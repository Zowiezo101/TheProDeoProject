/* global session_settings, getItemFromDatabase
 * , updateSessionSettings, dict_NavBar, dict_Search, setMaps
 * , dict_PeoplesParams, dict_Peoples
 * , dict_LocationsParams, dict_Locations
 * , dict_SpecialsParams, dict_Specials
 * , dict_Books, dict_BooksParams, dict_Events, dict_EventsParams */

var dict_params = null;
var dict = null;
var item_links = null;

switch(session_settings["table"]) {
    case "peoples":
        dict_params = dict_PeoplesParams;
        dict = dict_Peoples;
        item_links = [
            {table: "people_to_activity", column: "people_id", data: "activity_id", descr: "Verwante gebeurtenissen"},
            {table: "people_to_location", column: "people_id", data: "location_id", descr: "Verwante locaties"},
            {table: "people_to_parent", column: "people_id", data: "parent_id", descr: "Ouders"},
            {table: "people_to_parent", column: "parent_id", data: "people_id", descr: "Kinderen"},
            {table: "people_to_people", column: "people1_id", data: "people2_id", descr: "Ook bekend als"},
            {table: "people_to_people", column: "people2_id", data: "people1_id", descr: "Ook bekend als"}
        ];
        break;
        
    case "locations":
        dict_params = dict_LocationsParams;
        dict = dict_Locations;
        item_links = [
            {table: "location_to_activity", column: "location_id", data: "activity_id", descr: "Verwachte gebeurtenissen"},
            {table: "people_to_location", column: "location_id", data: "people_id", descr: "Verwante personen"},
            {table: "location_to_location", column: "location1_id", data: "location2_id", descr: "Ook bekend als"},
            {table: "location_to_location", column: "location2_id", data: "location1_id", descr: "Ook bekend als"}
        ];
        break;
        
    case "specials":
        dict_params = dict_SpecialsParams;
        dict = dict_Specials;
        item_links = [
            {table: "special_to_activity", column: "special_id", data: "activity_id", descr: "Verwachte gebeurtenissen"}
        ];
        break;
        
    case "books":
        dict_params = dict_BooksParams;
        dict = dict_Books;
        item_links = [];
        break;
        
    case "events":
        dict_params = dict_EventsParams;
        dict = dict_Events;
        item_links = [
            {table: "people_to_activity", column: "activity_id", data: "people_id", descr: "Verwachte personen"},
            {table: "location_to_activity", column: "activity_id", data: "location_id", descr: "Verwante locaties"},
            {table: "special_to_activity", column: "activity_id", data: "special_id", descr: "Verwante specials"}
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
        if ((value === -1) || (value === "-1")) {
            value = " ";
        } 

        // Name is already shown. 
        // ID number might just confuse the reader, so hide it.
        var main_key = session_settings["table"].substr(0, session_settings["table"].length - 1) + "_id";
        if ((key === "name") || (key === main_key) || (key === "order_id")) {
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
        
        if (item_link.column === "activity_id") {
            // TODO: Convert this event_id to all activity IDs
            // Get all the linked activity IDs
            var id = "";
            
            await getItemFromDatabase("activity_to_event", session_settings["id"], "event_id").then(function (information2) {
                var ids = [];
                
                for (var idx2 in information2) {
                    var item = information2[idx2];
                    ids.push(item.activity_id);
                }
                
                id = ids;
            });
        } else {
            id = session_settings["id"];
        }
        
        await getItemFromDatabase(item_link.table, id, item_link.column).then(async function(information2) {
            var names = [];
            var values = [];
            var types = [];
            var genders = [];

            if (information2 === null) {
                return;
            }

            // For all the available information
            for (var idx2 in information2) {
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
                    case "people_id":
                    case "people1_id":
                    case "people2_id":
                        table_name = "peoples";
                        break;
                }
                
                await getItemFromDatabase(table_name, item[item_link.data]).then(function(object) {
                    if (item_link.data === "activity_id") {
                        // Turn this into event ID
                    }
                    object = object[0];
                    
                    names.push(object["name"]);
                    values.push(item[item_link.data]);
                    genders.push(getGenderNoun(object["gender"], 1));
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
                var gender = genders[idx3];
                
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
                    TableLink2.innerHTML = name + gender;
                    TableLink2.value = value;
                    TableLink2.table_name = table_name;
                    TableLink2.onclick = function() {
                        if (this.table_name !== session_settings["table"]) {
                            goToPage(this.table_name + ".php", "", this.value);
                        } else { 
                            updateSessionSettings("id", this.value).then(getItemFromDatabase(this.table_name, this.value).then(showItemInfo, console.log), console.log);
                        }
                    };

                } else {
                    // When the ID is not given, just give the name..
                    TableData2.innerHTML = name;
                }
                
            }
        }, console.log);

    }

    if ((session_settings["table"] === "peoples") || (session_settings["table"] === "events")) {
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