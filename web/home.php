<?php 
	// Make it easier to copy/paste code or make a new file
	$id = "home";
	require "layout/layout.php"; 
?>
<?php

function home_Helper_layout() {
	// Dictionaries
	global $dict_Search;
	global $dict_Home;
	
	// Our connection with the SQL database
	global $conn;
	
	// The query to execute
	$sql = "CREATE TABLE IF NOT EXISTS 
				blog (
					id INT AUTO_INCREMENT, 
					title VARCHAR(255), 
					text TEXT, 
					user VARCHAR(255), 
					date VARCHAR(255), 
					PRIMARY KEY(id)
				)";
	$result = $conn->query($sql);
	
	if (!$result) {
		// If the table for the blogs does not exist or cannot be created
		// Give an error message to the user
		PrettyPrint($dict_Search["NoResults"], 1);
	} else {
		// Get all the separate blogs from the blog table
		// in descending order (latest blogs first)
		$sql = "SELECT * FROM blog ORDER BY id DESC";
		$result = $conn->query($sql);
		
		if (!$result) {
			// If the query failed, return with an error message
			PrettyPrint($dict_Search["NoResults"], 1);
		} elseif($result->num_rows == 0) {
			PrettyPrint($dict_Search["NoResults"], 1);
		} else {
			// Start creating the actual table to show the blogs in
			PrettyPrint("<table>", 1);
			while ($blog = $result->fetch_array()) {
				// For each blog that can be retrieved, make a row
				// Fill the row with a single cell
				PrettyPrint("	<tr>");
				PrettyPrint("		<td>");
				// Insert the title, using heading 1
				// Insert the main text, but collapse it for now (do not show all text).
				// Insert the date that the blog was written, and the user that wrote the blog
				PrettyPrint("			<h1>".$blog['title']."</h1>");
				PrettyPrint("			<pre id='blog".$blog["id"]."' class='blog_pre'>".$blog['text']."</pre>");
				PrettyPrint("			<p class='blog_date'>".$blog['date']." ".$dict_Home['user_blog']." ".$blog["user"]."</p>");
				// Close the cell and the row
				PrettyPrint("		</td>");
				PrettyPrint("	</tr>");
				PrettyPrint("");
			}
			// Close the table
			PrettyPrint("</table>");
		}
	}
}

?>

<script>
function Helper_onLoad() {	
	// Get all the elements that contain a blog text
	var blogTexts = document.getElementsByClassName("blog_pre");
	
	// Get all the elements that contain a blog date
	var blogDates = document.getElementsByClassName("blog_date");
	
	for(var blogId = 0; blogId < blogTexts.length; blogId++) {
		// Now for each and every blog text and date
		var Blog = blogTexts[blogId];
		var Date = blogDates[blogId];
		
		if (Blog.offsetHeight > 75) {
			// If the length of this blog text exceeds the maximum allowed,
			// which means that it has more than 5 lines of text, the lines
			// after those 5 allowed lines will be hidden. To prevent
			// a single blog to take too much space
			
			// In this case, get the actual blog where this text belongs to.
			var Parent = Blog.parentNode;
			
			// And add a link and some preview text with "click here"
			// If the link is clicked, the function _expandBlog will be executed.
			// This function will show the rest of the blog that is currently
			// hidden by default.
			var Link = document.createElement("a");
			Link.innerHTML = "<?php echo $dict_Home["link_blog"]; ?>...";
			Link.href = "javascript:_expandBlog('" + Blog.id + "')";
			Link.id = "link" + Blog.id;
			Link.className = "blog_link";
			
			// Place this new link between the text of the blog 
			// and the date of the blog.
			Parent.insertBefore(Link, Date);
		}
	}
}

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
	Link.innerHTML = "<?php echo $dict_Home["unlink_blog"]; ?>...";
	
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
	Link.innerHTML = "<?php echo $dict_Home["link_blog"]; ?>...";
	
	// Update function to execute
	Link.href = "javascript:_expandBlog('" + idBlog + "')";
}
	
window.onload = Helper_onLoad;
</script>

<?php
	// require_once "TESTAPI.php";
?>