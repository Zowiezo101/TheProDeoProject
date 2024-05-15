<?php
    // Database access and the base class
    require "src/tools/database.php";
    require "src/modules/Shapes/Module.php";
    
    // Different kinds of pages
    require "src/modules/ContactPage/ContactPage.php";
    require "src/modules/SearchPage/SearchPage.php";
    require "src/modules/DataPage/DataPage.php";
    require "src/modules/TabPage/TabPage.php";

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
            // - Settings & Login <div class="container-fluid py-5">
            // - Item: <div class="container-fluid">
            // - Map: <div class="container-fluid">
            // - Search: <div class="py-5 container-fluid">
            // - About us: <div class="py-5 container-fluid">
            // - Contact: <div class="py-5 container-fluid">
            $this->container_class = $class;
        }

        // Functions to return modules
        public function ContactPage($params = []) {
            return new ContactPage($params);
        }

        // Functions to return modules
        public function DataPage($params = []) {
            return new DataPage($params);
        }

        // Functions to return modules
        public function TabPage() {
            return new TabPage();
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
            return '
        <div id="content" class="flex-grow-1" style="'.$this->content_style.'">
            '.$container.'
        </div>';
        }
        
        // Echo the page
        public function insertPage() {
            echo $this->getPage();
        }
    }