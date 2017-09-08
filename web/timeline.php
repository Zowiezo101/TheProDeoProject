<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div>
		<h1><?php echo $Content["tbd"]; ?></h1>
	</div>
	
	<div class="clearfix">
		<div class="contents_left">			
			<div id="timeline_bar">
				<!-- We fill this up in the TimeLine javascript code -->
			</div>
		</div>
		
		<div class="contents_right" id="timeline">
			<div id="default">
				<?php echo $Content["default_tl"]; ?>
			</div>
		</div>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
// Small list of enums:
var ENUM_UNDEFINED = -1;
var ENUM_SEC = 0;
var ENUM_MIN = 1;
var ENUM_HRS = 2;
var ENUM_DAY = 3;
var ENUM_WKS = 4;
var ENUM_MTH = 5;
var ENUM_YRS = 6;
var ENUM_DEC = 7;
var ENUM_CEN = 8;
var ENUM_MIL = 9;

// The amount needed to go to the "next level"
var MULTS = [60, 	// Seconds
	60,		// Minutes
	24,		// Hours
	7,		// Days
	4,		// Weeks
	12,		// Months
	10,		// Years
	10,		// Decades
	10		// Centuries
];

// List of events of which the order is known
var Events = [<?php echo FindEvents(); ?>];

// This is the list of events that can be chosen to create a timeline with
var EventsList = [];

// This is a global variable, used calculate the level index
var levelCounter = [];
var levelIDs = [];

// Global sizes, used to get everything on the SVG within the borders
var globalOffset = 0;
var globalHeight = 0;
			
// Create all the connections between parents and children
setEvents();

window.onload = function createTimeLine() {	
	// Make a nice list here to choose from the set of EventsList Events
	// When chosen, update focus in the timeline
	var timeLine = document.getElementById("timeline_bar");
	
	var table = document.createElement("table");
	for (var i = 0; i < EventsList.length; i++) {
		var EventId = EventsList[i];
		var Event = Events[EventId];
		
		var TableButton = document.createElement("button");
		TableButton.innerHTML = Event.name;
		TableButton.value = Event.ID;
		TableButton.onclick = SetSVG;
		
		var TableData = document.createElement("td");
		TableData.appendChild(TableButton);
	
		var TableRow = document.createElement("tr");
		TableRow.appendChild(TableData);
		
		table.appendChild(TableRow);
	}
	timeLine.appendChild(table);
}

