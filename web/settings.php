<?php 
    // Make it easier to copy/paste code or make a new file
    // Less chance for errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'src/template.php';
    include "src/tools/server/server.php";
    
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
?>

<script>
    function onLoadSettings() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the tabs
                    .append(getTabsMenu())
                    // The column with the selected tabs
                    .append(getTabsContent())
            )
        );
    }
</script>