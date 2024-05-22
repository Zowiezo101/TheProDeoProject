<?php
    // A tool to help us build the page with modules
    require "src/modules/Page.php";

    // This is an object to easily generate the page
    $page = new Page();

    // Setting a custom style for this page
    $page->setContentStyle("
                    background-image: url(img/background_home.svg); 
                    background-position: top left; 
                    background-size: 100% 32px;
                    background-repeat: repeat-y");

    // The HomePage Module, this consists out of the following Modules:
    // - BlogList
    $home_page = $page->HomePage();

    // Add the module to the page
    $page->addContent($home_page);

    // Generate the page with our chosen content
    $page->insertPage();