function CreateEvent(name, ID, previousID, length, verses) {
	this.name = name;
	this.ID = ID;
	this.previousID = previousID;
	this.Length = length;
	this.verses = verses;
	
	// Own loop counter to prevent the counters messing each other up
	this.counter = 0;
	
	// Children of this event
	this.ChildIDs = [];
	this.ChildIndex;
	
	// Generations from the first ancestor
	this.level = -1;
	// Which event on this level is this
	this.levelIndex = -1;
	
	// The length variables and types
	this.lengthIndex = 2;
	this.lengthType = -1;
	
	// Location of this event
	this.Location = [-1, -1];
	this.offset = 0;
	
	/** setLevel function */
	this.setLevel = function (level) {
		var IDset = [];
		
		// alert("setLevel! Level " + level + " for child " + this.name);
		if (level > this.level) {
			// Take the highest level possible
			this.level = level;
		}
		
		if (this.ChildIDs.length != 0)
		{
			IDset = this.ChildIDs;
		}
		
		// This would have been much easier using recursive functions
		// But there is too much recursion for the browser to handle..
		return IDset;
	}
	
	/** CalcLocation function */
	this.calcLocation = function () {
		
		this.calcTime();
		
		// Calculate the Y coordinate
		var X = 0;
		
		// Calculate the X coordinate
		var Y = 0;
				
		// Is this the first person of the family tree?
		if (this.previousID != -1) {
			
			// ID number of the parent that will be used
			var id = this.previousID;
			
			var Parent = Events[id];
			var numChildren = Parent.ChildIDs.length;
			
			// Is it odd or even?
			var odd = numChildren % 2;
			
			// And which index do we have?
			var Index = this.ChildIndex;
			
			// Now calculate where our position should be
			if (odd) {
				var middle = ((numChildren + 1) / 2) - 1;
				
				if (Index == middle) {
					// Are we in the middle? 
					// Then just use parents X coordinate
					Y = Parent.Location[1];
				} else if (Index > middle) {
					// Are we on the right side of the middle?
					// Place the block on the right side of parents X coordinate
					var offset = Index - middle;
					Y = Parent.Location[1] + offset*75;
				} else {
					// Are we on the left side of the middle?
					// Place the block on the left side of parents X coordinate
					var offset = middle - Index;
					Y = Parent.Location[1] - offset*75;
				}
			} else {
				var middle = numChildren / 2;
				if (Index >= middle) {
					// Are we on the right side of the middle?
					// Place the block on the right side of parents X coordinate
					var offset = Index - middle;
					Y = (Parent.Location[1] + (75 / 2)) + offset*75;
				} else {
					// Are we on the left side of the middle?
					// Place the block on the left side of parents X coordinate
					var offset = middle - Index;
					Y = (Parent.Location[1] + (75 / 2)) - offset*75;
				}
			}
			
			X = Parent.Location[0] + (Parent.lengthIndex + 1)*50;
		}
		
		// This value is used, in case someone is overlapping with someone else
		this.Location[0] = X;
		this.Location[1] = Y + this.offset;
		
		return;
	}
	
	/** */
	this.getTimeColor = function () {
		var color = '';
		switch(this.lengthType) {
			case ENUM_SEC:
			color = "Green";
			break;
			
			case ENUM_MIN:
			color = "LightBlue";
			break;
			
			case ENUM_HRS:
			color = "Yellow";
			break;
			
			case ENUM_DAY:
			color = "Red";
			break;
			
			case ENUM_WKS:
			color = "Purple";
			break;
			
			case ENUM_MTH:
			color = "White";
			break;
			
			case ENUM_YRS:
			color = "LightGrey";
			break;
			
			case ENUM_DEC:
			color = "Violet";
			break;
			
			case ENUM_CEN:
			color = "Brown";
			break;
			
			case ENUM_MIL:
			color = "DarkBlue";
			break;
			
			default:
			color = "grey";
			break;
		}
		return color;
	}
	
	this.StringToValue = function (lengthTypeStr) {
		
		switch(lengthTypeStr) {
			case 's':
			lengthType = ENUM_SEC;
			break;
			
			case 'i':
			lengthType = ENUM_MIN;
			break;
			
			case 'h':
			lengthType = ENUM_HRS;
			break;
			
			case 'd':
			lengthType = ENUM_DAY;
			break;
			
			case 'w':
			lengthType = ENUM_WKS;
			break;
			
			case 'm':
			lengthType = ENUM_MTH;
			break;
			
			case 'y':
			lengthType = ENUM_YRS;
			break;
			
			case 'D':
			lengthType = ENUM_DEC;
			break;
			
			case 'C':
			lengthType = ENUM_CEN;
			break;
			
			case 'M':
			lengthType = ENUM_MIL;
			break;
			
			default:
			lengthType = ENUM_UNDEFINED;
			break;
		}
		
		return lengthType;
	}
	
	this.calcTime = function () {
		var timeParts = this.Length.split(" ");
		
		var lengthTypeStr = "";
		// Only a single time type given
		if ((timeParts.length == 1) && (timeParts[0] != ""))
		{
			lengthTypeStr = timeParts[0][timeParts[0].length - 1];
			
		} else if (timeParts > 1) {
			// Find the smallest timepart and convert the bigger timeparts to the same level
			// The code down here will try to find the biggest possible level of the sum 
			
		}
		
		var lengthType = this.StringToValue(lengthTypeStr);
		
		// Can we get a higher level length type?
		if (lengthType != -1) {
			// While loop here, getting a higher level untill a value is smaller than zero
		}
		
		this.lengthIndex = 2;
		this.lengthType = lengthType;
	}
	
	/** */
	this.drawEvent = function() {
		var svgns = "http://www.w3.org/2000/svg";
		var hrefns = "http://www.w3.org/1999/xlink";
		var Group = document.createElementNS(svgns, "g");
		
		// Move everything away from the upper border
		var x = this.Location[0];
		var y = this.Location[1] + globalOffset;
		
		// TODO: Debug
		if (this.level > 900) {
			return null;
		}
		
		if (this.previousID != -1) {
			// Draw the lines to the mother, to the middle of the bottom
			var Parent = Events[this.previousID];
			
			// And only if the parents are drawn as well
			if ((Parent.Location[0] != -1) && (Parent.Location[1] != -1)) {
				var x_parent = Parent.Location[0] + Parent.lengthIndex*50;
				var y_parent = Parent.Location[1] + 25 + globalOffset;
				
				// Make three lines, to get nice 90 degree angles
				var LineMother1 = document.createElementNS(svgns, "line");
				var LineMother2 = document.createElementNS(svgns, "line");
				var LineMother3 = document.createElementNS(svgns, "line");
				
				var x_halfway1 = x_parent + (50 / 2);
				var x_halfway2 = x - (50 / 2);
				
				// The first line goes only vertical, and halfway
				LineMother1.setAttributeNS(null, 'x1', x_parent);
				LineMother1.setAttributeNS(null, 'y1', y_parent);
				LineMother1.setAttributeNS(null, 'x2', x_halfway1);
				LineMother1.setAttributeNS(null, 'y2', y_parent);
				
				// The second line goes only horizontal, or diagonal
				LineMother2.setAttributeNS(null, 'x1', x_halfway1);
				LineMother2.setAttributeNS(null, 'y1', y_parent);
				LineMother2.setAttributeNS(null, 'x2', x_halfway2);
				LineMother2.setAttributeNS(null, 'y2', y + 25);
				
				// The last line goes only vertical, the second half
				LineMother3.setAttributeNS(null, 'x1', x_halfway2);
				LineMother3.setAttributeNS(null, 'y1', y + 25);
				LineMother3.setAttributeNS(null, 'x2', x);
				LineMother3.setAttributeNS(null, 'y2', y + 25);
				
				if (this.level == (Parent.level + 1)) {
					LineMother1.setAttributeNS(null, 'stroke', 'pink');
					LineMother2.setAttributeNS(null, 'stroke', 'pink');
					LineMother3.setAttributeNS(null, 'stroke', 'pink');
				
					LineMother1.setAttributeNS(null, 'stroke-width', '5');
					LineMother2.setAttributeNS(null, 'stroke-width', '5');
					LineMother3.setAttributeNS(null, 'stroke-width', '5');
				} else {
					LineMother1.setAttributeNS(null, 'stroke', 'deeppink');
					LineMother2.setAttributeNS(null, 'stroke', 'deeppink');
					LineMother3.setAttributeNS(null, 'stroke', 'deeppink');
				
					LineMother1.setAttributeNS(null, 'stroke-width', '2');
					LineMother2.setAttributeNS(null, 'stroke-width', '2');
					LineMother3.setAttributeNS(null, 'stroke-width', '2');
				}
				
				Group.appendChild(LineMother1);
				Group.appendChild(LineMother2);
				Group.appendChild(LineMother3);
			}
		}
		
		var Rect = document.createElementNS(svgns, "rect");		
		Rect.setAttributeNS(null, 'width', this.lengthIndex*50);
		Rect.setAttributeNS(null, 'height', 50);
		
		Rect.setAttributeNS(null, 'x', x);
		Rect.setAttributeNS(null, 'y', y);
		
		Rect.setAttributeNS(null, 'stroke', 'black');
		Rect.setAttributeNS(null, 'fill', this.getTimeColor());
		
		var Text = document.createElementNS(svgns, "text");		
		Text.setAttributeNS(null, 'width', this.lengthIndex*50);
		Text.setAttributeNS(null, 'height', 50);
		
		Text.setAttributeNS(null, 'x', x);
		Text.setAttributeNS(null, 'y', y + 25);
		Text.textContent = this.name;
		
		var newHref = updateURLParameter('<?php echo AddLangParam("events.php")?>', "id", this.ID);
		var Link = document.createElementNS(svgns, "a");
		Link.setAttributeNS(hrefns, 'xlink:href', newHref);
		Link.setAttributeNS(hrefns, 'xlink:title', '<?php echo $Content["link_event"]; ?>');
		Link.setAttributeNS(hrefns, 'target', "_top");
		Link.appendChild(Rect);
		Link.appendChild(Text);
				
		Group.appendChild(Link);		
		return Group;
	}
	
	/** */
	this.drawTimeLine = function(SVG) {	
		var IDset = [];
		
		// TODO: Debug
		var Group = this.drawEvent();
		if (Group != null) {
			SVG.appendChild(Group);
		}
		
		if (this.ChildIDs.length != 0)
		{
			IDset = this.ChildIDs;
		}
		
		// This would have been much easier using recursive functions
		// But there is too much recursion for the browser to handle..
		return IDset;
	}
}

