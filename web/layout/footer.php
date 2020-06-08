
        <!-- And the footer of every page -->
        <div id="footer">
            <?php 
                // Get the name of the file that has currently included this file
                $uri_parts = explode('?', basename(filter_input(INPUT_SERVER, 'REQUEST_URI'), 2));
                $current_page = $uri_parts[0];

                // Now get the timestamp of that file
                $date_page = filemtime($current_page);

                // Set the timezone to the timezone that I use on my computer
                date_default_timezone_set('Europe/Amsterdam');

                // Print the copyright year and the name of this website
                PrettyPrint($dict_Footer["PP_name"]."&copy;".date("Y"), 1);
                PrettyPrint("<br>");

                // Version and date of file modification
                PrettyPrint($dict_Footer["PP_version"].": v3.0. ");
                PrettyPrint($dict_Footer["PP_date"]." ".date("d-m-Y H:i", $date_page)); 
            ?>
        </div>