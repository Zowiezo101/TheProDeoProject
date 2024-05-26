<?php

    class ItemContent extends Module {   
        // Properties for this Module     
        protected $default;
        protected $details;
        protected $list_toggle;
        
        public function __construct($params = []) {  
            parent::__construct();
            
            // The content when no ID is given
            $this->default = new ItemDefault($params);
            
            // The content when an ID is given
            $this->details = new ItemDetails($params);
            
            // A toggle button to hide the ItemList
            $this->list_toggle = new ItemListToggle();
        }
        
        // Add a module to the list of content for the DataDefault Module
        // We're doing it from here to make it look nice
        public function addDefaultContent($module) {
            $this->default->addContent($module);
        }
        
        // Add a module to the list of content for the DataDetails Module
        // We're doing it from here to make it look nice
        public function addDetailContent($module) {
            $this->details->addContent($module);
        }
        
        public function getContent() {
            $content = '';
            
            // The selected ID
            $id = filter_input(INPUT_GET, "id");
            if ($id !== null) {
                // Show the details of this item
                $content = $this->details->getContent();
                $classes_col = "col pt-3 pb-5";
            } else {
                // Show the default content when no item is selected
                $content = $this->default->getContent();
                $classes_col = "col py-5";
            }
            
            // Wrap it all into some divs
            return '<!-- The column with the selected content -->
                    <div id="content_col" class="'.$classes_col.'">
                        <div id="content_row" class="row h-100">
                            <div id="item_content" class="col-12 h-100">
                                '.$content.'
                            </div>
                            '.$this->list_toggle->getContent().'
                        </div>
                    </div>';
        }
    }
