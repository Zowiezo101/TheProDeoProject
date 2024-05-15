<?php

    $TAB_LOGIN = "login";
    $TAB_ADD = "add";
    $TAB_EDIT = "edit";
    $TAB_DELETE = "delete";
    $TAB_LOGOUT = "logout";

    class Tab extends Module {
        private $tablistitem;
        private $tabcontent;
        
        public function __construct($params = []) {    
            // The TabListItem Module
            $this->tablistitem = new TabListItem();
            
            // The TabContent Module
            $this->tabcontent = new TabContent();
            
            // Parse the parameters given       
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "id":
                        $this->setId($value);
                        break;
                    case "title":
                        $this->setTitle($value);
                        break;
                    case "icon":
                        $this->setIcon($value);
                        break;
                    case "content":
                        $this->setContent($value);
                        break;
                }
            }
        }

        public function setId($id) {
            if (true) {
                // TODO: Check this is a valid value
                $this->tablistitem->setId($id);
                $this->tabcontent->setId($id);
            } else {
                // TODO: Throw an error
            }
        }

        public function setTitle($title) {
            if (true) {
                // TODO: Check this is a valid value
                $this->tablistitem->setTitle($title);
            } else {
                // TODO: Throw an error
            }
        }

        public function setIcon($icon) {
            if (true) {
                // TODO: Check this is a valid value
                $this->tablistitem->setIcon($icon);
            } else {
                // TODO: Throw an error
            }
        }

        public function setContent($content) {
            global $TAB_LOGIN,
                   $TAB_ADD,
                   $TAB_EDIT,
                   $TAB_DELETE,
                   $TAB_LOGOUT;
            
            if (array_search($content, [$TAB_LOGIN, $TAB_ADD, 
                                        $TAB_EDIT, $TAB_DELETE,
                                        $TAB_LOGOUT]) !== false) {
                // TODO: Check this is a valid value
                switch($content) {
                    case $TAB_LOGIN:
                        $content = $this->TabLogin();
                        $this->tabcontent->setContent($content);
                        $this->tabcontent->setExtraClasses("col-lg-6 col-10");
                        break;
                    
                    case $TAB_ADD:
                        $content = $this->TabAdd();
                        $this->tabcontent->setContent($content);
                        break;
                    
                    case $TAB_EDIT:
                        $content = $this->TabEdit();
                        $this->tabcontent->setContent($content);
                        break;
                    
                    case $TAB_DELETE:
                        $content = $this->TabDelete();
                        $this->tabcontent->setContent($content);
                        break;
                    
                    case $TAB_LOGOUT:
                        $content = $this->TabLogout();
                        $this->tabcontent->setContent($content);
                        break;
                        
                }
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getTabListItem() {
            return $this->tablistitem;
        }
        
        public function getTabContent() {
            return $this->tabcontent;
        }
    
        private function TabLogin() {
            global $dict, $login_err,
                    $param_username, $username_err, $username,
                    $param_password1, $password1_err, $password1;

            // If there is an error message, include it in this tab
            $message = (!empty($login_err)) ? '<div class="alert alert-danger">' . $dict[$login_err] . '</div>' : '';

            $username_value = $param_username;
            $username_feedback = (!empty($username_err)) ? 
                                    $dict[$username_err] : 
                                    "";
            $username_class = (!empty($username_err)) ? 
                                    "is-invalid" : 
                                    ((!empty($username)) ? "is-valid" : "");

            $password_value = $param_password1;
            $password_feedback = (!empty($password1_err)) ? 
                                    $dict[$password1_err] : 
                                    "";
            $password_class = (!empty($password1_err)) ? 
                                    "is-invalid" : 
                                    ((!empty($password1)) ? "is-valid" : "");

            $tab =  $message.'
                    <form class="text-left" action="login" method="post" name="login">
                        <div class="form-group">
                            <label for="email_username">'.$dict["settings.username"]. '</label>
                            <input type="text" class="form-control '.$username_class.'" name="username" id="email_username" value="'.$username_value.'">
                            <span class="invalid-feedback">'.$username_feedback.'</span>
                        </div>
                        <div class="form-group">
                            <label for="password">'.$dict["settings.password"].'</label>
                            <input type="password" class="form-control '.$password_class.'" name="password" id="password" value="'.$password_value.'">
                            <span class="invalid-feedback">'.$password_feedback.'</span>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary">'.$dict["settings.login"].'</button>
                    </form>';
            return $tab;
        }
    
        private function TabAdd() {
            global $dict;

            $tab =  '<form onsubmit="addBlog()">
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
                        </form>';
            return $tab;
        }
        
        private function TabEdit() {
            global $dict;
            
            $tab = '<form onsubmit="editBlog()">
                            <h2>'.strtoupper($dict["settings.blog.editing"]).'</h2>
                            <div class="form-group w-75">
                                <select class="form-control" id="edit_blog_select" onchange="onChangeEdit()">
                                    <option selected disabled value="-1"> 
                                        '.$dict["settings.blog.select_edit"].'
                                    </option>
                                    '.$this->insertBlogs().'
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
                        </form>';
            
            return $tab;
        }
        
        private function TabDelete() {
            global $dict;
            
            $tab = '<form onsubmit="removeBlog()">
                            <h2>'.strtoupper($dict["settings.blog.deleting"]).'</h2>
                            <div class="form-group w-75">
                                <select class="form-control" id="delete_blog_select" onchange="onChangeDelete()">
                                    <option selected disabled value="-1"> 
                                        '.$dict["settings.blog.select_delete"].'
                                    </option>
                                    '.$this->insertBlogs().'
                                </select>
                            </div>
                            <!-- Text for the blog -->
                            <div class="form-group w-75"> 
                                <textarea id="delete_blog_text" class="form-control" placeholder="'.$dict["settings.blog.text_placeholder"].'" required></textarea> 
                            </div>
                            <button disabled class="btn btn-primary">
                                '.$dict["settings.blog.delete"].'
                            </button>
                        </form>';
            
            return $tab;
        }
        
        private function TabLogout() {
            global $dict;
            
            $tab = '<form action="settings" method="post" name="logout">
                            <button class="btn btn-danger" type="submit" name="logout">
                                <i class="fa fa-sign-out text-muted fa-lg"></i> 
                                '.strtoupper($dict["settings.logout"]).'
                            </button>
                        </form>';
            
            return $tab;
        }
    
        function insertBlogs() {
            global $data, $TYPE_BLOG;
    
            $data = getItems($TYPE_BLOG);

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
    }

