<?php 
    session_start();
    
    if (filter_input(INPUT_POST, "lang") !== null) {
        $_SESSION["lang"] = filter_input(INPUT_POST, "lang");
        ?>
        
        <script>
            // Do a reload to the desired language
            window.location.href = window.location.href;
        </script>
        
        <?php
    }
    
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

    require "tools/baseHelper.php";

    /* Only used by layout.php, for the dropdown with languages */
    function getLangList($page_lang) {
        foreach (get_available_langs() as $lang) {
            if ($lang != $page_lang) {
                PrettyPrint('<input style=" 
                                    background-image: url(\'img/lang/lang_'.$lang.'.svg\'); 
                                    background-size: auto 100%;" 
                                    class="dropdown_lang_option" 
                                    type="submit" 
                                    name="lang" 
                                    value="'.$lang.'">', 1);
            }
        }
    }

    /* Only used by layout.php, for the navigation buttons */
    function MakeButton($name) {
        global $$name;
        global $id;
        global $$id;
        global $dict_NavBar;

        $button_id = "nav_".$name;
        $class = "nav_".$$name;
        if ($name == $id) {
            $class = "select_".$$name;
        } elseif (($name == "items") &&
                  (($id == "peoples") || 
                   ($id == "locations") || 
                   ($id == "specials") || 
                   ($id == "books") || 
                   ($id == "events") ||
                   ($id == "search"))) {
            // This is the case where we also want the database button to have the selected class
            $class = "select_".$$id;
        } elseif (($name == "prodeo") &&
                  (($id == "aboutus") ||
                   ($id == "contact"))) {
            // This is the case where we also want the prodeo button to have the selected class
            $class = "select_".$$id;
        }

        if ($name == "items") {
            PrettyPrint('<button id="dropdown_db_button" class="'.$class.'" onclick="ShowDropDown(\'dropdown_db_menu\')">', 1);
        } elseif ($name == "prodeo") {
            PrettyPrint('<button id="dropdown_prodeo_button" class="'.$class.'" onclick="ShowDropDown(\'dropdown_prodeo_menu\')">', 1);
        } else {
            PrettyPrint('<button id="'.$button_id.'" class="'.$class.'" onclick="location.href=\''.$name.'.php\'" type="button" >'.$dict_NavBar[ucfirst($name)].'</button>', 1);
        }
    }
?>

             
<script>
    function onLoadDefault() {
        var body = document.getElementsByTagName("body")[0];
        body.id = "<?php echo $id; ?>";
        body.className = "<?php global $$id; echo $$id; ?>";
    }

    /* When the user clicks on the button, 
    toggle between hiding and showing the dropdown content */
    function ShowDropDown(name) {
        var menu = document.getElementById(name);
        menu.style.display = "block";
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

