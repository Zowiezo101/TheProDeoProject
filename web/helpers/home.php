<script>
    function _expandBlog(idBlog) {
        // The currently selected blog text will be expanded.
        // This means that the hidden text will be visible.
        var Blog = document.getElementById(idBlog);

        // This is done by changing the class to a class
        // that does not hide the overflowing text.
        Blog.className = "blog_pre_expand";

        // The added link is now updated. When it is clicked,
        // it will now execute a function called _collapseBlog.
        // This function will hide the overflowing text that is
        // currently being shown
        var Link = document.getElementById("link" + idBlog);

        // Update text
        Link.innerHTML = dict_Home["unlink_blog"] + "...";

        // Update function to execute
        Link.href = "javascript:_collapseBlog('" + idBlog + "')";
    }

    function _collapseBlog(idBlog) {
        // The currently selected blog text will be collapsed.
        // This means that the overflowing text will be hidden.
        var Blog = document.getElementById(idBlog);

        // This is done by changing the class to a class
        // that hides the overflowing text.
        Blog.className = "blog_pre";

        // The added link is now updated. When it is clicked,
        // it will now execute a function called _expandeBlog.
        // This function will show the overflowing text that is
        // currently hidden
        var Link = document.getElementById("link" + idBlog);

        // Update text
        Link.innerHTML = dict_Home["link_blog"] + "...";

        // Update function to execute
        Link.href = "javascript:_expandBlog('" + idBlog + "')";
    }
</script>