function setEvents() {	
	// Create all connections
	for (i = 0; i < Events.length; i++) {
		var Event = Events[i];
	
		if (Event.previousID != -1) {
			var Parent = Events[Event.previousID];
			Event.ChildIndex = Parent.ChildIDs.length;
			Parent.ChildIDs.push(Event.ID);
		}
	}
	
	for (i = 0; i < Events.length; i++) {
		var Event = Events[i];
		if ((Event.previousID == -1) && (Event.ChildIDs.length > 0)) {
			// This event is at the beginning of a time line
			EventsList.push(Event.ID);
		}
	}
}
	
/** setLevels function */
function resetLevels(ID) {
	
	for (var m = 0; m < Events.length; m++)
	{		
		var Event = Events[m];
			
		// Reset levelIndex
		Event.level = -1;
		Event.Location = [-1, -1];
	}
	
	return;
}
	
/** setLevels function */
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
		// alert("Event with level " + levelCount + " : " + IDset);
		
		var newIDset = [];
		for (i = 0; i < IDset.length; i++) {
			var Event = Events[IDset[i]];
			var childSet = Event.setLevel(levelCount);
			
			// Create the ID set of the next generation
			newIDset = newIDset.concat(childSet);
		}
		levelCount++;
		
		// There are no more children to update
		IDset = newIDset;
		if (IDset.length == 0) {
			lastSet = 1;
		}
	}
	
	
	// Use minus one, since the levelcount was incremented on the last iteration
	return levelCount - 1;
}

