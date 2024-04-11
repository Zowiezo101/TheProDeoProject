<?php 
    include "src/tools/server/server.php";
    
    // Are we already logged in?
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        // Redirect to login page
        $URL = "settings";
        if( headers_sent() ) { 
            echo("<script>location.href='$URL'</script>"); 
        } else { 
            header("Location: $URL"); 
        }
        exit;
    }
?>

<script>
    $(function() {
        var username = {
            param: "<?php echo $param_username; ?>",
            err: "<?php echo $username_err; ?>",
            attr: "<?php echo (!empty($username_err)) ? 'is-invalid' : ((!empty($username)) ? 'is-valid' : ''); ?>"
        };
        
        var password = {
            param: "<?php echo $param_password1; ?>",
            err: "<?php echo $password1_err; ?>",
            attr: "<?php echo (!empty($password1_err)) ? 'is-invalid' : ((!empty($password1)) ? 'is-valid' : ''); ?>"
        };
        
        var login_err = `<?php echo (!empty($login_err)) ? '<div class="alert alert-danger">' . $dict[$login_err] . '</div>' : ''; ?>`;
    
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the tabs
                    .append(
                        $("<div>").addClass("col-3").append(`
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tablogin"> <i class="fa fa-user-circle text-muted fa-lg"></i>` + dict["settings.login"].toUpperCase() + `</a> </li>
                            </ul>
                        `))
                    // The column with the tab contents
                    .append(
                        $("<div>").addClass("col-9").append(
                            $("<div>").addClass("tab-content").append(`
                                <div class="tab-pane fade show active col-lg-6 col-10" id="tablogin" role="tabpanel">
                                    ` + login_err + `
                                    <form class="text-left" action="login" method="post" name="login">
                                        <div class="form-group">
                                            <label for="email_username">` + dict["settings.username"] + `</label>
                                            <input type="text" class="form-control ` + username.attr + `" name="username" id="email_username" value="` + username.param + `">
                                            <span class="invalid-feedback">` + dict[username.err] + `</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">` + dict["settings.password"] + `</label>
                                            <input type="password" class="form-control ` + password.attr + `" name="password" id="password" value="` + password.param + `">
                                            <span class="invalid-feedback">` + dict[password.err] + `</span>
                                        </div>
                                        <button type="submit" name="login" class="btn btn-primary">` + dict["settings.login"] + `</button>
                                    </form>
                                </div>
                            `)
                        ))
            )
        );
    });
</script>