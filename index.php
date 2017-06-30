<!DOCTYPE html>
<html>
	<head>
		<title>ProDeo Productions Database</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="styles.css">
	</head>
	
	<body>
		<div class="header">
			<h1>ProDeo Productions Database</h1>
			
			<a href="index.php"><img src="img_nl/home.png" alt="Home"></a>
			<div class="dropdown">
				<button class="dropimg" onclick="CreateDropDown()">
					<img src="img_nl/database.png" alt="Database">
				</button>
				<div class="dropdown_content" id="dropdown_menu">
					<a href="peoples.php"><img src="img_nl/peoples.png" alt="Peoples"></a>
					<a href="books.php"><img src="img_nl/books.png" alt="Books"></a>
					<a href="locations.php"><img src="img_nl/locations.png" alt="Locations"></a>
					<a href="events.php"><img src="img_nl/events.png" alt="Events"></a>
					<a href="specials.php"><img src="img_nl/specials.png" alt="Specials"></a>
				</div>
			</div>
			<a href="timeline.php"><img src="img_nl/timeline.png" alt="Timeline"></a>
			<a href="familytree.php"><img src="img_nl/familytree.png" alt="Familytree"></a>
			<a href="worldmap.php"><img src="img_nl/worldmap.png" alt="World Map"></a>
			<a href="contact.php"><img src="img_nl/contact.png" alt="Contact"></a>
			
			<hr>
		</div>
		
		<div>
			<h1>Contents</h1>
		</div>
		
		<div class="footer">
			<h1>Footer</h1>
		</div>
	</body>
	
	<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function CreateDropDown() {
    document.getElementById("dropdown_menu").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(e) {
  if (!e.target.matches('.dropimg')) {
    var myDropdown = document.getElementById("dropdown_menu");
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
  }
}
	</script>
</html>