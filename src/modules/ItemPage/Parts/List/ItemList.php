<?php

    namespace List;
    
    use Shapes\Module;
    use List\ItemSearch;
    use List\ItemPages;
    use List\ItemListItem;

    const SORT_0_TO_9 = "0_to_9";
    const SORT_9_TO_0 = "9_to_0";
    const SORT_A_TO_Z = "a_to_z";
    const SORT_Z_TO_A = "z_to_a";

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
            
            // Add the necessary modules in here
            $this->item_search = new ItemSearch();
            $this->item_pages = new ItemPages();
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
            $id = intval(filter_input(INPUT_GET, "id"));
            return $id;
        }
        
        private function getData() {
            // Options for the itemlist    
            $data = getItems($this->type);
            
            if ($this->checkData($data) === false) {
                $data = null;
            }   
            
            return $data;
        }
        
        public function addClasses($classes) {
            // Add some extra classes to the item_bar
            $this->classes .= " ".$classes;
        }
        
        public function getItemList() {
            // Options for the itemlist
            $search = isset($_SESSION["search"]) ? htmlspecialchars($_SESSION["search"]) : "";
            $sort = isset($_SESSION["sort"]) ? $_SESSION["sort"] : SORT_0_TO_9;
            $page = isset($_SESSION["page"]) ? $_SESSION["page"] : 0;
            
            $content = ''; 
            if ($this->data === null) {
                // Something went wrong
                $content = $this->getError();
            } else {                
                $list_items = [];
                for ($i = 0; $i < count($this->data->records); $i++) {
                    $record = $this->data->records[$i];

                    // When the PageListItem is the currently selected item
                    $active = $this->id === $record->id;

                    // Insert all the items into a PageListItem Module
                    $list_item = new ItemListItem([
                        "data" => $record,
                        "base_url" => $this->base_url,
                        "active" => $active]);

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
                                <div class="list-group text-center" style="height:510px; overflow:hidden"> 
                                    <table class="table-borderless w-100" id="item_list"
                                        data-page-type="'.$this->type.'"
                                        data-page-url="'.$this->base_url.'"
                                        data-table-sort="'.$sort.'"
                                        data-table-search="'.$search.'"
                                        data-table-page="'.$page.'"
                                        data-id="'.$this->id.'">
                                            <thead class="d-none">
                                                <!-- The name that is being displayed -->
                                                <th>name</th>
                                                <!-- Invisible order_id column for sorting -->
                                                <th>order_id</th>
                                            </thead>
                                            <tbody>'.$content.'</tbody>
                                    </table>
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
