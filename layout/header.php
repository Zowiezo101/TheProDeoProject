<?php require "database/translation_nl.php"; ?>

<head>
	<title>ProDeo Productions Database</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="layout/styles.css">
</head>
	
<body>
	<div class="header">
		<a href="index.php">
			Logo
			<!-- <img src="img/logo.bmp" alt="Logo"/> -->
		</a>
		
		<div class="header_options">
			<button class="header_options_lang" type="button" onclick="Language()"></button>
			<button class="header_options_settings" type="button" onclick="LogIn()"></button>
			
			<div class="header_options_search">
				<form method="get" action="search.php">
					<input class="header_options_search_text" type="text" required>
					<input class="header_options_search_button" type="submit" value="">
				</form>
			</div>
		</div>
	</div>
	
	<div class="navigation">
		<ul>
			<li><a href="index.php"><?php echo $NavParams["Home"]; ?></a></li>
			<li><div class="nav_dropdown">
				<button class="nav_button" onclick="DropDown('nav_menu')">
					<?php echo $NavParams["Database"]; ?>
				</button>
				
				<div id="nav_menu">
					<a href="peoples.php"><?php echo $NavParams["Peoples"]; ?></a>
					<a href="books.php"><?php echo $NavParams["Books"]; ?></a>
					<a href="locations.php"><?php echo $NavParams["Locations"]; ?></a>
					<a href="events.php"><?php echo $NavParams["Events"]; ?></a>
					<a href="specials.php"><?php echo $NavParams["Specials"]; ?></a>
				</div>
			</div></li>
			
			<li><a href="timeline.php"><?php echo $NavParams["Timeline"]; ?></a></li>
			<li><a href="familytree.php"><?php echo $NavParams["Familytree"]; ?></a></li>
			<li><a href="worldmap.php"><?php echo $NavParams["Worldmap"]; ?></a></li>
			<li><a href="contact.php"><?php echo $NavParams["Contact"]; ?></a></li>
		</ul>
	</div>
	
		
<script>
	function Language() {
		alert("Language");
	}
	
	function LogIn() {
		// alert("LogIn");
		window.location.href = "settings.php";
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