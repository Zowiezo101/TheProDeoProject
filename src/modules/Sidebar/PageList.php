<?php
    require "src/modules/Sidebar/Searchbar.php";
    require "src/modules/Sidebar/PageListItem.php";
    require "src/modules/Sidebar/Pagination.php";
    require "src/modules/Sidebar/ToggleMenu.php";
    
    $SORT_0_to_9 = "0_to_9";
    $SORT_9_to_0 = "9_to_0";
    $SORT_A_to_Z = "a_to_z";
    $SORT_Z_to_A = "z_to_a";
    
    class PageList extends Module {
        private $type;
        private $base_url;
        private $hide;
        private $id;
        
        private $search;
        private $pagination;
        
        public function __construct($params = []) {    
            // The Search Bar
            $this->search = new Searchbar();
            
            // The Pagination
            $this->pagination = new Pagination();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "type":
                        $this->setType($value);
                        break;
                    case "base_url":
                        $this->setBaseUrl($value);
                        break;
                    case "hide":
                        $this->setHide($value);
                        break;
                    case "id":
                        $this->setId($value);
                        break;
                }
            }
        }

        public function setType($type) {
            if (true) {
                // TODO: Check this is a valid value
                $this->type = $type;
            } else {
                // TODO: Throw an error
            }
        }

        public function setBaseUrl($url) {
            if (true) {
                // TODO: Check this is a valid value
                $this->base_url = $url;
            } else {
                // TODO: Throw an error
            }
        }

        public function setHide($hide) {
            if (true) {
                // TODO: Check this is a valid value
                $this->hide = $hide;
            } else {
                // TODO: Throw an error
            }
        }

        public function setId($id) {
            if (true) {
                // TODO: Check this is a valid value
                $this->id = $id;
            } else {
                // TODO: Throw an error
            }
        }
        
        private function getPageList() {
            global $page_size, $SORT_0_to_9;
            
            // Options for the pagelist
            $search = isset($_SESSION["search"]) ? htmlspecialchars($_SESSION["search"]) : "";
            $sort = isset($_SESSION["sort"]) ? $_SESSION["sort"] : $SORT_0_to_9;
            $page = isset($_SESSION["page"]) ? $_SESSION["page"] : 0;
            
            // The pagination for the sidebar.
            // Updates are done in javascript, but the initial loading is done in PHP
            $data = getPage($this->type, $page, [
                "filter" => $search,
                "sort" => $sort
            ]);
            
            $content = ''; 
            if ($this->checkData($data) === false) {
                // Something went wrong
                $content = $this->getError();
            } else {
                // Get the amount of pages
                $count = $data->paging;
                $this->pagination->setCount($count);
                
                $list_items = [];
                for ($i = 0; $i < $page_size; $i++) {
                    if ($i < count($data->records)) { 
                        $record = $data->records[$i];
                        
                        // When the PageListItem is the currently selected item
                        $active = $this->id === $record->id;
                        
                        // Insert all the items into a PageListItem Module
                        $list_item = new PageListItem([
                            "data" => $record,
                            "base_url" => $this->base_url,
                            "active" => $active]);
                    } else {
                        // Empty PageListItems to fill up the PageList
                        $list_item = new PageListItem();
                    }
                    
                    // Add all the items into an array
                    array_push($list_items, $list_item->getContent());
                }
                
                // Put it all together
                $content = implode("", $list_items);
                
            }
            
            // Wrap it into a div
            return  '
                        <!-- The list of items -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="list-group text-center" id="item_list">
                                    '.$content.'
                                </div>
                            </div>
                        </div>';
        }
        
        public function getContent() {
            // The classes to be used for the PageList
            // If the PageList is to be hidden for small windows, d-none and
            // d-md-block are added.
            $class = "col-md-4 col-lg-2 py-3 shadow";
            if (isset($this->hide) && ($this->hide === true)) {
                $class = $class . "  d-none d-md-block";
            }
            
            // The PageList content
            $content = '<!-- The column with the menu -->
                    <nav id="item_bar" class="'.$class.'">
                        '.$this->search->getContent().'
                        '.$this->getPageList().'
                        '.$this->pagination->getContent().'
                    </nav>';
            
            return $content;
        }
    }

