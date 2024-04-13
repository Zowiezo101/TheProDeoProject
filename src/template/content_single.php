<!-- This is for content that remains the same while using this page -->
<?php require "src/pages/{$id}_static.php"; 

$page_dynamic = "src/pages/{$id}_dynamic.php";
if (is_file($page_dynamic)) {
    ?><!-- This is for content that changes while using this page --><?php
    require $page_dynamic; 
}
?>

