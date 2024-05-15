<?php

    class TabContainer extends Module{
        
        public function getContent() {
            // TODO: Get seperate classes for TabContent and the TabContainer
            $content = '
                    <!-- The column with the tab contents -->
                    <div class="col-9">
                        <div class="tab-content">
                            '.parent::getContent().'
                        </div>
                    </div>';
            
            return $content;
        }
    }

