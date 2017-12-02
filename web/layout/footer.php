		<div class="footer">
			<?php echo $Footer["PP_Name"]; ?>&copy <?php echo date("Y"); ?>
			<br>
			<?php 
				echo $Footer["PP_Version"].": v2.0. ";
				
				$uri_parts = explode('?', basename($_SERVER['REQUEST_URI'], 2));
				$current_page = $uri_parts[0];
				
				$date_page = filemtime($current_page);
				
				date_default_timezone_set('Europe/Amsterdam');
				echo $Footer["PP_date"]." ".date("d-m-Y H:i", $date_page); 
			?>
		</div>
	</body>
</html>