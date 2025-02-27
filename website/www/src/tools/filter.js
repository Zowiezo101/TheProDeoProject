
// This function is executed once the DOM is loaded
$(function(){
    if ($("#item_content").length > 0) {
        // Initialize the table used for filtering
        initTable();
        
        // Get all the search options to choose from
        initFilter();
        
        // TODO:
        // - Make sure the icon changes color when filter is used
        // - Make sure the AKA is also searched with search-term and name parts
        // - Reset filter

        // Make the table visible
        $("#item_list").removeClass("d-none");

        // And the spinner invisible
        $("#item_list_spinner").addClass("d-none");
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
        order: [1, "asc"],

        // Callback to remove the spinner and show the table
        drawCallback: function(settings) {
            // Make the table visible
            $("#item_list").removeClass("d-none");

            // And the spinner invisible
            $("#item_list_spinner").addClass("d-none");
        },
    });
}

function initFilter() {
    // Loop through all the search fields and fill in saved settings
    var search_fields = $(".search-field");
    search_fields.each((idx, el) => {
        var field = $(el);
        
        // Initialize this field
        initField(field);
    });

    // Setting the sorting type without drawing the page right away
    if ($("#item_list").data("table-sort")) {
        setSort($("#item_list").data("table-sort"), true);
    }

    // Setting the saved page or go to the first page to trigger the initial search
    if ($("#item_list").data("table-page")) {
        setPage($("#item_list").data("table-page"));
    } else {
        // Apply the filters to the datatable
        $("#item_list").DataTable().draw();
        setPage(0);
    }
}

function onFilterChange() {    
    // Make the table invisible
    $("#item_list").addClass("d-none");

    // And the spinner visible
    $("#item_list_spinner").removeClass("d-none");

    // This timeout function fixes a race condition, where jQuery initiates a change in the DOM
    // But this change has a slight delay and executes AFTER the redraw of the table
    // Using this timeout function will give the DOM enough time to update
    setTimeout(function() {
        // Loop through all the search fields and save the settings
        var search_fields = $(".search-field");
        search_fields.each((idx, el) => {
            var field = $(el);
            
            // Save this field
            saveField(field);
        });

        // Apply the filters to the datatable
        $("#item_list").DataTable().draw();
        
        // Go back to the first page
        setPage(0);
    }, 1);
}

function onTextChange(name) {    
    // The value to filter with
    value = getFieldValue($("#" + name));
    
    if (name === "name") {
        // Also update the search bar
        $("#item_search").val(value);
    }
}

function onBookChange(name, init=false) {
    // The selected book
    var book = getField(name);

    // Get the chapter field that corresponds with this book
    var field = getField(name.replace("id", "chap"));

    // Clear the chapter select
    field.children().remove();

    // Insert the disabled option for this book
    field.append("<option selected disabled value='-1'>" + dict["books.chapter"] + "</option>");

    if (getFieldValue(book) !== -1) {
        // The amount of chapters this book has
        var num_chaps = parseInt(book.find(":selected").data("num-chapters"), 10);

        // Insert all the chapters for this book
        for (var chap = 0; chap < num_chaps; chap++) {
            var option = `
                <option value='${chap + 1}'>
                    ${chap + 1}
                </option>`;
    
            field.append(option);
        }
    }

    if (init !== false) {
        // Initialize the chapters by selecting the stored chapter (if any)
        var value = field.data("val");
        if (value !== -1) {
            field.val(value);
        }
    }

    // Show the 'clear field' button
    showClear(name);
}

function onFilterReset() {
    // Loop through all the search fields and clear the settings
    var search_fields = $(".search-field");
    search_fields.each((idx, el) => {
        var field = $(el);
        
        // Clear this field
        clearField(field);
    });

    onFilterChange();
}

