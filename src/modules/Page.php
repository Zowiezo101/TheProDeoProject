<?php

    namespace modules;
    
    use Shapes\Module;
    use HomePage;
//    use modules\TabPage;
//    use modules\ItemPage;
//    use modules\SearchPage;
//    use modules\MapPage;
//    use modules\AboutUsPage;
//    use modules\ContactPage;

    const PAGE_SIZE = 10;

    class Page extends Module {
        // The style to be used for the content div
        private $content_style = "background-color: hsl(0, 100%, 99%);";
        
        // Classes to be added to the container div
        private $container_class = "";
        
        public function __construct() {
            global $page_id;
            parent::__construct();
            
            switch($page_id) {
                case "home":
                    // The HomePage Module, this consists out of the following Modules:
                    // - BlogList
                    $module = $this->getHomePage();
                    break;
                
                case "login":
                case "settings":
                    // The tab page comes with 2 modules
                    // - The TabList sidebar (Which consists of the list of clickable tabs)
                    // - The TabContent module (Showing the content of the clicked tab)
                    $module = $this->getTabPage($page_id);
                    break;
                
                case "books":
                case "events":
                case "peoples":
                case "locations":
                case "specials":
                    // The item page comes with 2 modules
                    // - The ItemList sidebar (Which consists of the clickable items)
                    // - The ItemContent module (Showing the content of the clicked item)
                    $module = $this->getItemPage($page_id);
                    break;
                
                case "search":
                    // The search page comes with 2 modules:
                    // - The SearchMenu sidebar (With search options)
                    // - The SearchContent module (Showing result on the right side)
                    $module = $this->getSearchPage();
                    break;
                
                case "timeline":
                case "familytree":
                case "worldmap":
                    // The map page comes with 2 modules
                    // - The MapList sidebar (Which consists of the clickable items)
                    // - The MapContent module (Showing the content of the clicked map)
                    $module = $this->getMapPage($page_id);
                    break;
                
                case "aboutus":
                    // The AboutUs Module, this consists out of the following Modules:
                    // - Text
                    $module = $this->getAboutUsPage();
                    break;
                
                case "contact":
                    // The contact page, it comes with 2 modules:
                    // - The left column with text
                    // - The right column with the contact form
                    $module = $this->getContactPage();
                    break;
            }

            // Add the module to the page
            $this->addContent($module);
        }

        // Setting the style used for the content div
        public function setContentStyle ($style) {
            $this->content_style = $style;
        }

        // Setting the classes used for the container div
        public function setContainerClass($class) {
            // - Home: <div class="py-5 container blogs">
            // - Tab: <div class="py-5 container-fluid">
            // - Search: <div class="py-5 container-fluid">
            // - Item: <div class="container-fluid">
            // - Map: <div class="container-fluid">
            // - About us: <div class="py-5 container-fluid">
            // - Contact: <div class="py-5 container-fluid">
            $this->container_class = $class;
        }

        // Functions to return modules
        public function getHomePage() {
            // The container style for this page
            $this->setContainerClass("py-5 container blogs");      

            // Setting a custom style for this page
            $this->setContentStyle("
                            background-image: url(img/background_home.svg); 
                            background-position: top left; 
                            background-size: 100% 32px;
                            background-repeat: repeat-y");
    
            return new HomePage\HomePage();
        }

        // Functions to return modules
        public function getTabPage($params = []) {
            $this->setContainerClass("py-5 container-fluid");
            return new TabPage($params);
        }

        // Functions to return modules
        public function getItemPage($params = []) {
            $this->setContainerClass("container-fluid");
            return new ItemPage($params);
        }

        // Functions to return modules
        public function getSearchPage() {
            $this->setContainerClass("py-5 container-fluid");
            return new SearchPage();
        }

        // Functions to return modules
        public function getMapPage($params = []) {
            $this->setContainerClass("container-fluid");
            return new MapPage($params);
        }

        // Functions to return modules
        public function getAboutUsPage() {
            $this->setContainerClass("py-5 container-fluid");
            return new AboutUsPage();
        }

        // Functions to return modules
        public function getContactPage() {
            $this->setContainerClass("py-5 container-fluid");
            return new ContactPage();
        }
        
        // Get the content wrapped in the container div
        private function getContainer() {
            $content = $this->getContent();
            
            return '<div class="'.$this->container_class.'">
                '.$content.'
            </div>';
        }
        
        public function getPage() {
            // The content is wrapped in the container
            $container = $this->getContainer();
            
            // Wrap the container into the content div
            return '<div id="content" class="flex-grow-1" style="'.$this->content_style.'">
            '.$container.'
        </div>';
        }
        
        // Echo the page
        public function insertPage() {
            echo $this->getPage();
        }
    }
