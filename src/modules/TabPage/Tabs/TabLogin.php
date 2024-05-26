<?php

    class TabLogin extends Tab {        
        public function __construct() {
            global $dict, $message,
                    $username_class, $username_value, $username_feedback,
                    $password_class, $password_value, $password_feedback;
            parent::__construct();
            
            // This tab is always the active one, as it's the only tab
            $active = true;
            $id = "tab_login";
            
            // Add the necessary modules in here
            $this->TabListItem([
                "id" => $id,
                "title" => $dict["settings.login"],
                "icon" => "fa-user-circle",
                "active" => $active
            ]);
            
            $tab_content_item = $this->TabContentItem([
                "id" => $id,
                "active" => $active,
                "extra-classes" => "col-lg-6 col-10"
            ]);
            $tab_content_item->addContent($message.'
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
                                </form>');
        }
    }       
