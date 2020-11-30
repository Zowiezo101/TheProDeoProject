<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "search";
    require 'layout/template.php'; 
?>

<script>
    function onLoadSearch() {
        
        // Change the title text of the select element
//        $("#default").html(dict_Search["category"]);
//        $("#table").attr("", "");
    
//    // Set back all the data that was entered for searching
//    <?php // if ((filter_input(INPUT_GET, 'submitSearch') !== null)) { ?>
//        var SelectElement = document.getElementById("table");
//        SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'table'); ?>";
//        SelectElement.onchange();
//    <?php // } ?>
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        $("#content").append(
            $("<div/>").addClass("contents_left col-md-3 px-0").attr("id", "search_bar").append(
                $("<h1/>").html(dict_Search["options"])
            ).append(
                $("<form/>").attr("method", "get").attr("action", "search.php").append(
                    $("<select/>")
                        .attr("id", "table")
                        .attr("name", "table")
//                            .attr("disabled", "false") //"true")
                        .change(function() {
                            selectTableOptions($(this));
                        })
                        .append(
                            $("<option>")
                                .attr("id", "default")
                                .attr("value", "")
//                                    .attr("disabled", "true")
                                .attr("selected", "true")
                                .html(dict_Search["category"]) //dict_Search["busy"])
                        ).append(
                            $("<option>")
                                .attr("value", "books")
                                .html(dict_NavBar["books"])
                        ).append(
                            $("<option>")
                                .attr("value", "events")
                                .html(dict_NavBar["events"])
                        ).append(
                            $("<option>")
                                .attr("value", "peoples")
                                .html(dict_NavBar["peoples"])
                        ).append(
                            $("<option>")
                                .attr("value", "locations")
                                .html(dict_NavBar["locations"])
                        ).append(
                            $("<option>")
                                .attr("value", "specials")
                                .html(dict_NavBar["specials"])
                        ).append(
                            $("<option>")
                                .attr("value", "all")
                                .html(dict_Search["all"])
                        )
                )
            )
        ).append(
            // This is where the items will be displayed
            $("<div/>").addClass("contents_right col-md-9 px-0").attr("id", "search_results")
                // When no search is performed yet
                .html(dict_Search["default_search"])
        );

        onSearch();
    }
    
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
                    .attr("value", dict_Search["Search"])
                    .addClass("added")
        );

        if (get_settings.hasOwnProperty("submitSearch")) {
            // Function to remove all selected search options
            $(form).append(
                    $("<a/>")
                        .attr("href", "search.php")
                        .html(dict_Search["Remove"])
                        .addClass("added")
                
                        // No border please..
                        .style("borderwidth: 0px")
            );
        }
    }
    
    function onSearch() {
        //    if (filter_input(INPUT_GET, 'submitSearch') !== null) {
//        // Generating search results
//        $options = "";
//
//        if ((filter_input(INPUT_GET, 'meaning_name') !== null) and (filter_input(INPUT_GET, "meaning_name") != "")) {
//            $options = $options." AND meaning_name LIKE '%".filter_input(INPUT_GET, "meaning_name")."%'";
//        }
//
//        // TODO: Linking tables
////        if (isset(filter_input(INPUT_GET, 'NameChanges']) and (filter_input(INPUT_GET, "NameChanges"] != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "NameChanges"]);
////            foreach ($multoptions as $value) {
////                $options = $options." AND NameChanges LIKE '%".$value."%'";
////            }
////        }
////
////        if (isset(filter_input(INPUT_GET, 'Father']) and (filter_input(INPUT_GET, "Father"] != "")) {
////            $options = $options." AND Father LIKE '%".filter_input(INPUT_GET, "Father"]."%'";
////        }
////
////        if (isset(filter_input(INPUT_GET, 'Mother']) and (filter_input(INPUT_GET, "Mother"] != "")) {
////            $options = $options." AND Mother LIKE '%".filter_input(INPUT_GET, "Mother"]."%'";
////        }
//
//        if ((filter_input(INPUT_GET, 'gender') !== null) and (filter_input(INPUT_GET, "gender") != "")) {
//            if (filter_input(INPUT_GET, "gender") != 0) {
//                $options = $options." AND gender = '%".filter_input(INPUT_GET, "gender")."%'";
//            }
//        }
//
//        if ((filter_input(INPUT_GET, 'tribe') !== null) and (filter_input(INPUT_GET, "tribe") != "")) {
//            if (filter_input(INPUT_GET, "tribe") != 0) {
//                $options = $options." AND tribe = '%".filter_input(INPUT_GET, "tribe")."%'";
//            }
//        }
//
//        if ((filter_input(INPUT_GET, 'type') !== null) and (filter_input(INPUT_GET, "type") != "")) {
//            if (filter_input(INPUT_GET, "type") != 0) {
//                $options = $options." AND type = '%".filter_input(INPUT_GET, "type")."%'";
//            }
//        }
//
//        // TODO: Linking yable
////        if (isset(filter_input(INPUT_GET, 'Founder')) and (filter_input(INPUT_GET, "Founder") != "")) {
////            $options = $options." AND Founder LIKE '%".filter_input(INPUT_GET, "Founder")."%'";
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Destroyer')) and (filter_input(INPUT_GET, "Destroyer") != "")) {
////            $options = $options." AND Destroyer LIKE '%".filter_input(INPUT_GET, "Destroyer")."%'";
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Previous')) and (filter_input(INPUT_GET, "Previous") != "")) {
////            $options = $options." AND Previous LIKE '%".filter_input(INPUT_GET, "Previous")."%'";
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Locations')) and (filter_input(INPUT_GET, "Locations") != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "Locations"));
////            foreach ($multoptions as $value) {
////                $options = $options." AND Locations LIKE '%".$value."%'";
////            }
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Peoples')) and (filter_input(INPUT_GET, "Peoples") != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "Peoples"));
////            foreach ($multoptions as $value) {
////                $options = $options." AND Peoples LIKE '%".$value."%'";
////            }
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Specials')) and (filter_input(INPUT_GET, "Specials") != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "Specials"));
////            foreach ($multoptions as $value) {
////                $options = $options." AND Specials LIKE '%".$value."%'";
////            }
////        }
//        
//        if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, "book_start_book") != "")) {
//            $options = $options." AND book_start_id >= '".filter_input(INPUT_GET, "book_start_book")."'";
//            
//            if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, "book_start_chap") != "")) {
//                $options = $options." AND book_start_chap >= '".filter_input(INPUT_GET, "book_start_chap")."'";
//            }
//        }
//        
//        if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, "book_end_book") != "")) {
//            $options = $options." AND book_end_id >= '".filter_input(INPUT_GET, "book_end_book")."'";
//            
//            if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, "book_end_chap") != "")) {
//                $options = $options." AND book_end_chap >= '".filter_input(INPUT_GET, "book_end_chap")."'";
//            }
//        }
//        
//        // If all types are chosen, make some shortcuts at the top of the search results
//        if (filter_input(INPUT_GET, 'table') == "all") {
//            PrettyPrint('        <center> ');
//            PrettyPrint('            '.$dict_Search["Show"].
//                                    "<a href='#search_peoples'>".$dict_NavBar["Peoples"]."</a> | ".
//                                    "<a href='#search_locations'>".$dict_NavBar["Locations"]."</a> | ".
//                                    "<a href='#search_specials'>".$dict_NavBar["Specials"]."</a> | ".
//                                    "<a href='#search_books'>".$dict_NavBar["Books"]."</a> | ".
//                                    "<a href='#search_events'>".$dict_NavBar["Events"]."</a>");
//            PrettyPrint('        </center> ');
//            PrettyPrint('');
//        }
//    
//        if ((filter_input(INPUT_GET, 'table') == "peoples") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_peoples"> ');
//            // Search Peoples database
//            SearchItems(filter_input(INPUT_GET, 'search'), "peoples", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "locations") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_locations"> ');
//            // Search Locations database
//            SearchItems(filter_input(INPUT_GET, 'search'), "locations", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "specials") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_specials"> ');
//            // Search Specials database
//            SearchItems(filter_input(INPUT_GET, 'search'), "specials", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "books") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_books"> ');
//            // Search Books database
//            SearchItems(filter_input(INPUT_GET, 'search'), "books", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "events") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_events"> ');
//            // Search Events database
//            SearchItems(filter_input(INPUT_GET, 'search'), "events", $options);
//            PrettyPrint('        </div> ');
//        }
//    }
//    PrettyPrint('    </div> ');
//    PrettyPrint('</div> ');
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
</script>

<?php 
//    

// The function that executes the search, and returns the results
//function SearchItems($text, $table, $options) {
//    global $dict_Search;
//    global $dict_NavBar;
//    global $conn;
//    
//    // The the desired parameters dictionary, depending on the type of search
//    $dictName = "dict_".ucfirst($table)."Params";
//    global $$dictName;
//    $dict = $$dictName;
//    
//    // Remove any newlines or characters
//    $text_escaped = $conn->real_escape_string($text);
//    
//    // Search the database with the chosen string and options
//    $sql = "SELECT * FROM ".$table." WHERE name LIKE '%".$text_escaped."%'".$options;
//    $result = $conn->query($sql);
//    
//    if (!$result) {
//        // If there are no results, show a message
//        PrettyPrint('            '.$dict_Search["NoResults"]."<br />");
//    }
//    else {
//        // If there are results..
//        $num_res = $result->num_rows;
//        
//        // Type of search performed
//        // Show the amount of results found. If it is more than one result, use plural forms
//        PrettyPrint("            <a name='".$table."'><h1>".$dict_NavBar[ucfirst($table)].":</h1><br /></a>");
//        if ($num_res == 1) {
//            PrettyPrint('            '.$num_res.$dict_Search['Result']."\"".$text_escaped."\":<br />");
//        } else {
//            PrettyPrint('            '.$num_res.$dict_Search['Results']."\"".$text_escaped."\":<br />");
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
//}

?>

<script>






</script>