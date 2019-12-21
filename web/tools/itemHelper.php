<?php

// These extra libraries are needed for the list of timelines and the list of family trees
if ($id == "peoples") {
	require "familytree.php";
	require "tools/mapHelper.php";
} else if ($id == "events") {
	require "timeline.php";
	require "tools/mapHelper.php";
} 

// A function to add parameters to links
function AddParams($page, $id, $sort) {
	$return_val = "";
		
	// If values are not defined, define them now
	// Use the default value, if they are not in the address bar
	if ($page == -1) {
		if (isset($_GET["page"])) {
			$page = $_GET["page"];
		} else {
			$page = 0;
		}
	}
		
	if ($sort == -1) {
		if (isset($_GET["sort"])) {
			$sort = $_GET["sort"];
		} else {
			$sort = "app";
		}
	}
	
	if ($page != 0) {
		$return_val = "?page=".$page."&id=".$id;
	} else {
		$return_val = "?id=".$id;
	} 
	
	if ($sort != "app") {
		$return_val = $return_val."&sort=".$sort;
	}
		
	return $return_val;
}

// This function creates a table with one page of item results.
// One page contains 100 items in a table.
function GetListOfItems($table) {
	global $dict_Search;
	global $conn;
	
	// Check the page number. If it isn't defined, just
	// use the default value of 0.
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	// Check if the results should be sorted.
	if (!isset($_GET["sort"])) {
		$sort = "app";
	} else {
		$sort = $_GET["sort"];
	}
			
	// Sorting results by name or ID.
	switch($sort) {
		case 'alp':
		// Get new SQL array of items
		$sortBy = 'name ASC';
		break;
		
		case 'r-alp':
		// Get new SQL array of items
		$sortBy = 'name DESC';
		break;
		
		case 'r-app':
		// Get new SQL array of items
		$sortBy = substr($table, 0, -1).'_id DESC';
		break;
		
		default:
		// Get new SQL array of items
		$sortBy = substr($table, 0, -1).'_id ASC';
	}
	
	// Getting the query ready
	$sql = "SELECT ".substr($table, 0, -1)."_id, name FROM ".$table." ORDER BY ".$sortBy." LIMIT ".($page_nr*100).",".(($page_nr+1)*100);
	$result = $conn->query($sql);
	
	// If there are no results
	if (!$result) {
		PrettyPrint($dict_Search["NoResults"]);
	} elseif($result->num_rows == 0) {
		PrettyPrint($dict_Search["NoResults"]);
	} else {
		// If there are results, create the table with the results
		PrettyPrint("			<table>");
		while ($name = $result->fetch_array()) {
			PrettyPrint("				<tr>");
			PrettyPrint("					<td>");
			PrettyPrint("						<button onclick='saveScroll(\"".$table.".php".AddParams($page_nr, $name[substr($table, 0, -1).'_id'], $sort)."\")'>".$name['name']."</button>");
			PrettyPrint("					</td>");
			PrettyPrint("				</tr>");
			PrettyPrint("");
		}
		PrettyPrint("			</table>");
	}
}

// Get the numbers of items that are stored in a table for a certain page
// This is to see if it was the last page
function GetNumberOfItems($table) {
	global $conn;
	
	// Check if the page number is set
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	// The query to run
	$sql = "SELECT ".substr($table, 0, -1)."_id, name FROM ".$table." WHERE ".substr($table, 0, -1)."_id >= ".($page_nr*100)." LIMIT 101";
	$result = $conn->query($sql);
	
	if (!$result) {
		return 0;
	}
	
	// Return the results
	return $result->num_rows;
}

// Get the information for a single item
function GetItemInfo($table, $ID) {
	global $dict_Search;
    global $conn;
	
	// The query to run
	$sql = "SELECT * FROM ".$table." WHERE ".substr($table, 0, -1)."_id = ".$ID;
	$result = $conn->query($sql);
	$item = NULL;
	
	// No results
	if (!$result) {
		$Error = array("Name" => $dict_Search["NoResults"]);
		return $Error;
	}
	else {
		// If there are results, put them in a dictionary
		$item = $result->fetch_assoc();
	}
	
	return $item;
}
	