function resetIndexes() {
	
	// Reset all numbers and levelIndexes to recalculate
	levelIDs = [];
	levelCounter = [];
	
	for (var m = 0; m < Events.length; m++)
	{		
		var Event = Events[m];
			
		// Reset levelIndex
		Event.levelIndex = -1;
		Event.offset = 0;
	}
	
	return;
}
	
/** setLevels function */
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
			var Event = Events[IDset[i]];
			var level = Event.level;
			
			var childSet = [];
			
			// Only use the children of the direct next generation to get the correct numbers
			for (var j = 0; j < Event.ChildIDs.length; j++) {
				Child = Events[Event.ChildIDs[j]];
				
				if (Child.level == (Event.level + 1)) {
					childSet.push(Child.ID);
				}
			}
			
			if (Event.levelIndex == -1) {
				var currentLevelIDs = levelIDs[level];
				currentLevelIDs.push(Event.ID);
				levelIDs[level] = currentLevelIDs;
			
				Event.levelIndex = levelCounter[level];
			}
			
			levelCounter[level] = levelIDs[level].length;
			
			// Create the ID set of the next generation
			newIDset = newIDset.concat(childSet);
		}
		
		// There are no more children to update
		IDset = newIDset;
		if (IDset.length == 0) {
			lastSet = 1;
		}
	}
	
	return;
}
	
