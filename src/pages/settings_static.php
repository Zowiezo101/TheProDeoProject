<?php 
    require "src/tools/server.php";
    require "src/tools/database.php";
    
    $data = getItems($TYPE_BLOG);
    
    // Are we already logged in?
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        // Redirect to login page
        $URL = "login";
        if( headers_sent() ) { 
            echo("<script>location.href='$URL'</script>"); 
        } else { 
            header("Location: $URL"); 
        }
        exit;
    }
    
    // TODO: Make sure the tabs stay selected when refreshing the page
    function insertTabList() {
        // The list of tabs for this page
        global $dict;
        $list = 
            '<li class="nav-item">
                <a href="" class="active nav-link" data-toggle="pill" data-target="#tabadd">
                    <i class="fa fa-plus text-muted fa-lg"></i> 
                    '.strtoupper($dict["settings.blog.add"]).' 
                </a> 
            </li>
            <li class="nav-item"> 
                <a href="" class="nav-link" data-toggle="pill" data-target="#tabedit">
                    <i class="fa fa-edit text-muted fa-lg"></i> 
                    '.strtoupper($dict["settings.blog.edit"]).'
                </a> 
            </li>
            <li class="nav-item"> 
                <a href="" class="nav-link" data-toggle="pill" data-target="#tabdelete">
                    <i class="fa fa-trash text-muted fa-lg"></i> 
                    '.strtoupper($dict["settings.blog.delete"]).' 
                </a> 
            </li>
            <li class="nav-item"> 
                <form action="settings" method="post" name="logout">
                    <button class="btn btn-link nav-link" type="submit" name="logout">
                        <i class="fa fa-sign-out text-muted fa-lg"></i> 
                        '.strtoupper($dict["settings.logout"]).'
                    </button>
                </form>
            </li>';
        return $list;
        
    }
    
    function insertTabContent() {
        $tabs = "";
        
        $tabs = $tabs.insertTabAdd();
        $tabs = $tabs.insertTabEdit();
        $tabs = $tabs.insertTabDelete();
        
        return $tabs;
    }
    
    function insertTabAdd() {
        // TODO: When clicking submit without having filled in editordata, error occurs because it's not focusable
        global $dict;
        
        $tab = 
            '<div class="tab-pane fade show active" id="tabadd" role="tabpanel">
                <form onsubmit="addBlog()">
                    <h2>'.strtoupper($dict["settings.blog.adding"]).'</h2>
                    <!-- Title for the blog -->
                    <div class="form-group"> 
                        <label>'.$dict["settings.blog.title"].'</label> 
                        <input id="add_blog_title" type="text" class="form-control w-75" placeholder="'.$dict["settings.blog.title_placeholder"].'" required/> 
                    </div>
                    <!-- Text for the blog -->
                    <div class="form-group w-75"> 
                        <label>'.$dict["settings.blog.text"].'</label> 
                        <textarea id="add_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'" required name="editordata"></textarea> 
                    </div>
                    <button class="btn btn-primary">'.$dict["settings.blog.add"].'</button>
                </form>
            </div>';
        return $tab;
    }
    
    function insertTabEdit() {
        global $dict;
        
        $tab = 
            '<div class="tab-pane fade" id="tabedit" role="tabpanel">
                <form onsubmit="editBlog()">
                    <h2>'.strtoupper($dict["settings.blog.editing"]).'</h2>
                    <div class="form-group w-75">
                        <select class="form-control" id="edit_blog_select" onchange="onChangeEdit()">
                            <option selected disabled value="-1"> 
                                '.$dict["settings.blog.select_edit"].'
                            </option>
                            '.insertBlogs().'
                        </select>
                    </div>
                    <!-- Title for the blog -->
                    <div class="form-group"> 
                        <input id="edit_blog_title" type="text" disabled class="form-control w-75" placeholder="'.$dict["settings.blog.title_placeholder"].'" required/> 
                    </div>
                    <!-- Text for the blog -->
                    <div class="form-group w-75"> 
                        <textarea id="edit_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'" required></textarea> 
                    </div>
                    <button disabled class="btn btn-primary">
                        '.$dict["settings.blog.edit"].'
                    </button>
                </form>
            </div>';
                
        return $tab;
    }
    
    function insertTabDelete() {
        global $dict;
        
        $tab = 
            '<div class="tab-pane fade" id="tabdelete" role="tabpanel">
                <form onsubmit="removeBlog()">
                    <h2>'.strtoupper($dict["settings.blog.deleting"]).'</h2>
                    <div class="form-group w-75">
                        <select class="form-control" id="delete_blog_select" onchange="onChangeDelete()">
                            <option selected disabled value="-1"> 
                                '.$dict["settings.blog.select_delete"].'
                            </option>
                            '.insertBlogs().'
                        </select>
                    </div>
                    <!-- Text for the blog -->
                    <div class="form-group w-75"> 
                        <textarea id="delete_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'" required></textarea> 
                    </div>
                    <button disabled class="btn btn-primary">
                        '.$dict["settings.blog.delete"].'
                    </button>
                </form>
            </div>';
                
        return $tab;
    }
    
    function insertBlogs() {
        global $data;
        
        // Collect the blogs if there are any
        $blogs = [];
        if (isset($data->records) && ($data->records !== [])) {
            $blogs = $data->records;
        }
        
        // The HTML to insert
        $options = "";
        
        // Insert the blogs
        foreach ($blogs as $blog) {
                
            // The date of the blog, saved in UTC timezone
            $timezone = new DateTimeZone("UTC");

            // A datetime object
            $datetime = new DateTime($blog->date, $timezone);

            // The date and time, formatted to a string
            $date = $datetime->format("d-m-Y H:i:s");
                
            $options = $options.'
            <option value="'.$blog->id.'">
                '.$date.' - '.$blog->title.'
            </option>';
        }
        
        return $options;
    }
?>