function _Database_Helper_layout() {
	// The item type to use
	global $id;
	global $$id;
	$single_item = substr($id, 0, -1);
	
	// Get the desired dictionary to use
	global ${"dict_".ucfirst($id)};
	global ${"dict_".ucfirst($id)."Params"};
	global $dict_NavBar;
	global $dict_Search;
	
			// This div is used to separate item_choice and item_info in two columns.
			// But resume with one column under these two columns.
	PrettyPrint('<div class="clearfix"> ' , 1);
	PrettyPrint('');
			// Left column
	PrettyPrint('	<div class="contents_left" id="item_choice"> ');
				// Div with all the buttons for the item bar
	PrettyPrint('		<div id="button_bar"> ');
					// Previous page
	PrettyPrint('			<button id="button_left" class="button_'.$$id.'" onClick="PrevPage()"> ');
	PrettyPrint('				← ');
	PrettyPrint('			</button> ');
	PrettyPrint('');
					// Sort on alphabet
	PrettyPrint('			<button id="button_alp" class="sort_a_z" onClick="SortOnAlphabet()"> ');
	PrettyPrint('			</button> ');
	PrettyPrint('');		
					// Sort on appearance
	PrettyPrint('			<button id="button_app" class="sort_9_1" onClick="SortOnAppearance()"> ');
	PrettyPrint('			</button> ');
	PrettyPrint('');
					// Next page
	PrettyPrint('			<button id="button_right" class="button_'.$$id.'" onClick="NextPage()"> ');
	PrettyPrint('				→ ');
	PrettyPrint('			</button> ');
				// Closing the button_bar
	PrettyPrint('		</div> ');
	PrettyPrint('');
				// Show a list of the available items in the item bar
				// When clicked, it will show information about this item.
	PrettyPrint('		<div id="item_bar" class="item_'.$$id.'"> ');
					GetListOfItems($id);
	PrettyPrint('		</div> ');
	PrettyPrint('	</div> ');
	PrettyPrint('');
			// Right column. This is where the item info will be displayed
			// when an item is clicked from the item bar. When no item is
			// clicked yet, show default text with instructions.
	PrettyPrint('	<div class="contents_right" id="item_info"> ');
	PrettyPrint('		<div id="default"> ');
					// echo dict_Peoples["defailt_people"];
					// echo dict_Locations["defailt_location"];
					// echo dict_Specials["defailt_special"];
					// echo dict_Books["defailt_book"];
					// echo dict_Events["defailt_event"];
	PrettyPrint('			'.${"dict_".ucfirst($id)}["default_".$single_item]);
	PrettyPrint('		</div> ');
	PrettyPrint('	</div> ');
	
	// Closing div clearfix
	PrettyPrint('</div> ');

	if (isset($_GET['id'])) {
		PrettyPrint('');
		PrettyPrint('<script>');
				// Grab the right part of the information window
		PrettyPrint('	var contentEl = document.getElementById("item_info"); ');
		PrettyPrint('');
				// Remove the default text
		PrettyPrint('	var defaultText = document.getElementById("default"); ');
		PrettyPrint('	contentEl.removeChild(defaultText); ');
		PrettyPrint('');
		// Get the information of the person that we want to show
		$information = GetItemInfo($id, $_GET['id']); 
		
				// Add the name of the current person as a header
		PrettyPrint('	var Name = document.createElement("h1"); ');
		PrettyPrint('	Name.innerHTML = "'.$information["name"].'"; ');
		PrettyPrint('	contentEl.appendChild(Name); ');
		PrettyPrint('');
				// Create a Table
		PrettyPrint('	var table = document.createElement("table"); ');
		PrettyPrint('');
		// For all the available information
		foreach ($information as $key => $value)
		{
			// If a value is set as -1 (unknown), 
			// set it to an emtpty string for human readability
			if ($value == -1) {
				$value = " ";
			} 
			
			// Name is already shown. 
			// ID number might just confuse the reader, so hide it.
			if (($key == "name") or ($key == substr($id, 0, -1)."_id") or ($key == "order_id")) {
				continue;
			}
            
            // We'll use these when we get to book_start_vers
            if (($key == "book_start_id") or ($key == "book_start_chap")) {
                continue;
            }
            
            // We'll use these when we get to book_end_vers
            if (($key == "book_end_id") or ($key == "book_end_chap")) {
                continue;
            }
			
			// Get the value in Javascript
			PrettyPrint('	var value = "'.$value.'"; ');
			PrettyPrint('');
			
			// When a key contains the name 'ID', give it a special treatment.
			// These keys usually contain references to peoples, locations, specials or events. 
			if (strpos($key, "_id") !== false) {
					
                // TODO: This comes from item_to_item tables
//				if (($key == "PlaceOfBirthID") 	|| 
//					($key == "PlaceOfEndID") 	|| 
//					($key == "PlaceOfLivingID") || 
//					($key == "LocationIDs")) 	{	
//						$table = "locations";
//				} else if (
//					($key == "FounderID") 	||
//					($key == "DestroyerID") ||
//					($key == "PeopleIDs"))	{
//						$table = "peoples";
//				} else if  (
//					($key == "StartEventID") ||
//					($key == "EndEventID"))	{
//						$table = "events";
//				} else if  ($key == "SpecialIDs") {
//						$table = "specials";
//				} else {
//						$table = $id;
//				}
//				
//				// Only if the value of this key is actually set, 
//				// otherwise we might run into some errors..
//				if ($value != "") {
//					
//					// There might be multiple IDs linked to this item.
//					// The different IDs are separated by comma's
//					PrettyPrint('	var linkParts = value.split(","); ');
//					PrettyPrint('');	
//							// Same for the names they refer to
//					PrettyPrint('	names = TableData.innerHTML; ');
//					PrettyPrint('	var nameParts = names.split(","); ');
//					PrettyPrint('');
//							// Create a table with the different names
//					PrettyPrint('	Table2 = document.createElement("table"); ');
//					PrettyPrint('');
//							// And for each name, create the amount of rows needed to show
//							// all the different linked names
//					PrettyPrint('	for (var types = 0; types < nameParts.length; types++) { ');
//								
//								// Table data
//					PrettyPrint('		TableData2 = document.createElement("td"); ');
//					PrettyPrint('');
//								// Not every linked name has an ID given..
//								// When the ID is given, refer to the item with the same ID.
//								// If not, just place the name
//					PrettyPrint('		if (types < linkParts.length) { ');
//									// Table links, the name is the name of the item
//					PrettyPrint('			TableLink2 = document.createElement("a"); ');
//					PrettyPrint('			TableLink2.innerHTML = nameParts[types]; ');
//					PrettyPrint('');		
//									// The link itself is linked to the item it is referring to
//					PrettyPrint('			currentHref = window.location.href; ');
//					PrettyPrint('			TableLink2.href = updateURLParameter("'.$table.'.php", "id", linkParts[types]); ');
//					PrettyPrint('');			
//									// Add it to the table with linked items
//					PrettyPrint('			TableData2.appendChild(TableLink2); ');
//					PrettyPrint('		} else { ');
//									// When the ID is not given, just give the name..
//					PrettyPrint('			TableData2.innerHTML = nameParts[types]; ');
//					PrettyPrint('		} ');
//					PrettyPrint('');		
//								// Table row
//					PrettyPrint('		TableRow2 = document.createElement("tr"); ');
//					PrettyPrint('		TableRow2.appendChild(TableData2); ');
//					PrettyPrint('');		
//								// Little table inside of table
//					PrettyPrint('		Table2.appendChild(TableRow2); ');					
//					PrettyPrint('	} ');
//					PrettyPrint('');
//							// Update the previous table cell with links to the IDs
//					PrettyPrint('	TableData.innerHTML = ""; ');
//					PrettyPrint('	TableData.appendChild(Table2); ');
//					PrettyPrint('');
//					PrettyPrint('');
//				}
				
			} else if ((($key == "book_start_vers") ||
						($key == "book_end_vers")) & ($value != "")) {
				// Create a link to the EO jongerenbijbel website or an english bible website!
				// This website should correspond to the translation used for the database!
				
				PrettyPrint('	var TableKey = document.createElement("td"); ');
				PrettyPrint('	TableKey.innerHTML = "'.${"dict_".ucfirst($id)."Params"}[$key].'"; ');
				PrettyPrint('');
								// Only show two decimals after the comma
				PrettyPrint('	var TableLink = document.createElement("a"); ');
                if ($key == "book_start_vers") {
                    PrettyPrint('	TableLink.innerHTML = "'.convertBibleVerseText($information["book_start_id"], 
                                                                                   $information["book_start_chap"], 
                                                                                   $value).'"; ');
                    PrettyPrint('	TableLink.href = "'.convertBibleVerseLink($information["book_start_id"], 
                                                                              $information["book_start_chap"], 
                                                                              $value).'"; ');
                } else {
                    PrettyPrint('	TableLink.innerHTML = "'.convertBibleVerseText($information["book_end_id"], 
                                                                                   $information["book_end_chap"], 
                                                                                   $value).'"; ');
                    PrettyPrint('	TableLink.href = "'.convertBibleVerseLink($information["book_end_id"], 
                                                                              $information["book_end_chap"], 
                                                                              $value).'"; ');
                }
				PrettyPrint('	TableLink.target = "_blank"; ');
				PrettyPrint('');
				PrettyPrint('	var TableData = document.createElement("td"); ');
				PrettyPrint('	TableData.appendChild(TableLink); '); 
				PrettyPrint('');
			
								// Left is key names
								// right is value names
				PrettyPrint('	var TableRow = document.createElement("tr"); ');
				PrettyPrint('	TableRow.appendChild(TableKey); ');
				PrettyPrint('	TableRow.appendChild(TableData); ');
				PrettyPrint('');
				PrettyPrint('	table.appendChild(TableRow); ');
				PrettyPrint('');
				PrettyPrint('');
				
			} else {
								// Add a new table row
				PrettyPrint('	var TableKey = document.createElement("td"); ');
				PrettyPrint('	TableKey.innerHTML = "'.${"dict_".ucfirst($id)."Params"}[$key].'"; ');
				PrettyPrint('');
				PrettyPrint('	var TableData = document.createElement("td"); ');
				// In case of coordinates
				if (($key == "coordinates") && ($value != "")) {
									// Split the string coordinates into two separate coordinates
					PrettyPrint('	var coordinatesStr = value.split(","); ');
					PrettyPrint('');
									// Now turn them into floats
					PrettyPrint('	var coordinatesFl = [-1, -1]; ');
					PrettyPrint('	coordinatesFl[0] = parseFloat(coordinatesStr[0]); ');
					PrettyPrint('	coordinatesFl[1] = parseFloat(coordinatesStr[1]); ');
					PrettyPrint('');
									// Only show two decimals after the comma
					PrettyPrint('	var TableLink = document.createElement("a"); ');
					PrettyPrint('	TableLink.innerHTML = coordinatesFl[0].toFixed(2) + ", " + coordinatesFl[1].toFixed(2); ');
					PrettyPrint('');
									// Link it to the worldmap
					PrettyPrint('	TableLink.href = updateURLParameter("worldmap.php", "id", '.$information[substr($id, 0, -1)."_id"].'); ');
					PrettyPrint('	TableData.appendChild(TableLink); ');
				} else {
					PrettyPrint('	TableData.innerHTML = "'.$value.'"; ');
				}
				PrettyPrint('');
			
								// Left is key names
								// right is value names
				PrettyPrint('	var TableRow = document.createElement("tr"); ');
				PrettyPrint('	TableRow.appendChild(TableKey); ');
				PrettyPrint('	TableRow.appendChild(TableData); ');
				PrettyPrint('');
				PrettyPrint('	table.appendChild(TableRow); ');
				PrettyPrint('');
				PrettyPrint('');
			}
		}
		PrettyPrint('	contentEl.appendChild(table); ');	

		// These two have a map to either the timeline or the familytree
		if (($id == "peoples") || ($id == "events")) {
			if ($id == "peoples") {
				$map = "Familytree";
			} else {
				$map = "Timeline";
			}
			
			PrettyPrint('');
			PrettyPrint('	// Show a list of maps where this item is included in');
			PrettyPrint('	var ItemText = document.createElement("p"); ');
			PrettyPrint('	ItemText.innerHTML = "'.${"dict_".ucfirst($id)}["map_".$single_item].'"; ');
			PrettyPrint('	contentEl.appendChild(ItemText); ');
			PrettyPrint('');
			PrettyPrint('	// The actual list to be created');
			PrettyPrint('	var ItemList = document.createElement("ul"); ');
			PrettyPrint('');
			PrettyPrint('	// The contents of the list');
			PrettyPrint('	var ItemListIDs = getMaps('.$_GET['id'].'); ');
			PrettyPrint('');
			PrettyPrint('	if (ItemListIDs.length > 0) { ');
			PrettyPrint('		// For every map that this item is included in');
			PrettyPrint('		for (var i = 0; i < ItemListIDs.length; i++) { ');
			PrettyPrint('			// Create a link to the map');
			PrettyPrint('			var ItemListLink = document.createElement("a"); ');
			PrettyPrint('			ItemListLink.innerHTML = "'.$dict_NavBar[$map].' " + (Number(ItemListIDs[i]) + 1); ');
			PrettyPrint('			ItemListLink.href = updateURLParameter("'.strtolower($map).'.php", "id", "" + ItemListIDs[i] + "," + '.$_GET['id'].'); ');
			PrettyPrint('');
			PrettyPrint('			// Put the link in a list item');
			PrettyPrint('			var ItemListItem = document.createElement("li"); ');
			PrettyPrint('			ItemListItem.appendChild(ItemListLink); ');
			PrettyPrint('');
			PrettyPrint('			// Put the list item in the list of maps');
			PrettyPrint('			ItemList.appendChild(ItemListItem); ');
			PrettyPrint('		} ');
			PrettyPrint('	} else { ');
			PrettyPrint('		// If this item is not in a known map');
			PrettyPrint('		// Show a message');
			PrettyPrint('		var ItemListItem = document.createElement("li"); ');
			PrettyPrint('		ItemListItem.innerHTML = "'.$dict_Search["NoResults"].'"; ');
			PrettyPrint('		ItemList.appendChild(ItemListItem); ');
			PrettyPrint('	} ');
			PrettyPrint('');
			PrettyPrint('	contentEl.appendChild(ItemList); ');
		}
		PrettyPrint('</script>');
	}
	
}

