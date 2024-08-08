<script>
    
    const TYPE_ALL = [TYPE_BOOK, TYPE_EVENT, TYPE_PEOPLE, TYPE_LOCATION, TYPE_SPECIAL];
    
    var tables = [];
    
    /*
     * Start with filling the search table. The actual searching is done using
     * DataTables, but it's prepared with the full database.
     * Once the page is loaded, do an initial search
     */
    
    // This function is executed once the DOM is loaded
    $(function(){
        // Get all the search options and search data
        initSearch();
    });
    
    function initSearch() {
        // Get all the filled in search parameters
        var parameters = getSearchParameters();
        
        // Update the query to the session
        updateSession(parameters);
        
        // Get all the search data for every item type
        TYPE_ALL.forEach(
            function (item_type) {                
                // Get the search data and options
                getItems(item_type).then(function(results) {
                    // Insert the data into the search table
                    if (results.error !== "") {
                        // Error message, because database can't be reached
                        // TODO: Actual error message here
                        $("#tab" + item_type).append(dict["database.no_results"]);
                    } else {
                        // The table with all the search results
                        insertOptions(item_type, results);
                        
                        // The table with all the search results
                        insertTable(item_type, results);
        
                        // Get the search results with the given parameters
                        // Only do the current item type, as we don't know
                        // in which order the item types are returned
                        updateTable(item_type, parameters);
                    }
                });
            }
        );
    }
    
    /*
     * Inserting the options, like sliders and books to select
     */
    
    function insertOptions(item_type, results) {
        switch(item_type) {
            case TYPE_BOOK:
                insertBooks(results.records);
                insertSlider("num_chapters", results.options);
                break;
                
            case TYPE_PEOPLE:
                insertSlider("age", results.options);
                insertSlider("parent_age", results.options);
                insertSelect("gender", results.options);
                insertSelect("tribe", results.options);
                break;
                
            case TYPE_LOCATION:
                insertSelect("location", results.options);
                break;
                
            case TYPE_SPECIAL:
                insertSelect("special", results.options);
                break;
        }
    }
    
    function insertBooks(records) {
        var book_start = $("#book_start");
        var book_end = $("#book_end");
        
        // Insert all the books as options into the selects
        records.forEach((record) => {
            var option = `<option
                data-num-chapters=${record.num_chapters}
                value='${record.id}'>
                    ${record.name}
                </option>`;
            
            book_start.append(option);
            book_end.append(option);
        });
        
        // Select the session values
        if (book_start.data("item-val") !== "") {
            book_start.val(book_start.data("item-val"));
            book_start.change();
        }
        
        if (book_end.data("item-val") !== "") {
            book_end.val(book_end.data("item-val"));
            book_end.change();
        }
    }
    
    function insertChapters(name, num_chapters, init) {
        // The chapter select
        var chap_select = $(`#chap_${name}`);
        
        // Replace the current contents
        chap_select.children(":not([disabled])").remove();
        
        for (var i = 0; i < num_chapters; i++) {
            chap_select.append(
                `<option chapter value="${i+1}"> 
                    ${i+1}
                </option>`
            );
        }
        
        // If we were called from an element (init = false), reset the chapters
        // If we were called from insertBooks (init = true), leave the chapters be
        if (init === false) {
            // Set the first chapter by default for the first appearance and
            // the last chapter by default for the last appearance
            var chap_val = (name === "start") ? 1 : num_chapters - 1;
            chap_select.val(chap_val);
            
            // Also call the onChange() for this element
            chap_select.change();
        } else {
            chap_select.val(chap_select.data("item-val"));
        }
    }
    
    function insertSlider(name, options) {
//        // Set the max and min values
//        var slider_num_chapters = $("#item_num_chapters").slider({
//            max: 100,
//            min: 0
//        });
//
//        // Initialize the sliders and set their values
//        slider_num_chapters.slider("setValue", [0, 100]);
//        
//        // Set the max and min values
//        var slider_num_chapters = $("#item_age").slider({
//            max: 100,
//            min: 0
//        });
//
//        // Initialize the sliders and set their values
//        slider_num_chapters.slider("setValue", [0, 100]);
//        
//        // Set the max and min values
//        var slider_num_chapters = $("#item_parent_age").slider({
//            max: 100,
//            min: 0
//        });
//
//        // Initialize the sliders and set their values
//        slider_num_chapters.slider("setValue", [0, 100]);
    }
    
    function insertSelect(name, options) {
        
    }
    
    /*
     * Inserting the table, its header rows and data rows
     */
    
    function insertTable(item_type, results) {
        // All the columns this table can display
        var columns = getColumns(results.columns);
        
        // Get an array with header cells
        var header = getHeader(columns);
            
        // Insert all the records into the table
        var body = getBody(item_type, columns, results);
        
        // The table, this will be filled in later
        $("#tab" + item_type).append(`
            <div class="table-responsive">
                <table class="table table-striped table-borderless w-100">
                    <thead>` + header + `</thead>
                    <tbody>` + body + `</tbody>
                </table>
            </div>
        `);
        
        // This is to be able to sort the results
        tables[item_type] = $("#tab" + item_type + " table").DataTable({
            // TODO: Paging needs to be turned on and translated into own languages
            // TODO: https://datatables.net/blog/2024/inputPaging
            
            layout: {
                topStart: null,
                topEnd: null,
                bottomStart: null,
                bottomEnd: null,
                bottom: "inputPaging"
//                bottom: "paging"
            },
            
            // Insert the column list, so we can make unused columns invisible
            columns: columns,
            order: [0, "asc"]
        });
    }
    
    function getColumns(columns) {
        return columns.filter((column) => {
            // Remove these columns
            return ["book_start_vers", "book_start_chap", 
                    "book_end_vers",   "book_end_chap",
                    "order_id"].includes(column) ? false : true;
        }).concat("link").map((column) => {
            // Some renaming here
            switch(column) {
                case "book_start_id":
                    column = "book_start";
                    break;
                    
                case "book_end_id":
                    column = "book_end";
                    break;
                    
                case "father_age":
                case "mother_age":
                    column = "parent_age";
                    break;
            }
                
            // Do not show column if the name is id or parameter is not filled in
            var visible = false;
            if (["name", "num_chapters", "book_start", "book_end", "link"].includes(column)) {
                // Always show these columns
                visible = true;
            }
            
            // Return the column names with the following syntax
            return {
                name: column,
                title: dict["items." + column],
                
                // Make the ID column invisible
                visible: visible,
                
                // Make the name column bold
                className: (column === "name") ? "font-weight-bold" : ""
            };
        });
    }
    
    function getHeader(columns) {
        return columns.map((column) => {            
            // Return the row header cells
            return '<th scope="col" class="text-center">' + column.title + '</th>';
        }).join("");
    }
    
    function getBody(item_type, columns, results) {
        return results.records.map((record) => {
            return getDataRow(item_type, columns, record);
        }).join("");
    }
    
    function getDataRow(item_type, columns, record) {        
        // Loop through all the keys
        var data_row = columns.map((column) => {
            switch(column.name) {
//                case "name":
//                    // TODO: Do this in database with AKA
//                    data_cell = record["name"] + (record["aka"] ? " (" + record["aka"] + ")" : "");
//                    break;
//                    
//                case "book_start":
//                case "book_end":
//                    data_cell = getBookString(column.name, record);
//                    break;
//                    
//                case "father_age":
//                case "mother_age":
//                    parent_age = [];
//                    if (record["father_age"] > 0) { parent_age.push("father_age"); }
//                    if (record["mother_age"] > 0) { parent_age.push("mother_age"); }
//                    data_cell = parent_age.join(", ");
//                    break;
//                    
//                case "gender":
//                case "tribe":
//                case "type":
//                    data_cell = getTypeString(column.name, record);
//                    break;
//                    
//                case "link":
//                    data_cell = getLinkToObject(item_type, record);
//                    break;
                    
                default:
                    // Default sitation is to take the data as is
                    data_cell = record[column.name];
                    break
            }
            
            // TODO: Something with data order to get the correct order
            return "<td class='text-center'>" + data_cell + "</td>";
        });

        return "<tr>" + data_row.join("") + "</tr>";
    }
    
    /*
     * The search functions
     */

    function onSearch() {
        // Get all the filled in search parameters
        var parameters = getSearchParameters();
        
        // Update the query to the session
        updateSession(parameters);
        
        // Get database results with the given parameters
        updateTables(parameters);
    }
    
    function getSearchParameters() {
        var parameters = [];
        
        $(".search-field").each(function() {
            // Each separate field
            var field = $(this);
            
            // Get the value of this parameter
            param_val = getParameter(field);
            param_name = getName(field);
            
            // If the value is null, it's not set and thus not 
            // needed for searching, but still needed to be able to remove it
            // from the session
            parameters[param_name] = param_val;
        });
        
        return parameters;
    }
    
    function updateTables(parameters) {
        // Update all tables with the given parameters
        TYPE_ALL.forEach(
            function (item_type) {
                // Update this table with the parameters
                updateTable(item_type, parameters);
            }
        );
    }
    
    function updateTable(item_type, parameters) {        
        // Insert the parameters and redraw the table
        for (var param in parameters) {
            // The value of this parameter
            var value = parameters[param];
            
            // The column associated with this parameter
            var column = getColumn(item_type, param);
            
            // Skip every parameter that has value null and has no column
            // associated with it
            if (value !== null && column !== null) {                
                // Start filtering using the seach value
                // and make this column visible
                column.search(value).draw().visible(true);
            } else if (value === null && column !== null) {
                // Stop searching with this field
                column.search("").draw();
        
                // Make this column invisible, unless it's one of the core columns
                column.visible(["name", "num_chapters", "book_start", "book_end", "link"].includes(param));
            }
        };
    }
    
    function getColumn(item_type, param) {
        var column = tables[item_type].column(param + ":name");
        
        if (column[0].length === 0) {
            // The books column doesn't have a description field
            // or a meaning_name field, so skip these
            column = null;
        }
        
        return column;
    }
    
    
    /*
     * Parameter functions
     */
    
    function getParameter(field) {
        // The value of this parameter (if given)
        var val = getValue(field);
        
//        // The user can select a specific item type to search for, 
//        // when this item type is selected, only the parameters related 
//        // to this item_type are used. The given item_type corresponds 
//        // with a specific parameter and this function checks whether this 
//        // parameter will be used
//        var is_applicable = isApplicable(field);
        
        // If the current value is empty or not of need, set it to null
//        if (val === "" || val === "-1" || is_applicable === false) {
        if (val === "" || val === "-1") {
            val = null;
        }
        
        // Return the current value
        return val;
    }
    
    function getValue(field) {
        // Pre-define this variable
        var val = "";
        
        // Get the input type (text input, select or slider)
        var input_type = field.data("input-type");
        
        switch(input_type) {
            case "text":
                // Get the current value of the field
                val = field.val();
                break;
                
            case "select":
                // Get the current selected value of the field
                val = field.find("[selected]").val();
                break;
                
            case "slider":
                // Get the current selected value of the field
                val = $("#item_" + param_name).slider('getValue');
                break;
                
//        if (elementInit["age"]) {
//            var age = $("#item_age").slider('getValue');
//            params["search_age"] = 
//                    elementEnabled["age"] ? 
//                    age.join('-') : "";
//        }
        }
        
        return val;
    }
    
    function getName(field) {
        return field.attr("id");
    }
    
