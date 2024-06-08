<?php
    require "src/modules/MapPage/Parts/List/MapListItem.php";

    class MapList extends ItemList {
        // Properties for this Module
        private $onclick;
        
        public function __construct($params = []) {
            parent::__construct($params);
            $this->addClasses("d-none d-md-block");
        }
        
        // Extended version of getParams
        // onClick is added, since this part is needed for maps
        public function getParams($params) {
            // Also execute the parent version of this function
            parent::getParams($params);
            
            foreach($params as $param => $value) {
                switch($param) {
                    case "onclick":
                        $this->setOnClick($value);
                        break;
                }
            }
        }

        public function setOnClick($onclick) {
            $this->onclick = $onclick;
        }
        
        public function getItemList() {
            global $page_size;
            
            $content = ''; 
            if ($this->data === null) {
                // Something went wrong
                $content = $this->getError();
            } else {                
                $list_items = [];
                for ($i = 0; $i < $page_size; $i++) {
                    if ($i < count($this->data->records)) { 
                        $record = $this->data->records[$i];
                        
                        // When the PageListItem is the currently selected item
                        $active = $this->id === $record->id;
                        
                        // Insert all the items into a PageListItem Module
                        $list_item = new MapListItem([
                            "data" => $record,
                            "base_url" => $this->base_url,
                            "active" => $active,
                            "onclick" => $this->onclick]);
                    } else {
                        // Empty PageListItems to fill up the PageList
                        $list_item = new MapListItem();
                    }
                    
                    // Add all the items into an array
                    $list_items[] = $list_item->getContent();
                }
                
                // Put it all together
                $content = implode("", $list_items);
                
            }
            
            // Wrap it into a div
            return  '
                        <!-- The list of items -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="list-group text-center" id="item_list"
                                    data-page-type="'.$this->type.'"
                                    data-page-size="'.$page_size.'"
                                    data-page-url="'.$this->base_url.'"
                                    data-id="'.$this->id.'">
                                    '.$content.'
                                </div>
                            </div>
                        </div>';
        }
    }

