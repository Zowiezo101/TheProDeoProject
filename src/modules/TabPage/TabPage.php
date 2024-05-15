<?php
    require "src/modules/TabPage/Tab.php";
    require "src/modules/TabPage/TabList.php";
    require "src/modules/TabPage/TabListItem.php";
    require "src/modules/TabPage/TabContainer.php";
    require "src/modules/TabPage/TabContent.php";

    class TabPage extends Module {
        private $tabs = [];
        
        public function Tab($params) {
            return new Tab($params);
        }
        
        public function addTab($tab) {
            if ($tab instanceof Tab) {
                array_push($this->tabs, $tab);
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            // Convert the added tabs to a tablist and tabcontent
            $tab_list = new TabList();
            $tab_container = new TabContainer();
            
            foreach($this->tabs as $idx => $tab) {
                // Get all the TabListItems and put them in the TabList
                // Set the first tab as active
                $tab_list_item = $tab->getTabListItem();
                $tab_list_item->setActive($idx === 0);
                $tab_list->addContent($tab_list_item);
                
                // Get all the TabContents and put them in the TabContent
                $tab_content = $tab->getTabContent();
                $tab_content->setActive($idx === 0);
                
                $tab_container->addContent($tab_content);
            }
            
            $content = '<div class="row">
                    '.$tab_list->getContent().'
                    '.$tab_container->getContent().'
                </div>';
            
            return $content;
        }
    }