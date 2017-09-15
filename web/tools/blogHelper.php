<?php 

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
		
		$sql = "INSERT INTO blog (title, text, user, date) VALUES ('".$title."','".$text."','".$user."','".$date."')";
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
		$sql = "UPDATE blog SET title='".$title."', text='".$text."' WHERE id=".$id;
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
				// The newlines in the string cause problems..
				$cleanText = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"), "<br/>", $blog['text']);
				
				echo $newOption;
				echo "optionForm.value = '".$blog['id']."';";
				echo "optionForm.innerHTML = '".$blog['title']." @".$blog['date']."';";
				echo "optionForm.extra_text = '".$cleanText."';";
				echo "optionForm.extra_title = '".$blog['title']."';";
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
				echo "<pre>".$blog['text']."</pre>";
				echo "<p>".$blog['date']." by ".$blog["user"]."</p>";
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
}
?>
