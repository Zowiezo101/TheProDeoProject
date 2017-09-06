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
// List of events of which the order is known
var Events = [<?php echo FindEvents(); ?>];

// This is the list of events that can be chosen to create a timeline with
var EventsList = [];

// This is a global variable, used calculate the level index
var levelCounter = [];
var levelIDs = [];

// Global sizes, used to get everything on the SVG within the borders
var globalOffset = 0;
var globalWidth = 0;
			
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
	this.length = length;
	this.verses = verses;
	
	// Own loop counter to prevent the counters messing each other up
	this.counter = 0;
	
	// Children of this event
	this.ChildIDs = [];
	
	// Generations from the first ancestor
	this.level = -1;
	// Which event on this level is this
	this.levelIndex = -1;
	
	// Location of this event
	this.Location = [-1, -1];
	this.offset = 0;
}

function setEvents() {	
	// Create all connections
	for (i = 0; i < Events.length; i++) {
		var Event = Events[i];
	
		if (Event.previousID != -1) {
			var Parent = Events[Event.previousID];
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

function SetSVG() {
	
}

window.onerror = function(msg, url, linenumber) {
    alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
    return true;
}
</script>