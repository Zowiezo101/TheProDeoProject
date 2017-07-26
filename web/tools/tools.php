<?php 
if (!isset($_GET["lang"])) {
	$page_lang = "nl";
} else {
	$page_lang = $_GET["lang"];
}

require "tools/translation_".$page_lang.".php"; 
require "../login_data.php";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


function GetListOfItems($table) {
	global $Content;
	global $conn;
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	$sql = "SELECT ID,Name FROM ".$table." WHERE ID>=".($page_nr*100)." LIMIT 100";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Content["NoResults"]);
	}
	else {
		echo "<table>";
		while ($name = $result->fetch_array()) {
			echo "<tr>";
			echo "<td>";
			echo "<a href='".AddLangParam($table.".php", 0).AddPageParam($page_nr).AddIdParam($name['ID'])."'>".$name['Name']."</a>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}

function GetNumberOfItems($table) {
	global $Content;
	global $conn;
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	$sql = "SELECT ID,Name FROM ".$table." WHERE ID>=".($page_nr*100)." LIMIT 100";
	$result = $conn->query($sql);
	
	return $result->num_rows;
}


function GetItemInfo($table, $ID) {
	global $Content;
	global $conn;
	
	$sql = "SELECT * FROM ".$table." WHERE ID=".$ID;
	$result = $conn->query($sql);
	$item = NULL;
	
	if (!$result) {
		$Error = array("Name" => $Content["NoResults"]);
		return $Error;
	}
	else {
		$item = $result->fetch_assoc();
	}
	
	return $item;
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
	global $Content;
	global $conn;
	
	$sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Content["NoResults"]);
	} else {
		$sql = "SELECT * FROM blog ORDER BY id DESC";
		$result = $conn->query($sql);
		
		if (!$result) {
			echo($Content["NoResults"]);
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

function AddLangParam($href) {
	global $page_lang;
	$return_val = "";
	
	if ($page_lang == "nl") {
		$return_val = $href;
	} else {
		$return_val = $href."?lang=".$page_lang;
	}
	
	return $return_val;
}


function AddPageParam($page_nr) {
	global $page_lang;
	$return_val = "";
	
	if (($page_lang == "nl") && ($page_nr > 0)) {
		# When the page language is dutch, there is no parameter in the URL
		$return_val = "?page=".$page_nr;
	} else if ($page_nr > 0) {
		# When the page language is not dutch, there is already a parameter in the URL
		# Now use & to add this parameter as well.
		$return_val = "&page=".$page_nr;
	}
	
	return $return_val;
}


function AddIdParam($id_nr) {
	global $page_lang;
	$return_val = "";
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	if ((!($page_lang == "nl")) || ($page_nr > 0)) {
		# When the page language is dutch, there is no parameter in the URL
		$return_val = "&id=".$id_nr;
	} else  {
		# When the page language is not dutch, there is already a parameter in the URL
		# Now use & to add this parameter as well.
		$return_val = "?id=".$id_nr;
	}
	
	return $return_val;
}
?>
