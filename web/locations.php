<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left">
			<div id="button_bar">
				<button id="button_left" onClick="PrevPage()"><?php echo $Content["prev"]; ?></button>
				<button id="button_right" onClick="NextPage()"><?php echo $Content["next"]; ?></button>
			</div>
			
			<div id="location_bar">
				<?php GetListOfItems("locations"); ?>
			</div>
		</div>
		
		<div class="contents_right" id="location_info">
			<div id="default">
				<?php echo $Content["default_loc"]; ?>
			</div>
		</div>
	</div>
	
	<?php require "layout/footer.php"; ?>
</html>

<script>
<?php
if (isset($_GET['id'])) {
?>
	var contentEl = document.getElementById("location_info");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	contentEl.removeChild(defaultText);
	
	<?php 
		$information = GetItemInfo("locations", $_GET['id']); 
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
			var value = "<?php echo $value ?>";
			
			<?php if ((strpos($key, "ID") !== false) and ($key != "ID")) {
				
				if ($value != "") {
					
					if ($key == "NameChangesID") { ?>
						
						// Get all the strings to get all the links
						var linkParts = value.split(",");
						
						names = TableData.innerHTML;
						var nameParts = names.split(",");
						
						if (nameParts.length > 1) {
							Table2 = document.createElement("table");
							for (var types = 0; types < nameParts.length; types++) {
								
								// Table data
								TableData2 = document.createElement("td");
								
								if (types < linkParts.length) {
									// Table links
									TableLink2 = document.createElement("a");
									TableLink2.innerHTML = nameParts[types];
								
									currentHref = window.location.href;
									TableLink2.href = updateURLParameter(currentHref, "id", linkParts[types]);
									
									TableData2.appendChild(TableLink2);
								} else {
									TableData2.innerHTML = nameParts[types];
								}
								
								// Table row
								TableRow2 = document.createElement("tr");
								TableRow2.appendChild(TableData2);
								
								// Little table inside of table
								Table2.appendChild(TableRow2);								
							}
							// Update the previous table cell with links to the IDs
							TableData.innerHTML = "";
							TableData.appendChild(Table2);
						} else {
							// Update the previous table cell with a link to the ID
							var TableLink = document.createElement("a");
							TableLink.innerHTML = TableData.innerHTML;
							TableData.innerHTML = "";
							
							currentHref = window.location.href;
							TableLink.href = updateURLParameter(currentHref, "id", value);
							
							TableData.appendChild(TableLink);
						}
					<?php } else { ?>
				
						// Update the previous table cell with a link to the ID
						var TableLink = document.createElement("a");
						TableLink.innerHTML = TableData.innerHTML;
						TableData.innerHTML = "";
						
						<?php if (($key == "FounderID") || ($key == "DestroyerID")) { ?>
							currentHref = "<?php echo AddLangParam('peoples.php') ?>";
							TableLink.href = updateURLParameter(currentHref, "id", value);
						<?php } else if (($key == "StartEventID") || ($key == "EndEventID")) { ?>
							currentHref = "<?php echo AddLangParam('events.php') ?>";
							TableLink.href = updateURLParameter(currentHref, "id", value);
						<?php } else { ?>
							currentHref = window.location.href;
							TableLink.href = updateURLParameter(currentHref, "id", value);
						<?php } ?>
						
						TableData.appendChild(TableLink);
					<?php } 
				}
			} else { ?>
				// Add a new table row
				var TableKey = document.createElement("td");
				TableKey.innerHTML = "<?php echo $LocationsParams[$key]; ?>";
			
				var TableData = document.createElement("td");
				<?php if (($key == "Coordinates") && ($value != "")) { ?>
					// Split the string coordinates into two separate coordinates
					var coordinatesStr = value.split(',');
					
					// Now turn them into floats
					var coordinatesFl = [-1, -1];
					coordinatesFl[0] = parseFloat(coordinatesStr[0]);
					coordinatesFl[1] = parseFloat(coordinatesStr[1]);
					
					// Only show two decimals after the comma
					TableData.innerHTML = coordinatesFl[0].toFixed(2) + ", " + coordinatesFl[1].toFixed(2);
				<?php } else { ?>
					TableData.innerHTML = value;
				<?php } ?>
			
				// Left is key names
				// right is value names
				var TableRow = document.createElement("tr");
				TableRow.appendChild(TableKey);
				TableRow.appendChild(TableData);
				
				table.appendChild(TableRow);
			<?php }
		} ?>
	
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
			echo "var NrOfItems = ".GetNumberOfItems("locations").";";
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
	// function updateURLParameter(url, param){
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
		// return "";
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