<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "aboutus";
    require "layout/template.php"; 
?>

<script>
    function onLoadAboutus() {
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        var content = document.getElementById("content");
    
        var h1 = document.createElement("h1");
        content.appendChild(h1);
        
        var p1 = document.createElement("p");
        var p2 = document.createElement("p");
        var p3 = document.createElement("p");
        content.appendChild(p1);
        content.appendChild(p2);
        content.appendChild(p3);
        
        h1.innerHTML = dict_Contact["welcome"];
        p1.innerHTML = dict_Contact["aboutus"];
        p2.innerHTML = dict_Contact["info"];
        p3.innerHTML = dict_Contact["other"];
    }
</script>