<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	<?php require "database/database.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left" id="people_bar">
			<?php GetListOfPeoples(); ?>
		</div>
		
		<div class="contents_right" id="people_info">
			<h1>Right</h1>
		</div>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>