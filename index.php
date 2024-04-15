<!DOCTYPE html>
<html>   
<?php 
    // This needs to be started at the very beginning
    session_start();
    
    // Initializing some variables to be used by the template
    require "src/template/init.php";
?>
    <head>
        <!-- Name shown on the tab -->
        <title><?= $dict["globals.prodeo_database"] ?></title>

        <!-- Some extra information used for viewing -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- External Javascript and script files -->
<?php require "src/template/imports.php"; ?>

    </head>
    
    <body class="d-flex flex-column min-vh-100">
        <!-- Navigation bar on top of the page -->
<?php require "src/template/navigation.php"; ?>
    
        <!-- The content of this page, 
            This is done using templates and will not always have correct indentation or spacing -->
<?php require "src/template/content.php"; ?>
    
        <!-- The footer of this page -->
<?php require "src/template/footer.php"; ?>
        
<?php
$page_dynamic = "src/pages/{$id}_dynamic.php";
if (is_file($page_dynamic)) {
?>
        <!-- Javascript for dynamic content 
            (content that changes while using this page) -->
<?php
    require $page_dynamic; 
}
?>
    
        
    </body>
</html>