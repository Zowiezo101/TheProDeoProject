<?php 

function CleanText($text, $convertBR = 0) {
	// The newlines in the string cause problems..
	$text1 = str_replace(array("\r\n","\r","\n","\\r\\n","\\r","\\n"), "<br/>", $text);
	
	// Escape slashes
	$text2 = str_replace("\\", "\\\\", $text1);
	
	// Escape apastrophs
	$text3 = str_replace("'", "\\'", $text2);
	
	// Escape quotes
	$text4 = str_replace('"', '\\"', $text3);
	
	if ($convertBR == 1) {
		// Put the \n chars back
		$text5 = str_replace('<br/>', '\n', $text4);
	} else {
		$text5 = $text4;
	}
	
	return $text5;
}

function AddBlog($title, $text, $user) {
	global $Settings;
	global $conn;
	
	$sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
	$result = $conn->query($sql);

	if (!$result) {
		echo "<h1>SQL: ".$conn->error."</h1>";
	} else {		
		// TODO, check timezone etc.
		date_default_timezone_set('Europe/Amsterdam');
		$date = date("Y-m-d H:i:s a"); 
		
		$sql = "INSERT INTO blog (title, text, user, date) VALUES ('".CleanText($title)."','".CleanText($text)."','".$user."','".$date."')";
		$result = $conn->query($sql);	
		
		if (!$result) {
			echo "<h1>SQL: ".$conn->error."</h1>";
		} else {
			echo "<h1>".$Settings["blog_added"]."</h1>";
		}
	}	
}

function DeleteBlog($id) {
	global $Settings;
	global $conn;
	
	$sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
	$result = $conn->query($sql);

	if (!$result) {
		echo "<h1>SQL: ".$conn->error."</h1>";
	} else {		
		$sql = "DELETE FROM blog WHERE id=".$id;
		$result = $conn->query($sql);
		
		if (!$result) {
			echo "<h1>SQL: ".$conn->error."</h1>";
		} else {		
			echo "<h1>".$Settings["blog_removed"]."</h1>";
		}
	}	
}

function EditBlog($id, $title, $text) {
	global $Settings;
	global $conn;
	
	$sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
	$result = $conn->query($sql);

	if (!$result) {
		echo "<h1>SQL: ".$conn->error."</h1>";
	} else {		
		$sql = "UPDATE blog SET title='".CleanText($title)."', text='".CleanText($text)."' WHERE id=".$id;
		$result = $conn->query($sql);
	
		if (!$result) {
			echo "<h1>SQL: ".$conn->error."</h1>";
		} else {		
			echo "<h1>".$Settings["blog_edited"]."</h1>";
		}
	}	
}

function GetListOfBlogs() {
	global $Settings;
	global $conn;
	
	$sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
	$result = $conn->query($sql);

	if (!$result) {
		echo "<h1>SQL: ".$conn->error."</h1>";
	} else {	
		$sql = "SELECT * FROM blog";
		$result = $conn->query($sql);
	
		if (!$result) {
			echo "<h1>SQL: ".$conn->error."</h1>";
		} else {
			$newOption = "optionForm = document.createElement('option');";
			$addOption = "selectForm.appendChild(optionForm);";
			
			// Default value
			echo $newOption;
			echo "optionForm.value = '';";
			echo "optionForm.disabled = true;";
			echo "optionForm.selected = true;";
			echo "optionForm.innerHTML = '".$Settings['default']."';";
			echo $addOption;
			
			while ($blog = $result->fetch_array()) {				
				echo $newOption;
				echo "optionForm.value = '".$blog['id']."';";
				echo "optionForm.innerHTML = '".CleanText($blog['title'], 1)." @".$blog['date']."';";
				echo "optionForm.extra_text = '".CleanText($blog['text'], 1)."';";
				echo "optionForm.extra_title = '".CleanText($blog['title'], 1)."';";
				echo $addOption;
			}
		}	
	}
}

function ShowBlogs() {
	global $Search;
	global $conn;
	
	$sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Search["NoResults"]);
	} else {
		$sql = "SELECT * FROM blog ORDER BY id DESC";
		$result = $conn->query($sql);
		
		if (!$result) {
			echo($Search["NoResults"]);
		} else {
			echo "<table>";
			while ($blog = $result->fetch_array()) {
				echo "<tr>";
				echo "<td>";
				echo "<h1>".$blog['title']."</h1>";
				echo "<pre id='blog".$blog["id"]."' class='blog_pre'>".$blog['text']."</pre>";
				echo "<p class='blog_date'>".$blog['date']." by ".$blog["user"]."</p>";
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
}
?>

<script>
function CheckBlogs() {	
	var blogs = document.getElementsByClassName("blog_pre");
	var dates = document.getElementsByClassName("blog_date");
			
	for(var blogId = 0; blogId < blogs.length; blogId++) {
		var currentBlog = blogs[blogId];
		var currentDate = dates[blogId];
		
		// If the length of this blog exceeds the maximum allowed
		if (currentBlog.offsetHeight > 75) {
			var Parent = currentBlog.parentNode;
			
			// Add a link and some preview text with "click here"
			var blogLink = document.createElement("a");
			blogLink.innerHTML = "<?php echo $Content["link_blog"]; ?>...";
			blogLink.href = "javascript:expandBlog('" + currentBlog.id + "')";
			blogLink.id = "link" + currentBlog.id;
			blogLink.className = "blog_link";
			
			// First find the node where to put it in between
			Parent.insertBefore(blogLink, currentDate);
		}
	}
}

function expandBlog(idBlog) {
	// Use a different class, one that does not hide the overflow
	var elBlog = document.getElementById(idBlog);
	elBlog.className = "blog_pre_expand";
	
	// Add the function to collapse again
	var elLink = document.getElementById("link" + idBlog);
	elLink.innerHTML = "<?php echo $Content["unlink_blog"]; ?>...";
	elLink.href = "javascript:collapseBlog('" + idBlog + "')";
}

function collapseBlog(idBlog) {
	// Use a different class, one that hides the overflow
	var elBlog = document.getElementById(idBlog);
	elBlog.className = "blog_pre";
	
	// Add the function to expand again
	var elLink = document.getElementById("link" + idBlog);
	elLink.innerHTML = "<?php echo $Content["link_blog"]; ?>...";
	elLink.href = "javascript:expandBlog('" + idBlog + "')";
}
</script>
