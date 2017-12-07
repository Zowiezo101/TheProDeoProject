<?php 
	$id = "settings";
	require "layout/header.php"; 
	require "tools/blogHelper.php"; 
?>

<?php if (isset($_SESSION['login'])) { ?>
	<div class="clearfix">
		<div class="contents_left" id="settings_bar">
			<button onclick="ShowNew()"><?php echo $Settings["new_blog"]; ?></button>
			<button onclick="ShowDelete()"><?php echo $Settings["delete_blog"]; ?></button>
			<button onclick="ShowEdit()"><?php echo $Settings["edit_blog"]; ?></button>
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
	<!-- Log in page, in case no login is found yet -->
	<div id="settings_login">
		<form method="post" action="tools/login.php">
			<!-- User name -->
			<p><?php echo $Settings["user"]; ?></p>
			<input type="text" name="user" placeholder="<?php echo $Settings["user"]; ?>">
			
			<!-- Password -->
			<p><?php echo $Settings["password"]; ?></p>
			<input type="password" name="password" placeholder="<?php echo $Settings["password"]; ?>">
			
			<!-- Submit button -->
			<br>
			<input id="submitForm" type="submit" name="submitLogin" value="<?php echo $Settings["login"]; ?>">
			<br>
		</form>
		
		<?php
		// When the entered data is incorrect
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

<!-- This part is only available, when the user is logged in -->
<?php if (isset($_SESSION['login'])) { ?>
<script>
	// Add a new blog to the database
	function ShowNew() {
		// This is the title of the right side of the page
		Settings = document.getElementById("settings_content");
		Settings.innerHTML = "<h1><?php echo $Settings["new_blog"]; ?></h1>";

		// A little textbox for the title of a new blog
		titleForm = document.createElement("textarea");
		titleForm.name = "title";
		titleForm.placeholder = "<?php echo $Settings["title"]; ?>";
		titleForm.rows = 1;
		titleForm.required = true;
		
		// Contents of the new blok
		textForm = document.createElement("textarea");
		textForm.name = "text";
		textForm.placeholder = "<?php echo $Settings["text"]; ?>";
		textForm.rows = 10;
		textForm.required = true;
		
		// The submit button
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submitAdd";
		submitForm.value = "<?php echo $Settings["new_blog"]; ?>";
		submitForm.id = "submitForm";
		
		// Add all these things to the form
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(titleForm);
		newForm.appendChild(textForm);
		newForm.appendChild(submitForm);
		
		// Add the form to the page
		Settings.appendChild(newForm);
	}
	
	function ShowDelete() {
		// This is the title of the right side of the page
		Settings = document.getElementById("settings_content");
		Settings.innerHTML = "<h1><?php echo $Settings["delete_blog"]; ?></h1>";

		// Make a selection bar
		selectForm = document.createElement("select");
		selectForm.name = "select";
		selectForm.onchange = PreviewRemove;
		selectForm.id = "select";

		// Add all the options to select
		<?php GetListOfBlogs(); ?>

		// Place holder for the text that will be deleted
		textForm = document.createElement("textarea");
		textForm.name = "text";
		textForm.placeholder = "<?php echo $Settings["text"]; ?>";
		textForm.rows = 10;
		textForm.disabled = true;
		textForm.id = "text";
		
		// Submit button, disabled until a blog is chosen
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submitDelete";
		submitForm.value = "<?php echo $Settings["delete_blog"]; ?>";
		submitForm.id = "submitForm";
		submitForm.disabled = true;
		
		// Add all these things to a form
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(selectForm);
		newForm.appendChild(textForm);
		newForm.appendChild(submitForm);
		
		// Add the form to the page
		Settings.appendChild(newForm);
	}
	
	function ShowEdit() {
		// The title of the right side of the page
		Settings = document.getElementById("settings_content");
		Settings.innerHTML = "<h1><?php echo $Settings["edit_blog"]; ?></h1>";

		// Add a selection bar
		selectForm = document.createElement("select");
		selectForm.name = "select";
		selectForm.onchange = PreviewEdit;
		selectForm.id = "select";

		// The options of the selection bar
		<?php GetListOfBlogs(); ?>

		// Place holder for the title that will be edited
		titleForm = document.createElement("textarea");
		titleForm.name = "title";
		titleForm.placeholder = "<?php echo $Settings["title"]; ?>";
		titleForm.rows = 1;
		titleForm.required = true;
		titleForm.disabled = true;
		titleForm.id = "title";
		
		// Place holder for the text that will be edited
		textForm = document.createElement("textarea");
		textForm.name = "text";
		textForm.placeholder = "<?php echo $Settings["text"]; ?>";
		textForm.rows = 10;
		textForm.required = true;
		textForm.disabled = true;
		textForm.id = "text";
		
		// Submit button, disabled until a blog is chosen
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submitEdit";
		submitForm.value = "<?php echo $Settings["edit_blog"]; ?>";
		submitForm.id = "submitForm";
		submitForm.disabled = true;
		
		// Add all these things to a form
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(selectForm);
		newForm.appendChild(titleForm);
		newForm.appendChild(textForm);
		newForm.appendChild(submitForm);
		
		// Add the form to the page
		Settings.appendChild(newForm);
	}
	
	function PreviewRemove() {
		// Get the text that needs to be visualised
		var select = document.getElementById("select");
		var selected = select.options[select.selectedIndex];
		var text = selected.extra_text;
		
		// The box with the text
		var textForm = document.getElementById("text");
		textForm.value = text;
		
		// Now enable the submit button
		var select = document.getElementById("submitForm");
		select.disabled = false;
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
		
		// Now enable the submit button and the textareas
		var selectForm = document.getElementById("submitForm");
		selectForm.disabled = false;
		titleForm.disabled = false;
		textForm.disabled = false;
	}

<?php
	if (isset($_POST['submitAdd'])) {
?>
		Settings = document.getElementById("settings_content");
		
		Settings.innerHTML = "<?php AddBlog($_POST["title"], $_POST["text"], $_SESSION['login']); ?>";
		
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
}
?>
</script>