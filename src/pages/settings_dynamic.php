<script>    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation
        // Using Summernote for adding blog
        $('#add_blog_text').summernote({
            inheritPlaceholder: true,
            disableResizeEditor: true,
            height: 200,
            styleTags: ['p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph', 'style']]
            ]
        });
        
        
        // Using Summernote for editing blog
        $('#edit_blog_text').summernote({
            inheritPlaceholder: true,
            disableResizeEditor: true,
            height: 200,
            styleTags: ['p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph', 'style']]
            ]
        });
        
        
        // Using Summernote for deleting blog
        $('#delete_blog_text').summernote({
            inheritPlaceholder: true,
            disableResizeEditor: true,
            height: 200,
            styleTags: [],
            toolbar: []
        });
        
        // Remove the resize bar
        $('.note-statusbar').hide();
        
        // Disable the textbox for now
        $('#edit_blog_text').summernote('disable');
        $('#delete_blog_text').summernote('disable');
    });

    function addBlog() {
        /* Title of a blog */
        var blog_title = $("#add_blog_title").val();

        /* The contents of a blog, done with SummerNote */
        var blog_text = $('#add_blog_text').summernote('code');

        // The user depends on the logged-in user
        var blog_user = session_settings["user_id"];

        // Date is the moment the blog was added
        var blog_date = new Date();

        if (session_settings["loggedin"]) {

            // Form tries to reload the page before the post could return..
            event.preventDefault();

            if ($('#add_blog_text').summernote('isEmpty')) {
                blog_text = "";
            }

            // Post the blog to the database
            postBlog(blog_title, blog_text, blog_user, blog_date).then(function (result) {
                if (result.message !== "settings.blog.success.add") {
                    // Show error if anything went wrong
                    alert(dict[result.message]);
                } else {
                    // Let the user know it went right
                    alert(dict[result.message]);
                    location.reload();
                }
            }).catch(function (result) {
                // Show error if anything went wrong
                alert(dict["settings.blog.error.add"]);
            });
        }

        return;
    }

    function editBlog() {

        /* Title of a blog */
        var blog_title = $("#edit_blog_title").val();

        /* The contents of a blog, done with SummerNote */
        var blog_text = $('#edit_blog_text').summernote('code');

        var blog_id = $("#edit_blog_select option:selected")[0].value;

        if (session_settings["loggedin"]) {

            // Form tries to reload the page before the post could return..
            event.preventDefault();

            if ($('#edit_blog_text').summernote('isEmpty')) {
                blog_text = "";
            }

            // Post the blog to the database
            putBlog(blog_id, blog_title, blog_text).then(function (result) {
                if (result.message !== "settings.blog.success.edit") {
                    // Show error if anything went wrong
                    alert(dict[result.message]);
                } else {
                    // Let the user know it went right
                    alert(dict[result.message]);
                    location.reload();
                }
            }).catch(function () {
                // Show error if anything went wrong
                alert(dict["settings.blog.error.edit"]);
            });
        }

        return true;
    }

    function removeBlog() {    
        // The ID of the selected blog
        var blog_id = $("#delete_blog_select option:selected")[0].value;

        if (session_settings["loggedin"]) {

            // Form tries to reload the page before the post could return..
            event.preventDefault();

            // Delete the blog from the database
            deleteBlog(blog_id).then(function (result) {
                // Let the user know it went right
                if (result.message !== "settings.blog.success.delete") {
                    // Show error if anything went wrong
                    alert(dict[result.message]);
                } else {
                    // Let the user know it went right
                    alert(dict[result.message]);
                    location.reload();
                }
            }).catch(function () {
                // Show error if anything went wrong
                alert(dict["settings.blog.error.delete"]);

                location.reload();
            });
        }

        return true;
    }

    function onChangeEdit() {
        var option = $("#edit_blog_select option:selected")[0];
        getItem(TYPE_BLOG, option.value).then(function(blog) {
            if (blog.id === option.value) {
                // Enable the textboxs for editing
                $("#edit_blog_title").removeAttr("disabled");
                $("#edit_blog_title").val(blog.title);

                $('#edit_blog_text').summernote('enable');
                $('#edit_blog_text').summernote('code', blog.text);

                // Enable the button
                $('#tabedit button').removeAttr('disabled');
            }
        });
    }

    function onChangeDelete() {
        var option = $("#delete_blog_select option:selected")[0];
        getItem(TYPE_BLOG, option.value).then(function(blog) {
            if (blog.id === option.value) {
                // Update the text and enable the button
                $('#delete_blog_text').summernote('code', blog.text);

                $('#tabdelete button').removeAttr('disabled');
            }
        });
    }
</script>