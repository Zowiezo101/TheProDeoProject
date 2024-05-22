<?php
    // The Parts used by this Page
    require "src/modules/TabPage/Parts/Tab.php";
    require "src/modules/TabPage/Parts/TabList.php";
    require "src/modules/TabPage/Parts/TabListItem.php";
    require "src/modules/TabPage/Parts/TabContent.php";
    require "src/modules/TabPage/Parts/TabContentItem.php";
    
    // The different tabs
    require "src/modules/TabPage/Tabs/TabAdd.php";
    require "src/modules/TabPage/Tabs/TabEdit.php";
    require "src/modules/TabPage/Tabs/TabDelete.php";
    require "src/modules/TabPage/Tabs/TabLogout.php";
    require "src/modules/TabPage/Tabs/TabLogin.php";

    $TAB_LOGIN = "login";
    $TAB_ADD = "add";
    $TAB_EDIT = "edit";
    $TAB_DELETE = "delete";
    $TAB_LOGOUT = "logout";

    class TabPage extends Module {
        private $tab_list;
        private $tab_content;
        
        public function __construct() {
            // Add the necessary modules in here
            $this->tab_list = new TabList();
            $this->tab_content = new TabContent();
        }
        
        public function addTab($tab) {
            global $TAB_ADD, $TAB_EDIT, $TAB_DELETE,
                    $TAB_LOGIN, $TAB_LOGOUT;
            
            // Five tabs that can be chosen from
            switch($tab) {
                // The tab for adding blogs
                case $TAB_ADD:
                    $tab_item = new TabAdd();
                    break;
                // The tab for editing blogs
                case $TAB_EDIT:
                    $tab_item = new TabEdit();
                    break;
                // The tab for deleting blogs
                case $TAB_DELETE:
                    $tab_item = new TabDelete();
                    break;
                // The tab for logging out
                case $TAB_LOGOUT:
                    $tab_item = new TabLogout();
                    break;
                // The tab for logging in
                case $TAB_LOGIN:
                    $tab_item = new TabLogin();
                    break;
            }
            
            // Adding an item to the TabList
            $tab_list_item = $tab_item->getTabListItem();
            $this->tab_list->addContent($tab_list_item);
            
            // Adding an item to the TabContent
            $tab_content_item = $tab_item->getTabContentItem();
            $this->tab_content->addContent($tab_content_item);            
        }
        
        public function getContent() {
            $content = '<div class="row">
                    '.$this->tab_list->getContent().'
                    '.$this->tab_content->getContent().'
                </div>';
            
            return $content;
        }
    }