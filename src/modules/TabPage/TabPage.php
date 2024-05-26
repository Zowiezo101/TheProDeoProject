<?php
    // The PHP file that contains everything we need to log in
    require "src/tools/server.php";
    
    // The Parts used by this Page
    require "src/modules/TabPage/Parts/TabList.php";
    require "src/modules/TabPage/Parts/TabListItem.php";
    require "src/modules/TabPage/Parts/TabContent.php";
    require "src/modules/TabPage/Parts/TabContentItem.php";
    
    // The different tabs
    require "src/modules/TabPage/Tabs/Tab.php";
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
    
    $MODE_SETTINGS = "settings";
    $MODE_LOGIN = "login";

    class TabPage extends Module {
        private $tab_list;
        private $tab_content;
        
        public function __construct($mode) {
            global $MODE_LOGIN, $MODE_SETTINGS, 
                    $TAB_LOGIN, $TAB_LOGOUT,
                    $TAB_ADD, $TAB_EDIT, $TAB_DELETE;
            parent::__construct();
            
            // Add the necessary modules in here
            $this->tab_list = new TabList();
            $this->tab_content = new TabContent();
            
            // This will redirect the page if needed to the correct page and mode
            // If we are in settings mode and not logged in, go to login mode
            // If we are in login mode and are logged in, go to settings mode
            $this->checkMode($mode);
            
            if ($mode === $MODE_LOGIN) {
                // Not yet logged in, show login page
                $this->addTab($TAB_LOGIN);
            } else if ($mode === $MODE_SETTINGS) {
                // We are logged in, show settings page
                $this->addTab($TAB_ADD);
                $this->addTab($TAB_EDIT);
                $this->addTab($TAB_DELETE);
                $this->addTab($TAB_LOGOUT);
            }
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
        
        private function checkMode($mode) {
            global $MODE_LOGIN, $MODE_SETTINGS;
    // TODO: Make sure the tabs stay selected when refreshing the page
            
            // Are we logged in?
            $logged_in = (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
            
            if($mode === $MODE_LOGIN && $logged_in) {
                // We are logged in, go to settings page
                $url = "settings";
                if( headers_sent() ) { 
                    echo("<script>location.href='$url'</script>"); 
                } else { 
                    header("Location: $url"); 
                }
                exit;
            } else if ($mode === $MODE_SETTINGS && !$logged_in) {
                // We are not logged in, go to login page
                $url = "login";
                if( headers_sent() ) { 
                    echo("<script>location.href='$url'</script>"); 
                } else { 
                    header("Location: $url"); 
                }
                exit;
            }
        }
        
        public function getContent() {
            $content = '<div class="row">
                    '.$this->tab_list->getContent().'
                    '.$this->tab_content->getContent().'
                </div>';
            
            return $content;
        }
    }