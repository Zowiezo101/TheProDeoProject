<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left">
			<div id="button_bar">
				<button id="button_left" onClick="PrevPage()"><?php echo $Content["prev"]; ?></button>
				<button id="button_right" onClick="NextPage()"><?php echo $Content["next"]; ?></button>
			</div>
			
			<div id="event_bar">
				<?php GetListOfItems("events"); ?>
			</div>
		</div>
		
		<div class="contents_right" id="event_info">
			<div id="default">
				<?php echo $Content["default_event"]; ?>
			</div>
		</div>
	</div>
	
	<?php require "layout/footer.php"; ?>
</html>

<script>
<?php
if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("event_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("events", $_GET['id']); 
	?>
	
	// Create a Table
	var table = document.createElement("table");
	
	<?php		
		foreach ($information as $key => $value)
		{
			?>
			
			<?php if ($value == -1) {
				$value = "";
			}?>
			
			<?php if ((strpos($key, "ID") !== false) and ($key != "ID")) { ?>
				// Update the previous table cell with a link to the ID
				var TableLink = document.createElement("a");
				TableLink.innerHTML = TableData.innerHTML;
				TableData.innerHTML = "";
				
				currentHref = window.location.href;
				TableLink.href = updateURLParameter(currentHref, "id", <?php echo "'".$value."'"; ?>);
				
				TableData.appendChild(TableLink);
			<?php } else { ?>
				// Add a new table row
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $EventsParams[$key]; ?>";
			
				var TableData = document.createElement("td");
				TableData.innerHTML = "<?php echo $value; ?>";
			
				// Left is key names
				// right is value names
				var TableRow = document.createElement("tr");
				TableRow.appendChild(TableKey);
				TableRow.appendChild(TableData);
				
				table.appendChild(TableRow);
			<?php } ?>
			<?php
		}
	?>
	
	contentEl.appendChild(table);
<?php
}
?>

	window.onload = function CheckButtons() {
		var ButtonPrev = document.getElementById("button_left");
		var ButtonNext = document.getElementById("button_right");
		
		// Check if this is page 0. If so, disable to prev button..	
		<?php
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			echo "var PageNr = ".$page_nr.";";
			echo "var NrOfItems = ".GetNumberOfItems("events").";";
		?>
		
		if (PageNr == 0) {
			ButtonPrev.disabled = true;
		} else {
			ButtonPrev.disable = false;
		}
		if (NrOfItems < 100) {
			ButtonNext.disabled = true;
		} else {
			ButtonNext.disable = false;
		}
	}
	
	function PrevPage() {		
		<?php
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			echo "var PageNr = ".$page_nr.";";
		?>
		
		if (PageNr == 1) {
			// The page parameter should now be removed
			oldHref = window.location.href;
			newHref = removeURLParameter(oldHref, "page");
			window.location.href = newHref;
		} else if (PageNr > 1) {
			// The page parameter only has to be updated
			oldHref = window.location.href;
			newHref = updateURLParameter(oldHref, "page", PageNr - 1);
			window.location.href = newHref;
		}
	}
	
	function NextPage() {
		<?php
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			echo "var PageNr = ".$page_nr." + 1;";
		?>
		
		oldHref = window.location.href;
		newHref = updateURLParameter(oldHref, "page", PageNr);
		window.location.href = newHref;
	}
	
	/**
	* http://stackoverflow.com/a/10997390/11236
	*/
	function updateURLParameter(url, param, paramVal){
		var newAdditionalURL = "";
		var tempArray = url.split("?");
		var baseURL = tempArray[0];
		var additionalURL = tempArray[1];
		var temp = "";
		
		if (additionalURL) {
			tempArray = additionalURL.split("&");
			
			for (var i=0; i<tempArray.length; i++){
				if(tempArray[i].split('=')[0] != param){
					newAdditionalURL += temp + tempArray[i];
					temp = "&";
				}
			}
		}

		var rows_txt = temp + "" + param + "=" + paramVal;
		return baseURL + "?" + newAdditionalURL + rows_txt;
	}
	
	function removeURLParameter(url, param){
		var newAdditionalURL = "";
		var tempArray = url.split("?");
		var baseURL = tempArray[0];
		var additionalURL = tempArray[1];
		var temp = "?";
		
		if (additionalURL) {
			tempArray = additionalURL.split("&");
			
			for (var i=0; i<tempArray.length; i++){
				if(tempArray[i].split('=')[0] != param){
					newAdditionalURL += temp + tempArray[i];
					temp = "&";
				}
			}
		}
		return baseURL + newAdditionalURL;
	}
</script>