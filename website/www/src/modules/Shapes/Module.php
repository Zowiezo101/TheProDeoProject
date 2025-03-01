<?php

    namespace Shapes;

    class Module {
        protected $content;
        
        public function __construct() {
            $this->content = [];
        }
        
        // Add a module to the list of content
        public function addContent($module) {
            $this->content[] = $module;
        }
        
        protected function getData($type) {
            // Options for the itemlist    
            $data = getItems($type);
            
            if ($this->checkData($data) === false) {
                $data = null;
            }   
            
            return $data;
        }
        
        // Check data from the database for errors
        public function checkData($data) {
            $error = false;
            
            if (isset($data->error) && ($data->error !== "")) {
                $error = true;
            } else if ((!isset($data->records)) || ($data->records === [])) {
                $error = true;
            }
            
            return $error === false;
        }
        
        // Standard div to show a database error
        public function getError() {
            global $dict;
            
            return '
            <div class="row">
                <div class="col-12 text-center">
                    <h1>'.$dict["settings.database_err"].'</h1>
                </div>
            </div>';
        }
        
        // Return all the contents of this module
        public function getContent() {
            $content = "";
            
            // Get all the modules and insert their content
            foreach($this->content as $module) {
                // Check if the module is text or Module class
                if ($module instanceof Module) {
                    // Insert the contents of this module
                    $content = $content.$module->getContent();
                } else {
                    // Insert the contents
                    $content .= $module;
                }
            }
            
            return $content;
        }
    }
