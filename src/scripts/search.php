<script>
    
    const ITEM_BOOK = "0";
    const ITEM_EVENT = "1";
    const ITEM_PEOPLE = "2";
    const ITEM_LOCATION = "3";
    const ITEM_SPECIAL = "4";
    const ITEM_ALL = "-1";
    
    const TYPE_INPUT = 0;
    const TYPE_SELECT = 1;
    const TYPE_SLIDER = 2;
    
    // This function is executed once the DOM is loaded
    $(function(){
        // Get the search results with the given parameters
        onSearch();
    });
    
//    // The elements that need initializing
//    var elementInit = {
//        "start": false,
//        "end": false,
//        "specific": false,
//        "num_chapters": false,
//        "age": false,
//        "age_parents": false
//    };
//
//    // The elements that can be disabled
//    var elementEnabled = {
//        "num_chapters": false,
//        "age": false,
//        "age_parents": false
//    };
//
    function onSearch() {
        // Get all the filled in search parameters
        var parameters = getSearchParameters();
        
        // Update the query to the session
        updateSession(parameters);
        
        // Get database results with the given parameters
        searchDatabase(parameters);
    }
    
    function getSearchParameters() {
        var parameters = [];       
        
        // All the parameters to look for
        param_list = {
            "name": [ITEM_ALL, TYPE_INPUT],
            "meaning_name": [ITEM_ALL, TYPE_INPUT],
            "descr": [ITEM_ALL, TYPE_INPUT],
            "start_book": [ITEM_ALL, TYPE_SELECT],
            "start_chap": [ITEM_ALL, TYPE_SELECT],
            "end_book": [ITEM_ALL, TYPE_SELECT],
            "end_chap": [ITEM_ALL, TYPE_SELECT],
            "num_chapters": [ITEM_BOOK, TYPE_SLIDER],
            "length": [ITEM_EVENT, TYPE_INPUT],
            "date": [ITEM_EVENT, TYPE_INPUT],
            "age": [ITEM_PEOPLE, TYPE_SLIDER],
            "parent_age": [ITEM_PEOPLE, TYPE_SLIDER],
            "gender": [ITEM_PEOPLE, TYPE_SELECT],
            "tribe": [ITEM_PEOPLE, TYPE_SELECT],
            "profession": [ITEM_PEOPLE, TYPE_INPUT],
            "nationality": [ITEM_PEOPLE, TYPE_INPUT],
            "type_location": [ITEM_LOCATION, TYPE_SELECT],
            "type_special": [ITEM_SPECIAL, TYPE_SELECT]
        };
        
        for (var param_name in param_list) {
            // Get the type of parameter
            param_type = param_list[param_name];
            
            // Get the value of this parameter
            param_val = getParameter(param_name, param_type);
            if (param_val !== null) {
                // If the value is null, it's not set and thus not 
                // needed for searching
                parameters[param_name] = param_val;
            }
        };
        
        return parameters.length > 0 ? parameters : false;
    }
    
    function searchDatabase(parameters) {
        // For every item type
        [ITEM_BOOK,
         ITEM_EVENT,
         ITEM_PEOPLE,
         ITEM_LOCATION,
         ITEM_SPECIAL].forEach((item_type) => {
            // Check if we selected a specific item type, or will search
            // all item types
            if (isItemType(item_type)) {
                // Get the search result if this type is selected
                getSearchResults(getItemType(item_type), parameters).then(function(results) {
                    // Insert the database results
                    insertSearchResults(getItemType(item_type), results);
                });;
            }
        });
    }
    
    function insertSearchResults(item_type, results) {
        // Start out clean
        $("#tab" + item_type).empty();
        
        if (results.error !== "") {
            // Error message, because database can't be reached
            $("#tab" + item_type).append(dict["database.no_results"]);
        } else if (results.records.length > 0) {
            // The table with all the search results
            insertTable(item_type, results);
        }
    }
    
    function getParameter(param_name, param_type) {
        var item_type = param_type[0];
        var input_type = param_type[1];
        
        // Pre-define this variable
        var val = "";
        
        switch(input_type) {
            case TYPE_INPUT:
                // Get the current value of the parameter
                val = $("#item_" + param_name).val();
                break;
                
            case TYPE_SELECT:
                // Get the current selected value of the parameter
                val = $("#item_" + param_name + " :selected").val();
                break;
                
            case TYPE_SLIDER:
                // Get the current selected value of the parameter
                val = $("#item_" + param_name).slider('getValue');
                break;
                
//        if (elementInit["age"]) {
//            var age = $("#item_age").slider('getValue');
//            params["search_age"] = 
//                    elementEnabled["age"] ? 
//                    age.join('-') : "";
//        }
        }
        
        // If the current value is empty or not of need, set it to null
        if (val === "" || val === "-1" || checkItemType(item_type) === false) {
            val = null;
        }
        
        // Return the current value
        return val;
    }
    
    function checkItemType(item_type) {
        // Get the selected specific type
        var selected_type = $("#item_specific :selected").val();
        
        // The user can select a specific item type to search for, 
        // when this item type is selected (selected_type), only the
        // parameters related to this item_type are used. The given
        // item_type corresponds with a specific parameter and this function
        // checks whether this parameter will be used
        return (item_type === selected_type || item_type === "-1");
    }
    
    function isItemType(item_type) {
        // Get the selected specific type
        var selected_type = $("#item_specific :selected").val();
        
        // This function is similar to checkItemType, except it returns
        // true if the selected specific type is equal to the given item
        // type, of equal to "ITEM_ALL"
        return (item_type === selected_type || selected_type === "-1");
    }
    
    function getItemType(item_type) {
        // Get the ITEM_xxx value and convert it to a TYPE_xxx value
        // for communication with the database
        item_string = "";
        
        switch(item_type) {
            case ITEM_BOOK:
                item_string = TYPE_BOOK;
                break;
                
            case ITEM_EVENT:
                item_string = TYPE_EVENT;
                break
                
            case ITEM_PEOPLE:
                item_string = TYPE_PEOPLE;
                break
                
            case ITEM_LOCATION:
                item_string = TYPE_LOCATION;
                break
                
            case ITEM_SPECIAL:
                item_string = TYPE_SPECIAL;
                break
        }
        
        return item_string;
    }
    
    function insertTable(item_type, results) {
        // All the selected/used columns for this item type
        var columns = results.columns;
        
        // Add a link column and get an array with header cells
        var header = getHeaderRow(columns);
            
        // Insert all the records into the table
        var rows = results.records.map((record) => {
            return getDataRow(record);
        });
        
        var body = rows.join("");
        
        // The table, this will be filled in later
        $("#tab" + item_type).append(`
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>` + header + `</thead>
                    <tbody>` + body + `</tbody>
                </table>
            </div>
        `);
        
        // This is to be able to sort the results
        $("#tab" + item_type + " table").DataTable({
            paging: false,
            searching: false,
            info: false
        });
    }
    
    function getHeaderRow(columns) {
        return columns.filter((column) => {
            // Remove these columns
            return ["book_start_vers", "book_start_chap", 
                    "book_end_vers",   "book_end_chap",
                    "id"].includes(column) ? false : true;
        }).concat("link").map((column) => {
            // Some renaming for these columns
            if(column === "book_start_id") {
                column = "book_start";
            } else if(column === "book_end_id") {
                column = "book_end";
            }
            
            // Return the row header cells
            return '<th scope="col">' + dict["items." + column] + '</th>';
        }).join("");
    }
    
    function getDataRow(record) {
        // Get all the keys and filter out the ones we don't need
        var keys = Object.keys(record).filter((key) => {
            // Remove these columns
            return ["book_start_vers", "book_start_chap", 
                    "book_end_vers",   "book_end_chap",
                    "id"].includes(key) ? false : true;
        }).concat("link");
        
        // Loop through all the keys
        var data_row = keys.map((key) => {
            switch(key) {
                case "name":
                    // TODO: Do this in database with AKA
                    data_cell = '<th scope="row">' + record["name"] + (record["aka"] ? " (" + record["aka"] + ")" : "") + '</th>';
                    break;
                    
                case "link":
                    data_cell = "<td>" + "TODO" + "</td>";
                    break;
                    
                default:
                    // Default sitation is to take the data as is
                    data_cell = "<td>" + record[key] + "</td>";
                    break
            }
            
            // TODO: Something with data order to get the correct order
            return data_cell;
        });

        return "<tr>" + data_row.join("") + "</tr>";
    }
    
