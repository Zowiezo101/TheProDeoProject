<?php
    session_start();
    require "../../login_data.php";
    
    // The login page
    if (filter_input(INPUT_POST, 'submitLogin') != null) {
        
        // Check if username and password are correct
        $username = filter_input(INPUT_POST, "user");
        $password = filter_input(INPUT_POST, "password");
        
        // If they are, login with the username
        if (($username == $login_username) && ($password == $login_password)) {
            $_SESSION["login"] = $username;
        } 
        // If not, return an error
        elseif (($username != $login_username) || ($password != $login_password)) {
            $_SESSION["error"] = true;
        }
    }
?>

<script>
window.onload = function () {
    window.location.href = "../settings.php";
};
</script>
