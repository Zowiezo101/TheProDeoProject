<?php

    namespace Parts;
    
    use Shapes\Module;

    class TabList extends Module {
        public function getContent() {
            $content = '<!-- The column with the tabs -->
                    <div class="col-3">
                        <ul class="nav nav-pills flex-column">
                            '.parent::getContent().'
                        </ul>
                    </div>';
            
            return $content;
        }
    }
