<?php 
    session_start();
    
    require "tools/lang.php";
    
    // The various colors per page
    $home = "purple";
    $items = "green";
    $books = "orange";
    $events = "blue";
    $peoples = "red";
    $locations = "yellow";
    $specials = "purple";
    $search = "black";
    $timeline = "blue";
    $timeline_ext = "blue"; // TODO: Extended events
    $familytree = "red";
    $worldmap = "yellow";
    $prodeo = "orange";
    $aboutus = "green";
    $contact = "purple";
    $settings = "black";

    // Save the old value
    if (isset($_SESSION["table"])) {
        $_SESSION["table_old"] = $_SESSION["table"];
    }
    $_SESSION["table"] = $id;
    $_SESSION["theme"] = $$id;
?>

             
<script>
    var session_settings = {
        <?php foreach($_SESSION as $key => $value) {
           echo "'".$key."': '".$value."',\n\t\t";
        }?>
    };
    var get_settings = {
        <?php  
        $input_get = filter_input_array(INPUT_GET);
        if ($input_get) {
            foreach($input_get as $key => $value) {
               echo "'".$key."': '".$value."',\n\t\t";
            }
        }?>
    };
    var post_settings = {
        <?php 
        $input_post = filter_input_array(INPUT_POST);
        if ($input_post) {
            foreach($input_post as $key => $value) {
               echo "'".$key."': '".$value."',\n\t\t";
            }
        }?>
    };
    
    var lang_list = [
        <?php 
        $lang_list = get_available_langs();
        foreach($lang_list as $lang) {
            echo "'".$lang."',\n\t\t";
        }
        ?>
    ];
    
    var theme_list = {
        // The various colors per page
        home: "<?php echo $home; ?>",
        items: "<?php echo $items; ?>",
        books: "<?php echo $books; ?>",
        events: "<?php echo $events; ?>",
        peoples: "<?php echo $peoples; ?>",
        locations: "<?php echo $locations; ?>",
        specials: "<?php echo $specials; ?>",
        search: "<?php echo $search; ?>",
        timeline: "<?php echo $timeline; ?>",
        familytree: "<?php echo $familytree; ?>",
        worldmap: "<?php echo $worldmap; ?>",
        prodeo: "<?php echo $prodeo; ?>",
        aboutus: "<?php echo $aboutus; ?>",
        contact: "<?php echo $contact; ?>",
        settings: "<?php echo $settings; ?>"
    };

    /* When the user clicks on the button, 
    toggle between hiding and showing the dropdown content */
    function ShowDropDown(name) {
        var menu = document.getElementById(name);
        menu.style.display = "block";
    }

    function makeLangList() {
        // Get the menu that will be filled up
        var div = document.getElementById("dropdown_lang_menu");
        
        // Clear it
        div.innerHTML = "";
        
        for (var idx in lang_list) {
            var lang = lang_list[idx];
            if (lang !== session_settings["lang"]) {
                var input = document.createElement("input");
                div.appendChild(input);
                
                // Set the attributes
                input.style = "background-image: url('img/lang/lang_" + lang + ".svg'); \
                               background-size: auto 100%;";
                input.className = "dropdown_lang_option";
                input.type = "submit";
                input.name = "lang";
                input.value = lang;
                input.onclick = function() {
                    updateSessionSettings("lang", this.value).then(async function () {
                        location.reload();
                    });
                };
            }
        }
    }
    
    /* Only used by layout.php, for the navigation buttons */
    function makeButton(name, id_name) {
        // The parent
        var element = document.getElementById(id_name);
        
        // Create the button
        var button = document.createElement("button");
        element.appendChild(button);
        
        // Set the attributes
        if (name === "items") {
            button.id = "dropdown_db_button";
            button.className = "nav_green";
            button.onclick = function() { ShowDropDown('dropdown_db_menu'); };
            button.innerHTML = dict_NavBar["Database"];
        } else if (name === "prodeo") {
            button.id = "dropdown_prodeo_button";
            button.className = "nav_orange";
            button.onclick = function() { ShowDropDown('dropdown_prodeo_menu'); };
            button.innerHTML = dict_NavBar["ProDeo"];
        } else {
            button.id = "nav_" + name;
            button.className = "nav_" + theme_list[name];
            button.onclick = function() { goToPage(name + '.php'); };
            button.innerHTML = dict_NavBar[name.charAt(0).toUpperCase() + name.slice(1)];
        }
        
        if (name === session_settings["table"]) {
            button.className = "select_" + theme_list[session_settings["table"]];
        } else if ((name === "items") &&
                  ((session_settings["table"] === "peoples") || 
                   (session_settings["table"] === "locations") || 
                   (session_settings["table"] === "specials") || 
                   (session_settings["table"] === "books") || 
                   (session_settings["table"] === "events") ||
                   (session_settings["table"] === "search"))) {
            // This is the case where we also want the database button to have the selected class
            button.className = "select_" + theme_list[session_settings["table"]];
        } else if ((name === "prodeo") &&
                  ((session_settings["table"] === "aboutus") ||
                   (session_settings["table"] === "contact"))) {
            // This is the case where we also want the prodeo button to have the selected class
            button.className = "select_" + theme_list[session_settings["table"]];
        }
    }
    
    async function goToPage(url="", page="", id="", sort="", map="") {
        // Clear the id, page and sort selections
        // Then go to the new page (only if one is given)
        await updateSessionSettings("page", page).then(async function () {
            await updateSessionSettings("id", id).then(async function () {
                await updateSessionSettings("sort", sort).then(async function () {
                    await updateSessionSettings("map", map).then(function () {
                        if (url !== "") {
                            window.location.href = url;
                        }
                    }, console.log);
                }, console.log);
            }, console.log);
        }, console.log);
    }
    
    function onLoadDefault() {
        // Are we in a different main page?
        var main_page_new = session_settings["table"];
        var main_page_old = session_settings["table_old"];
        var keep = session_settings["keep"];
        
        if ((main_page_new !== main_page_old) && !keep) {
            // Just remove the session settings for the old page
            goToPage();
        } else if (keep) {
            updateSessionSettings("keep", "");
        }
        
        var body = document.getElementsByTagName("body")[0];
        body.id = session_settings["table"];
        body.className = session_settings["theme"];
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        var ButtonIDs = [
            "dropdown_db",
            "dropdown_prodeo",
            "dropdown_lang"
        ];
        
        for (i = 0; i < ButtonIDs.length; i++) {
            var ButtonID = ButtonIDs[i];
        
            // See which button has been pressed, using multiple functions that support different webbrowsers
            if (event.target.matches) {
                var Button = event.target.matches("#" + ButtonID + "_button");
            } else if (event.target.msMatchesSelector) {
                var Button = event.target.msMatchesSelector("#" + ButtonID + "_button");
            } else {
                var Button = event.target.webkitMatchesSelector("#" + ButtonID + "_button");
            }
            
            // If none of the buttons is pressed, hide the menu again
            if (!Button) {
                var menu = document.getElementById(ButtonID + "_menu");
                menu.style.display = "none";
            }
        }
    };

    window.onload = function() {
        // Set some default stuff
        onLoadDefault();
        
        // Then run the function that is different per page
        <?php echo "onLoad".ucfirst($id)."()"; ?>;
    };
</script>

