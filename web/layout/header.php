
            <!-- Header, with logo, banner and options -->
            <div id="header">
                <!-- Logo -->
                <div >
                    <a id="logo_link" href="home.php" ></a>
                </div>
                
                <!-- Banner, in the corresponding language -->
                <div >
                    <img id="banner_img" src="img/banner/banner_<?php echo $$id; ?>.svg" alt="Banner" />
                    <h1 id="banner_text"><?php echo $dict_Footer["slogan"]; ?></h1>
                </div>
                
                <!-- Options -->
                <div id="options">
                
                    <!-- Dropdown list for available languages -->
                    <div id="dropdown_lang_div">
                    
                        <!-- The button to make the drop down list of options appear -->
                        <button 
                            style=" 
                                background-image: url('img/lang/lang_<?php echo $_SESSION["lang"]; ?>.svg'); 
                                background-size: auto 100%;" 
                            id="dropdown_lang_button" 
                            onclick="ShowDropDown('dropdown_lang_menu')">
                                <?php PrettyPrint($_SESSION["lang"], 1); ?>
                        </button>
                        
                        <!-- The actual drop down menu, hidden at first -->
                        <div id="dropdown_lang_menu">
                            <form method="post">
                                <?php getLangList($_SESSION["lang"]); ?>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Settings -->
                    <div class="settings" >
                        <a class="settings" href="settings.php" ></a>
                    </div>
                </div>
            </div>
            