//    function isApplicable(param_name, item_type) {
//        var is_applicable = true;
//        
//        if (item_type !== null) {
//            // Check if the user has selected a specific item type
//            // Only return the parameter if it's applicable to 
//            // the selected item type
//            var selected_type = $("#item_specific :selected").val();
//            
//            // Check for which item types this parameter is applicable
//            var item_types = SEARCH_FIELDS[param_name].item_type;
//            
//            // If the item_type is a string, convert it to an array
//            if (typeof item_types === "string") {
//                item_types = [item_types];
//            }
//            
//            // If the selected type is "ALL", all parameters will be applicable
//            // by default. If it's not "ALL", see if the selected type is in the 
//            // list of applicable item_types for this parameter.
//            if ((selected_type !== "-1") && (item_types.indexOf(selected_type) === -1)) {
//                is_applicable = false;
//            }
//        }
//        
//        return is_applicable;
//    }
    
    /*
     * Cell functions for the data in the table
     */
    
//    function getBookString(type, record) {
//        var book_string = "";
//        
//        if (type === "book_start") {
//            book_string = dict["books.book_" + record["book_start_id"]] + " " + 
//                    record["book_start_chap"] + ":" + 
//                    record["book_start_vers"];
//        } else {
//            book_string = dict["books.book_" + record["book_end_id"]] + " " + 
//                    record["book_end_chap"] + ":" + 
//                    record["book_end_vers"];
//        }
//        
//        return book_string;
//    }
//    
//    function getTypeString(int) {
//        var str = "";
//
//        if (typeof dict[int] !== "undefined") {
//            str = dict[int];
//        }
//
//        return str;
//    }
//    
//    function getLinkToObject(item_type, record) {
//        var text = item_type + "/" + item_type.slice(0, -1) + "/" + record["id"];
//        var href = $("body").data("base-url") + text;
//        
//        return '<a href="' + href + '" target="_blank" ' +
//            'class="font-weight-bold">' + 
//                text + 
//        '</a>';
//    }
    
    /*
     * onChange functions
     */
    
    function onBookChange(name) {
        // Get the selected book option
        var book_select = $(`#book_${name} option:selected`);
        var book_val = book_select.val();
        
        // Update the query to the session
        parameters = [];
        parameters[`book_${name}`] = book_val;
        updateSession(parameters);
        
        // TODO: Set remove filter
        
        // Get the number of chapters and insert those chapters
        var num_chapters = book_select.data("num-chapters");
        insertChapters(name, num_chapters, this.event === undefined);
    }
    
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
