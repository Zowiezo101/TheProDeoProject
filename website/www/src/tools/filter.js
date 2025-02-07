
// This function is executed once the DOM is loaded
$(function(){
    if ($("#item_content").length > 0) {
        // Initialize the table used for filtering
        initTable();
        
        // Get all the search options to choose from
        initFilter();
        
        // TODO:
        // - Make sure the icon changes color when filter is used
        // - Make sure the search-term and name parts are both samen value on init
        // - Make sure the AKA is also searched with search-term and name parts
        // - Reset filter
        // - Bij zoeken het aantal resultaten laten zien op de knop
        // Duidelijk maken op de website dat alle ideeen en opvattingen van mij persoonlijk zijn en niet altijd correct zullen zijn.
    }
});

function initTable() {
    var columns = [];
    $("#item_list th").each((idx, el) => {
        var td = $(el);

        // Get all the columns we've added
        columns.push({
            name: td.text(),
            visible: td.hasClass("d-none") === false
        });
    });

    // Set the dataTable to be able to sort and change pages
    table = $("#item_list").DataTable({

        // Hide all layout items, we add them ourselves
        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: null,
            bottomEnd: null
        },
        language: {
            // This is the only message from dataTables the user should see
            // Make sure it's translated in the selected language
            zeroRecords: dict["database.no_results"]
        },

        // Let the DataTable know which columns we've added
        columns: columns,
        order: [1, "asc"]
    });
}

function initFilter() {
    // TODO: The page number is incorrect after the filter has been initialized
    // Loop through all the search fields and fill in saved settings
    var search_fields = $(".search-field");
    search_fields.each((idx, el) => {
        var field = $(el);
        
        // Initialize this field
        initField(field);
    });

    // Setting the sorting type
    if ($("#item_list").data("table-sort")) {
        setSort($("#item_list").data("table-sort"));
    }

    // Adding the saved search term
    if ($("#item_list").data("table-search")) {
        setSearch($("#item_list").data("table-search"));
    }

    // Setting the saved page
    if ($("#item_list").data("table-page")) {
        setPage($("#item_list").data("table-page"));
    }
}

function onFilterChange() {
    
    // Loop through all the search fields and save the settings
    var search_fields = $(".search-field");
    search_fields.each((idx, el) => {
        var field = $(el);
        
        // Save this field
        saveField(field);
    });
    
    // TODO: Implement suggestion while typing
    // Basically filtering a specific property while typing
    // Sliders and selects still need to be filled in (select can be done in PHP)
    
    // Apply the filters to the datatable
    $("#item_list").DataTable().draw();
    
    // Go back to the first page
    setPage(0);
}

function onTextChange(name) {
    // The table
    var table = $("#item_list").DataTable();
    
    // The value to filter with
    value = getFieldValue($("#" + name));
    
    // Add the search function
    table.search.fixed(name, value);
    
    // Draw the changes in the filter
    onFilterChange();
    
    if (name === "name") {
        // Also update the search bar
        $("#item_search").val(value);
    }
}

function onFilterReset() {

}

function initField(field) {    
    // Get the type of field
    var type = field.data("type");

    // Each type gets a different treatment
    switch(type) {
        case "text":
            initText(field);
            break;

        case "slider":
            initSlider(field);
            break;
            
        case "checkbox":
            initCheckbox(field);
            break;

        case "select":
            initSelect(field);
            break;

        case "book":
            initBook(field);
            break;
    }
}

function initText(field) {
    // The table
    var table = $("#item_list").DataTable();
    
    // The textfield
    var name = getFieldName(field);
    
    table.search.fixed(name, function(string, data, idx) {
        // Get the column we're trying to compare with
        var column_idx = $("#item_list").DataTable().column(name + ":name")[0][0];
        var column_value = data[column_idx].toLowerCase();
    
        // Get the search value
        var search_value = getFieldValue(field).toLowerCase();
        
        // Compare the two values and return the result
        return column_value.includes(search_value);
    });
}

function initSlider(field) {
    
    // Get the minimum and maximum values from the table
    var range = getSliderRange(field);
    
    // Set the max, min and values
    slider = field.slider({
        max: range[1],
        min: range[0]
    });
    
    // Set the checkbox as well
    // TODO:
    
    // The table
    var table = $("#item_list").DataTable();
    
    // The slider
    var name = getFieldName(field);
    
    table.search.fixed(name, function(string, data, idx) {        
        // Get the column we're trying to compare with
        var column_idx = $("#item_list").DataTable().column(name + ":name")[0][0];
        var column_value = parseInt(data[column_idx], 10);
    
        // Get the search value
        var search_value = getFieldValue(field);
        
        var search_result = false;
        // Check whether the column value is within the selected range
        if(column_value >= search_value[0] &&
           column_value <= search_value[1]) {
            search_result = true;
        }
        
        return search_result;
    });
}

