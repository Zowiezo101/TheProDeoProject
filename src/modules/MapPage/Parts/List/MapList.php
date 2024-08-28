<?php

    namespace List;
    
    use List\ItemList;    
    use List\MapListItem;

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
                    $list_item = new MapListItem([
                        "data" => $record,
                        "base_url" => $this->base_url,
                        "active" => $active,
                        "onclick" => $this->onclick]);
                    
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
    }
