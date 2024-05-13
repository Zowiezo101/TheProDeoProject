<?php
    require "src/modules/Shapes/Descr.php";
    require "src/modules/Shapes/Table.php";

    class Module {
        protected $content = [];
        
        // Add a module to the list of content
        public function addContent($module) {
            if (true) {
                // TODO: Check if this module is a valid module to be added
                array_push($this->content, $module);
            } else {
                // TODO: Throw an error when the module isn't a valid module
            }
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
                // insert the contents of this module
                $content = $content.$module->getContent();
            }
            
            return $content;
        }
    }

