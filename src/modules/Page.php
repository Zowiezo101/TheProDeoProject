<?php
    // Database access and the base class
    require "src/tools/database.php";
    require "src/modules/Shapes/Module.php";
    
    // Different kinds of pages
    require "src/modules/HomePage/HomePage.php";
    require "src/modules/TabPage/TabPage.php";
//    require "src/modules/SearchPage/SearchPage.php";
//    require "src/modules/ItemPage/ItemPage.php";
//    require "src/modules/MapPage/MapPage.php";
//    require "src/modules/AboutUsPage/AboutUsPage.php";
//    require "src/modules/ContactPage/ContactPage.php";

    class Page extends Module {
        // The style to be used for the content div
        private $content_style = "background-color: hsl(0, 100%, 99%);";
        
        // Classes to be added to the container div
        private $container_class = "";

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
        public function HomePage() {
            $this->setContainerClass("py-5 container blogs");
            return new HomePage();
        }

        // Functions to return modules
        public function TabPage() {
            $this->setContainerClass("py-5 container-fluid");
            return new TabPage();
        }

        // Functions to return modules
        public function SearchPage() {
            $this->setContainerClass("py-5 container-fluid");
            return new SearchPage();
        }

        // Functions to return modules
        public function ItemPage($params = []) {
            $this->setContainerClass("container-fluid");
            return new ItemPage($params);
        }

        // Functions to return modules
        public function MapPage($params = []) {
            $this->setContainerClass("container-fluid");
            return new MapPage($params);
        }

        // Functions to return modules
        public function AboutUsPage() {
            $this->setContainerClass("py-5 container-fluid");
            return new AboutUsPage();
        }

        // Functions to return modules
        public function ContactPage() {
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