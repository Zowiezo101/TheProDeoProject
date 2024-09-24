<?php

    namespace List;
    
    use Shapes\Module;

    class ItemListItem extends Module {        
        // Properties for this Module
        protected $data;
        protected $classes = "list-group-item list-group-item-action";
        protected $href;
        protected $columns;
        protected $value;
        
        public function __construct($params=[]) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        public function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "data":
                        $this->setData($value);
                        break;
                    case "base_url":
                        $this->setBaseUrl($value);
                        break;
                    case "columns":
                        $this->setColumns($value);
                        break;
                    case "active":
                        $this->setActive($value);
                        break;
                }
            }
        }

        public function setData($data) {
            $this->data = $data;
        }

        public function setBaseUrl($url) {
            $this->href = setParameters($url);
        }

        public function setColumns($columns) {
            $this->columns = $columns;
        }

        public function setActive($active) {
            if ($active === true) {
                $this->classes .= " active";
            }
        }

        public function setBehavior($behavior) {
            if (true) {
                // TODO: Check this is a valid value
                $this->behavior = $behavior;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {            
            // The PageListItem, this is an item in the PageList.
            // The PageList is a sidebar used for the item and map pages
            // and contains a list of clickable items to view other items or maps
            $record = $this->data;
            
            // The link to refer to
            $href = "$this->href/{$record->id}";

            // The name to be shown in the sidebar
            $value = $record->name;
            if (isset($record->aka) && $record->aka != "") {
                // The AKA value is only given when searching for a name and there is a hit
                // with an AKA value.
                $value = $value." ({$record->aka})";
            }

            // The list-group-item style remains, but make sure to remove the borders
            // This is because the underlying items also have this style and those
            // have the proper border radius since they are actually in a group.
            $content = '<a style="border-width: 0px;" href="'.$href.'" class="'.$this->classes.'">'.$value.'</a>';
            
            $columns_list = [];
            foreach($this->columns as $column) {
                $columns_list[] = '<td class="d-none">'.$record->$column.'</td>';
            }
            
            $columns = implode("", $columns_list);
            
            // Put it in the table data format
            return '
                    <tr class="p-0 '.$this->classes.'" height="51px">
                        <td class="p-0 '.$this->classes.'">'.$content.'</td>
                        '.$columns.'
                    </tr>';
        }
    }