function onBookReset(name) {
    field = getField(name);
    clearField(field);
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
    
    // The search function
    table.search.fixed(name, function(string, data, idx) {
        if (idx === 0) {
            // Debug stuff
            console.log(name);
            console.log($("#item_list").DataTable().data().length);
        }

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
    // Get the saved slider values, if any
    var values = field.data("slider-value");
    var min = field.data("slider-min");
    var max = field.data("slider-max");
    
    // Set the max, min and values
    slider = field.slider({
        value: [values[0] !== -1 ? values[0] : min,
                values[1] !== -1 ? values[1] : max]
    });
    
    // The table
    var table = $("#item_list").DataTable();
    
    // The slider
    var name = getFieldName(field);
    
    // The search function
    table.search.fixed(name, function(string, data, idx) {  
        if (idx === 0) {
            // Debug stuff
            console.log(name);
            console.log($("#item_list").DataTable().data().length);
        }
    
        // Get the search value
        var search_value = getFieldValue(field);
        var search_result = false;

        // Are unknown values allowed?
        var search_nan = getFieldValue(getField(name + "_nan"));

        if (name === "parent_age") {
            // The columns are mother_age and father_age
            var column_idx_m = $("#item_list").DataTable().column("mother_age:name")[0][0];
            var column_idx_f = $("#item_list").DataTable().column("father_age:name")[0][0];
            var column_value_m = parseInt(data[column_idx_m], 10);
            var column_value_f = parseInt(data[column_idx_f], 10);

            // Check whether the column value is within the selected range
            // or is an unknown value. If any of the parent ages is known, 
            // don't treat it as an unknown value
            if (column_value_m == -1 && column_value_f == -1 && search_nan == true) {
                search_result = true;
            } else if(column_value_m !== -1 &&
                    column_value_m >= search_value[0] &&
                    column_value_m <= search_value[1]) {
                // The mother age is in this range
                search_result = true;
            } else if(column_value_f !== -1 &&
                column_value_f >= search_value[0] &&
                column_value_f <= search_value[1]) {
                // The father age is in this range
                search_result = true;
            } 
        } else {
            // Get the column we're trying to compare with
            var column_idx = $("#item_list").DataTable().column(name + ":name")[0][0];
            var column_value = parseInt(data[column_idx], 10);

            // Check whether the column value is within the selected range
            // or is an unknown value
            if (column_value == -1 && search_nan == true) {
                search_result = true;
            } else if(column_value !== -1 &&
                    column_value >= search_value[0] &&
                    column_value <= search_value[1]) {
                search_result = true;
            } 
        }
        
        return search_result;
    });
}

function initCheckbox(field) {
    // Get the saved value
    var value = field.val();

    if (value !== "") {
        // Set the saved value
        field.prop("checked", (value === "true") || (value === true));
    }
}

function initSelect(field) {
    // Select the saved option if one is saved
    var value = parseInt(field.data("val"), 10);
    if (value !== -1) {
        field.val(value);
    }

    // The table
    var table = $("#item_list").DataTable();
    
    // The slider
    var name = getFieldName(field);

    // The search function
    table.search.fixed(name, function(string, data, idx) {
        if (idx === 0) {
            // Debug stuff
            console.log(name);
            console.log($("#item_list").DataTable().data().length);
        }

        // By default the search function returns true
        var search_result = true;
        var search_value = getFieldValue(field);

        // Get the column we're trying to compare with
        var column_idx = $("#item_list").DataTable().column(name + ":name")[0][0];
        var column_value = parseInt(data[column_idx], 10);

        if (search_value !== -1) {
            search_result = search_value === column_value;
        }
            
        return search_result;
    });
}

function initBook(field) {
    // Name of the field we're working with
    var name = getFieldName(field);

    // Do not initialize the chapters here
    if (!name.includes("chap")) {
        // Select the saved book if one is saved
        var value = parseInt(field.data("val"), 10);
        if (value !== -1) {
            field.val(value);

            // Insert the chapters for the selected book and initialize this field
            onBookChange(name, true);
        }

        // The table
        var table = $("#item_list").DataTable();

        // The search function
        table.search.fixed(name, function(string, data, idx) {
            if (idx === 0) {
                // Debug stuff
                console.log(name);
                console.log($("#item_list").DataTable().data().length);
            }

            // By default the search function returns true
            var search_result = true;
            
            // Get the search values
            var book_search_value = getFieldValue(field);
            var chap_search_value = getFieldValue(getField(name.replace("id", "chap")));

            // Only if both the chapter and book are set, search with these values
            if (book_search_value !== -1 && chap_search_value !== -1) {
                // Get book and chapter columns for this book-select element
                var book_col_idx = $("#item_list").DataTable().column(name + ":name")[0][0];
                var chap_col_idx = $("#item_list").DataTable().column(name.replace("id", "chap") + ":name")[0][0];

                var book_col_value = parseInt(data[book_col_idx], 10);
                var chap_col_value = parseInt(data[chap_col_idx], 10);

                // First check on the book, then check on the chapter
                if (book_col_value === book_search_value) {
                    // If the book is equal to the search value, check the chapter
                    if (name.includes("start")) {
                        // Chapter needs to be higher than the search value
                        search_result = (chap_col_value >= chap_search_value);
                    } else if (name.includes("end")) {
                        // Chapter needs to be lower than the search value
                        search_result = (chap_col_value <= chap_search_value);
                    }
                } else if (name.includes("start") && (book_col_value < book_search_value)) {
                    // If the book is higher than the search value, it's a hit
                    search_result = false;
                } else if (name.includes("end") && (book_col_value > book_search_value)) {
                    // If the book is lower than the search value, it's a hit
                    search_result = false;
                }
            }
            
            return search_result;
        });
    }
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

function clearField(field) {
    // Get the type of field
    var type = field.data("type");

    // Each type gets a different treatment
    switch(type) {
        case "text":
            field.val("");
            break;

        case "slider":
            var min = field.data("slider-min");
            var max = field.data("slider-max");
            field.slider('setValue', [min, max]);
            break;
            
        case "checkbox":
            field.prop("checked", true);
            break;

        case "select":
            field.val(-1);
            break;

        case "book":
            field.val(-1);

            // Remove the "clear field" button
            hideClear(field);
            break;
    }
}

function getField(name) {
    return $("#" + name);
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

        case "checkbox":
            value = field.prop("checked");
            break;

        case "select":
            value = parseInt(field.find(":selected").val(), 10);
            break;

        case "book":
            value = parseInt(field.find(":selected").val(), 10);
            break;

        default:
            value = null;
            break;
    }
    
    return value;
}

function showClear(name) {
    // Remove the d-none class
    var clear = getField(name.replace("id", "clear"));
    clear.removeClass("d-none");
}

function hideClear(field) {
    // Get the field name
    var name = getFieldName(field);

    // Add the d-none class
    if (name.includes("id")) {
        var clear = getField(name.replace("id", "clear"));
        clear.addClass("d-none");
    }
}
