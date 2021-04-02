<?php 
    //require 'tools/layout.php';
?>

<!DOCTYPE html>
<html>    
    <head>
        <!-- Name shown on the tab -->
        <title>Prodeo Database</title>
        
        <!-- Some extra information used for viewing -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- The style sheets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="css/theme_<?php echo $theme; ?>.css">
        
        <!-- External Javascript files -->
        <?php echo $imports; ?>
    </head>
    
    <body>
        <?php require "page/navigation.php"; ?>
        
        <!-- Actual content of the page itself 
            This is defined in the corresponding php page -->
        <?php require ("content/".$id.".php"); ?>
        
        <?php require "page/footer.php"; ?>
    </body>
</html>