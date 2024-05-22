<?php
    // The Parts used by this Page
    require "src/modules/HomePage/Parts/BlogList.php";
    require "src/modules/HomePage/Parts/BlogListItem.php";

    class HomePage extends Module {
        public function __construct() {
            // Add the necessary modules in here
            $blog_list = new BlogList();
            $this->addContent($blog_list);
        }
    }

