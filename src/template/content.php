<?php
    
    // All required modules to make the tool work
    require "src/modules/Shapes/Module.php";
    
    // Small shapes
    require "src/modules/Shapes/Title.php";
    require "src/modules/Shapes/Table.php";
    require "src/modules/Shapes/TableRow.php";
    require "src/modules/Shapes/Text.php";
    
    // The HomePage
    require "src/modules/HomePage/HomePage.php";
    require "src/modules/HomePage/Parts/BlogList.php";
    require "src/modules/HomePage/Parts/BlogListItem.php";
    
    // The TabPage
    require "src/modules/TabPage/TabPage.php";
    
    // The Parts used by this Page
    require "src/modules/TabPage/Parts/TabList.php";
    require "src/modules/TabPage/Parts/TabListItem.php";
    require "src/modules/TabPage/Parts/TabContent.php";
    require "src/modules/TabPage/Parts/TabContentItem.php";
    
    // The different tabs
    require "src/modules/TabPage/Tabs/Tab.php";
    require "src/modules/TabPage/Tabs/TabAdd.php";
    require "src/modules/TabPage/Tabs/TabEdit.php";
    require "src/modules/TabPage/Tabs/TabDelete.php";
    require "src/modules/TabPage/Tabs/TabLogout.php";
    require "src/modules/TabPage/Tabs/TabLogin.php";
    
//    require "src/modules/SearchPage/SearchPage.php";
//    require "src/modules/MapPage/MapPage.php";
//    require "src/modules/AboutUsPage/AboutUsPage.php";
//    require "src/modules/ContactPage/ContactPage.php";
    
    // A tool to help us build the page with modules
    require "src/modules/Page.php";
    
    // The PHP file that contains everything we need to log in
    require "src/tools/server.php";

    // This is an object to easily generate the page
    // Depending on the page_id (the page we're currently looking at) it
    // wil automatically select the correct page to generate
    $page = new modules\Page();

    // Generate the page with our chosen content
    $page->insertPage();
