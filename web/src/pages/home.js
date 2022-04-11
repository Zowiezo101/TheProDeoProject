
/* global dict */

/** Add a blog the to given container
 * @param container
 * @param blog
 * */
function addBlogToContainer(container, blog) {
    var blogNumber = container.children().length;
    var blogBgColor = getBgColor(blogNumber);
    var blogId = "blog_" + blog.id;
    var blogTitle = blog.title;
    var blogText = blog.text ? blog.text + (blog.text.endsWith('</p>') ? '<br>' : '<br><br>') : '';
    var blogUser = blog.user === "undefined" ? "Zowiezo101" : blog.user;
    var blogDate = blog.date ? " @ " + blog.date.toUpperCase() : "";
        
    container.append(
    '<div class="row">' + 
    '    <div class="col-md-11 mb-3">' + 
    '        <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-' + blogBgColor + ')">' + blogTitle + '</h1>' + 
    '        <div class="text-center pb-2 pt-2 mb-0 h5" style="word-break:break-word; background-color: var(--light-' + blogBgColor + ')" id="' + blogId + '">' + blogText + '</div>' + 
    '        <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-' + blogBgColor + ')">' + dict["blogs.posted_by"] + ' <a href="' + setParameters("settings") + '" class="text-decoration-none text-body">' + blogUser + '</a>' + blogDate + ' </h6>' + 
    '    </div>' + 
    '</div>');
}

/** Get the correct background color depending on the number of blogs already placed
 * @param {String} number
 *   */
function getBgColor(number) {
    var bgColor = "";
    switch(number % 5) {
        case 1:
            bgColor = "yellow";
            break;
        case 2:
            bgColor = "red";
            break;
        case 3:
            bgColor = "green";
            break;
        case 4:
            bgColor = "blue";
            break;
        default:
            bgColor = "purple";
            break;
    }
    
    return bgColor;
}