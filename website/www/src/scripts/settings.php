<script>    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation
        // Using Summernote for adding blogs
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
        
        
        // Using Summernote for editing blogs
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
        
        
        // Using Summernote for deleting blogs
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

        // Date is the moment the blog was added
        var blog_date = new Date();

<?php if (isset($_SESSION["loggedin"])) { ?>
        // The user depends on the logged-in user
        var blog_user = <?= $_SESSION["user_id"];?>;
        
        // Form tries to reload the page before the post could return..
        event.preventDefault();

        if ($('#add_blog_text').summernote('isEmpty')) {
            blog_text = "";
        }

        // Post the blog to the database
        createItem(TYPE_BLOG, {
            "title": blog_title, 
            "text": blog_text, 
            "user": blog_user, 
            "date": blog_date
        }).then(function (result) {
            if (result.error !== "") {
                // Show error if anything went wrong
                alert(dict["settings.blog.error.add"] + ": " + result.error);
            } else {
                // Let the user know it went right
                alert(dict["settings.blog.success.add"]);
                location.reload();
            }
        }).catch(function (result) {
            // Show error if anything went wrong
            alert(dict["settings.blog.error.add"]);
        });
        
<?php } ?>
        return;
    }

    function editBlog() {

        /* Title of a blog */
        var blog_title = $("#edit_blog_title").val();

        /* The contents of a blog, done with SummerNote */
        var blog_text = $('#edit_blog_text').summernote('code');

        var blog_id = $("#edit_blog_select option:selected")[0].value;

<?php if (isset($_SESSION["loggedin"])) { ?>
        // Form tries to reload the page before the post could return..
        event.preventDefault();

        if ($('#edit_blog_text').summernote('isEmpty')) {
            blog_text = "";
        }

        // Post the blog to the database
        updateItem(TYPE_BLOG, blog_id, {
            "title": blog_title, 
            "text": blog_text
        }).then(function (result) {
            if (result.error !== "") {
                // Show error if anything went wrong
                alert(dict["settings.blog.error.edit"] + ": " + result.error);
            } else {
                // Let the user know it went right
                alert(dict["settings.blog.success.edit"]);
                location.reload();
            }
        }).catch(function () {
            // Show error if anything went wrong
            alert(dict["settings.blog.error.edit"]);
        });
        
<?php } ?>
        return true;
    }

    function removeBlog() {    
        // The ID of the selected blog
        var blog_id = $("#delete_blog_select option:selected")[0].value;

<?php if (isset($_SESSION["loggedin"])) { ?>
            // Form tries to reload the page before the post could return..
            event.preventDefault();

            // Delete the blog from the database
            deleteItem(TYPE_BLOG, blog_id).then(function (result) {
                // Let the user know it went right
                if (result.error !== "") {
                    // Show error if anything went wrong
                    alert(dict["settings.blog.error.delete"] + ": " + result.error);
                } else {
                    // Let the user know it went right
                    alert(dict["settings.blog.success.delete"]);
                    location.reload();
                }
            }).catch(function () {
                // Show error if anything went wrong
                alert(dict["settings.blog.error.delete"]);
            });
            
<?php } ?>
        return true;
    }

    function onChangeEdit() {
        var option = $("#edit_blog_select option:selected")[0];
        getItem(TYPE_BLOG, option.value).then(function(data) {
            if (data.hasOwnProperty("records") && (data.records !== [])) {
                // Take the first record
                var blog = data.records[0];
        
                if (blog.id === parseInt(option.value, 10)) {
                    // Enable the textboxs for editing
                    $("#edit_blog_title").removeAttr("disabled");
                    $("#edit_blog_title").val(blog.title);

                    $('#edit_blog_text').summernote('enable');
                    $('#edit_blog_text').summernote('code', blog.text);

                    // Enable the button
                    $('#tab_edit button').removeAttr('disabled');
                }
            }
            
        });
    }

    function onChangeDelete() {
        var option = $("#delete_blog_select option:selected")[0];
        getItem(TYPE_BLOG, option.value).then(function(data) {
            if (data.hasOwnProperty("records") && (data.records !== [])) {
                // Take the first record
                var blog = data.records[0];
                
                if (blog.id === parseInt(option.value, 10)) {
                    // Update the text and enable the button
                    $('#delete_blog_text').summernote('code', blog.text);

                    // Enable the button
                    $('#tab_delete button').removeAttr('disabled');
                }
            }
        });
    }
</script>
