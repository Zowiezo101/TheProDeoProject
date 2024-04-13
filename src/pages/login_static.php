<?php 
    include "src/tools/server.php";
    
    // Are we already logged in?
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        // Redirect to login page
        $url = "settings";
        if( headers_sent() ) { 
            echo("<script>location.href='$url'</script>"); 
        } else { 
            header("Location: $url"); 
        }
        exit;
    }
    
    function insertTabList () {
        // The list of tabs for this page
        global $dict;
        $list = 
            '<li class="nav-item">
                <a href="" class="active nav-link" data-toggle="pill" data-target="#tablogin"> 
                    <i class="fa fa-user-circle text-muted fa-lg"></i>
                    '.strtoupper($dict["settings.login"]).'
                </a> 
            </li>';
        return $list;
    }
    
    function insertTabContent() {
        // The content of all the tabs for this page
        $tabs = insertTabLogin();
        return $tabs;
    }
    
    function insertTabLogin() {
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
        
        $tab = 
            '<div class="tab-pane fade show active col-lg-6 col-10" id="tablogin" role="tabpanel">
                '.$message.'
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
                </form>
            </div>';
        return $tab;
    }
?>