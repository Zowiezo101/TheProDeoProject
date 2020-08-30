<?php 
    require 'tools/layout.php';
?>

<!DOCTYPE html>
<html>    
    <head id="head">
        <!-- Name shown on the tab -->
        <title><?php echo $dict_Footer["PP_name"]; ?> Database</title>
        
        <!-- Some extra information used for viewing -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- The style sheets -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="styles/styles.css">
        <link rel="stylesheet" href="styles/styles_color_theme.css">
        
        <!-- External Javascript files -->
        <?php require "layout/import.php"; ?>
    </head>
    
    <body>
        <?php require "layout/header.php"; ?>
        <?php require "layout/navigation.php"; ?>
        
        <!-- Actual content of the page itself 
            This is defined in the corresponding php page -->
        <div id="content">
        </div>
        
        <?php require "layout/footer.php"; ?>
    </body>
</html>