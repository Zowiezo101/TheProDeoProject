
/* global postBlog, dict, session_settings */

function getTabsMenu() {
    var menu = $("<div>").addClass("col-3").append(`
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabadd"><i class="fa fa-plus text-muted fa-lg"></i> ` + dict["settings.blog.add"] + ` </a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabedit"><i class="fa fa-edit text-muted fa-lg"></i> ` + dict["settings.blog.edit"] + ` </a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabdelete"><i class="fa fa-trash text-muted fa-lg"></i> ` + dict["settings.blog.delete"] + ` </a> </li>
            <li class="nav-item"> 
                <form action="settings" method="post" name="logout">
                    <button class="btn btn-link nav-link" type="submit" name="logout"><i class="fa fa-sign-out text-muted fa-lg"></i> ` + dict["settings.logout"] + ` </button>
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
                        <h2>ADDING A BLOG</h2>
                        <!-- Title for the blog -->
                        <div class="form-group"> 
                            <label>Title</label> 
                            <input id="add_blog_title" type="text" class="form-control w-75" placeholder="Enter a title" required> 
                        </div>
                        <!-- Text for the blog -->
                        <div class="form-group w-75"> 
                            <label>Text</label> 
                            <textarea id="add_blog_text" class="form-control" placeholder="` + dict["settings.placeholder"] + `" required name="editordata"></textarea> 
                        </div>
                        <button class="btn btn-primary" onclick="addBlog()">Add Blog</button>
                      </form>
                    </div>`)
            // Tab for editing blogs
            .append(`
                    <div class="tab-pane fade" id="tabedit" role="tabpanel">
                      <form class="">
                        <p class="lead">Use this page to edit a blog</p>
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Example select</label>
                          <select class="form-control" id="exampleFormControlSelect1">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                        <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                        <div class="form-group"> <label>Password</label> <textarea class="form-control" placeholder="Password" rows="5"></textarea> </div>
                        <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                        <button class="btn btn-primary">Submit</button>
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
        
        // Remove the resize bar
        $('.note-statusbar').hide() 
    });
    
    
    return content;
}

function addBlog() {
    /* Title of a blog */
    var blog_title = $("#add_blog_title").val();
    
    /* The contents of a blog, done with SummerNote */
    var blog_text = $('#add_blog_text').summernote('code');;
    
    // The user depends on the logged-in user
    var blog_user = session_settings["username"];
    
    // Date is the moment the blog was added
    var blog_date = new Date();
    
    if (session_settings["loggedin"]) {
        // Post the blog to the database
        postBlog(blog_title, blog_text, blog_user, blog_date).then(function (result) {
            alert(result);
        });
    }
}

function editBlog() {
    var blog_title = $("#edit_blog_title").val();
    var blog_text = $("#edit_blog_text").val();
    var blog_user = session_settings["username"];   // TODO
}

function deleteBlog() {
    
}