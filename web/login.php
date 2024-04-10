<?php 
    // Make it easier to copy/paste code or make a new file
    // Less chance for errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'src/template.php';
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
    
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the tabs
                    .append(
                        $("<div>").addClass("col-3").append(`
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tablogin"> <i class="fa fa-user-circle text-muted fa-lg"></i> <?php echo strtoupper($dict["settings.login"]); ?></a> </li>
                            </ul>
                        `))
                    // The column with the tab contents
                    .append(
                        $("<div>").addClass("col-9").append(
                            $("<div>").addClass("tab-content").append(`
                                <div class="tab-pane fade show active col-lg-6 col-10" id="tablogin" role="tabpanel">

                                    <?php 
                                    if(!empty($login_err)){
                                        echo '<div class="alert alert-danger">' . $dict[$login_err] . '</div>';
                                    }        
                                    ?>
                                    <form class="text-left" action="login" method="post" name="login">
                                        <div class="form-group">
                                            <label for="email_username"><?php echo $dict["settings.username"]; ?></label>
                                            <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ((!empty($username)) ? 'is-valid' : ''); ?>" name="username" id="email_username" value="<?php echo $param_username; ?>">
                                            <span class="invalid-feedback"><?php echo $dict[$username_err]; ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="password"><?php echo $dict["settings.password"]; ?></label>
                                            <input type="password" class="form-control <?php echo (!empty($password1_err)) ? 'is-invalid' : ((!empty($password1)) ? 'is-valid' : ''); ?>" name="password" id="password" value="<?php echo $param_password1; ?>">
                                            <span class="invalid-feedback"><?php echo $dict[$password1_err]; ?></span>
                                        </div>
                                        <button type="submit" name="login" class="btn btn-primary"><?php echo $dict["settings.login"]; ?></button>
                                    </form>
                                </div>
                            `)
                        ))
            )
        );
    });
</script>