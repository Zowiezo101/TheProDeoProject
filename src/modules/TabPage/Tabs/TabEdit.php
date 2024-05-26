<?php

    class TabEdit extends Tab {        
        public function __construct() {
            global $dict;
            parent::__construct();
            
            $id = "tab_edit";
            $active = isset($_SESSION["tab"]) ? 
                    // If the tab is set in the session settings, check if it's this tab
                    ($_SESSION["tab"] === $id) :
                    // If it's not set, automatically take the first tab
                    false;
            
            // Add the necessary modules in here
            $this->TabListItem([
                "id" => $id,
                "title" => $dict["settings.blog.edit"],
                "icon" => "fa-edit",
                "active" => $active
            ]);
            
            $tab_content_item = $this->TabContentItem([
                "id" => $id,
                "active" => $active
            ]);
            $tab_content_item->addContent('<form onsubmit="editBlog()">
                                    <h2>'.strtoupper($dict["settings.blog.editing"]).'</h2>
                                    <div class="form-group w-75">
                                        <select class="form-control" id="edit_blog_select" onchange="onChangeEdit()">
                                            <option selected disabled value="-1"> 
                                                '.$dict["settings.blog.select_edit"].'
                                            </option>
                                            '.$this->BlogList().'
                                        </select>
                                    </div>
                                    <!-- Title for the blog -->
                                    <div class="form-group"> 
                                        <input id="edit_blog_title" type="text" disabled class="form-control w-75" placeholder="'.$dict["settings.blog.title_placeholder"].'" required/> 
                                    </div>
                                    <!-- Text for the blog -->
                                    <div class="form-group w-75"> 
                                        <textarea id="edit_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'"></textarea> 
                                    </div>
                                    <button disabled class="btn btn-primary">
                                        '.$dict["settings.blog.edit"].'
                                    </button>
                                </form>');
        }
    }       
