<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "aboutus";
    require "layout/layout.php"; 
?>
<?php

function aboutus_Helper_layout() {
    global $dict_Contact;
    
    PrettyPrint('<h1>'.$dict_Contact["welcome"].'</h1>', 1);
    PrettyPrint('<p>'.$dict_Contact["aboutus"].'</p>');
    PrettyPrint('<p>'.$dict_Contact["info"].'</p>');
    PrettyPrint('<p>'.$dict_Contact["other"].'</p>');
}

?>