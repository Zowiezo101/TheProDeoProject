<?php
    // A tool to help us build the page with modules
    require "src/modules/Page.php";

    // This is an object to easily generate the page
    // Depending on the page_id (the page we're currently looking at) it
    // wil automatically select the correct page to generate
    $page = new Page();

    // Generate the page with our chosen content
    $page->insertPage();

