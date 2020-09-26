
        <!-- Navigation bar -->
        <div id="navigation" class="row mx-0">
            <!-- Home page -->
            <div id="div_home" class="col-md-2 px-0">
                <script>makeButton("home", "div_home");</script>
            </div>

            <!-- Dropdown menu for Database items -->
            <div id="dropdown_db_div" class="col-md-2 px-0">

                <!-- The button to make the drop down list of options appear -->
                <script>makeButton("items", "dropdown_db_div");</script>

                <!-- The actual drop down menu, hidden at first -->
                <div id="dropdown_db_menu" class="dropdown_nav_menu">
                    <div class="row"><script>makeButton("books", "dropdown_db_menu");</script></div>
                    <div class="row"><script>makeButton("events", "dropdown_db_menu");</script></div>
                    <div class="row"><script>makeButton("peoples", "dropdown_db_menu");</script></div>
                    <div class="row"><script>makeButton("locations", "dropdown_db_menu");</script></div>
                    <div class="row"><script>makeButton("specials", "dropdown_db_menu");</script></div>
                    <div class="row"><script>makeButton("search", "dropdown_db_menu");</script></div>
                </div>
            </div>

            <!-- Other options in the navigation bar -->
            <div id="div_timeline" class="col-md-2 px-0"><script>makeButton("timeline", "div_timeline");</script></div>
            <div id="div_familytree" class="col-md-2 px-0"><script>makeButton("familytree", "div_familytree");</script></div>
            <div id="div_worldmap" class="col-md-2 px-0"><script>makeButton("worldmap", "div_worldmap");</script></div>

            <!-- Dropdown menu for Pro Deo items -->
            <div id="dropdown_prodeo_div" class="col-md-2 px-0">

                <!-- The button to make the drop down list of options appear -->
                <script>makeButton("prodeo", "dropdown_prodeo_div");</script>

                <!-- The actual drop down menu, hidden at first -->
                <div id="dropdown_prodeo_menu" class="dropdown_nav_menu">
                    <div class="row"><script>makeButton("aboutus", "dropdown_prodeo_menu");</script></div>
                    <div class="row"><script>makeButton("contact", "dropdown_prodeo_menu");</script></div>
                </div>
            </div>
        </div>

