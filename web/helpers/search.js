/* global get_settings, dict_PeoplesParams, dict_Links, dict_LocationsParams, dict_SpecialsParams, dict_EventsParams, dict_Search, dict_NavBar, select_Search_tribes, select_Search_gender, select_Search_locations, select_Search_specials, dict_Settings, getItemFromDatabase, searchDatabase */

// The function that is executed, when the select box for the type of item has changed values
function selectTableOptions(sel) {
    // Get the selected values
    var value = sel[0].options[sel[0].selectedIndex].value;
    var form = sel[0].parentElement;

    // Remove all existing options and start fresh
    $(".added").remove();
    $(".added_app_div").remove();

    $(form).append(addInput("search", dict_PeoplesParams["name"], value));

    switch(value) {
        case "peoples":
            $(form).append(    
                // Meaning Name
                addInput("meaning_name", dict_PeoplesParams["meaning_name"], value)
            ).append(
                // Linking tables
                addInput("name_changes", dict_Links["a.k.a"], value)
            ).append(
                // Name Father
                addInput("father", dict_PeoplesParams["father_id"], value)
            ).append(
                // Name Mother
                addInput("mother", dict_PeoplesParams["mother_id"], value)
            ).append(
                // Gender
                addSelect("gender", dict_PeoplesParams["gender"], value)
            ).append(
                // Tribe
                addSelect("tribe", dict_PeoplesParams["tribe"], value)
            );

            // First appearance
            addAppearance("book_start", dict_PeoplesParams["book_start_vers"], value).then(function(value) {
                $(form).append(value);

                if (get_settings.hasOwnProperty("book_start_book") && (get_settings["table"] === "peoples")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    var SelectElement = $("#book_start_book").attr("value", get_settings["book_start_book"])[0];
                    SelectElement.onchange();
                } 
                if (get_settings.hasOwnProperty("book_start_chap") && (get_settings["table"] === "peoples")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    SelectElement = $("#book_start_chap").attr("value", get_settings["book_start_chap"]);
                }

                // Last appearance
                addAppearance("book_end", dict_PeoplesParams["book_end_vers"], value).then(function(value) {
                    $(form).append(value);

                    if (get_settings.hasOwnProperty("book_end_book") && (get_settings["table"] === "peoples")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        var SelectElement = $("#book_end_book").attr("value", get_settings["book_end_book"])[0];
                        SelectElement.onchange();
                    } 
                    if (get_settings.hasOwnProperty("book_end_chap") && (get_settings["table"] === "peoples")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        SelectElement = $("#book_end_chap").attr("value", get_settings["book_end_chap"]);
                    }
                });
            });
        break;

        case "locations":

            $(form).append(    
                // Meaning Name
                addInput("meaning_name", dict_LocationsParams["meaning_name"], value)
            ).append(
                // Linking tables
                addInput("name_changes", dict_Links["a.k.a"], value)
            ).append(
                // Name Founder & Destroyer
                addInput("people", dict_Links["to_people"], value)
            ).append(
                // Type of Location
                addSelect("locations", dict_LocationsParams["type"], value)
            );

            // First appearance
            addAppearance("book_start", dict_LocationsParams["book_start_vers"], value).then(function(value) {
                $(form).append(value);

                if (get_settings.hasOwnProperty("book_start_book") && (get_settings["table"] === "locations")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    var SelectElement = $("#book_start_book").attr("value", get_settings["book_start_book"])[0];
                    SelectElement.onchange();
                } 
                if (get_settings.hasOwnProperty("book_start_chap") && (get_settings["table"] === "locations")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    SelectElement = $("#book_start_chap").attr("value", get_settings["book_start_chap"]);
                }

                // Last appearance
                addAppearance("book_end", dict_LocationsParams["book_end_vers"], value).then(function(value) {
                    $(form).append(value);

                    if (get_settings.hasOwnProperty("book_end_book") && (get_settings["table"] === "locations")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        var SelectElement = $("#book_end_book").attr("value", get_settings["book_end_book"])[0];
                        SelectElement.onchange();
                    } 
                    if (get_settings.hasOwnProperty("book_end_chap") && (get_settings["table"] === "locations")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        SelectElement = $("#book_end_chap").attr("value", get_settings["book_end_chap"]);
                    }
                });
            });
        break;

        case "specials":
            $(form).append(    
                // Meaning Name
                addInput("meaning_name", dict_SpecialsParams["meaning_name"], value)
            ).append(
                // Type of Special
                addSelect("specials", dict_SpecialsParams["type"], value)
            );

            // First appearance
            addAppearance("book_start", dict_SpecialsParams["book_start_vers"], value).then(function(value) {
                $(form).append(value);

                if (get_settings.hasOwnProperty("book_start_book") && (get_settings["table"] === "specials")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    var SelectElement = $("#book_start_book").attr("value", get_settings["book_start_book"])[0];
                    SelectElement.onchange();
                } 
                if (get_settings.hasOwnProperty("book_start_chap") && (get_settings["table"] === "specials")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    SelectElement = $("#book_start_chap").attr("value", get_settings["book_start_chap"]);
                }

                // Last appearance
                addAppearance("book_end", dict_SpecialsParams["book_end_vers"], value).then(function(value) {
                    $(form).append(value);

                    if (get_settings.hasOwnProperty("book_end_book") && (get_settings["table"] === "specials")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        var SelectElement = $("#book_end_book").attr("value", get_settings["book_end_book"])[0];
                        SelectElement.onchange();
                    } 
                    if (get_settings.hasOwnProperty("book_end_chap") && (get_settings["table"] === "specials")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        SelectElement = $("#book_end_chap").attr("value", get_settings["book_end_chap"]);
                    }
                });
            });
        break;

        case "events":
            $(form).append(    
                // Linking events
                addInput("previous", dict_Links["to_activity"], value)
            ).append(    
                // Location
                addInput("location", dict_Links["to_location"], value)
            ).append(    
                // People
                addInput("people", dict_Links["to_people"], value)
            ).append(
                // Special
                addInput("special", dict_Links["to_special"], value)
            );

            // First appearance
            addAppearance("book_start", dict_EventsParams["book_start_vers"], value).then(function(value) {
                $(form).append(value);

                if (get_settings.hasOwnProperty("book_start_book") && (get_settings["table"] === "events")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    var SelectElement = $("#book_start_book").attr("value", get_settings["book_start_book"])[0];
                    SelectElement.onchange();
                } 
                if (get_settings.hasOwnProperty("book_start_chap") && (get_settings["table"] === "events")) {
                    // Pre-fill this property when it is set,
                    // and when the table is the same of for the previous search
                    SelectElement = $("#book_start_chap").attr("value", get_settings["book_start_chap"]);
                }

                // Last appearance
                addAppearance("book_end", dict_EventsParams["book_end_vers"], value).then(function(value) {
                    $(form).append(value);

                    if (get_settings.hasOwnProperty("book_end_book") && (get_settings["table"] === "events")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        var SelectElement = $("#book_end_book").attr("value", get_settings["book_end_book"])[0];
                        SelectElement.onchange();
                    } 
                    if (get_settings.hasOwnProperty("book_end_chap") && (get_settings["table"] === "events")) {
                        // Pre-fill this property when it is set,
                        // and when the table is the same of for the previous search
                        SelectElement = $("#book_end_chap").attr("value", get_settings["book_end_chap"]);
                    }
                });
            });
        break;
    }

    $(form).append(
            $("<input/>")
                .attr("id", "submit")
                .attr("name", "submitSearch")
                .attr("type", "submit")
                .attr("value", dict_Search["search"])
                .addClass("added")
    );

    if (get_settings.hasOwnProperty("submitSearch")) {
        // Function to remove all selected search options
        $(form).append(
                $("<a/>")
                    .attr("href", "search.php")
                    .html(dict_Search["remove"])
                    .addClass("added")

                    // No border please..
                    .css("borderwidth", "0px")
        );
    }
}

