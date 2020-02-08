            
            <!-- Navigation bar -->
            <div id="navigation">
                <ul>
                    <!-- Home page -->
                    <li>
                        <?php MakeButton("home"); ?>
                    </li>
                    
                    <!-- Dropdown menu for Database items -->
                    <li><div id="dropdown_db_div">
                    
                        <!-- The button to make the drop down list of options appear -->
                        <?php MakeButton("items");
                            PrettyPrint("                ".$dict_NavBar["Database"]); ?>
                        </button>
                        
                        <!-- The actual drop down menu, hidden at first -->
                        <div id="dropdown_db_menu" class="dropdown_nav_menu">
                            <?php MakeButton("peoples"); ?>
                            <?php MakeButton("locations"); ?>
                            <?php MakeButton("specials"); ?>
                            <?php MakeButton("books"); ?>
                            <?php MakeButton("events"); ?>
                            <?php MakeButton("search"); ?>
                        </div>
                    </div></li>
                    
                    <!-- Other options in the navigation bar -->
                    <li>
                        <?php // MakeButton("timeline"); ?>
                        <?php MakeButton("timeline_ext"); // TODO: Extended events timelin ?>
                    </li>
                    <li>
                        <?php MakeButton("familytree"); ?>
                    </li>
                    <li>
                        <?php MakeButton("worldmap"); ?>
                    </li>
                    
                    <!-- Dropdown menu for Pro Deo items -->
                    <li><div id="dropdown_prodeo_div">
                    
                        <!-- The button to make the drop down list of options appear -->
                        <?php MakeButton("prodeo");
                            PrettyPrint("                ".$dict_NavBar["ProDeo"]); ?>
                        </button>
                        
                        <!-- The actual drop down menu, hidden at first -->
                        <div id="dropdown_prodeo_menu" class="dropdown_nav_menu">
                            <?php MakeButton("aboutus"); ?>
                            <?php MakeButton("contact"); ?>
                        </div>
                    </div></li>
                </ul>
            </div>