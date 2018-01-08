<?php 
	$id = "timeline";
	require "layout/header.php"; 
	require "tools/timelineHelper.php";
?>

<div class="clearfix">
	<div class="contents_left" id="item_choice">			
		<div id="item_bar">
			<!-- We fill this up in the TimeLine javascript code -->
		</div>
	</div>
	
	<div class="contents_right" id="timeline_div">
		<div id="default">
			<?php if (isset($_GET['id'])) {
				echo $Content["loading_tl"];?>
				
				<div id="progress_bar">
					<div id="progress">
						1%
					</div>
				</div>
			<?php } else {
				echo $Content["default_tl"];
			} ?>
		</div>
		
		<div id="hidden_div"
			 style="display: none"
			 >
			 
			<svg id="hidden_svg"></svg>
			<canvas id="hidden_cs"></canvas>
			<a id="hidden_a"></a>
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