// TODO: When more than one language is available, 
// use convertBibleVerseLinkDEF, convertBibleVerseLinkEN functions 
function convertBibleVerseLink($book, $chap, $verse) {
	global $dict_Footer;
	
	// Get the book name from the databse (this is to be sure it is in the correct language
	$bookTXT = GetItemInfo("books", $book);
	
	// Convert the text to UTF for the dutch website to understand
	// Local and hosted websites use different encoding..
	if (mb_detect_encoding($bookTXT['name']) == "UTF-8") {
		// Already UTF-8
		$bookUTF = $bookTXT['name'];
		// $bookUTF = mb_detect_encoding($book['Name']);
	} else {
		$bookUTF = iconv("ISO-8859-1", "UTF-8", $bookTXT['name']);
	}
	
	// The first part of the webpage to refer to
	$weblink = $dict_Footer['DB_website'].$bookUTF."/".$chap;
	
	$bookAbv = ["GEN", "EXO", "LEV", "NUM", "DEU",
				"JOS", "JDG", "RUT", "1SA", "2SA",
				"1KI", "2KI", "1CH", "2CH", "EZR",
				"NEH", "EST", "JOB", "PSA", "PRO",
				"ECC", "SNG", "ISA", "JER", "LAM",
				"EZK", "DAN", "HOS", "JOL", "AMO",
				"OBA", "JON", "MIC", "NAM", "HAB",
				"ZEP", "HAG", "ZEC", "MAL", "MAT",
				"MRK", "LUK", "JHN", "ACT", "ROM",
				"1CO", "2CO", "GAL", "EPH", "PHP",
				"COL", "1TH", "2TH", "1TI", "2TI",
				"TIT", "PHM", "HEB", "JAS", "1PE",
				"2PE", "1JN", "2JN", "3JN", "JUD",
				"REV"];
	
	// Link to a certain part of the webpage, to get the exact verse mentioned
	$weblink2 = sprintf("#%s-%03d-%03d", $bookAbv[$book], $chap, $verse);
	
	return $weblink.$weblink2;
}

