<!DOCTYPE html>
<html>   
<?php 
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require 'src/tools/base.php';

    $dropdown = "";
    // Get the dropdown menu that needs to have it's button activated
    if (in_array($id, ['books', 'events', 'peoples', 'locations', 'specials', 'search'])) {
        $dropdown = "database";
    }
    
    // The theme that is used for this page
    switch($id) {
        case 'home':
        case 'search':
        case 'settings':
            $theme = "purple";
            break;
        
        case 'books':
        case 'aboutus':
            $theme = "pink";
            break;
        
        case 'events':
        case 'timeline':
            $theme = "orange";
            break;
        
        case 'peoples':
        case 'familytree':
            $theme = "red";
            break;
        
        case 'locations':
        case 'worldmap':
            $theme = "blue";
            break;
        
        case 'specials':
        case 'contact':
            $theme = "green";
            break;
        
        default:
            $theme = "purple";
            break;
    }
?>
    
    <head>
        <!-- Name shown on the tab -->
        <title><?php echo $dict["globals.prodeo_database"] ?></title>
        
        <!-- Some extra information used for viewing -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- External Javascript and script files -->
<?php require "page/import.php"; ?>
        
        <script>

            window.onload = function() {
                // Set some default stuff
                <?php echo "onLoad".ucfirst($id)."()"; ?>;
            };
        </script>
    </head>
    
    <body class="d-flex flex-column min-vh-100">
<?php require "page/navigation.php"; ?>
        
        <!-- Actual content of the page itself 
            This is defined in the corresponding php page -->
        <div id="content" class="py-5 flex-grow-1" style="background-color: hsl(0, 100%, 99%)">
        </div>
        
<?php require "page/footer.php"; ?>
    </body>
</html>