async function onSearch() {
                    
    // When no search is performed yet
    $("<div/>")
            .appendTo($("#search_results"))
            .attr("id", "default")
            .html(dict_Search["default_search"]);
    
    if (get_settings.hasOwnProperty("submitSearch") && ['submitSearch'] !== null) {
    
        $("#search_results").children().remove();
        
        var search_table = get_settings.hasOwnProperty('table') ? get_settings['table'] : null;
        var search_name = get_settings.hasOwnProperty('search') ? get_settings['search'] : null;

        // Generating search results
        var options = "";

//            if ((filter_input(INPUT_GET, 'meaning_name') !== null) and (filter_input(INPUT_GET, "meaning_name") != "")) {
//                $options = $options." AND meaning_name LIKE '%".filter_input(INPUT_GET, "meaning_name")."%'";
//            }
//
//            // TODO: Linking tables
//            if (isset(filter_input(INPUT_GET, 'NameChanges']) and (filter_input(INPUT_GET, "NameChanges"] != "")) {
//                $multoptions = explode(";", filter_input(INPUT_GET, "NameChanges"]);
//                foreach ($multoptions as $value) {
//                    $options = $options." AND NameChanges LIKE '%".$value."%'";
//                }
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Father']) and (filter_input(INPUT_GET, "Father"] != "")) {
//                $options = $options." AND Father LIKE '%".filter_input(INPUT_GET, "Father"]."%'";
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Mother']) and (filter_input(INPUT_GET, "Mother"] != "")) {
//                $options = $options." AND Mother LIKE '%".filter_input(INPUT_GET, "Mother"]."%'";
//            }
//
//            if ((filter_input(INPUT_GET, 'gender') !== null) and (filter_input(INPUT_GET, "gender") != "")) {
//                if (filter_input(INPUT_GET, "gender") != 0) {
//                    $options = $options." AND gender = '%".filter_input(INPUT_GET, "gender")."%'";
//                }
//            }
//
//            if ((filter_input(INPUT_GET, 'tribe') !== null) and (filter_input(INPUT_GET, "tribe") != "")) {
//                if (filter_input(INPUT_GET, "tribe") != 0) {
//                    $options = $options." AND tribe = '%".filter_input(INPUT_GET, "tribe")."%'";
//                }
//            }
//
//            if ((filter_input(INPUT_GET, 'type') !== null) and (filter_input(INPUT_GET, "type") != "")) {
//                if (filter_input(INPUT_GET, "type") != 0) {
//                    $options = $options." AND type = '%".filter_input(INPUT_GET, "type")."%'";
//                }
//            }
//
//            // TODO: Linking yable
//            if (isset(filter_input(INPUT_GET, 'Founder')) and (filter_input(INPUT_GET, "Founder") != "")) {
//                $options = $options." AND Founder LIKE '%".filter_input(INPUT_GET, "Founder")."%'";
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Destroyer')) and (filter_input(INPUT_GET, "Destroyer") != "")) {
//                $options = $options." AND Destroyer LIKE '%".filter_input(INPUT_GET, "Destroyer")."%'";
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Previous')) and (filter_input(INPUT_GET, "Previous") != "")) {
//                $options = $options." AND Previous LIKE '%".filter_input(INPUT_GET, "Previous")."%'";
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Locations')) and (filter_input(INPUT_GET, "Locations") != "")) {
//                $multoptions = explode(";", filter_input(INPUT_GET, "Locations"));
//                foreach ($multoptions as $value) {
//                    $options = $options." AND Locations LIKE '%".$value."%'";
//                }
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Peoples')) and (filter_input(INPUT_GET, "Peoples") != "")) {
//                $multoptions = explode(";", filter_input(INPUT_GET, "Peoples"));
//                foreach ($multoptions as $value) {
//                    $options = $options." AND Peoples LIKE '%".$value."%'";
//                }
//            }
//
//            if (isset(filter_input(INPUT_GET, 'Specials')) and (filter_input(INPUT_GET, "Specials") != "")) {
//                $multoptions = explode(";", filter_input(INPUT_GET, "Specials"));
//                foreach ($multoptions as $value) {
//                    $options = $options." AND Specials LIKE '%".$value."%'";
//                }
//            }
//        
//            if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, "book_start_book") != "")) {
//                $options = $options." AND book_start_id >= '".filter_input(INPUT_GET, "book_start_book")."'";
//
//                if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, "book_start_chap") != "")) {
//                    $options = $options." AND book_start_chap >= '".filter_input(INPUT_GET, "book_start_chap")."'";
//                }
//            }
//
//            if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, "book_end_book") != "")) {
//                $options = $options." AND book_end_id >= '".filter_input(INPUT_GET, "book_end_book")."'";
//
//                if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, "book_end_chap") != "")) {
//                    $options = $options." AND book_end_chap >= '".filter_input(INPUT_GET, "book_end_chap")."'";
//                }
//            }

        // If all types are chosen, make some shortcuts at the top of the search results
        if (search_table === "all") {
            $("#search_results").append(
                    $("<center/>")
                        .html(dict_Search["show"])
                        .append($("<a/>").attr("href", "#search_peoples").html(dict_NavBar["peoples"]))
                        .append(" | ")
                        .append($("<a/>").attr("href", "#search_locations").html(dict_NavBar["locations"]))
                        .append(" | ")
                        .append($("<a/>").attr("href", "#search_specials").html(dict_NavBar["specials"]))
                        .append(" | ")
                        .append($("<a/>").attr("href", "#search_books").html(dict_NavBar["books"]))
                        .append(" | ")
                        .append($("<a/>").attr("href", "#search_events").html(dict_NavBar["events"]))
            );
        }

        if ((search_table === "peoples") ||
            (search_table === "all")) {
            // Search Peoples database
            await searchDatabase(search_name, "peoples", options).then(function(results) {
                SearchItems(results, search_name, "peoples");
            }, console.log);
        }

        if ((search_table === "locations") ||
            (search_table === "all")) {
            // Search Locations database
            await searchDatabase(search_name, "locations", options).then(function(results) {
                SearchItems(results, search_name, "locations");
            }, console.log);
        }

        if ((search_table === "specials") ||
            (search_table === "all")) {
            // Search Specials database
            await searchDatabase(search_name, "specials", options).then(function(results) {
                SearchItems(results, search_name, "specials");
            }, console.log);
        }

        if ((search_table === "books") ||
            (search_table === "all")) {
            // Search Books database
            await searchDatabase(search_name, "books", options).then(function(results) {
                SearchItems(results, search_name, "books");
            }, console.log);
        }

        if ((search_table === "events") ||
            (search_table === "all")) {
            // Search Events database
            await searchDatabase(search_name, "events", options).then(function(results) {
                SearchItems(results, search_name, "events");
            }, console.log);
        }
    }
}

