<?php

    namespace Tabs;

    class TabLogout extends Tab {        
        public function __construct() {
            global $dict;
            parent::__construct();
            
            $id = "tab_logout";
            $active = isset($_SESSION["tab"]) ? 
                    // If the tab is set in the session settings, check if it's this tab
                    ($_SESSION["tab"] === $id) :
                    // If it's not set, automatically take the first tab
                    false;
            
            // Add the necessary modules in here
            $this->createTabListItem([
                "id" => $id,
                "title" => $dict["settings.logout"],
                "icon" => "fa-sign-out",
                "active" => $active
            ]);
            
            $tab_content_item = $this->createTabContentItem([
                "id" => $id,
                "active" => $active
            ]);
            $tab_content_item->addContent('<form action="settings" method="post" name="logout">
                                    <button class="btn btn-danger" type="submit" name="logout">
                                        <i class="fa fa-sign-out text-muted fa-lg"></i> 
                                        '.strtoupper($dict["settings.logout"]).'
                                    </button>
                                </form>');
        }
    }       
