<?php
    // TODO: Extended events
	$__included_by_maps__ = (($id == "timeline") || ($id == "familytree") || ($id == "timeline_ext"));

if($__included_by_maps__) {
		
	function _Map_Helper_Layout() {
		// The item type to use
		global $id;
		global $$id;
		
		// Getting the abbrevation that is used
		if ($id == "familytree") {
			$abv = "ft";
		} else {
			$abv = "tl";
		}
		
		// Get the desired dictionary to use
		global ${"dict_".ucfirst($id)};
	
		PrettyPrint('<div class="clearfix"> ', 1);
		PrettyPrint('	<div class="contents_left" id="item_choice"> ');
		PrettyPrint('		<div id="item_bar" class="item_'.$$id.'"> ');
		PrettyPrint('			<!-- We fill this up in the map javascript code -->');
		PrettyPrint('		</div> ');
		PrettyPrint('	</div> ');
		PrettyPrint('');
		PrettyPrint('	<div class="contents_right" id="'.$id.'_div"> ');
		PrettyPrint('		<div id="default"> ');
	if (isset($_GET['id'])) {
		PrettyPrint('			'.${"dict_".ucfirst($id)}["loading_".$abv]);
		PrettyPrint('');
		PrettyPrint('			<div id="progress_bar"> ');
		PrettyPrint('				<div id="progress"> ');
		PrettyPrint('					1% ');
		PrettyPrint('				</div> ');
		PrettyPrint('			</div> ');
	} else {
		PrettyPrint('			'.${"dict_".ucfirst($id)}["default_".$abv]);
	}
		PrettyPrint('		</div> ');
		PrettyPrint('');
		PrettyPrint('		<div id="hidden_div" ');
		PrettyPrint('			 style="display: none" ');
		PrettyPrint('			 > ');
		PrettyPrint('');
		PrettyPrint('			<svg id="hidden_svg"></svg> ');
		PrettyPrint('			<canvas id="hidden_cs"></canvas> ');
		PrettyPrint('			<a id="hidden_a"></a> ');
		PrettyPrint('		</div> ');
		PrettyPrint('	</div> ');
		PrettyPrint('</div> ');
	}
}

	function FindItems() {
		global $dict_Search;
		global $conn;
		global $id;
		$item_set = "";
		
		if (($id == "timeline") || ($id == "events")) {
			$sql = "SELECT * FROM events";
        } elseif ($id == "timeline_ext") {
            // TODO: Extended events
            $sql = "SELECT * FROM ext_events LEFT JOIN event_to_event ON ext_events.ext_event_id = event_to_event.next_event_id";
		} else {
			$sql = "SELECT * FROM peoples";
		}
		$result = $conn->query($sql);
		
		if (!$result) {
			$_SESSION["disp_error"] = $dict_Search["NoResults"];
		} elseif($result->num_rows == 0) {
			$_SESSION["disp_error"] = $dict_Search["NoResults"];
		} else {
			while ($item = $result->fetch_array()) {
				if (($id == "timeline") || ($id == "events")) {
					$name = $item['name'];
					$ID = $item['event_id'];
					$length = $item['length'];
                    
                    // TODO: This needs to change, unless everything is nicely on order..?
                    $previousID = $ID - 1;
					
					$item = 'new CreateEvent("'.$name.'", "'.$ID.'", "'.$previousID.'", "'.$length.'"),';
					$item_set = $item_set."\r\n\t".$item;
				} elseif ($id == "timeline_ext") {
                    // TODO: Extended events
					$name = $item['descr'];
					$ID = $item['ext_event_id'];
					$length = $item['length'];
                    $previousID = $item['curr_event_id'];
					
					$item = 'new CreateEvent("'.$name.'", "'.$ID.'", "'.$previousID.'", "'.$length.'"),';
					$item_set = $item_set."\r\n\t".$item; 
                } else {
					$name = $item['name'];
					$ID = $item['people_id'];
					$IDMother = $item['mother_id'];
					$IDFather = $item['father_id'];
					$Gender = $item['gender'];
				
					$item = 'new CreatePeople("'.$name.'", "'.$ID.'", "'.$IDMother.'", "'.$IDFather.'", "'.$Gender.'"),';
					$item_set = $item_set."\r\n\t".$item;
				}
			}
		}
		
		return $item_set;
	}
?>



<script>
	

	// This is the list of items that can be chosen to create a map with
	var ItemsList = [];
	var Items = [];

	// This is a global variable, used calculate the level index
	var levelCounter = [];
	var levelIDs = [];

	// Global sizes, used to get everything on the SVG within the borders
	var globalOffset = 0;
	var globalHeight = 0;
	var globalWidth = 0;

	var ActualHeight = 0;
	var ActualWidth = 0;

	var ZoomFactor = 1.00;
	var transMatrix = [1,0,0,1,0,0];

	var viewX = 0;
	var viewY = 0;

	var globalItemId = -1;
	var globalMapId = -1;

	var highestLevel = 0;