/** calcLocations function */
function calcLocations() {
	
	// This breaks the while loop
	var done = 0;
	var MaxLevel = levelCounter.length;
	// var MaxLevel = 38;
	var debugFrom = 900;
	
	while (done == 0)
	{
		var collision = 0;
		// alert("Start while loop");
		
		// Draw the tree per level
		for (level = 0; level < MaxLevel; level++) {
			
			// The IDs of the people of the current level
			var IDset = levelIDs[level];
			
			for (i = 0; i < IDset.length; i++) {
				var Event = Events[IDset[i]];
				Event.calcLocation();
				
				// To get the width, keep the highest Y coordinate we can find
				if (Event.Location[1] > globalHeight) {
					globalHeight = Event.Location[1];
				}
				
				// Do a check on the location of the person
				if (Event.Location[1] < 25) {
					// Person seems to fall out of boundary
					// What offset do we need?
					var offset = 25 - Event.Location[1];
					
					if (offset > globalOffset) {
						// Take the highest offset found
						globalOffset = offset;
					}
				}
				
				if ((Event.level > 0) && (Event.levelIndex > 0)) {
					// alert("Name: " + Person.name + "\nIndex: " + Person.ID + "\nLevel: " + Person.level + "\nLevelIndex: " + Person.levelIndex);
					// alert("set of IDs of level " + Person.level + " :" + IDset);
					
					// Find the neighbour.
					// This is the person who has the same level, but levelIndex - 1
					var idNeighbour = IDset[Event.levelIndex - 1];
					// alert("ID of Neighbour: " + idNeighbour);
					var Neighbour = Events[idNeighbour];
					
					// alert("Neighbour Name: " + Neighbour.name + "\nLevel: " + Neighbour.level + "\nLevelIndex: " + Neighbour.levelIndex);
					
					// If we get in the if function, these two people are overlapping.
					// Or the lower person is too far up and needs to move down
					if (Event.Location[1] < (Neighbour.Location[1] + 75)) {
						// alert("set of IDs of level " + Person.level + " :" + IDset);
						if (Event.level >= debugFrom) {
						alert("Is people " + Event.name + " on location (" + Event.Location[0] + ", " + Event.Location[1] + ") overlapping with neighbour " + Neighbour.name + " on location (" + Neighbour.Location[0] + ", " + Neighbour.Location[1] + ")?");
						alert("Idx of " + Event.name + " :" + Event.levelIndex);
						alert("Idx of " + Neighbour.name + " :" + Neighbour.levelIndex);
						}
						
						var found = 0;
						var FoundID = -1;
						
						// Us
						var currentAncestorsR = [Event.ID];
						
						// The neighbour
						var currentAncestorsL = [Neighbour.ID];
						
						// Our starting level
						var currentLevel = Event.level;
						
						// found = 1;
						while (found == 0) {
							// Get a list with people that are a generation level lower (placed higher)
							if (Event.level >= debugFrom) {
							alert("Trying level: " + currentLevel);
							}
							var currentIDset = levelIDs[currentLevel];
							if (Event.level >= debugFrom) {
							alert("Set of people: " + currentIDset);
							}
							var currentIDset = levelIDs[currentLevel - 1];
							if (Event.level >= debugFrom) {
							alert("Set of people: " + currentIDset);
							}
							var newAncestorsR = [];
							var newAncestorsL = [];
							
							// Find all the possible ancestors for the right person
							for (var j = 0; j < currentAncestorsR.length; j++) {
								var EventR = Events[currentAncestorsR[j]];
								if (Event.level >= debugFrom) {
								alert("Working with " + EventR.name);
								}
								
								for (var k = 0; k < currentIDset.length; k++) {
									var ID = currentIDset[k];
									
									// Remember the list of ancestors that we find for this person
									if (ID == EventR.previousID) {
										newAncestorsR.push(ID);
										if (Event.level >= debugFrom) {
										alert("Found parentR with ID: " + ID);
										}
									}
								}
							}
							
							// Find all the possible ancestors for the left person
							for (var j = 0; j < currentAncestorsL.length; j++) {
								var EventL = Events[currentAncestorsL[j]];
								if (Event.level >= debugFrom) {
								alert("Working with " + EventL.name);
								}
								
								for (var k = 0; k < currentIDset.length; k++) {
									var ID = currentIDset[k];
									
									// Remember the list of ancestors that we find for this person
									if (ID == EventL.previousID) {
										newAncestorsL.push(ID);
										if (Event.level >= debugFrom) {
										alert("Found parentL with ID: " + ID);
										}
									}
								}
							}
							
							// Now check if we have a match on this level!
							var count = 0;
							for (var j = 0; j < newAncestorsR.length; j++) {
								var RightID = newAncestorsR[j];
								for (var k = 0; k < newAncestorsL.length; k++) {
									var LeftID = newAncestorsL[k];
									
									// We have found a match!
									// This is the ancestor that connects to two colliding people
									if (RightID == LeftID) {
										FoundID = RightID;
										found = 1;
										count++;
									}
								}
							}
							
							if (Event.level >= debugFrom) {
							alert("Count is equal to: " + count);
							}
							
							// collision = 1;
							if (found == 0) {
								// Keep the current data if we have a match
								currentAncestorsR = newAncestorsR;
								currentAncestorsL = newAncestorsL;
								currentLevel--;
							}
							
							if (currentLevel < 0) {
								// Couldn't find the parent?
								// alert("Could not find the connecting parent?");
								break;
							}
						}
						
						var Parent = Events[FoundID];
						var Child = null;
						if (Event.level >= debugFrom) {
						alert("The connecting parent is: " + Parent.name + " with ID: " + Parent.ID);
						}							
						
						// This is just a normal clash, fix in the normal way
						for (var k = currentAncestorsR.length; k > 0; k--) {
							var ID = currentAncestorsR[k - 1];
							
							for (var j = 0; j < Parent.ChildIDs.length; j++) {
								var ChildID = Parent.ChildIDs[j];

								if (ID == ChildID) {
									// Find the child that needs to be moved
									Child = Events[ID];
								}
							}
						}
							
						Child.offset += (Neighbour.Location[1] + 75) - Event.Location[1];
						if (Event.level >= debugFrom) {
						alert("The child is: " + Child.name + " with ID: " + Child.ID + " and moved with: " + Child.offset);
						}
						collision = 1;
						
						if (collision == 1) {
							// alert("Breaking first for-loop");
							break;
						}
					}
					
					// Update IDset to [ID] (start all over again)
					// newIDset = [ID];
					// break;
				}
			}
			
			if (collision == 1) {
				// Break out of the loop and start again
				// alert("Breaking second for-loop");
				break;
			}
		}
		
		// There are no more children to update
		if (level == MaxLevel) {
			done = 1;
		}
	}
	
	return;
}
	
