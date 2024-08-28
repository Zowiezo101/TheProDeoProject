<?php

    namespace List;
    
    use List\ItemListItem;

    const ONCLICK_LOADING = "loading";
    const ONCLICK_SHOWONMAP = "showonmap";

    class MapListItem extends ItemListItem {
        // Properties for this Module
        private $onclick;
        
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
        
        public function getContent() {
            global $dict;
            
            // The PageListItem, this is an item in the PageList.
            // The PageList is a sidebar used for the item and map pages
            // and contains a list of clickable items to view other items or maps
            $record = $this->data;
                
            // The link to refer to
            $href = "$this->href/{$record->id}";
            // Void the href (so the link doesn't lead anywhere when clicking it
            if ($this->onclick === ONCLICK_SHOWONMAP) {
                $href = 'javascript: void(0)';
            }

            // The name to be shown in the sidebar
            $value = $record->name;
            if ($value == "timeline.global") {
                // In case of the timeline, there is a global timeline
                // consisting of all the events
                $value = $dict[$value];
            }
                
            if (isset($record->aka) && $record->aka != "") {
                // The AKA value is only given when searching for a name and there is a hit
                // with an AKA value.
                $value = $value." ({$record->aka})";
            }
                
            $onclick = '';
            if ($this->onclick === ONCLICK_LOADING) {
                // When onclick is set, an action is executed when clicking the button
                $onclick = 'onclick="showLoadingScreen()"';
            } else {
                $onclick = "onclick=\"getLinkToMap({$record->id})\"";
            }

            $content = '<a href="'.$href.'" class="'.$this->classes.'" '.$onclick.'>'.$value.'</a>';
            
            // Put it in the table data format
            return '
                    <tr height="51px">
                        <td  class="p-0">'.$content.'</td>
                        <td class="d-none">'.$record->order_id.'</td>
                    </tr>';
        }
    }