function initCheckbox(field) {
    
}

function initSelect(field) {
    
}

function initBook(field) {
    
}

function saveField(field) {
    // Get the field value and name
    var field_name = getFieldName(field);
    var field_value = getFieldValue(field);

    // The session parameter to save
    var params = {};
    params[field_name] = field_value;
    
    // Saving the parameter
    updateSession(params);
}

function getFieldName(field) {
    return field.attr("id");
}

function getFieldValue(field) {
    // Get the type of field
    var type = field.data("type");

    // Each type gets a different treatment
    switch(type) {
        case "text":
            var value = field.val();
            break;

        case "slider":
            value = field.slider('getValue');
            break;

        case "check":
            value = field.is(":checked");
            break;

        case "select":
            value = field.find(":selected").val();
            break;

        case "book":
            // TODO:
            value = null;
            break;

        default:
            value = null;
            break;
    }
    
    return value;
}

function getSliderRange(field) {
    // Get the field name
    var name = getFieldName(field);
    
    // Get the column that corresponds to this field
    var column = $("#item_list").DataTable().column(name + ":name");
    
    // Get the data of this column in an array
    var data = [];
    column.data().map((el) => {
        data.push(parseInt(el, 10));
    });
    
    return [Math.min(...data), Math.max(...data, 0)];
}

//function insertBooks(records) {
//    var book_start = $("#book_start_id");
//    var book_end = $("#book_end_id");
//
//    // Insert all the books as options into the selects
//    records.forEach((record) => {
//        var option = `<option
//            data-num-chapters=${record.num_chapters}
//            value='${record.id}'>
//                ${record.name}
//            </option>`;
//
//        book_start.append(option);
//        book_end.append(option);
//    });
//
//    // Select the session values
//    if (book_start.data("item-val") !== "") {
//        book_start.val(book_start.data("item-val"));
//        book_start.change();
//    }
//
//    if (book_end.data("item-val") !== "") {
//        book_end.val(book_end.data("item-val"));
//        book_end.change();
//    }
//}
//
//function insertChapters(name, num_chapters, init) {
//    // The chapter select
//    var chap_select = $(`#book_${name}_chap`);
//
//    // Replace the current contents
//    chap_select.children(":not([disabled])").remove();
//
//    for (var i = 0; i < num_chapters; i++) {
//        chap_select.append(
//            `<option chapter value="${i+1}"> 
//                ${i+1}
//            </option>`
//        );
//    }
//
//    // If we were called from an element (init = false), reset the chapters
//    // If we were called from insertBooks (init = true), leave the chapters be
//    if (init === false) {
//        // Set the first chapter by default for the first appearance and
//        // the last chapter by default for the last appearance
//        var chap_val = (name === "start") ? 1 : num_chapters;
//        chap_select.val(chap_val);
//
//        // Also call the onChange() for this element
//        chap_select.change();
//    } else {
//        chap_select.val(chap_select.data("item-val"));
//    }
//}
//
//function insertSlider(name, options) {
////        // Set the max and min values
////        var slider_num_chapters = $("#item_num_chapters").slider({
////            max: 100,
////            min: 0
////        });
////
////        // Initialize the sliders and set their values
////        slider_num_chapters.slider("setValue", [0, 100]);
////        
////        // Set the max and min values
////        var slider_num_chapters = $("#item_age").slider({
////            max: 100,
////            min: 0
////        });
////
////        // Initialize the sliders and set their values
////        slider_num_chapters.slider("setValue", [0, 100]);
////        
////        // Set the max and min values
////        var slider_num_chapters = $("#item_parent_age").slider({
////            max: 100,
////            min: 0
////        });
////
////        // Initialize the sliders and set their values
////        slider_num_chapters.slider("setValue", [0, 100]);
//}
//
//function onBookChange(name) {
//    // Get the selected book option
//    var book_select = $(`#book_${name}_id option:selected`);
//    var book_val = book_select.val();
//
//    // Update the query to the session
//    parameters = [];
//    parameters[`book_${name}_id`] = book_val;
//    updateSession(parameters);
//
//    // TODO: Set remove filter
//
//    // Get the number of chapters and insert those chapters
//    var num_chapters = book_select.data("num-chapters");
//    insertChapters(name, num_chapters, this.event === undefined);
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


