<?php

    namespace SearchPage;
    
    use Shapes\Module;
    use Parts\SearchMenu;
    use Parts\SearchContent;

    class SearchPage extends Module {
        private $search_menu;
        private $search_content;
        
        public function __construct() {
            parent::__construct();
            
            // The search options
            $this->search_menu = new SearchMenu();
            
            // Results of the search
            $this->search_content = new SearchContent();
        }
        
        public function getContent() {
            $content = '<div class="row">
                    '.$this->search_menu->getContent().'
                    '.$this->search_content->getContent().'
                </div>';
            
            return $content;
        }
    }