// Function to speed up the process of adding Form elements
function addInput(name, placeholder, table) {
    var selected = get_settings.hasOwnProperty(name) && get_settings["table"] === table;

    return $("<input/>")
                .attr("type", "text")
                .attr("name", name)
                .attr("placeholder", placeholder)
                .attr("value", selected ? get_settings[name] : "")
                .addClass("added");
}

// Function to speed up the process of adding Form elements
function addSelect(name, placeholder, table) {
    var selected = get_settings.hasOwnProperty(name) && get_settings["table"] === table;

    // Made easy, and manageble in one single place
    switch(name) {
        case "tribe":
            var array = select_Search_tribes;
            break;
        case "gender":
            array = select_Search_gender;
            break;
        case "locations":
            array = select_Search_locations;
            break;
        case "specials":
            array = select_Search_specials;
            break;
    }

    var element = $("<select/>")
            .attr("name", name)
            .addClass("added")
            .append(
                $("<option/>")
                    .attr("value", "")
                    .attr("disabled", "true")
                    .attr("selected",  selected ? "false" : "true")
                    .html(dict_Settings["default"] + " voor " + placeholder)
            );

    // First option is to choose all
    element.append($("<option/>").attr("value", 0).html(dict_Search["all"]));        
    for (var i = 0; i < Object.keys(array).length; i++) {
        if (selected && get_settings[name] === i + 1) {
            element.append(
                    $("<option/>")
                        .attr("value", i + 1)
                        .attr("selected", "true")
                        .html(array[Object.keys(array)[i]])
            );
        } else {
            element.append(
                    $("<option/>")
                        .attr("value", i + 1)
                        .html(array[Object.keys(array)[i]])
            );
        }
    }

    return element;
}

