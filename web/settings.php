<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<!-- Important, do something with sessions here!! -->
	<?php if (isset($_SESSION['login'])) { ?>
		<div class="clearfix">
			<div class="contents_left" id="settings_bar">
				<a onclick="ShowNew()"><?php echo $Settings["new_blog"]; ?></a>
				<a onclick="ShowDelete()"><?php echo $Settings["delete_blog"]; ?></a>
				<a onclick="ShowEdit()"><?php echo $Settings["edit_blog"]; ?></a>
				<a href="tools/logout.php"><?php echo $Settings["logout"]; ?></a>
			</div>
			
			<div class="contents_right" id="settings_content">
				<?php echo $Settings["welcome"]; ?>
				<?php echo "- ".$Settings["new_blog"]."<br>"; ?>
				<?php echo "- ".$Settings["delete_blog"]."<br>"; ?>
				<?php echo "- ".$Settings["edit_blog"]."<br>"; ?>
			</div>
		</div>
	<?php } else { ?>
		<div id="settings_login">
			<form method="post" action="tools/login.php">
				<p><?php echo $Settings["user"]; ?></p>
				<input type="text" name="user" value="<?php echo $Settings["user"]; ?>">
				<p><?php echo $Settings["password"]; ?></p>
				<input type="password" name="password" value="<?php echo $Settings["password"]; ?>">
				<br>
				<input type="submit" name="submitLogin" value="<?php echo $Settings["login"]; ?>">
			</form>
			
			<?php
			if (isset($_SESSION["error"])) {
				if ($_SESSION["error"] == true) {
					echo "<p>".$Settings["incorrect"]."</p>";
					$_SESSION["error"] = false;
				}
			}
			?>
		</div>
	<?php }?>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
	function ShowNew() {
		Settings = document.getElementById("settings_content");
		Settings.innerHTML = "<h1><?php echo $Settings["new_blog"]; ?></h1>";

		titleForm = document.createElement("textarea");
		titleForm.name = "title";
		titleForm.value = "<?php echo $Settings["title"]; ?>";
		titleForm.rows = 1;
		titleForm.required = true;
		
		textForm = document.createElement("textarea");
		textForm.name = "text";
		textForm.value = "<?php echo $Settings["text"]; ?>";
		textForm.rows = 10;
		textForm.required = true;
		
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submitAdd";
		
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(titleForm);
		newForm.appendChild(textForm);
		newForm.appendChild(submitForm);
		
		Settings.appendChild(newForm);
	}
	
	function ShowDelete() {
		Settings = document.getElementById("settings_content");
		Settings.innerHTML = "<h1><?php echo $Settings["delete_blog"]; ?></h1>";

		selectForm = document.createElement("select");
		selectForm.name = "select";
		selectForm.onchange = PreviewRemove;
		selectForm.id = "select";

		<?php GetListOfBlogs(); ?>
		
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submitDelete";
		
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(selectForm);
		newForm.appendChild(submitForm);
		
		Settings.appendChild(newForm);
	}
	
	function ShowEdit() {
		Settings = document.getElementById("settings_content");
		Settings.innerHTML = "<h1><?php echo $Settings["edit_blog"]; ?></h1>";

		selectForm = document.createElement("select");
		selectForm.name = "select";
		selectForm.onchange = PreviewEdit;
		selectForm.id = "select";

		<?php GetListOfBlogs(); ?>

		titleForm = document.createElement("textarea");
		titleForm.name = "title";
		titleForm.value = "<?php echo $Settings["title"]; ?>";
		titleForm.rows = 1;
		titleForm.required = true;
		titleForm.id = "title";
		
		textForm = document.createElement("textarea");
		textForm.name = "text";
		textForm.value = "<?php echo $Settings["text"]; ?>";
		textForm.rows = 10;
		textForm.required = true;
		textForm.id = "text";
		
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submitEdit";
		
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(selectForm);
		newForm.appendChild(titleForm);
		newForm.appendChild(textForm);
		newForm.appendChild(submitForm);
		
		Settings.appendChild(newForm);
	}
	
	function PreviewRemove() {
		// Get the text that needs to be visualised
		var select = document.getElementById("select");
		var selected = select.options[select.selectedIndex];
		var text = selected.extra_text;
		
		var Preview = document.getElementById("preview");
		
		if (Preview == null) {
			// It does not exist yet, lets add it
			Settings = document.getElementById("settings_content");
			
			Preview = document.createElement("p");
			Preview.id = "preview";
			Preview.innerHTML = text;
			
			Settings.appendChild(Preview);
		} else {
			// It already exists, lets update it
			Preview.innerHTML = text;
		}
	}
	
	function PreviewEdit() {
		// Get the text that needs to be visualised
		var select = document.getElementById("select");
		var selected = select.options[select.selectedIndex];
		var title = selected.extra_title;
		var text = selected.extra_text;
		
		var titleForm = document.getElementById("title");
		var textForm = document.getElementById("text");
		
		// Update the default text to make updates easier..
		titleForm.value = title;
		textForm.value = text;
	}

<?php
if (isset($_POST['submitAdd'])) {
?>
	Settings = document.getElementById("settings_content");
	
	Settings.innerHTML = "<?php AddBlog($_POST["title"], $_POST["text"], "Zowiezo101"); ?>";
	
	// Reload without resending the action
	oldHref = window.location.href;
	window.location.href = oldHref;
<?php
}

if (isset($_POST['submitDelete'])) {
?>
	Settings = document.getElementById("settings_content");
	
	Settings.innerHTML = "<?php DeleteBlog($_POST["select"]); ?>";
	
	// Reload without resending the action
	oldHref = window.location.href;
	window.location.href = oldHref;
<?php
}

if (isset($_POST['submitEdit'])) {
?>
	Settings = document.getElementById("settings_content");
	
	Settings.innerHTML = "<?php EditBlog($_POST["select"], $_POST["title"], $_POST["text"]); ?>";
	
	// Reload without resending the action
	oldHref = window.location.href;
	window.location.href = oldHref;
<?php
}
?>
</script>