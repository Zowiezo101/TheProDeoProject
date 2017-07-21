<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<!-- Important, do something with sessions here!! -->
	<div class="clearfix">
		<div class="contents_left" id="settings_bar">
			<a onclick="ShowNew()"><?php echo $Settings["new_blog"]; ?></a>
			<a onclick="ShowDelete()"><?php echo $Settings["delete_blog"]; ?></a>
			<a onclick="ShowEdit()"><?php echo $Settings["edit_blog"]; ?></a>
		</div>
		
		<div class="contents_right" id="settings_content">
			<h1><?php echo $Content["tbd"]; ?></h1>
		</div>
	</div>
	
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
		selectForm.onchange = PreviewText;
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
		alert("Edit");
	}
	
	function PreviewText() {
		// Get the text that needs to be visualised
		var select = document.getElementById("select");
		var selected = select.options[select.selectedIndex];
		var text = selected.extra_data;
		
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

<?php
}
?>
</script>