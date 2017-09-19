<?php 
	require "layout/header.php"; 
	require "tools/timelineHelper.php";
?>

<div class="clearfix">
	<div class="contents_left">			
		<div id="timeline_bar">
			<!-- We fill this up in the TimeLine javascript code -->
		</div>
	</div>
	
	<div class="contents_right" id="timeline">
		<div id="legenda">
			<!-- Here comes the legenda -->
		</div>
		<div id="default">
			<?php if (isset($_GET['id'])) {
				echo $Content["loading_tl"];
			} else {
				echo $Content["default_tl"];
			} ?>
		</div>
	</div>
</div>

<?php require "layout/footer.php" ?>

<script>
// List of events of which the order is known
var Events = [<?php echo FindEvents(); ?>];
			
// Create all the connections between parents and children
setEvents();

window.onload = createTimeLine;
</script>