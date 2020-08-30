    /* global dict_Home */

function _expandBlog(idBlog) {
        // The currently selected blog text will be expanded.
        // This means that the hidden text will be visible.
        // This is done by changing the class to a class
        // that does not hide the overflowing text.
        $("#" + idBlog).removeClass("blog_pre").addClass("blog_pre_expand");

        // The added link is now updated. When it is clicked,
        // it will now execute a function called _collapseBlog.
        // This function will hide the overflowing text that is
        // currently being shown
        $("#link" + idBlog)
                .attr("href", "javascript:_collapseBlog('" + idBlog + "')")
                .text(dict_Home["unlink_blog"] + "...");
    }

    function _collapseBlog(idBlog) {
        // The currently selected blog text will be collapsed.
        // This means that the overflowing text will be hidden.
        // This is done by changing the class to a class
        // that hides the overflowing text.
        $("#" + idBlog).removeClass("blog_pre_expand").addClass("blog_pre");

        // The added link is now updated. When it is clicked,
        // it will now execute a function called _expandeBlog.
        // This function will show the overflowing text that is
        // currently hidden
        $("#link" + idBlog)
                .attr("href", "javascript:_expandBlog('" + idBlog + "')")
                .text(dict_Home["link_blog"] + "...");
    }
