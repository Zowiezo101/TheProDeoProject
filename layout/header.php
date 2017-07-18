<?php 	
	require "tools/tools.php";
?>

<head>
	<title>ProDeo Productions Database</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="layout/styles.css">
</head>
	
<body>
	<div class="header">
		<a href="<?php echo AddLangParam("index.php"); ?>" >
			Logo
			<!-- <img src="img/logo.bmp" alt="Logo"/> -->
		</a>
		
		<div class="header_options">
			<button class="header_options_lang" type="button" onclick="Language(<?php echo "'".$page_lang."'"; ?>)"></button>
			<button class="header_options_settings" type="button" onclick="LogIn()"></button>
			
			<div class="header_options_search">
				<form method="post" action="<?php echo AddLangParam("search.php"); ?>" >
					<input class="header_options_search_text" type="text" required>
					<input class="header_options_search_button" type="submit" value="">
				</form>
			</div>
		</div>
	</div>
	
	<div class="navigation">
		<ul>
			<li><a href="<?php echo AddLangParam("index.php"); ?>" ><?php echo $NavBar["Home"]; ?></a></li>
			<li><div class="nav_dropdown">
				<button class="nav_button" onclick="DropDown('nav_menu')">
					<?php echo $NavBar["Database"]; ?>
				</button>
				
				<div id="nav_menu">
					<a href="<?php echo AddLangParam("peoples.php"); ?>" ><?php echo $NavBar["Peoples"]; ?></a>
					<a href="<?php echo AddLangParam("books.php"); ?>" ><?php echo $NavBar["Books"]; ?></a>
					<a href="<?php echo AddLangParam("locations.php"); ?>" ><?php echo $NavBar["Locations"]; ?></a>
					<a href="<?php echo AddLangParam("events.php"); ?>" ><?php echo $NavBar["Events"]; ?></a>
					<a href="<?php echo AddLangParam("specials.php"); ?>" ><?php echo $NavBar["Specials"]; ?></a>
				</div>
			</div></li>
			
			<li><a href="<?php echo AddLangParam("timeline.php"); ?>" ><?php echo $NavBar["Timeline"]; ?></a></li>
			<li><a href="<?php echo AddLangParam("familytree.php"); ?>" ><?php echo $NavBar["Familytree"]; ?></a></li>
			<li><a href="<?php echo AddLangParam("worldmap.php"); ?>" ><?php echo $NavBar["Worldmap"]; ?></a></li>
			<li><a href="<?php echo AddLangParam("contact.php"); ?>" ><?php echo $NavBar["Contact"]; ?></a></li>
		</ul>
	</div>
	
		
<script>
	function Language(lang) {
		var newLang = "nl";
		if (lang == "nl") {
			newLang = "en";
		}
		
		if (newLang == "nl") {
			window.location.href = "index.php";
		} else {
			window.location.href = "index.php?lang=" + newLang;
		}
	}
	
	function LogIn() {
		// alert("LogIn");
		window.location.href = "<?php echo AddLangParam("settings.php"); ?>";
	}

	/* When the user clicks on the button, 
	toggle between hiding and showing the dropdown content */
	function DropDown(name) {
		var menu = document.getElementById(name);
		menu.style.display = "inline-block";
	}

	// Close the dropdown if the user clicks outside of it
	window.onclick = function(e) {
	    if (!e.target.matches(".nav_button")) {
			var menu = document.getElementById("nav_menu");
			menu.style.display = "none";
	    }
	}
</script>