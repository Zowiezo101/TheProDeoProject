
/* global postBlog, dict, session_settings */

function getTabsMenu() {
    var menu = $("<div>").addClass("col-3").append(`
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabadd"><i class="fa fa-plus text-muted fa-lg"></i> ` + dict["settings.blog.add"].toUpperCase() + ` </a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabedit"><i class="fa fa-edit text-muted fa-lg"></i> ` + dict["settings.blog.edit"].toUpperCase() + ` </a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabdelete"><i class="fa fa-trash text-muted fa-lg"></i> ` + dict["settings.blog.delete"].toUpperCase() + ` </a> </li>
            <li class="nav-item"> 
                <form action="settings" method="post" name="logout">
                    <button class="btn btn-link nav-link" type="submit" name="logout"><i class="fa fa-sign-out text-muted fa-lg"></i> ` + dict["settings.logout"].toUpperCase() + ` </button>
                </form>
            </li>
        </ul>
    `);
    
    return menu;
}

function getTabsContent() {
    var content = $("<div>").addClass("col-9").append(
        $("<div>").addClass("tab-content")
            // Tab for adding blogs
            .append(`
                    <div class="tab-pane fade show active" id="tabadd" role="tabpanel">
                      <form class="">
                        <h2>` + dict["settings.blog.adding"].toUpperCase() + `</h2>
                        <!-- Title for the blog -->
                        <div class="form-group"> 
                            <label>` + dict["settings.blog.title"] + `</label> 
                            <input id="add_blog_title" type="text" class="form-control w-75" placeholder="` + dict["settings.blog.title_placeholder"] + `" required> 
                        </div>
                        <!-- Text for the blog -->
                        <div class="form-group w-75"> 
                            <label>` + dict["settings.blog.text"] + `</label> 
                            <textarea id="add_blog_text" class="form-control" placeholder="` + dict["settings.blog.text_placeholder"] + `" required name="editordata"></textarea> 
                        </div>
                        <button class="btn btn-primary" onclick="addBlog()">` + dict["settings.blog.add"] + `</button>
                      </form>
                    </div>`)
            // Tab for editing blogs
            .append(`
                    <div class="tab-pane fade" id="tabedit" role="tabpanel">
                      <form class="">
                        <h2>` + dict["settings.blog.editing"].toUpperCase() + `</h2>
                        <div class="form-group w-75">
                          <label>Select a blog to edit</label>
                          <select class="form-control" id="edit_blog_select" onchange="onChangeEdit()">
                            <option selected disabled value="-1"> 
                                ` + dict["settings.blog.select_edit"] + `
                            </option>
                          </select>
                        </div>
                        <!-- Title for the blog -->
                        <div class="form-group"> 
                            <input id="edit_blog_title" type="text" disabled class="form-control w-75" placeholder="` + dict["settings.blog.title_placeholder"] + `" required> 
                        </div>
                        <!-- Text for the blog -->
                        <div class="form-group w-75"> 
                            <textarea id="edit_blog_text" class="form-control" placeholder="` + dict["settings.blog.text_placeholder"] + `" required name="editordata"></textarea> 
                        </div>
                        <button disabled class="btn btn-primary">Submit</button>
                      </form>
                    </div>`)
            // Tab for deleting blogs
            .append(`
                    <div class="tab-pane fade" id="tabdelete" role="tabpanel">
                      <form class="">
                        <p class="lead">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Example select</label>
                          <select class="form-control" id="exampleFormControlSelect1">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
                        </div>
                        <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                        <div class="form-group"> <label>Password</label> <textarea class="form-control" placeholder="Password" rows="5"></textarea> </div>
                        <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                        <button class="btn btn-primary">Submit</button>
                      </form>
                    </div>`)
    );
    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation
        // Using Summernote
        $('#add_blog_text').summernote({
            inheritPlaceholder: true,
            disableResizeEditor: true,
            height: 200,
            styleTags: ['p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph', 'style']],
            ],
        });
        
        
        // Using Summernote
        $('#edit_blog_text').summernote({
            inheritPlaceholder: true,
            disableResizeEditor: true,
            height: 200,
            styleTags: ['p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph', 'style']],
            ],
        });
        
        // Remove the resize bar
        $('.note-statusbar').hide() 
        
        // Disable the textbox for now
        $('#edit_blog_text').summernote('disable');
        
        getBlogs(session_settings["user_id"]).then(blogs => addBlogsToSelect(blogs));
    });
    
    
    return content;
}

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
    
        // Post the blog to the database
        postBlog(blog_title, blog_text, blog_user, blog_date).then(function (result) {
            // Let the user know it went right
            alert(result);
            
            location.reload();
        }).catch(function (result) {
            // Show error if anything went wrong
            alert(result);
            
            location.reload();
        });
    }
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
    
        // Post the blog to the database
        putBlog(blog_id, blog_title, blog_text).then(function (result) {
            // Let the user know it went right
            alert(result);
            
            location.reload();
        }).catch(function (result) {
            // Show error if anything went wrong
            alert(result);
            
            location.reload();
        });
    }
}

function deleteBlog() {
    
}

function addBlogsToSelect(blogs) {
    if (!blogs.records) {
        // No blogs
        return;
    }
    
    for(var i = 0; i < blogs.records.length; i++) {
        // The blog to be added
        var blog = blogs.records[i];
        
    
        // Showing the correct date in the correct timezone
        var date = new Date(blog.date);
        var blogDate = (!isNaN(Date.parse(blog.date))) ? date.toLocaleString() : dict["settings.blog.no_date"];

        // Add the blog to the container
        $("#edit_blog_select").append(
                '<option value="' + blog.id + '">' + 
                    blogDate + " - " + blog.title +
                '</option>'
            );
    }
}

function onChangeEdit(option) {
    var option = $("#edit_blog_select option:selected")[0];
    getBlog(option.value).then(function(blog) {
        if (blog) {
            // Enable the textboxs for editing
            $("#edit_blog_title").removeAttr("disabled");
            $("#edit_blog_title").val(blog.title);
            
            $('#edit_blog_text').summernote('enable');
            $('#edit_blog_text').summernote('code', blog.text);
            
            $('#tabedit button').removeAttr('disabled')
        }
    });
}