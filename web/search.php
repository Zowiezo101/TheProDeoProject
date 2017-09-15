<!DOCTYPE html>
<html>
	<?php 
		require "layout/header.php";
		require "tools/databaseHelper.php";
	?>
	
	<div class="search">
		<?php if (isset($_POST['submitSearch'])) { ?>
			<center>
				<?php echo $Search["Show"]."<a href='#peoples'>".$NavBar["Peoples"]."</a> | <a href='#locations'>".$NavBar["Locations"]."</a> | <a href='#specials'>".$NavBar["Specials"]."</a> | <a href='#books'>".$NavBar["Books"]."</a> | <a href='#events'>".$NavBar["Events"]."</a>";?>
			</center>
		
			<div id="peoples">
				<?php // Search Peoples database
				SearchItems($_POST['search'], "peoples"); ?>
			</div>
			
			<div id="locations">
				<?php // Search Locations
				SearchItems($_POST['search'], "locations"); ?>
			</div>
			
			<div id="specials">
				<?php // Search Specials
				SearchItems($_POST['search'], "specials"); ?>
			</div>
			
			<div id="books">
				<?php // Search Books
				SearchItems($_POST['search'], "books"); ?>
			</div>
			
			<div id="events">
				<?php // Search Events
				SearchItems($_POST['search'], "events"); ?>
			</div>
		<?php } ?>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>