
/* global postBlog, dict */

function getLoginMenu() {
    var menu = $("<div>").addClass("col-3").append(`
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tablogin"> LOG IN <i class="fa fa-user-circle text-muted fa-lg"></i></a> </li>
        </ul>
    `);
    
    return menu;
}

function getLoginContent() {
    var content = $("<div>").addClass("col-9").append(
        $("<div>").addClass("tab-content")
            // Tab for adding blogs
            .append(`
                    <div class="tab-pane fade show active" id="tablogin" role="tabpanel">
                      <form class="">
                        ` + dict["settings.overview"] + `
                      </form>
                    </div>`)
    );
    
    return content;
}

function getTabsMenu() {
    var menu = $("<div>").addClass("col-3").append(`
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabadd"> ADD BLOG <i class="fa fa-plus text-muted fa-lg"></i></a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabedit"> EDIT BLOG <i class="fa fa-edit text-muted fa-lg"></i></a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabdelete"> DELETE BLOG <i class="fa fa-trash text-muted fa-lg"></i></a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabprofile"> PROFILE <i class="fa fa-cog text-muted fa-lg"></i></a> </li>
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
                        <div class="form-group"> 
                            <label>Text</label> 
                            <textarea id="add_blog_text" class="form-control w-75" placeholder="Blog contents" rows="5" required></textarea> 
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
            // Tab for profile settings
            .append(`
                    <div class="tab-pane fade" id="tabprofile" role="tabpanel">
                      <p class="">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite. When I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms.</p>
                    </div>`)
    );
    
    return content;
}

function addBlog() {
    /* Title and the contents of a blog */
    var blog_title = $("#add_blog_title").val();
    var blog_text = $("#add_blog_text").val();
    
    // The user depends on the logged-in user
    var blog_user = "Zowiezo101";   // TODO
    
    // Date is the moment the blog was added
    var blog_date = new Date();
    
    // Post the blog to the database
    postBlog(blog_title, blog_text, blog_user, blog_date).then(function (result) {
        alert(result);
    });
}

function editBlog() {
    var blog_title = $("#edit_blog_title").val();
    var blog_text = $("#edit_blog_text").val();
    var blog_user = "Zowiezo101";   // TODO
}

function deleteBlog() {
    
}