
        <!-- And the footer of every page -->
        <div id="footer" class="row mx-0 fixed-bottom">
            <div class="col-md-12">
                <?php 
                    // Get the name of the file that has currently included this file
                    $uri_parts = explode('?', basename(filter_input(INPUT_SERVER, 'REQUEST_URI'), 2));
                    $current_page = $uri_parts[0];

                    // Now get the timestamp of that file
                    $date_page = filemtime($current_page);

                    // Set the timezone to the timezone that I use on my computer
                    date_default_timezone_set('Europe/Amsterdam');

                    // Print the copyright year and the name of this website
                    echo $dict_Footer["PP_name"]."&copy;".date("Y")."\n"; 
                    echo "\t\t\t\t<br>"."\n";

                    // Version and date of file modification
                    echo "\t\t\t\t".$dict_Footer["PP_version"].": v3.0. "."\n";
                    echo "\t\t\t\t".$dict_Footer["PP_date"]." ".date("d-m-Y H:i", $date_page)."\n";  ?>
            </div>
        </div>
