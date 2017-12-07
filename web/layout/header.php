<?php 	
	session_start();
	
	if (isset($_POST["lang"])) {
		$_SESSION["lang"] = $_POST["lang"];
		?>
		
		<script>
			// Do a reload to the desired language
			window.location.href = window.location.href;
		</script>
		
		<?php
	}
?>

<!DOCTYPE html>
<html>	
	<?php require "tools/baseHelper.php"; ?>
	
	<head id="head">
		<!-- Name shown on the tab -->
		<title><?php echo $Footer["PP_Name"]; ?> Database</title>
		
		<!-- Some extra information used for viewing -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- The style sheet -->
		<link rel="stylesheet" href="layout/styles.css">
	</head>
		
	<body id="<?php echo $id; ?>">
		<!-- Header, with logo, banner and options -->
		<div class="header">
			<!-- Logo -->
			<div class="logo" >
				<a href="home.php" ></a>
			</div>
			
			<!-- Banner -->
			<div class="banner" >
				<img src="img/banner_<?php echo $_SESSION["lang"];?>.bmp" alt="Banner" />
			</div>
			
			<!-- Options -->
			<div class="options">
			
				<!-- Dropdown list for available languages -->
				<div class="lang">
				
					<!-- The button to make the drop down list of options appear -->
					<button 
						style=" 
							background-image: url('img/lang_<?php echo $_SESSION["lang"]; ?>.svg'); 
							background-size: auto 100%;" 
						class="lang_button" 
						onclick="DropDown('lang_menu')">
							<?php echo $_SESSION["lang"]; ?>
					</button>
					
					<!-- The actual drop down menu, hidden at first -->
					<div id="lang_menu">
						<form method="post">
							<?php getLangList($_SESSION["lang"]); ?>
						</form>
					</div>
				</div>
				
				<!-- Settings -->
				<div class="settings" >
					<a href="settings.php" ></a>
				</div>
			</div>
		</div>
		
		<!-- Navigation bar -->
		<div class="navigation">
			<ul>
				<!-- Home page -->
				<li><a id="nav_home" href="home.php" ><?php echo $NavBar["Home"]; ?></a></li>
				
				<!-- Dropdown menu for Database items -->
				<li><div class="nav_dropdown">
				
					<!-- Button to get the available options -->
					<button id="nav_button_db" onclick="DropDown('nav_menu_db')">
						<?php echo $NavBar["Database"]; ?>
					</button>
					
					<!-- The actual drop down menu, hidden at first -->
					<div id="nav_menu_db" class="nav_menu">
						<a id="nav_peoples" href="peoples.php" ><?php echo $NavBar["Peoples"]; ?></a>
						<a id="nav_locations" href="locations.php" ><?php echo $NavBar["Locations"]; ?></a>
						<a id="nav_specials" href="specials.php" ><?php echo $NavBar["Specials"]; ?></a>
						<a id="nav_books" href="books.php" ><?php echo $NavBar["Books"]; ?></a>
						<a id="nav_events" href="events.php" ><?php echo $NavBar["Events"]; ?></a>
						<a id="nav_search" href="search.php" ><?php echo $NavBar["Search"]; ?></a>
					</div>
				</div></li>
				
				<!-- Other options in the navigation bar -->
				<li><a id="nav_timeline" href="timeline.php" ><?php echo $NavBar["Timeline"]; ?></a></li>
				<li><a id="nav_familytree" href="familytree.php" ><?php echo $NavBar["Familytree"]; ?></a></li>
				<li><a id="nav_worldmap" href="worldmap.php" ><?php echo $NavBar["Worldmap"]; ?></a></li>
				
				<!-- Dropdown menu for Pro Deo items -->
				<li><div class="nav_dropdown">
				
					<!-- Button to get the available options -->
					<button id="nav_button_pd" onclick="DropDown('nav_menu_pd')">
						<?php echo $NavBar["ProDeo"]; ?>
					</button>
					
					<div id="nav_menu_pd" class="nav_menu">
						<a id="nav_aboutus" href="aboutus.php" ><?php echo $NavBar["AboutUs"]; ?></a>
						<a id="nav_contact" href="contact.php" ><?php echo $NavBar["Contact"]; ?></a>
					</div>
				</div></li>
			</ul>
		</div>
		
<script>

	/* When the user clicks on the button, 
	toggle between hiding and showing the dropdown content */
	function DropDown(name) {
		var menu = document.getElementById(name);
		menu.style.display = "block";
	}

	// Close the dropdown if the user clicks outside of it
	window.onclick = function(event) {
		if (event.target.matches) {
			var matchesNavDB = event.target.matches('#nav_button_db');
			var matchesNavPD = event.target.matches('#nav_button_pd');
			var matchesLang = event.target.matches('.lang_button');
		} else if (event.target.msMatchesSelector) {
			var matchesNavDB = event.target.msMatchesSelector('#nav_button_db');
			var matchesNavPD = event.target.msMatchesSelector('#nav_button_pd');
			var matchesLang = event.target.msMatchesSelector('.lang_button');
		} else {
			var matchesNavDB = event.target.webkitMatchesSelector('#nav_button_db');
			var matchesNavPD = event.target.webkitMatchesSelector('#nav_button_pd');
			var matchesLang = event.target.webkitMatchesSelector('.lang_button');
		}
		
	    if (!matchesNavDB) {
			var menu = document.getElementById("nav_menu_db");
			menu.style.display = "none";
	    }
		
	    if (!matchesNavPD) {
			var menu = document.getElementById("nav_menu_pd");
			menu.style.display = "none";
	    }
		
	    if (!matchesLang) {
			var menu = document.getElementById("lang_menu");
			menu.style.display = "none";
	    }
	}
</script>