//    function onBookChange() {
//        // For start and end
//    }
//    
//    function onItemChange() {
//        
//    }
//    
//    function onGenderChange() {
//        
//    }
//    
//    function onTribeChange() {
//        
//    }
//    
//    function onLocationChange() {
//        
//    }
//    
//    function onSpecialChange() {
//        
//    }
//
///** Inserting the results in a readable table format 
// * @param {String} type
// * @param {Object} result * 
// * */
//function insertItems(type, result) {
//    
//    // No errors and at least 1 item of data
//    if (result.records) {
//        
//        // Table header is the name
//        var table_header = insertHeader(type, "name");
//        table_header += insertHeader(type, "meaning_name");
//        table_header += insertHeader(type, "descr");
//        table_header += insertHeader(type, "length");
//        table_header += insertHeader(type, "date");
//        table_header += insertHeader(type, "age");
//        table_header += insertHeader(type, "parent_age");
//        table_header += insertHeader(type, "gender");
//        table_header += insertHeader(type, "tribe");
//        table_header += insertHeader(type, "profession");
//        table_header += insertHeader(type, "nationality");
//        table_header += insertHeader(type, "type");
//        table_header += insertHeader(type, "book_start");
//        table_header += insertHeader(type, "book_end");
//        table_header += insertHeader(type, "num_chapters");
//        table_header += insertHeader(type, "link");
//        
//        var table_row = [];
//        for (var i = 0; i < result.records.length; i++) {
//            var data = result.records[i];
//            
//            // Table header is the name
//            var table_data = insertData(type, "name", data);
//            table_data += insertData(type, "meaning_name", data);
//            table_data += insertData(type, "descr", data);
//            table_data += insertData(type, "length", data);
//            table_data += insertData(type, "date", data);
//            table_data += insertData(type, "age", data);
//            table_data += insertData(type, "parent_age", data);
//            table_data += insertData(type, "gender", data);
//            table_data += insertData(type, "tribe", data);
//            table_data += insertData(type, "profession", data);
//            table_data += insertData(type, "nationality", data);
//            table_data += insertData(type, "type", data);
//            table_data += insertData(type, "book_start", data);
//            table_data += insertData(type, "book_end", data);
//            table_data += insertData(type, "num_chapters", data);
//            table_data += insertData(type, "link", data);
//            
//            // The row for every item we've got
//            table_row.push('<tr>' + table_data + '</tr>');
//        }
//    }
//
///**
// * Inserting data into the table of results
// * @param {String} type
// * @param {String} name
// * @param {Object} data
// * */
//function insertData(type, name, data) {
//    var types = getTypes(name);
//    
//    var table_data = "";
//    if (types.includes(type)) {
//        if (name === "name") {
//            table_data = '<th scope="row">' + data["name"] + (data["aka"] ? " ("+data["aka"] +")" : "") + '</th>';
//        } else if (name === "link") {
//            table_data = '<td data-order="' + data["id"] + '">' + getLinkToItem(type, data["id"], "self") + '</td>';
//        } else if (name === "length") {
//            table_data = '<td>' + data["length"] + '</td>';
//        } else if (name === "parent_age") {
//            if ((data["father_age"] !== "-1") && (data["mother_age"] !== "-1")) {
//                table_data = '<td>' + data["father_age"] + ', ' + data["mother_age"] + '</td>';
//            } else {
//                table_data = '<td>' + Math.max(data["father_age"], data["mother_age"]) + '</td>';
//            } 
//        } else if (name === "gender") {
//            table_data = '<td>' + getTypeString(data["gender"]) + '</td>';
//        } else if (name === "tribe") {
//            table_data = '<td>' + getTypeString(data["tribe"]) + '</td>';
//        } else if (name === "type") {
//            table_data = '<td>' + getTypeString(data["type"]) + '</td>';
//        } else if (name === "book_start") {
//            // Data to order by
//            var data_order =
//                        ((data["book_start_id"].length < 3) ? 
//                            ("0".repeat(3 - data["book_start_id"].length) + data["book_start_id"]) : 
//                                                                            data["book_start_id"]) + 
//                        ((data["book_start_chap"].length < 3) ? 
//                            ("0".repeat(3 - data["book_start_chap"].length) + data["book_start_chap"]) : 
//                                                                              data["book_start_chap"]) + 
//                        ((data["book_start_vers"].length < 3) ? 
//                            ("0".repeat(3 - data["book_start_vers"].length) + data["book_start_vers"]) : 
//                                                                              data["book_start_vers"]);
//                    
//            table_data = '<td data-order="' + data_order + '">' + 
//                    dict["books.book_" + data["book_start_id"]] + 
//                    " " + data["book_start_chap"] + 
//                    ":" + data["book_start_vers"] + 
//                '</td>';
//        } else if (name === "book_end") {
//            // Data to order by
//            var data_order =
//                        ((data["book_end_id"].length < 3) ? 
//                            ("0".repeat(3 - data["book_end_id"].length) + data["book_end_id"]) : 
//                                                                          data["book_end_id"]) + 
//                        ((data["book_end_chap"].length < 3) ? 
//                            ("0".repeat(3 - data["book_end_chap"].length) + data["book_end_chap"]) : 
//                                                                            data["book_end_chap"]) + 
//                        ((data["book_end_vers"].length < 3) ? 
//                            ("0".repeat(3 - data["book_end_vers"].length) + data["book_end_vers"]) : 
//                                                                            data["book_end_vers"]);
//                                                                      
//            table_data = '<td data-order="' + data_order + '">' + 
//                    dict["books.book_" + data["book_end_id"]] + 
//                    " " + data["book_end_chap"] + 
//                    ":" + data["book_end_vers"] + 
//                '</td>';
//        } else {
//            table_data = '<td>' + data[name] + '</td>';
//        }
//    }
//    
//    return table_data;
//}
//
//function getTypes(name) {
//    var types = [];
//    if ($.inArray(name, ["name", "link", "book_start", "book_end", "num_chapters"]) !== -1) {
//        switch(name) {
//            case "name":
//            case "link":
//                types = ["books", "events", "peoples", "locations", "specials"];
//                break;
//                
//            case "book_start":
//            case "book_end":
//                types = ["events", "peoples", "locations", "specials"];
//                break;
//                
//            case "num_chapters":
//                types = ["books"];
//                break;
//        }
//    } else if (session_settings["search_" + name]) {
//        // If this value saved in the session?
//        switch(name) {
//            case "meaning_name":
//                types = ["peoples", "locations", "specials"];
//                break;
//
//            case "descr":
//                types = ["events", "peoples", "locations", "specials"];
//                break;
//                
//            case "length":
//            case "date":
//                types = ["events"];
//                break;
//                
//            case "age":
//            case "parent_age":
//            case "gender":
//            case "tribe":
//            case "profession":
//            case "nationality":
//                types = ["peoples"];
//                break;
//        }
//    }  else if ((name === "type") && (session_settings["search_" + name + "_location"])) {
//        types = ["locations"];
//    } else if ((name === "type") && (session_settings["search_" + name + "_special"])) {
//        types = ["specials"];
//    }
//    
//    return types;
//}
//
//function getTypeString(int) {
//    var str = "";
//    
//    if (typeof dict[int] !== "undefined") {
//        str = dict[int];
//    }
//    
//    return str;
//}
//
//function getLinkToItem(type, id, text, options) {
//    var newTab = options && options.hasOwnProperty("openInNewTab") ? options.openInNewTab : false;
//    var classes = options && options.hasOwnProperty("classes") ? options.classes : "";
//    var panTo = options && options.hasOwnProperty("panToItem") ? options.panToItem : "";
//    
//    // If any other classes are inserted
//    if (typeof classes === "undefined" || classes === "") {
//        classes = "font-weight-bold";
//    }
//    
//    var to_table = type;
//    var to_item = to_table.substr(0, to_table.length - 1);
//    if (["familytree", "timeline"].includes(type)) {
//        to_item = "map";
//    }
//    
//    var link = setParameters(to_table + (id !== "-1" ? ("/" + to_item + "/" + id) : ""));
//    if (text === "self") {
//        text = link.substr(get_settings["lang"] ? 4 : 1);
//    }
//    if (text === "Global") {
//        text = dict["timeline.global"];
//    }
//    
//    if (id === null) {
//        link = '#';
//    }
//    
//    if (panTo !== "") {
//        link += '?panTo=' + panTo;
//    }
//    
//    if ((type === "worldmap") && id !== "-1") {
//        // Use a function to link to the item
//        return '<a href="javascript: void(0)" onclick="getLinkToMap(' + id + ')"' + 
//            'class="' + classes + '">' + 
//                text + 
//        '</a>';        
//    } else {
//        // Use an actual hyhperlink to the item
//        return '<a href="' + link + '" ' + (newTab ? 'target="_blank" ' : '') +
//            (type === "worldmap" ? 'data-toggle="tooltip" title="' + dict["items.details.worldmap"] + '"' : "") + 
//            'class="' + classes + '">' + 
//                text + 
//        '</a>';
//    }
//}
//
//function insertChapters(type) {
//    // Get the selected book and its amount of chapters
//    var book = $("#item_" + type + "_book option:selected");
//    var num_chapters = book.data("numChapters");
//    
//    // Insert all the options
//    $("#item_" + type + "_chap").empty();
//    $("#item_" + type + "_chap").append(
//                // Default option, is not selectable
//                '<option selected disabled value="-1">' + 
//                    dict["books.chapter"] + 
//                '</option>'
//            );
//    for (var i = 0; i < num_chapters; i++) {
//        // Inserting the chapters
//        $("#item_" + type + "_chap").append(
//                '<option value="' + (i+1) + '">' + 
//                    (i+1) + 
//                '</option>'
//            );
//    }
//    
//    // Need to initialize First/Last appearance chapters?
//    if (!elementInit[type]) {
//        // Setting back the selected chapter from the session
//        $("#item_" + type + "_chap").val(
//                session_settings["search_" + type + "_chap"] ? 
//                session_settings["search_" + type + "_chap"] : -1);
//                
//        // Done initializing this dropdown
//        elementInit[type] = true;
//    } else {
//        // When changing books, preset it to the first/last chapter
//        $("#item_" + type + "_chap").val(type === "start" ? 1 : num_chapters);
//        
//        // Take over the changes
//        $("#item_" + type + "_chap").change();
//    }
//    
//    // Set the filter if a value is set
//    if ($("#item_" + type + "_chap").val() !== -1 &&
//        $("#item_" + type + "_chap").val() !== null) {
//        removeFilter(type, "#item_" + type + "_label");
//    }
//}
//
//function insertSpecifics() {
//    // Get the selected book and its amount of chapters
//    var type = $("#item_specific option:selected").val();
//    
//    if (elementInit["specific"]) {
//        // Update the query to the session
//        // Only if this was an actual change and not the initializing
//        updateSession({
//            "search_specific": type,
//
//            // Set all the search options to zero
//            "search_num_chapters": null,
//            "search_length": null,
//            "search_date": null,
//            "search_age": null,
//            "search_parent_age": null,
//            "search_gender": null,
//            "search_tribe": null,
//            "search_profession": null,
//            "search_nationality": null,
//            "search_type_location": null,
//            "search_type_special": null
//        });
//        
//        removeFilter("num_chapters", null, true);
//        removeFilter("length", null, true);
//        removeFilter("date", null, true);
//        removeFilter("age", null, true);
//        removeFilter("parent_age", null, true);
//        removeFilter("gender", null, true);
//        removeFilter("tribe", null, true);
//        removeFilter("profession", null, true);
//        removeFilter("nationality", null, true);
//        removeFilter("type_location", null, true);
//        removeFilter("type_special", null, true);
//        
//        searchItems();
//    } else {
//        elementInit["specific"] = true;
//    }
//
//    // Make all the specific filters invisible
//    $("#item_specifics_books").addClass("d-none");
//    $("#item_specifics_events").addClass("d-none");
//    $("#item_specifics_peoples").addClass("d-none");
//    $("#item_specifics_locations").addClass("d-none");
//    $("#item_specifics_specials").addClass("d-none");
//    
//    if (type !== "-1") {
//        // Option to remove the filter
//        removeFilter("specific", "#item_specific_label");
//        
//        // Remove the tabs on the top to only show the selected specific item
//        $("#search_tabs").addClass("d-none");
//        
//        // Also, remove all the main filters that are not related to 
//        // the specific filter type..
//        $(".book_prop input, \n\
//           .event_prop input, \n\
//           .people_prop input, \n\
//           .location_prop input, \n\
//           .special_prop input").addClass("disabled").attr("disabled", "true");
//        $(".book_prop select, \n\
//           .event_prop select, \n\
//           .people_prop select, \n\
//           .location_prop select, \n\
//           .special_prop select").addClass("disabled").attr("disabled", "true");
//    
//        // Only show the selected specifics
//        switch(type) {
//            case "0":
//                // Books
//                $("#item_specifics_books").removeClass("d-none");
//                $(".book_prop input").removeClass("disabled").removeAttr("disabled");
//                $(".book_prop select").removeClass("disabled").removeAttr("disabled");
//                $("a[data-target='#tabbooks']").trigger('click');
//                break;
//            case "1":
//                // Events
//                $("#item_specifics_events").removeClass("d-none");
//                $(".event_prop input").removeClass("disabled").removeAttr("disabled");
//                $(".event_prop select").removeClass("disabled").removeAttr("disabled");
//                $("a[data-target='#tabevents']").trigger('click');
//                break;
//            case "2":
//                // Peoples
//                $("#item_specifics_peoples").removeClass("d-none");
//                $(".people_prop input").removeClass("disabled").removeAttr("disabled");
//                $(".people_prop select").removeClass("disabled").removeAttr("disabled");
//                $("a[data-target='#tabpeoples']").trigger('click');
//                break;
//            case "3":
//                // Locations
//                $("#item_specifics_locations").removeClass("d-none");
//                $(".location_prop input").removeClass("disabled").removeAttr("disabled");
//                $(".location_prop select").removeClass("disabled").removeAttr("disabled");
//                $("a[data-target='#tablocations']").trigger('click');
//                break;
//            case "4":
//                // Specials
//                $("#item_specifics_specials").removeClass("d-none");
//                $(".special_prop input").removeClass("disabled").removeAttr("disabled");
//                $(".special_prop select").removeClass("disabled").removeAttr("disabled");
//                $("a[data-target='#tabspecials']").trigger('click');
//                break;
//        }
//    } else {
//        
//        // Show the tabs again
//        $("#search_tabs").removeClass("d-none");
//        
//        // Add back the main filters
//        $(".book_prop input, \n\
//           .event_prop input, \n\
//           .people_prop input, \n\
//           .location_prop input, \n\
//           .special_prop input").removeClass("disabled").removeAttr("disabled");
//        $(".book_prop select, \n\
//           .event_prop select, \n\
//           .people_prop select, \n\
//           .location_prop select, \n\
//           .special_prop select").removeClass("disabled").removeAttr("disabled");
//    }
//}
//
//
//function removeFilter(type, label, force) {
//    if (typeof force === "undefined") {
//        force = false;
//    }
//    
//    if ((typeof label !== "undefined") && (label !== null)) {
//        // Option to remove the filter
//        $(label + " a").remove();
//        $(label).append(
//                '<a tabindex=0 onclick="removeFilter(\'' + type + '\')" data-toggle="tooltip" data-placement="top" title="' + dict["search.remove_filter"] + '">' + 
//                    '<i class="fa fa-times-circle" aria-hidden="true"></i>' + 
//                '</a>');
//    } else {
//        switch(type) {
//            case "start":
//            case "end":
//                // Reset the book and chapter
//                $("#item_" + type + "_book").val(-1);
//                $("#item_" + type + "_chap").val(-1);
//                break;
//                
//            case "specific":
//            case "gender":
//            case "tribe":
//            case "type_location":
//            case "type_special":
//                // Reset the specifics
//                $("#item_" + type).val(-1);
//                if (!force) {
//                    $("#item_" + type).change();
//                }
//                break
//                
//            case "num_chapters":
//            case "age":
//            case "parent_age":
//                // Reset the sliders
//                var slider = $("#item_" + type).slider();
//                slider.slider('refresh');
//                    
//                // Set back the color to disabled
//                $("#slider_" + type)
//                    .find(".slider-selection")
//                    .css("background-color", "");
//            
//                // To make a different between enabled and disabled values
//                elementEnabled[type] = false;
//                break;
//                
//            case "length":
//            case "date":
//            case "profession":
//            case "nationality":
//                $("#item_" + type).val("");
//                break;
//        }
//
//        // Remove the [x]
//        $("#item_" + type + "_label a").remove();
//    
//        // Search again (unless we're clearing some filters)
//        if (!force) {
//            searchItems();
//        }
//    }
//}
//
//
///** Insert the search term from the session */
//function insertSearch() {
//            
//    // Sliders     
//    searchBooks(JSON.stringify({"sliders": ["chapters"]})).then(function(result) {
//        
//        // No errors and at least 1 item of data
//        if (result.records) {
//            var data = result.records[0];
//            var max = parseInt(data["max_num_chapters"], 10);
//            var min = parseInt(Math.max(data["min_num_chapters"], 1), 10);
//            
//            // Set the max and min values
//            var slider_num_chapters = $("#item_num_chapters").slider({
//                max: max,
//                min: min
//            });
//            
//            // Set the onSlideStop event
//            slider_num_chapters.on("slideStop", onSliderChangeNumChapters);
//
//            if (session_settings["search_num_chapters"]) {
//                // Initialize the sliders and set their values
//                slider_num_chapters.slider("setValue", 
//                    [parseInt(session_settings["search_num_chapters"].split('-')[0], 10),
//                     parseInt(session_settings["search_num_chapters"].split('-')[1], 10)]);
//
//                // Activate the onchange function
//                onSliderChangeNumChapters({value: session_settings["search_num_chapters"].split("-")});
//            } else {
//                // Initialize the sliders and set their values
//                slider_num_chapters.slider("setValue", 
//                    [min, max]);
//            }
//        }
//    });
//    
//    // Sliders & Select
//    searchPeoples(JSON.stringify({
//        'sliders': 
//            ["age",
//             "parent_age"],
//        'select':
//            ["gender",
//             "tribe"]
//    })).then(function(result) {
//        
//        // No errors and at least 1 item of data
//        if (result.records) { 
//            var data = result.records[0];
//            
//            // The age & parent age sliders
//            var max1 = parseInt(Math.max(data["max_age"], 1), 10);
//            var min1 = parseInt(Math.max(data["min_age"], 1), 10);
//            var max2 = parseInt(Math.max(data["max_parent_age"], 1), 10);
//            var min2 = parseInt(Math.max(data["min_parent_age"], 1), 10);
//            
//            // Set the max and min values
//            var slider_age = $("#item_age").slider({
//                max: max1,
//                min: min1
//            });
//            
//            // Set the max and min values
//            var slider_parent_age = $("#item_parent_age").slider({
//                max: max2,
//                min: min2
//            });
//            
//            // Set the onSlideStop event
//            slider_age.on("slideStop", onSliderChangeAge);
//            slider_parent_age.on("slideStop", onSliderChangeParentAge);
//
//            // Re-set the search settings that were already present
//            if (session_settings["search_age"]) {
//                slider_age.slider('setValue',
//                  [parseInt(session_settings["search_age"].split('-')[0], 10),
//                   parseInt(session_settings["search_age"].split('-')[1], 10)]);
//                 
//                // Activate the onchange function
//                onSliderChangeAge({value: session_settings["search_age"].split("-")});
//            } else {
//                slider_age.slider('setValue',
//                  [min1, max1]);
//            }
//
//            if (session_settings["search_parent_age"]) {
//                slider_parent_age.slider('setValue',
//                  [parseInt(session_settings["search_parent_age"].split('-')[0], 10),
//                   parseInt(session_settings["search_parent_age"].split('-')[1], 10)]);
//                 
//                // Activate the onchange function
//                onSliderChangeParentAge({value: session_settings["search_parent_age"].split("-")});
//            } else {
//                slider_parent_age.slider('setValue',
//                  [min2, max2]);
//            }
//        }
//        
//        // These come from different tables and are an entirely different type
//        // of item, so putting them in the same array wasn't really possible
//        if (result.types) {
//            var data = result.types;
//            
//            var gender_types = data["type_gender"];
//            gender_types.forEach(function(type) {
//                $("#item_gender").append('<option value="' + type.type_id + '">' + dict[type.type_name] + '</option>');
//            });
//            
//            var tribe_types = data["type_tribe"];
//            tribe_types.forEach(function(type) {
//                $("#item_tribe").append('<option value="' + type.type_id + '">' + dict[type.type_name] + '</option>');
//            });
//            
//            $("#item_gender").val(
//                    session_settings["search_gender"] ? 
//                    session_settings["search_gender"] : -1);
//            $("#item_tribe").val(
//                    session_settings["search_tribe"] ? 
//                    session_settings["search_tribe"] : -1);
//        }
//    });
//    
//    // Selects
//    searchLocations(JSON.stringify({
//        'select':
//            ["type_location"]
//    })).then(function(result) {
//        
//        // These come from different tables and are an entirely different type
//        // of item, so putting them in the same array wasn't really possible
//        if (result.types) {
//            var data = result.types;
//            
//            var location_types = data["type_location"];
//            location_types.forEach(function(type) {
//                $("#item_type_location").append('<option value="' + type.type_id + '">' + dict[type.type_name] + '</option>');
//            });
//            
//            $("#item_type_location").val(
//                    session_settings["search_type_location"] ? 
//                    session_settings["search_type_location"] : -1);
//        }
//    });
//    
//    // Selects
//    searchSpecials(JSON.stringify({
//        'select':
//            ["type_special"]
//    })).then(function(result) {
//        
//        // These come from different tables and are an entirely different type
//        // of item, so putting them in the same array wasn't really possible
//        if (result.types) {
//            var data = result.types;
//            
//            var special_types = data["type_special"];
//            special_types.forEach(function(type) {
//                $("#item_type_special").append('<option value="' + type.type_id + '">' + dict[type.type_name] + '</option>');
//            });
//            
//            $("#item_type_special").val(
//                    session_settings["search_type_special"] ? 
//                    session_settings["search_type_special"] : -1);
//        }
//    });
//
//    // On change for the different select boxes
//    $("#item_start_book").change();
//    $("#item_end_book").change();
//    $("#item_specific").change();
//    $("#item_gender").change();
//    $("#item_tribe").change();
//    $("#item_type_location").change();
//    $("#item_type_special").change();
//}
//
//function onSliderChangeNumChapters(value) {
//    onSliderChange('num_chapters', value.value);
//}
//
//function onSliderChangeAge(value) {
//    onSliderChange('age', value.value);
//}
//
//function onSliderChangeParentAge(value) {
//    onSliderChange('parent_age', value.value);
//}
//
//function onSliderChange(type, value) {
//    if (value === "") {
//        return;
//    }
//    
//    // Update the query to the session
//    var params = {};
//    params["search_" + type] = value.join('-');
//    updateSession(params);
//
//    // Set the slider as active
//    $("#slider_" + type)
//            .find(".slider-selection")
//            .css("background-color", "#46c1fe");
//    
//    // Add the [x] to disable the slider
//    removeFilter(type, "#item_" + type + "_label");
//
//    // Set the slider as enabled
//    elementEnabled[type] = true;
//    elementInit[type] = true;
//    
//    // Recalculate the search results
//    insertResults();
//    
//    return;
//}
//
//function onSelectChange(type) {    
//    // Update the query to the session
//    var value = $("#item_" + type).val();
//    if (!value || value === "-1") {
//        return;
//    }
//    
//    var params = {};
//    params["search_" + type] = value;
//    updateSession(params);
//    
//    // Add the [x] to disable the slider
//    removeFilter(type, "#item_" + type + "_label");
//    
//    // Recalculate the search results
//    insertResults();
//    
//    return;
//}
</script>
