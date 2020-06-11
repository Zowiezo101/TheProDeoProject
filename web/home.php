<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "home";
    require "layout/template.php";
?>

<script>
    async function onLoadHome() {
        
        await getItemFromDatabase("blog").then(function(blogs) {
            
            var content = document.getElementById('content');
            
            // Put the table in the div
            var blogTable = document.createElement('table');
            content.appendChild(blogTable);
            
            for (var blogIdx in blogs) {
                var blogObject = blogs[blogIdx];
                
                // Add a table row to the table
                var TableRow = document.createElement('tr');
                blogTable.appendChild(TableRow);
                
                // Add table data to the row
                // The blog will be inserted in this element
                var TableData = document.createElement('td');
                TableRow.appendChild(TableData);
                
                var blogTitle = document.createElement('h1');
                blogTitle.innerHTML = blogObject["title"];
                TableData.appendChild(blogTitle);
                
                var blogText = document.createElement('pre');
                blogText.id = "blog" + blogObject["id"];
                blogText.className = "blog_pre";
                blogText.innerHTML = blogObject["text"];
                TableData.appendChild(blogText);
                
                if (blogText.offsetHeight > 75) {
                    // If the length of this blog text exceeds the maximum allowed,
                    // which means that it has more than 5 lines of text, the lines
                    // after those 5 allowed lines will be hidden. To prevent
                    // a single blog to take too much space

                    // Add a link and some preview text with "click here"
                    // If the link is clicked, the function _expandBlog will be executed.
                    // This function will show the rest of the blog that is currently
                    // hidden by default.
                    var blogLink = document.createElement("a");
                    blogLink.innerHTML = "<?php echo $dict_Home["link_blog"]; ?>...";
                    blogLink.href = "javascript:_expandBlog('" + blogText.id + "')";
                    blogLink.id = "link" + blogText.id;
                    blogLink.className = "blog_link";
                    TableData.appendChild(blogLink);
                }
                
                var blogDate = document.createElement('p');
                blogDate.className = "blog_date";
                blogDate.innerHTML = blogObject["date"] + " " + "<?php echo $dict_Home['user_blog']; ?>" + "" + blogObject["user"];
                TableData.appendChild(blogDate);
            }

        }, console.log);
    }
</script>