<?php
    // A tool to help us build the page with modules
    require "src/modules/Page.php";
    
    // The PHP file that contains everything we need to log in
    require "src/tools/server.php";
    
    // Are we already logged in?
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        // Redirect to login page
        // TODO: Does this happen in the correct language?
        $url = "settings";
        if( headers_sent() ) { 
            echo("<script>location.href='$url'</script>"); 
        } else { 
            header("Location: $url"); 
        }
        exit;
    }
    
    // This is an object to easily generate the page
    $page = new Page();
    
    // The tab page comes with 2 modules
    // - The TabList sidebar (Which consists of the list of clickable tabs)
    // - The TabContent module (Showing the content of the clicked tab)
    $tab_page = $page->TabPage();
    
    // Add the different tabs for this page    
    $tab_page->addTab($TAB_LOGIN);

    // Add the modules to the page
    $page->addContent($tab_page);

    // Generate the page with our chosen content
    $page->insertPage();