async function addAppearance(name, placeholder, table) {
    var selected_book = get_settings.hasOwnProperty(name + "_book") && get_settings["table"] === table;
    var selected_chap = get_settings.hasOwnProperty(name + "_chap") && get_settings["table"] === table;

    // The dropdown list containing all the books
    var elementBook = $("<select>")
            .attr("name", name + "_book")
            .attr("id", name + "_book")
            .data("app", name)
            .change(function() {
                selectBookOptions($(this));
            })
            .addClass("added_app_select")
            .append(
                // Bible book that can be chosen
                $("<option>")
                    .attr("value", "")
                    .attr("disabled", "true")
                    .attr("selected", selected_book ? "false" : "true")
                    .html(dict_Search["bible_book"])
            );



    // List of options from the database, to get names in correct language.
    await getItemFromDatabase("books").then(
        function(information) {

            for (var itemIdx in information) {
                var item = information[itemIdx];

                if (selected_book && get_settings[name + "_book"] === item["book_id"]) {
                    elementBook.append(
                            $("<option>")
                                .attr("value", item["book_id"])
                                .attr("selected", "true")
                                .data("chapters", item["num_chapters"])
                                .html(item['name'])
                    );
                } else {
                    elementBook.append(
                            $("<option>")
                                .attr("value", item["book_id"])
                                .data("chapters", item["num_chapters"])
                                .html(item['name'])
                    );
                }


            }

        }, console.log
    );  

    // The dropdown list containing all the chapters of a book
    var elementChap = $("<select>")
            .attr("name", name + "_chap")
            .attr("id", name + "_chap")
            .addClass("added_app_select")
            .append(
                // Chapter that can be chosen (currently no available options
                // Will be filled in when a book is chosen
                $("<option>")
                    .attr("value", "")
                    .attr("disabled", "true")
                    .attr("selected", selected_chap ? "false" : "true")
                    .html(dict_Search["bible_chap"])
            );

    // The entire div that contains the drop downs and text bar
    // These are used to create the number that corresponds to
    // a bible verse
    var appearance = $("<div>")
            .addClass("added_app_div")
            .append(
                // The title, to let the user know whether
                // it's the first or last appearance we are
                // filling in
                $("<p>")
                        .html(placeholder)
                        .addClass("added_app_text")
            )
            .append(elementBook)
            .append(elementChap);

    return appearance;
}

