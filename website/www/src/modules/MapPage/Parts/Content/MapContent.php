<?php

    namespace Content;
    
    use Content\ItemContent;
    use Content\LoadingScreen;
    use Content\SmallScreen;

    class MapContent extends ItemContent {
        // Properties for this Module
        private $loading_screen;
        private $small_screen;
        
        public function __construct($params = []) {
            parent::__construct($params);
            
            // A loading screen
            $this->loading_screen = new LoadingScreen($params);
            
            // A screen only visible in case of small windows
            $this->small_screen = new SmallScreen($params);
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

            // Do we need to pan to an item?
            $pan_id = filter_input(INPUT_GET, "panId");
            $data_pan_id = (isset($pan_id)) ? ' data-pan-id="'.$pan_id.'"' : '';
            
            // Wrap it all into some divs
            return '<!-- The column with the selected content -->
                    <div id="content_col" class="'.$classes_col.'">
                        <div id="content_row" class="row h-100 d-none d-md-flex">
                            <div id="item_content" class="col-12 h-100"'.$data_pan_id.'>
                                '.$content.'
                            </div>
                            '.$this->loading_screen->getContent().'
                            '.$this->list_toggle->getContent().'
                        </div>
                        '.$this->small_screen->getContent().'
                    </div>';
        }
    }
