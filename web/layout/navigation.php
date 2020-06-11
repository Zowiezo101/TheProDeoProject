
        <!-- Navigation bar -->
        <div id="navigation">
            <ul>
                <!-- Home page -->
                <li id="li_home">
                    <script>makeButton("home", "li_home");</script>
                </li>

                <!-- Dropdown menu for Database items -->
                <li><div id="dropdown_db_div">

                    <!-- The button to make the drop down list of options appear -->
                    <script>makeButton("items", "dropdown_db_div");</script>

                    <!-- The actual drop down menu, hidden at first -->
                    <div id="dropdown_db_menu" class="dropdown_nav_menu">
                        <script>makeButton("peoples", "dropdown_db_menu");</script>
                        <script>makeButton("locations", "dropdown_db_menu");</script>
                        <script>makeButton("specials", "dropdown_db_menu");</script>
                        <script>makeButton("books", "dropdown_db_menu");</script>
                        <script>makeButton("events", "dropdown_db_menu");</script>
                        <script>makeButton("search", "dropdown_db_menu");</script>
                    </div>
                </div></li>

                <!-- Other options in the navigation bar -->
                <li id="li_timeline"><script>makeButton("timeline", "li_timeline");</script></li>
                <li id="li_familytree"><script>makeButton("familytree", "li_familytree");</script></li>
                <li id="li_worldmap"><script>makeButton("worldmap", "li_worldmap");</script></li>

                <!-- Dropdown menu for Pro Deo items -->
                <li><div id="dropdown_prodeo_div">

                    <!-- The button to make the drop down list of options appear -->
                    <script>makeButton("prodeo", "dropdown_prodeo_div");</script>

                    <!-- The actual drop down menu, hidden at first -->
                    <div id="dropdown_prodeo_menu" class="dropdown_nav_menu">
                        <script>makeButton("aboutus", "dropdown_prodeo_menu");</script>
                        <script>makeButton("contact", "dropdown_prodeo_menu");</script>
                    </div>
                </div></li>
            </ul>
        </div>

