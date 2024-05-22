<?php

    class TabAdd extends Tab {        
        public function __construct() {
            global $dict;
            
            $id = "tab_add";
            $active = isset($_SESSION["tab"]) ? 
                    // If the tab is set in the session settings, check if it's this tab
                    ($_SESSION["tab"] === $id) :
                    // If it's not set, automatically take the first tab
                    true;
            
            // Add the necessary modules in here
            $this->TabListItem([
                "id" => $id,
                "title" => $dict["settings.blog.add"],
                "icon" => "fa-plus",
                "active" => $active
            ]);
            
            $tab_content_item = $this->TabContentItem([
                "id" => $id,
                "active" => $active
            ]);
            $tab_content_item->addContent('<form onsubmit="addBlog()">
                                    <h2>'.strtoupper($dict["settings.blog.adding"]).'</h2>
                                    <!-- Title for the blog -->
                                    <div class="form-group"> 
                                        <label>'.$dict["settings.blog.title"].'</label> 
                                        <input id="add_blog_title" type="text" class="form-control w-75" placeholder="'.$dict["settings.blog.title_placeholder"].'" required/> 
                                    </div>
                                    <!-- Text for the blog -->
                                    <div class="form-group w-75"> 
                                        <label>'.$dict["settings.blog.text"].'</label> 
                                        <textarea id="add_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'" name="editordata"></textarea> 
                                    </div>
                                    <button class="btn btn-primary">'.$dict["settings.blog.add"].'</button>
                                </form>');
        }
    }