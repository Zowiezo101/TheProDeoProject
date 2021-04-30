<?php 
    // Make it easier to copy/paste code or make a new file
    // Less change of errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    // Function to load the content in the content div
    function onLoadHome() {
        // Set the background of the home page
        var content = $("#content").css({
            "background-image": "url(img/background_home.svg)",
            "background-position": "top left",
            "background-size": "100% 32px",
            "background-repeat": "repeat repeat-y"
        });
        
        // The container to add blogs to
        var div_container = $("<div>").attr("id", "blogs").addClass("container").appendTo(content);
        
        // Get the blogs from the database
        getBlogs().then(function(blogs) {
            for(var i = 0; i < blogs.data.length; i++) {
                // The blog to be added
                var blog = blogs.data[i];
                
                // Add the blog to the container
                addBlogToContainer(div_container, blog);
            }
        });
    }
</script>