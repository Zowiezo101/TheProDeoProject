<!DOCTYPE html>
<html>
	<?php require "layout/header.php";?>
	
	<div class="search">
		<?php if (isset($_POST['submitSearch'])) {
			// Search Peoples database
			SearchItems($_POST['search'], "peoples");
			
			// Search Locations
			SearchItems($_POST['search'], "locations");
			
			// Search Specials
			SearchItems($_POST['search'], "specials");
			
			// Search Books
			SearchItems($_POST['search'], "books");
			
			// Search Events
			SearchItems($_POST['search'], "events");
		}?>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>