<?php

    namespace HomePage;
    
    use Shapes\Module;
    use Parts\BlogList;

    class HomePage extends Module {
        public function __construct() {
            parent::__construct();
            
            // Add the necessary modules in here
            $blog_list = new BlogList();
            $this->addContent($blog_list);
        }
    }
