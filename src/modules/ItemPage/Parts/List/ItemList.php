<?php
    require "src/modules/ItemPage/Parts/List/ItemListItem.php";
    require "src/modules/ItemPage/Parts/List/ItemListToggle.php";
    require "src/modules/ItemPage/Parts/List/ItemSearch.php";
    require "src/modules/ItemPage/Parts/List/ItemPages.php";

    $SORT_0_to_9 = "0_to_9";
    $SORT_9_to_0 = "9_to_0";
    $SORT_A_to_Z = "a_to_z";
    $SORT_Z_to_A = "z_to_a";

    class ItemList extends Module {
        // Properties for this Module
        protected $type;
        protected $base_url;
        private $classes = "col-md-4 col-lg-2 py-3 shadow";
        
        // Info from the database
        protected $id;
        protected $data;
        
        // Modules used in this Module
        private $item_search;
        private $item_pages;
        
        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
            
            // Get the database information
            $this->id = $this->getId();
            $this->data = $this->getData();
            $count = $this->getCount();
            
            // Add the necessary modules in here
            $this->item_search = new ItemSearch();
            $this->item_pages = new ItemPages($count);
        }
        
        public function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "type":
                        $this->setType($value);
                        break;
                    case "base_url":
                        $this->setBaseUrl($value);
                        break;
                }
            }
        }

        public function setType($type) {
            $this->type = $type;
        }

        public function setBaseUrl($url) {
            $this->base_url = $url;
        }
        
        private function getId() {
            $id = filter_input(INPUT_GET, "id");
            return $id;
        }
        
        private function getData() {
            global $SORT_0_to_9;
            
            // Options for the itemlist
            $search = isset($_SESSION["search"]) ? htmlspecialchars($_SESSION["search"]) : "";
            $sort = isset($_SESSION["sort"]) ? $_SESSION["sort"] : $SORT_0_to_9;
            $page = isset($_SESSION["page"]) ? $_SESSION["page"] : 0;
    
            $data = getPage($this->type, $page, [
                "filter" => $search,
                "sort" => $sort
            ]);
            
            if ($this->checkData($data) === false) {
                $data = null;
            }   
            
            return $data;
        }
        
        private function getCount() {
            $count = 0;
            if (isset($this->data->paging) && $this->data->paging !== "") {
                // Get the amount of pages
                $count = $this->data->paging;
            }
            
            return $count;
        }
        
        public function addClasses($classes) {
            // Add some extra classes to the item_bar
            $this->classes .= " ".$classes;
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
                        $list_item = new ItemListItem([
                            "data" => $record,
                            "base_url" => $this->base_url,
                            "active" => $active]);
                    } else {
                        // Empty PageListItems to fill up the PageList
                        $list_item = new ItemListItem();
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
        
        public function getContent() {            
            // The PageList content
            $content = '<!-- The column with the menu -->
                    <nav id="item_bar" class="'.$this->classes.'">
                        '.$this->item_search->getContent().'
                        '.$this->getItemList().'
                        '.$this->item_pages->getContent().'
                    </nav>';
            
            return $content;
        }
    }

