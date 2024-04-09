<?php 
    // Make it easier to copy/paste code or make a new file
    // Less chance for errors
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
        var div_container = $("<div>")
                .addClass("container blogs")
                .appendTo(content);
        
        // Get the blogs from the database
        getBlogs().then(function(blogs) {
            if (!blogs.records) {
                // No blogs
                div_container
                        .addClass("text-center h1")
                        .append(dict["settings.database_err"]);
                return;
            }
            for(var i = 0; i < blogs.records.length; i++) {
                // The blog to be added
                var blog = blogs.records[i];
                
                // Add the blog to the container
                addBlogToContainer(div_container, blog);
            }
        });
    }
</script>