// This function is executed when the book for first/last appearance has changed
// It updates the dropdown with the number of chapters
function selectBookOptions(sel) {

    // Which dropdown are we currently accessing?
    var name = sel.data("app");

    // Which book has been chosen?
    var option = sel.prop('options')[sel.prop('selectedIndex')];

    // How many chapters does the chosen book have?
    var Chapters = $(option).data("chapters");

    // Remove the old chapters of the dropdown menu
    var options = $("#" + name + "_chap").prop('options');
    $(options).slice(1).remove();

    // Creating the dropdown list
    for (var i = 0; i < parseInt(Chapters); i++) {
        $("#" + name + "_chap").append(
                $("<option>")
                    .attr("value", i + 1)
                    .html(i + 1)
        );
    }
}

// The function that executes the search, and returns the results
function SearchItems(result, name, table) {
        
    // Type of search performed
    $("#search_results").append(
            $("<div/>")
                .attr("id", "search_" + table)
                .append(
                    $("<a/>").attr("name", table).html("<h1>" + dict_NavBar[table] + ":</h1><br />")
                )
        );
        
    if (!result) {
        // If there are no results, show a message
        $("#search_results").append(dict_Search["no_results"] + "<br />");
    }
//    else {
        // Show the amount of results found. If it is more than one result, use plural forms
//        // If there are results..
//        $num_res = $result->num_rows;
//        
//        // Type of search performed
//        // Show the amount of results found. If it is more than one result, use plural forms
//        PrettyPrint("            <a name='".$table."'><h1>".$dict_NavBar[table].":</h1><br /></a>");
//        if ($num_res == 1) {
//            PrettyPrint('            '.$num_res.$dict_Search['result']."\"".$text_escaped."\":<br />");
//        } else {
//            PrettyPrint('            '.$num_res.$dict_Search['results']."\"".$text_escaped."\":<br />");
//        }
//        
//        // If there are results, draw a table with all the results found
//        if ($num_res > 0) {
//            PrettyPrint("            <table>");
//            if (in_array($table, Array("peoples", "locations", "specials", "events"))) {
//                PrettyPrint("                <tr>");
//                PrettyPrint("                    <td>");
//                PrettyPrint('                        '.$dict['name']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                    <td>");
//                PrettyPrint('                        '.$dict['book_start_vers']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                    <td>");
//                PrettyPrint("                        ".$dict['book_end_vers']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                </tr>");
//            } else {
//                PrettyPrint("                <tr>");
//                PrettyPrint("                    <td>");
//                PrettyPrint("                        ".$dict['name']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                </tr>");
//            }
//            
//            while ($item = $result->fetch_array()) {
//                PrettyPrint("                <tr>");
//                PrettyPrint("                    <td>");
//                PrettyPrint("                        <a href='".$table.".php".AddParams(-1, $item[substr($table, 0, -1).'_id'], -2)."'>".$item['name']."</a>");
//                PrettyPrint("                    </td>");
//                
//                if (in_array($table, Array("peoples", "locations", "specials", "events"))) {
//                    PrettyPrint("                    <td>");
//                    PrettyPrint("                        ".convertBibleVerseText($item['book_start_id'], $item['book_start_chap'], $item['book_start_vers']));
//                    PrettyPrint("                    </td>");
//                    PrettyPrint("                    <td>");
//                    PrettyPrint("                        ".convertBibleVerseText($item['book_end_id'], $item['book_end_chap'], $item['book_end_vers']));
//                    PrettyPrint("                    </td>");
//                }
//                
//                PrettyPrint("                </tr>");
//            }
//            PrettyPrint("            </table>");
//        }
//    }
}