function convertBibleVerseText($book, $chap, $verse) {
	$text = "";
	if ($book != "") {		
		$bookTXT = GetItemInfo("books", $book);
		$text = $bookTXT['name']." ".$chap.":".$verse;
	}
	return $text;
}

?>

<script>
	function _Database_Helper_onLoad() {
		var ButtonPrev = document.getElementById("button_left");
		var ButtonNext = document.getElementById("button_right");
		var ButtonApp = document.getElementById("button_app");
		var ButtonAlp = document.getElementById("button_alp");
		
		// Check if this is page 0. If so, disable to prev button..	
		<?php			
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			if (!isset($_GET["sort"])) {
				$sort = 'app';
			} else {
				$sort = $_GET["sort"];
			}
			
			PrettyPrint("var PageNr = ".$page_nr.";", 1);
			PrettyPrint("var NrOfItems = ".GetNumberOfItems($id).";");
			PrettyPrint("var SortType = '".$sort."';");
		?>
		
		if (PageNr == 0) {
			// First page
			ButtonPrev.disabled = true;
			ButtonPrev.className = "off_button_<?php echo $$id; ?>";
		} else {
			// Not the first page
			ButtonPrev.disabled = false;
			ButtonPrev.className = "button_<?php echo $$id; ?>";
		}
		if (NrOfItems < 101) {
			// Last page
			ButtonNext.disabled = true;
			ButtonNext.className = "off_button_<?php echo $$id; ?>";
		} else {
			// Not the last page
			ButtonNext.disabled = false;
			ButtonNext.className = "button_<?php echo $$id; ?>";
		}
		
		switch (SortType) {
			case "app":
					ButtonApp.className = "sort_9_1";
					ButtonAlp.className = "sort_a_z";
				break;
			
			case "r-app":
					ButtonApp.className = "sort_1_9";
					ButtonAlp.className = "sort_a_z";
				break;
				
			case "alp":
					ButtonApp.className = "sort_1_9";
					ButtonAlp.className = "sort_z_a";
				break;
				
			case "r-alp":
					ButtonApp.className = "sort_1_9";
					ButtonAlp.className = "sort_a_z";
				break;
			
			default:
				break;
		}

		// Set the height of the left div, to the height of the right div
		var ContentsR = document.getElementsByClassName("contents_right")[0];
		var ContentsL = document.getElementsByClassName("contents_left")[0];
		
		ContentsL.setAttribute("style", "height: " + ContentsR.offsetHeight + "px");
		
		loadScroll();
	}
	
	function PrevPage() {		
		<?php
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			PrettyPrint("var PageNr = ".$page_nr.";", 1);
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
			
			PrettyPrint("var PageNr = ".$page_nr." + 1;", 1);
		?>
		
		oldHref = window.location.href;
		newHref = updateURLParameter(oldHref, "page", PageNr);
		window.location.href = newHref;
	}
	
	function SortOnAlphabet() {		
		Button = document.getElementById("button_alp");
	
		// The sort parameter only has to be updated
		oldHref = window.location.href;
		<?php if (isset($_GET["sort"]) && ($_GET["sort"]) == "alp") { ?>
			newHref = updateURLParameter(oldHref, "sort", "r-alp");
		<?php } else { ?>
			newHref = updateURLParameter(oldHref, "sort", "alp");
		<?php } ?>
		
		newHref = removeURLParameter(newHref, "page");
		window.location.href = newHref;
				
		return;
	}
	
	function SortOnAppearance() {
		Button = document.getElementById("button_app");
	
		// The sort parameter only has to be updated
		oldHref = window.location.href;
		<?php if (!isset($_GET["sort"])) { ?>
			newHref = updateURLParameter(oldHref, "sort", "r-app");
		<?php } else { ?>
			newHref = removeURLParameter(oldHref, "sort");
		<?php } ?>
		
		newHref = removeURLParameter(newHref, "page");
		window.location.href = newHref;
				
		return;
	}
</script>