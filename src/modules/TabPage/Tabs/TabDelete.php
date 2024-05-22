<?php

    class TabDelete extends Tab {        
        public function __construct() {
            global $dict;
            
            $id = "tab_delete";
            $active = isset($_SESSION["tab"]) ? 
                    // If the tab is set in the session settings, check if it's this tab
                    ($_SESSION["tab"] === $id) :
                    // If it's not set, automatically take the first tab
                    false;
            
            // Add the necessary modules in here
            $this->TabListItem([
                "id" => $id,
                "title" => $dict["settings.blog.delete"],
                "icon" => "fa-trash",
                "active" => $active
            ]);
            
            $tab_content_item = $this->TabContentItem([
                "id" => $id,
                "active" => $active
            ]);
            $tab_content_item->addContent('<form onsubmit="removeBlog()">
                                    <h2>'.strtoupper($dict["settings.blog.deleting"]).'</h2>
                                    <div class="form-group w-75">
                                        <select class="form-control" id="delete_blog_select" onchange="onChangeDelete()">
                                            <option selected disabled value="-1"> 
                                                '.$dict["settings.blog.select_delete"].'
                                            </option>
                                            '.$this->BlogList().'
                                        </select>
                                    </div>
                                    <!-- Text for the blog -->
                                    <div class="form-group w-75"> 
                                        <textarea id="delete_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'"></textarea> 
                                    </div>
                                    <button disabled class="btn btn-primary">
                                        '.$dict["settings.blog.delete"].'
                                    </button>
                                </form>');
        }
    }       