/** setLevels function */
function drawTimeLine(SVG) {	
	
	// This breaks the while loop
	var done = 0;
	var MaxLevel = levelCounter.length;
	
	while (done == 0)
	{		
		// Draw the timeline per level
		for (level = 0; level < MaxLevel; level++) {
			
			var IDset = levelIDs[level];
			
			for (var i = 0; i < IDset.length; i++) {
				var Event = Events[IDset[i]];
				Event.drawTimeLine(SVG);
			}
		}
		
		// There are no more children to update
		if (level == MaxLevel) {
			done = 1;
		}
	}
	
	return;
}

function SetSVG(ClickEvent) {
	var EventId = ClickEvent.target.value;
	
	// The Time line div
	var TimeLine = document.getElementById("timeline");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	if (defaultText != null) {
		TimeLine.removeChild(defaultText);
	}
	
	// Set all the generation levels of all events
	// Start out clean
	resetLevels();
	var highestLevel = setLevels(EventId);
	
	resetIndexes();
	setIndexes(EventId, highestLevel);
	
	// Make the calculations to see where everyone should be placed
	globalOffset = 0;
	globalHeight = 0;
	calcLocations();
	
	// Start out clean, remove the current SVG
	var SVG = document.getElementById("svg");
	if (SVG != null) {
		TimeLine.removeChild(SVG);
	}
	
	// Create this element
	var svgns = "http://www.w3.org/2000/svg";
	SVG = document.createElementNS(svgns, "svg");
	SVG.id = "svg";
	TimeLine.appendChild(SVG);
	
	// Set the height and the width
	SVG.setAttribute('height', globalHeight + globalOffset + 75);	
	SVG.setAttribute('width', (highestLevel + 1)*150);
	
	// Draw the current family tree
	var Event = Events[EventId];
	drawTimeLine(SVG);
}

window.onerror = function(msg, url, linenumber) {
    alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
    return true;
}
</script>