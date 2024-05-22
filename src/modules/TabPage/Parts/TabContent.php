<?php

    class TabContent extends Module{
        public function getContent() {            
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

