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
    
    // The ItemPage
    require "src/modules/ItemPage/ItemPage.php";
    
    // The Parts used by this Page
    require "src/modules/ItemPage/Parts/Content/ItemContent.php";
    require "src/modules/ItemPage/Parts/Content/ItemDefault.php";
    require "src/modules/ItemPage/Parts/Content/ItemDetails.php";
    require "src/modules/ItemPage/Parts/Content/ItemTable.php";
    require "src/modules/ItemPage/Parts/Content/MapTable.php";
    require "src/modules/ItemPage/Parts/List/ItemList.php";  
    require "src/modules/ItemPage/Parts/List/ItemListItem.php";
    require "src/modules/ItemPage/Parts/List/ItemListToggle.php";
    require "src/modules/ItemPage/Parts/List/ItemSearch.php";
    require "src/modules/ItemPage/Parts/List/ItemPages.php";  
    
    // The different items
    require "src/modules/ItemPage/Items/Item.php";
    require "src/modules/ItemPage/Items/ItemBook.php";
    require "src/modules/ItemPage/Items/ItemEvent.php";
    require "src/modules/ItemPage/Items/ItemPeople.php";
    require "src/modules/ItemPage/Items/ItemLocation.php";
    require "src/modules/ItemPage/Items/ItemSpecial.php";
    
//    require "src/modules/SearchPage/SearchPage.php";
    
    // The MapPage
    require "src/modules/MapPage/MapPage.php";
    
    // The Parts used by this Page
    require "src/modules/MapPage/Parts/Content/MapContent.php";
    require "src/modules/MapPage/Parts/Content/LoadingScreen.php";
    require "src/modules/MapPage/Parts/Content/SmallScreen.php";
    require "src/modules/MapPage/Parts/Content/Modal.php";
    require "src/modules/MapPage/Parts/Content/SVG.php";
    require "src/modules/MapPage/Parts/List/MapList.php";
    require "src/modules/MapPage/Parts/List/MapListItem.php";
    
    // The different maps
    require "src/modules/MapPage/Maps/Map.php";
    require "src/modules/MapPage/Maps/MapTimeline.php";
    require "src/modules/MapPage/Maps/MapFamilytree.php";
    require "src/modules/MapPage/Maps/MapWorldmap.php";
    
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
