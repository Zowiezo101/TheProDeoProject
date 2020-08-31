
        <!-- Header, with logo, banner and options -->
        <div id="header" class="row mx-0">
            <!-- Logo -->
            <div class="col-auto px-0">
                <a id="logo_link" href="home.php" ></a>
            </div>
                
            <!-- Banner, in the corresponding language -->
            <div class="banner_<?php echo $_SESSION["theme"]; ?> col px-0">
                <h1 id="banner_text">
                    <?php 
                        global $dict_Footer;
                        echo $dict_Footer["slogan"]; 
                    ?>
                </h1>
            </div>
                
            <!-- Options -->
            <div id="options" class="col-auto px-0">
                
                <div class="row mx-0">
                    <!-- Dropdown list for available languages -->
                    <div id="dropdown_lang_div" class="col-auto px-0">

                        <!-- The button to make the drop down list of options appear -->
                        <button 
                            style=" 
                                background-image: url('img/lang/lang_<?php echo $_SESSION["lang"]; ?>.svg'); 
                                background-size: auto 100%;" 
                            id="dropdown_lang_button" 
                            onclick="ShowDropDown('dropdown_lang_menu')">
                                <?php echo $_SESSION["lang"]; ?>
                        </button>

                        <!-- The actual drop down menu, hidden at first -->
                        <div id="dropdown_lang_menu">
                            <form method="post">
                                <script>makeLangList();</script>
                            </form>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="settings col-auto px-0 fas fa-cog" >
                        <a class="settings" href="settings.php" ></a>
                    </div>
                </div>
            </div>
        </div>
            