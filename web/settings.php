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
		
		LineBreak = document.createElement("br");

		titleForm = document.createElement("input");
		titleForm.type = "text";
		titleForm.name = "title";
		
		textForm = document.createElement("input");
		textForm.type = "text";
		textForm.name = "text";
		
		submitForm = document.createElement("input");
		submitForm.type = "submit";
		submitForm.name = "submit";
		
		newForm = document.createElement("form");
		newForm.method = "post";
		newForm.action = "";
		
		newForm.appendChild(titleForm);
		newForm.appendChild(LineBreak);
		newForm.appendChild(textForm);
		newForm.appendChild(LineBreak);
		newForm.appendChild(submitForm);
		
		Settings.appendChild(newForm);
	}
	
	function ShowDelete() {
		alert("Delete");
	}
	
	function ShowEdit() {
		alert("Edit");
	}

<?php
if (isset($_POST['submit'])) {
?>
	Settings = document.getElementById("settings_content");
	Settings.innerHTML = "Verzonden!";
	
	<?php AddBlog($_POST["title"], $_POST["text"], "Zowiezo101"); ?>
	
	// location.reload(true);
<?php
}
?>
</script>