// This file is not included when we get in this if case
// When the file is included, we don't want to overwrite the existing HelperOnload function
<?php if (!($__included_by_maps__)) { ?>
	function getMaps(ID) {
		
		// List of items
		Items = [<?php echo FindItems(); ?>];
					
		// Create all the connections between parents and children
		setItems();
		
		// Get all the ancestors of this person
		Item = Items[ID];
		ListOfIDs = Item.getAncestors();

		return ListOfIDs;
	}
			

<?php } else { ?>
	function Helper_onLoad() {	

		// List of items
		Items = [<?php echo FindItems(); ?>];

		// Create all the connections between parents and children
		setItems();
	
		// Make a nice list here to choose from the set of ItemList Item
		// When chosen, update ItemId and redraw page
		var itemBar = document.getElementById("item_bar");
		
		<?php  
		if (isset($_SESSION['disp_error'])) {
			if ($_SESSION['disp_error'] != "") {
				// If there is an error, display it!
				PrettyPrint("var Error = document.createElement('p');");
				PrettyPrint("Error.innerHTML = '".$_SESSION['disp_error']."';");
				PrettyPrint("itemBar.appendChild(Error);");
				
				$_SESSION['disp_error'] = "";
			}
		}
		?>
		
		var table = document.createElement("table");
		for (var i = 0; i < ItemsList.length; i++) {
			var ItemId = ItemsList[i];
			var Item = Items[ItemId];
			
			var TableLink = document.createElement("button");
			TableLink.onclick = UpdateLink;
			TableLink.newLink = updateURLParameter(window.location.href, "id", i + "," + Item.ID);
			TableLink.innerHTML = Item.name;
			
			var TableData = document.createElement("td");
			TableData.appendChild(TableLink);
		
			var TableRow = document.createElement("tr");
			TableRow.appendChild(TableData);
			
			table.appendChild(TableRow);
		}
		itemBar.appendChild(table);
		
		loadScroll();
		
		<?php if (isset($_GET['id'])) { ?>	
			var IDs = "<?php echo $_GET['id']; ?>".split(",");
			
			// Get the Map and the ID numbers
			globalMapId = IDs[0];
			globalItemId = IDs[1];
			
			prep_SetSVG();
		<?php } ?>
	}
	
	window.onload = Helper_onLoad;
	
	// setLevels function
	function setLevels(ID) {			
		// The set of people that will be updated 
		// in the iteration of the while loop
		var IDset = [ID];
		
		// This breaks the while loop
		var lastSet = 0;
		
		// The current generation level we are in
		var levelCount = 0;
		
		while (lastSet == 0)
		{
			var newIDset = [];
			for (i = 0; i < IDset.length; i++) {
				var Item = Items[IDset[i]];
				var childSet = Item.setLevel(levelCount);
				
				// Create the ID set of the next generation
				newIDset = newIDset.concat(childSet);
			}
			levelCount++;
			
			// There are no more children to update
			IDset = uniq(newIDset);
			if (IDset.length == 0) {
				lastSet = 1;
			}
		}
		
		
		// Use minus one, since the levelcount was incremented on the last iteration
		return levelCount - 1;
	}
	
	
	// resetLevels function
	function resetLevels(ID) {
		
		highestLevel = 0;
		
		for (var m = 0; m < Items.length; m++)
		{		
			var Item = Items[m];
				
			// Reset levelIndex
			Item.level = -1;
			Item.Location = [-1, -1];
		}
		
		return;
	}
	
	
	function setIndexes(ID, highestLevel) {
		// The set of people that will be updated 
		// in the iteration of the while loop
		var IDset = [ID];
		
		// This breaks the while loop
		var lastSet = 0;
		
		for (i = 0; i < highestLevel + 1; i++) {
			// Initialization
			levelIDs.push([]);
			levelCounter.push(0);
		}
		
		while (lastSet == 0)
		{		
			var newIDset = [];
			for (i = 0; i < IDset.length; i++) {
				var Item = Items[IDset[i]];
				var level = Item.level;
				
				var childSet = [];
				
				// Only use the children of the direct next generation to get the correct numbers
				for (var j = 0; j < Item.ChildIDs.length; j++) {
					Child = Items[Item.ChildIDs[j]];
					
					if (Child.level == (Item.level + 1)) {
						childSet.push(Child.ID);
					}
				}
			
				// Store all the unique IDs and keep track on the level they are on
				// alert("Adding " + Item.name + " with ID " + Item.ID + " to array of level " + level + "\nArray: " + levelIDs[level]);
				
				// Keep track of the amount of people on a certain level
				// Only if the levelIndex is not already set
				if (Item.levelIndex == -1) {
					var currentLevelIDs = levelIDs[level];
					currentLevelIDs.push(Item.ID);
					levelIDs[level] = currentLevelIDs;
				
					Item.levelIndex = levelCounter[level];
				} else {
					// alert("We have a double!!");
					// alert("Item " + Item.name + " already has it's levelIndex set to " + Item.levelIndex);
					// alert("It is requested to set it from " + Item.levelIndex + " to " + levelCounter[level]);
				}
				
				levelCounter[level] = levelIDs[level].length;
				
				// Create the ID set of the next generation
				newIDset = newIDset.concat(childSet);
			}
			
			// There are no more children to update
			IDset = uniq(newIDset);
			if (IDset.length == 0) {
				lastSet = 1;
			}
		}
		
		return;
	}

	
	function resetIndexes() {
		
		// Reset all numbers and levelIndexes to recalculate
		levelIDs = [];
		levelCounter = [];
		
		for (var m = 0; m < Items.length; m++)
		{		
			var Item = Items[m];
				
			// Reset levelIndex
			Item.levelIndex = -1;
			Item.offset = 0;
		}
		
		return;
	}

	
	function download_png () {
		// Get the SVG
		var SVG = document.getElementById("svg");
		var Controls = document.getElementById("controls");
		
		// Temporarily remove these..
		SVG.removeChild(Controls);
		
		// Use an invisible SVG
		var svg = document.getElementById('hidden_svg');
				
		svg.setAttribute("version", 1.1);
		svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
		svg.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");
		
		// Get the entire SVG
		svg.setAttribute('width',  ActualWidth);
		svg.setAttribute('height', ActualHeight);	
		
		updateViewbox(0, 0, 1);
		
		if (window.navigator.msSaveOrOpenBlob != undefined) {			
			// Temporary div to save the svg in
			var tempDiv = document.createElement("div");
			var svgParent = svg.parentNode;
			
			// The child group of SVG
			var group = document.getElementById("<?php echo $id; ?>_svg");
			
			// Get the group of SVG
			SVG.removeChild(group);
			svg.appendChild(group);
			
			// And save it in svg
			svgParent.removeChild(svg);
			tempDiv.appendChild(svg);
			
			var URL = tempDiv.innerHTML;
			
			// We don't want these empty name spaces!
			newURL = URL.replace(/ ?\S*NS1[^\d]\S*[>"] ?/g, ">");
			
			// No links anymore for the downloaded file..
			newURL = newURL.replace(/<a[^>]*>/g, "");
			newURL = newURL.replace(/<\/a>+/g, "");
			
			// Or double namespace..
			if (countOcurrences(newURL, "http://www.w3.org/2000/svg") > 1) {
				newURL = newURL.replace(' xmlns="http://www.w3.org/2000/svg"', '');
			}
			
			// Clean up our mess
			newURL = newURL.replace(">>", ">");
			
			// Get the link and download the file
			var topItem = Items[ItemsList[globalMapId]];
			var blobObject = new Blob([newURL]);
			window.navigator.msSaveOrOpenBlob(blobObject, topItem.name + ".svg");
			
			// Now get the group back
			svg.removeChild(group);
			SVG.appendChild(group);
			
			// And the controls
			SVG.appendChild(Controls);
			
			// And the link to the svg..
			tempDiv.removeChild(svg);
			svgParent.appendChild(svg);
			
		} else {
		
			
			svg.innerHTML = SVG.innerHTML;
			
			// No links anymore for the downloaded file..
			svg.innerHTML = svg.innerHTML.replace(/<a[^>]*>/g, "");
			svg.innerHTML = svg.innerHTML.replace(/<\/a>+/g, "");
			
			// Now turn it into a URL for downloading
			var Serialilzer = new XMLSerializer();
			var string = Serialilzer.serializeToString(svg);
			
			var URL = "data:image/svg+xml;base64," + b64EncodeUnicode(string);
			
			// Release the link
			svg.innerHTML = "";
			
			// Now add these back
			SVG.appendChild(Controls);
					
			// Get the link and download the file
			var topItem = Items[ItemsList[globalMapId]];
			var link = document.getElementById('hidden_a');
			link.href = URL;
			link.download = topItem.name + ".svg";
			link.click();
		}
		
		// Reset the zoom to the selected people
		ZoomReset();
	}
	
	// https://stackoverflow.com/questions/4009756/how-to-count-string-occurrence-in-string
	function countOcurrences(str, value) {
		var regExp = new RegExp(value, "gi");
		return (str.match(regExp) || []).length;
	}

	// https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
	function b64EncodeUnicode(str) {
		// first we use encodeURIComponent to get percent-encoded UTF-8,
		// then we convert the percent encodings into raw bytes which
		// can be fed into btoa.
		var PercentEncoded = encodeURIComponent(str);
		
		var RawBytes = PercentEncoded.replace(
			/%([0-9A-F]{2})/g,
			function toSolidBytes(match, p1) {
				return String.fromCharCode('0x' + p1);
			}
		)
		
		var BToAString = btoa(RawBytes);
		
		return BToAString;
	}

	function UpdateLink() {
		var Link = this.newLink;
		saveScroll(Link);
		return;
	}

	function disable_select() {
		element = document.body;
		element.classList.add('no_select');
	}

	function enable_select() {
		element = document.body;
		element.classList.remove('no_select');
	}

	var MouseX = 0;
	var MouseY = 0;
	var Moving = false;
	GetMousePos = function (event) {
		MouseX = event.clientX;
		MouseY = event.clientY;
		
		Moving = true;
		
		// Disable selecting text or any other element
		disable_select();
	}

	GetTouchPos = function (event) {
		MouseX = event.changedTouches[0].pageX;
		MouseY = event.changedTouches[0].pageY;
		
		Moving = true
		
		// Disable selecting text or any other element
		disable_select();
	}

	GetMouseMov = function (event) {
		if (Moving == true) {
			var dX = event.clientX - MouseX;
			var dY = event.clientY - MouseY;
			
			panTo(dX, dY);
			
			MouseX = event.clientX;
			MouseY = event.clientY;
		}
	}

	GetTouchMov = function (event) {
		if (Moving == true) {
			var dX = event.changedTouches[0].pageX - MouseX;
			var dY = event.changedTouches[0].pageY - MouseY;
			
			panTo(dX, dY);
			
			MouseX = event.changedTouches[0].pageX;
			MouseY = event.changedTouches[0].pageY;
		}
	}

	GetMouseOut = function (event) {
		Moving = false;
		
		// Enable the disabled selections again
		enable_select();
	}

	// https://www.sitepoint.com/html5-javascript-mouse-wheel/
	GetDelta = function (event) {
		// cross-browser wheel delta
		var event = window.event || event; // old IE support	
		var delta = Math.max(-1, Math.min(1, (event.wheelDelta || -event.detail)));
		
		if (delta > 0) {
			ZoomIn(1.4);
		} else {
			ZoomOut(1.4);
		}
	}

	function UpdateProgress(value) {	
		var ProgressBar = document.getElementById("progress");
		
		ProgressBar.style.width = value + "%";
		ProgressBar.innerHTML = value + "%";
	}

	
	// Preparing the map
	function prep_SetSVG() {	
		setTimeout(prep_SetAllLevels, 1);
	}

	function prep_SetAllLevels() {
		// Set all the generation levels of all people. Start out clean
		resetLevels();
		highestLevel = setLevels(ItemsList[globalMapId]);
		// UpdateProgress(5);
		
		// Get all the information of the peoples included
		setTimeout(prep_SetAllIndexes, 1);
	}

	function prep_SetAllIndexes() {
		// And all the indexes of all people
		resetIndexes();
		setIndexes(ItemsList[globalMapId], highestLevel);
		UpdateProgress(15);
		
		// Make the calculations to see where everyone should be placed
		setTimeout(prep_CalcAllLocations, 1);
	}

	function prep_CalcAllLocations() {	
		// Make the calculations to see where everyone should be placed
		globalOffset = 0;
		globalHeight = 0;
		
		calcLocations(ItemsList[globalMapId], highestLevel);
		UpdateProgress(35);
		
		setTimeout(prep_appendSVG, 1);
	}

	function prep_appendSVG() {
		var svgns = "http://www.w3.org/2000/svg";
		var ItemMap = document.getElementById("<?php echo $id; ?>_div");
		
		// Create this element
		SVG = document.createElementNS(svgns, "svg");
		SVG.id = "svg";
		
		SVG.setAttributeNS(null, "transform", "matrix(1 0 0 1 0 0)");
		SVG.setAttributeNS(null, "display", "none");
		
		// Now add it to the screen
		ItemMap.appendChild(SVG);
		UpdateProgress(40);
		
		setTimeout(prep_appendGroup, 1);
	}


	function prep_AddControlButtons() {
		var ItemMap = document.getElementById("<?php echo $id; ?>_div");
		
		// Show the controls to move around in the SVG
		var svgns = "http://www.w3.org/2000/svg";
		var SVG = document.getElementById("svg");
		
		var Controls = document.createElementNS(svgns, "g");
		Controls.id = "controls";
		
		// The zoom-in button in SVG
		var Button = document.createElementNS(svgns, "g");
		Button.setAttributeNS(null, "onclick", "ZoomIn(1.4)");
		Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
		Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');
		
		var ZoomInButton = document.createElementNS(svgns, "rect");
		ZoomInButton.setAttributeNS(null, 'width', 40);
		ZoomInButton.setAttributeNS(null, 'height', 40);
		ZoomInButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 75);
		ZoomInButton.setAttributeNS(null, 'rx', 12);
		ZoomInButton.setAttributeNS(null, 'y', ItemMap.offsetHeight - 100);
		ZoomInButton.setAttributeNS(null, 'ry', 6);
		ZoomInButton.setAttributeNS(null, 'stroke', 'black');
		ZoomInButton.setAttributeNS(null, 'fill', 'white');
		ZoomInButton.id = "ZoomIn";
		ZoomInButton.ID = "ZoomIn";
		ZoomInButton.className.baseVal = "svg_<?php echo $$id; ?>";
		
		// Horizontal line of the plus sign
		var ZoomInPlus1 = document.createElementNS(svgns, "line");
		ZoomInPlus1.setAttributeNS(null, "x1", ItemMap.offsetWidth - 65);
		ZoomInPlus1.setAttributeNS(null, "y1", ItemMap.offsetHeight - 80);
		ZoomInPlus1.setAttributeNS(null, "x2", ItemMap.offsetWidth - 45);
		ZoomInPlus1.setAttributeNS(null, "y2", ItemMap.offsetHeight - 80);
		ZoomInPlus1.setAttributeNS(null, "stroke", "black");
		ZoomInPlus1.setAttributeNS(null, "stroke-width", 5);
		ZoomInPlus1.ID = "ZoomIn";
		
		// Vertical line of the plus sign
		var ZoomInPlus2 = document.createElementNS(svgns, "line");
		ZoomInPlus2.setAttributeNS(null, "x1", ItemMap.offsetWidth - 55);
		ZoomInPlus2.setAttributeNS(null, "y1", ItemMap.offsetHeight - 90);
		ZoomInPlus2.setAttributeNS(null, "x2", ItemMap.offsetWidth - 55);
		ZoomInPlus2.setAttributeNS(null, "y2", ItemMap.offsetHeight - 70);
		ZoomInPlus2.setAttributeNS(null, "stroke", "black");
		ZoomInPlus2.setAttributeNS(null, "stroke-width", 5);
		ZoomInPlus2.ID = "ZoomIn";
		
		Button.appendChild(ZoomInButton);
		Button.appendChild(ZoomInPlus1);
		Button.appendChild(ZoomInPlus2);
		Controls.appendChild(Button);
		
		
		// The zoom-out button in SVG
		var Button = document.createElementNS(svgns, "g");
		Button.setAttributeNS(null, "onclick", "ZoomOut(1.4)");
		Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
		Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');
		
		var ZoomOutButton = document.createElementNS(svgns, "rect");
		ZoomOutButton.setAttributeNS(null, 'width', 40);
		ZoomOutButton.setAttributeNS(null, 'height', 40);
		ZoomOutButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 75);
		ZoomOutButton.setAttributeNS(null, 'rx', 12);
		ZoomOutButton.setAttributeNS(null, 'y', ItemMap.offsetHeight - 50);
		ZoomOutButton.setAttributeNS(null, 'ry', 6);
		ZoomOutButton.setAttributeNS(null, 'stroke', 'black');
		ZoomOutButton.setAttributeNS(null, 'fill', 'white');
		ZoomOutButton.id = "ZoomOut";
		ZoomOutButton.ID = "ZoomOut";
		ZoomOutButton.className.baseVal = "svg_<?php echo $$id; ?>";
		
		// Horizontal line of the minus sign
		var ZoomOutMinus = document.createElementNS(svgns, "line");
		ZoomOutMinus.setAttributeNS(null, "x1", ItemMap.offsetWidth - 65);
		ZoomOutMinus.setAttributeNS(null, "y1", ItemMap.offsetHeight - 30);
		ZoomOutMinus.setAttributeNS(null, "x2", ItemMap.offsetWidth - 45);
		ZoomOutMinus.setAttributeNS(null, "y2", ItemMap.offsetHeight - 30);
		ZoomOutMinus.setAttributeNS(null, "stroke", "black");
		ZoomOutMinus.setAttributeNS(null, "stroke-width", 5);
		ZoomOutMinus.ID = "ZoomOut";
		
		Button.appendChild(ZoomOutButton);
		Button.appendChild(ZoomOutMinus);
		Controls.appendChild(Button);
		
		
		// The zoom-fit button in SVG
		var Button = document.createElementNS(svgns, "g");
		Button.setAttributeNS(null, "onclick", "ZoomFit()");
		Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
		Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');
		
		var ZoomFitButton = document.createElementNS(svgns, "rect");
		ZoomFitButton.setAttributeNS(null, 'width', 200);
		ZoomFitButton.setAttributeNS(null, 'height', 40);
		ZoomFitButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 225);
		ZoomFitButton.setAttributeNS(null, 'rx', 10);
		ZoomFitButton.setAttributeNS(null, 'y', 10);
		ZoomFitButton.setAttributeNS(null, 'ry', 10);
		ZoomFitButton.setAttributeNS(null, 'stroke', 'black');
		ZoomFitButton.setAttributeNS(null, 'fill', 'white');
		ZoomFitButton.id = "ZoomFit";
		ZoomFitButton.ID = "ZoomFit";
		ZoomFitButton.className.baseVal = "svg_<?php echo $$id; ?>";
		
		var ZoomFitTitle = document.createElementNS(svgns, "text");
		ZoomFitTitle.setAttributeNS(null, 'x', ItemMap.offsetWidth - 220);
		ZoomFitTitle.setAttributeNS(null, 'y', 35);
		ZoomFitTitle.textContent = "<?php echo ${"dict_".ucfirst($id)}['zoomfit'];?>";
		ZoomFitTitle.ID = "ZoomFit";
		
		Button.appendChild(ZoomFitButton);
		Button.appendChild(ZoomFitTitle);
		Controls.appendChild(Button);
		
		// The zoom-reset button in SVG
		var Button = document.createElementNS(svgns, "g");
		Button.setAttributeNS(null, "onclick", "ZoomReset()");
		Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
		Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');
		
		var ZoomResetButton = document.createElementNS(svgns, "rect");
		ZoomResetButton.setAttributeNS(null, 'width', 200);
		ZoomResetButton.setAttributeNS(null, 'height', 40);
		ZoomResetButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 225);
		ZoomResetButton.setAttributeNS(null, 'rx', 10);
		ZoomResetButton.setAttributeNS(null, 'y', 60);
		ZoomResetButton.setAttributeNS(null, 'ry', 10);
		ZoomResetButton.setAttributeNS(null, 'stroke', 'black');
		ZoomResetButton.setAttributeNS(null, 'fill', 'white');
		ZoomResetButton.id = "ZoomReset";
		ZoomResetButton.ID = "ZoomReset";
		ZoomResetButton.className.baseVal = "svg_<?php echo $$id; ?>";
		
		var ZoomResetTitle = document.createElementNS(svgns, "text");
		ZoomResetTitle.setAttributeNS(null, 'x', ItemMap.offsetWidth - 220);
		ZoomResetTitle.setAttributeNS(null, 'y', 85);
		ZoomResetTitle.textContent = "<?php echo ${"dict_".ucfirst($id)}['zoomreset'];?>";
		ZoomResetTitle.ID = "ZoomReset";
		
		Button.appendChild(ZoomResetButton);
		Button.appendChild(ZoomResetTitle);
		Controls.appendChild(Button);
		
		// The download button in SVG
		var Button = document.createElementNS(svgns, "g");
		Button.setAttributeNS(null, "onclick", "download_png()");
		Button.setAttributeNS(null, 'onmouseover', 'extraInfo(evt)');
		Button.setAttributeNS(null, 'onmouseout',  'lessInfo(evt)');
		
		var DownloadButton = document.createElementNS(svgns, "rect");
		DownloadButton.setAttributeNS(null, 'width', 200);
		DownloadButton.setAttributeNS(null, 'height', 40);
		DownloadButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 225);
		DownloadButton.setAttributeNS(null, 'rx', 10);
		DownloadButton.setAttributeNS(null, 'y', 110);
		DownloadButton.setAttributeNS(null, 'ry', 10);
		DownloadButton.setAttributeNS(null, 'stroke', 'black');
		DownloadButton.setAttributeNS(null, 'fill', 'white');
		DownloadButton.id = "Download";
		DownloadButton.ID = "Download";
		DownloadButton.className.baseVal = "svg_<?php echo $$id; ?>";
		
		var DownloadTitle = document.createElementNS(svgns, "text");
		DownloadTitle.setAttributeNS(null, 'x', ItemMap.offsetWidth - 220);
		DownloadTitle.setAttributeNS(null, 'y', 135);
		DownloadTitle.textContent = "<?php echo ${"dict_".ucfirst($id)}['download'];?>";
		DownloadTitle.id = "DownloadText";
		DownloadTitle.ID = "Download";
		
		Button.appendChild(DownloadButton);
		Button.appendChild(DownloadTitle);
		Controls.appendChild(Button);
		
		// Add everything to the SVG
		SVG.appendChild(Controls);
		UpdateProgress(65);
		
		// Get all the information of the peoples included
		setTimeout(prep_DrawMap, 1);
		return
	}

	function prep_SetInterrupts() {
		// The FamilyTree div
		var SVG = document.getElementById("svg");
		
		// And some functions for mouse or keyboard panning/scrolling
		SVG.setAttributeNS(null, 'onmousedown', "GetMousePos(evt)");
		SVG.setAttributeNS(null, 'ontouchstart', "GetTouchPos(evt)");
		
		if (SVG.addEventListener) {
			// IE9, Chrome, Safari, Opera
			SVG.addEventListener("mousewheel", GetDelta, false);
			// Firefox
			SVG.addEventListener("DOMMouseScroll", GetDelta, false);
		}
		// IE 6/7/8
		else 
			SVG.attachEvent("onmousewheel", GetDelta);
		
		window.onmousemove = GetMouseMov;
		window.ontouchmove = GetTouchMov;
		
		window.onmouseup = GetMouseOut;
		window.ontouchend = GetMouseOut;
		UpdateProgress(85);
		
		// Update the width and the height of the viewbox and move to the person
		setTimeout(prep_SetView, 1);
	}

	function prep_SetView() {
		// Update the width and the height of the viewbox
		updateViewbox(0, 0, 1);
		
		// Move to the event
		var Item = Items[globalItemId];
		panItem(Item);
		UpdateProgress(95);
		
		setTimeout(prep_MakeVisible, 1);
	}

	function prep_MakeVisible() {
		// The Map div
		var ItemMap = document.getElementById("<?php echo $id; ?>_div");
		
		// Remove the default text
		var defaultText = document.getElementById("default");
		
		if (defaultText != null) {
			ItemMap.removeChild(defaultText);
			
			// Make the SVG visible
			var SVG = document.getElementById("svg");
			SVG.setAttributeNS(null, "display", "inline");
		}
	}

	
	// Zooming and panning
	function updateViewbox(x, y, zoom) {
		var SVG = document.getElementById("<?php echo $id; ?>_svg");
		if ((x != -1) || (y != -1)) {
			viewX = x;
			viewY = y;
			
			transMatrix[4] = viewX;
			transMatrix[5] = viewY;
		}
		
		if (zoom != -1) {
			ZoomFactor = zoom;
			
			transMatrix[0] = ZoomFactor;
			transMatrix[3] = ZoomFactor;
		}
		
		
		newMatrix = "matrix(" + transMatrix.join(' ') + ")";
		SVG.setAttributeNS(null, "transform", newMatrix);
		SVG.setAttributeNS(null, "webkitTransform", newMatrix);
		SVG.setAttributeNS(null, "MozTransform", newMatrix);
		SVG.setAttributeNS(null, "msTransform", newMatrix);
		SVG.setAttributeNS(null, "OTransform", newMatrix);
		
		return;
	}

	function panTo(x, y) {	
		var newX = viewX + x;
		var newY = viewY + y;	
		
		updateViewbox(newX, newY, -1);
	}

	function ZoomIn(factor) {
		var ItemMap = document.getElementById("<?php echo $id; ?>_div");
		
		newZoom = ZoomFactor * factor;
		
		newX = viewX*factor + (1 - factor)*(ItemMap.offsetWidth / 2);
		newY = viewY*factor + (1 - factor)*(ItemMap.offsetHeight / 2);
		updateViewbox(newX, newY, newZoom);
	}

	function ZoomOut(factor) {
		var ItemMap = document.getElementById("<?php echo $id; ?>_div");
		
		newZoom = ZoomFactor / factor;
		
		newX = (viewX / factor) + (1 - (1 / factor))*(ItemMap.offsetWidth / 2);
		newY = (viewY / factor) + (1 - (1 / factor))*(ItemMap.offsetHeight / 2);
		updateViewbox(newX, newY, newZoom);
	}

	function ZoomFit() {
		var ItemMap = document.getElementById("<?php echo $id; ?>_div");
		
		// To zoom out, we need to increase the size of the viewHeight and viewWidth
		// Keep the ratio between X and Y axis aligned
		// Find the biggest ratio and use that!
		var dX = ActualWidth / ItemMap.offsetWidth;
		var dY = ActualHeight / ItemMap.offsetHeight;
		
		if (dX > dY) { 
			newZoom = ItemMap.offsetWidth / ActualWidth;
		
			// Now zoom out untill the whole family tree is visible
			updateViewbox(0, (ItemMap.offsetHeight - (ActualHeight * newZoom)) / 2, newZoom);
		} else {
			newZoom = ItemMap.offsetHeight / ActualHeight;
		
			// Now zoom out untill the whole family tree is visible
			updateViewbox((ItemMap.offsetWidth - (ActualWidth * newZoom)) / 2, 0, newZoom);
		}
	}

	function ZoomReset() {
	<?php if (isset($_GET['id'])) { ?>
		var IDs = "<?php echo $_GET['id']; ?>".split(",");
		
		// Get the ID number
		var ItemId = IDs[1];
	<?php } ?>
		
		newZoom = 1;
		
		// Now pan to this item
		var Item = Items[ItemId];
		panItem(Item);
		
		// And zoom to the default zoom level (1)
		updateViewbox(-1, -1, newZoom);
	}
	
	setBorder = function (event) {
		var IDnum = event.target.RectID;
		var Rect = document.getElementById("Rect" + IDnum);
		Rect.setAttributeNS(null, "stroke", "red");
		Rect.setAttributeNS(null, "stroke-width", 5);
	}

	clearBorder = function (event) {
		var IDnum = event.target.RectID;
		var Rect = document.getElementById("Rect" + IDnum);
		Rect.setAttributeNS(null, "stroke", "black");
		Rect.setAttributeNS(null, "stroke-width", 1);
	}
		
	setBorderButton = function (event) {
		var ID = event.target.ID;
		var Rect = document.getElementById(ID);
		Rect.setAttributeNS(null, "stroke", "red");
		Rect.setAttributeNS(null, "stroke-width", 5);
	}

	clearBorderButton = function (event) {
		var ID = event.target.ID;
		var Rect = document.getElementById(ID);
		Rect.setAttributeNS(null, "stroke", "black");
		Rect.setAttributeNS(null, "stroke-width", 1);
	}
	
	extraInfo = function (event) {
		var Rect = document.getElementById("Download");
		var Text = document.getElementById("DownloadText");
		
		// Update rectangle
		Rect.setAttributeNS(null, "stroke", "red");
		Rect.setAttributeNS(null, "stroke-width", 5);
		Rect.setAttributeNS(null, "height", 75);
		
		// Create some descriptive text (if they are not already available)
		var svgns = "http://www.w3.org/2000/svg";
		if (document.getElementById("DownloadText2") == null) {
			Text2 = document.createElementNS(svgns, "text");
			Text3 = document.createElementNS(svgns, "text");
		
			// Prepare the text
			Text2.setAttributeNS(null, 'x', Text.getAttribute("x"));
			Text2.setAttributeNS(null, 'y', parseInt(Text.getAttribute("y")) + 20);
			Text2.setAttributeNS(null, 'font-style', 'italic');
			Text2.setAttributeNS(null, 'font-size', 15);
			Text2.textContent = "<?php echo ${"dict_".ucfirst($id)}['download_extra'];?>";
			Text2.id = "DownloadText2";
		
			Text3.setAttributeNS(null, 'x', Text.getAttribute("x"));
			Text3.setAttributeNS(null, 'y', parseInt(Text.getAttribute("y")) + 40);
			Text3.setAttributeNS(null, 'font-style', 'italic');
			Text3.setAttributeNS(null, 'font-size', 15);
			Text3.textContent = "<?php echo ${"dict_".ucfirst($id)}['download_extra2'];?>";
			Text3.id = "DownloadText3";
		
			// Add the text
			Text.parentNode.appendChild(Text2);
			Text.parentNode.appendChild(Text3);
		}
	}

	lessInfo = function (event) {
		var Rect = document.getElementById("Download");
		
		// Update rectangle
		Rect.setAttributeNS(null, "stroke", "black");
		Rect.setAttributeNS(null, "stroke-width", 1);
		Rect.setAttributeNS(null, "height", 40);
		
		if (document.getElementById("DownloadText2") != null) {
			var Text2 = document.getElementById("DownloadText2");
			var Text3 = document.getElementById("DownloadText3");
			
			// Remove the text
			Text2.parentNode.removeChild(Text2);
			Text3.parentNode.removeChild(Text3);
		}
	}
<?php } ?>


	//https://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
	function uniq(a) {
		var prims = {"boolean":{}, "number":{}, "string":{}}, objs = [];

		return a.filter(function(item) {
			var type = typeof item;
			if(type in prims)
				return prims[type].hasOwnProperty(item) ? false : (prims[type][item] = true);
			else
				return objs.indexOf(item) >= 0 ? false : objs.push(item);
		});
	}
	
</script>