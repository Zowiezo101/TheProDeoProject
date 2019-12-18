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
	
	// The various colors per page
	$home = "purple";
	$items = "green";
	$books = "orange";
	$events = "blue";
	$peoples = "red";
	$locations = "yellow";
	$specials = "purple";
	$search = "black";
	$timeline = "blue";
	$familytree = "red";
	$worldmap = "yellow";
	$prodeo = "orange";
	$aboutus = "green";
	$contact = "purple";
	$settings = "black";
?>

<!DOCTYPE html>
<html>	
	<?php 
		require "tools/baseHelper.php";

		/* Only used by layout.php, for the dropdown with languages */
		function getLangList($page_lang) {
			foreach (get_available_langs() as $lang) {
				if ($lang != $page_lang) {
					PrettyPrint('<input style=" 
										background-image: url(\'img/lang/lang_'.$lang.'.svg\'); 
										background-size: auto 100%;" 
										class="dropdown_lang_option" 
										type="submit" 
										name="lang" 
										value="'.$lang.'">', 1);
				}
			}
		}

		/* Only used by layout.php, for the navigation buttons */
		function MakeButton($name) {
			global $$name;
			global $id;
			global $$id;
			global $dict_NavBar;
			
			$button_id = "nav_".$name;
			$class = "nav_".$$name;
			if ($name == $id) {
				$class = "select_".$$name;
			} elseif (($name == "items") &&
					  (($id == "peoples") || 
					   ($id == "locations") || 
					   ($id == "specials") || 
					   ($id == "books") || 
					   ($id == "events") ||
					   ($id == "search"))) {
				// This is the case where we also want the database button to have the selected class
				$class = "select_".$$id;
			} elseif (($name == "prodeo") &&
					  (($id == "aboutus") ||
					   ($id == "contact"))) {
				// This is the case where we also want the prodeo button to have the selected class
				$class = "select_".$$id;
			}
			
			if ($name == "items") {
				PrettyPrint('<button id="dropdown_db_button" class="'.$class.'" onclick="ShowDropDown(\'dropdown_db_menu\')">', 1);
			} elseif ($name == "prodeo") {
				PrettyPrint('<button id="dropdown_prodeo_button" class="'.$class.'" onclick="ShowDropDown(\'dropdown_prodeo_menu\')">', 1);
			} else {
				PrettyPrint('<button id="'.$button_id.'" class="'.$class.'" onclick="location.href=\''.$name.'.php\'" type="button" >'.$dict_NavBar[ucfirst($name)].'</button>', 1);
			}
		}
	?>
	
	<head id="head">
		<!-- Name shown on the tab -->
		<title><?php echo $dict_Footer["PP_name"]; ?> Database</title>
		
		<!-- Some extra information used for viewing -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- The style sheet -->
		<link rel="stylesheet" href="layout/styles.css">
		<link rel="stylesheet" href="layout/styles_color_theme.css">
	</head>
		
	<body id="<?php echo $id; ?>" class="<?php echo $$id; ?>">
			<!-- Header, with logo, banner and options -->
			<div id="header">
				<!-- Logo -->
				<div >
					<a id="logo_link" href="home.php" ></a>
				</div>
				
				<!-- Banner, in the corresponding language -->
				<div >
					<img id="banner_img" src="img/banner/banner_<?php echo $$id; ?>.svg" alt="Banner" />
					<h1 id="banner_text"><?php echo $dict_Footer["slogan"]; ?></h1>
				</div>
				
				<!-- Options -->
				<div id="options">
				
					<!-- Dropdown list for available languages -->
					<div id="dropdown_lang_div">
					
						<!-- The button to make the drop down list of options appear -->
						<button 
							style=" 
								background-image: url('img/lang/lang_<?php echo $_SESSION["lang"]; ?>.svg'); 
								background-size: auto 100%;" 
							id="dropdown_lang_button" 
							onclick="ShowDropDown('dropdown_lang_menu')">
								<?php PrettyPrint($_SESSION["lang"], 1); ?>
						</button>
						
						<!-- The actual drop down menu, hidden at first -->
						<div id="dropdown_lang_menu">
							<form method="post">
								<?php getLangList($_SESSION["lang"]); ?>
							</form>
						</div>
					</div>
					
					<!-- Settings -->
					<div class="settings" >
						<a class="settings" href="settings.php" ></a>
					</div>
				</div>
			</div>
			
			<!-- Navigation bar -->
			<div id="navigation">
				<ul>
					<!-- Home page -->
					<li>
						<?php MakeButton("home"); ?>
					</li>
					
					<!-- Dropdown menu for Database items -->
					<li><div id="dropdown_db_div">
					
						<!-- The button to make the drop down list of options appear -->
						<?php MakeButton("items");
							PrettyPrint("				".$dict_NavBar["Database"]); ?>
						</button>
						
						<!-- The actual drop down menu, hidden at first -->
						<div id="dropdown_db_menu" class="dropdown_nav_menu">
							<?php MakeButton("peoples"); ?>
							<?php MakeButton("locations"); ?>
							<?php MakeButton("specials"); ?>
							<?php MakeButton("books"); ?>
							<?php MakeButton("events"); ?>
							<?php MakeButton("search"); ?>
						</div>
					</div></li>
					
					<!-- Other options in the navigation bar -->
					<li>
						<?php MakeButton("timeline"); ?>
					</li>
					<li>
						<?php MakeButton("familytree"); ?>
					</li>
					<li>
						<?php MakeButton("worldmap"); ?>
					</li>
					
					<!-- Dropdown menu for Pro Deo items -->
					<li><div id="dropdown_prodeo_div">
					
						<!-- The button to make the drop down list of options appear -->
						<?php MakeButton("prodeo");
							PrettyPrint("				".$dict_NavBar["ProDeo"]); ?>
						</button>
						
						<!-- The actual drop down menu, hidden at first -->
						<div id="dropdown_prodeo_menu" class="dropdown_nav_menu">
							<?php MakeButton("aboutus"); ?>
							<?php MakeButton("contact"); ?>
						</div>
					</div></li>
				</ul>
			</div>

			<!-- Actual content of the page itself 
				 This is defined in the corresponding php page -->
			<div id="content">
				<?php
					$Helper_layout = $id."_Helper_layout";
					$Helper_layout(); 
				?>
			</div>		

			<!-- And the footer of every page -->
			<div id="footer">
				<?php 
					// Get the name of the file that has currently included this file
					$uri_parts = explode('?', basename($_SERVER['REQUEST_URI'], 2));
					$current_page = $uri_parts[0];
					
					// Now get the timestamp of that file
					$date_page = filemtime($current_page);
					
					// Set the timezone to the timezone that I use on my computer
					date_default_timezone_set('Europe/Amsterdam');
					
					// Print the copyright year and the name of this website
					PrettyPrint($dict_Footer["PP_name"]."&copy;".date("Y"), 1);
					PrettyPrint("<br>");
					
					// Version and date of file modification
					PrettyPrint($dict_Footer["PP_version"].": v3.0. ");
					PrettyPrint($dict_Footer["PP_date"]." ".date("d-m-Y H:i", $date_page)); 
				?>
			</div>
	</body>
</html>
		
<script>

	/* When the user clicks on the button, 
	toggle between hiding and showing the dropdown content */
	function ShowDropDown(name) {
		var menu = document.getElementById(name);
		menu.style.display = "block";
	}

	// Close the dropdown if the user clicks outside of it
	window.onclick = function(event) {
		var ButtonIDs = [
			"dropdown_db",
			"dropdown_prodeo",
			"dropdown_lang",
		]
		
		for (i = 0; i < ButtonIDs.length; i++) {
			var ButtonID = ButtonIDs[i];
		
			// See which button has been pressed, using multiple functions that support different webbrowsers
			if (event.target.matches) {
				var Button = event.target.matches("#" + ButtonID + "_button");
			} else if (event.target.msMatchesSelector) {
				var Button = event.target.msMatchesSelector("#" + ButtonID + "_button");
			} else {
				var Button = event.target.webkitMatchesSelector("#" + ButtonID + "_button");
			}
			
			// If none of the buttons is pressed, hide the menu again
			if (!Button) {
				var menu = document.getElementById(ButtonID + "_menu");
				menu.style.display = "none";
			}
		}
	}
</script>