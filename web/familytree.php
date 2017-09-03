<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div class="clearfix">
		<div class="contents_left">			
			<div id="family_bar">
				<!-- We fill this up in the FamilyTree javascript code -->
			</div>
		</div>
		
		<div class="contents_right" id="familytree">
			<div id="default">
				<?php echo $Content["default_ft"]; ?>
			</div>
		</div>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
// List of peoples
var Peoples = [<?php echo FindPeoples(); ?>];

// This is the list of peoples who can be chosen to create a family tree with
var PeoplesList = [];

// This is a global variable, used calculate the level index
var levelCounter = [];
var levelIDs = [];

// Global sizes, used to get everything on the SVG within the borders
var globalOffset = 0;
var globalWidth = 0;
			
// Create all the connections between parents and children
setPeoples();

window.onload = function createFamilyTree() {	
	// Make a nice list here to choose from the set of PeopleList people
	// When chosen, update PeopleId and redraw page
	var familyBar = document.getElementById("family_bar");
	
	var table = document.createElement("table");
	for (var i = 0; i < PeoplesList.length; i++) {
		var PeopleId = PeoplesList[i];
		var People = Peoples[PeopleId];
		
		var TableButton = document.createElement("button");
		TableButton.innerHTML = People.name;
		TableButton.value = People.ID;
		TableButton.onclick = SetSVG;
		
		var TableData = document.createElement("td");
		TableData.appendChild(TableButton);
	
		var TableRow = document.createElement("tr");
		TableRow.appendChild(TableData);
		
		table.appendChild(TableRow);
	}
	familyBar.appendChild(table);
}

function CreatePeople(name, ID, MotherID, FatherID, Gender) {
	this.name = name;
	this.ID = ID;
	this.FatherID = FatherID;
	this.MotherID = MotherID;
	this.Gender = Gender;
	
	// Own loop counter to prevent the counters messing each other up
	this.counter = 0;
	
	// Children of this person
	this.ChildIDs = [];
	// Which child is this
	this.ChildIndexM = 0;
	this.ChildIndexF = 0;
	
	// Generations from the first ancestor
	this.level = -1;
	// Which Person on this level is this
	this.levelIndex = -1;
	
	// Location of this person
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
		
		// Calculate the Y coordinate
		var Y = this.level*75;
		
		// Calculate the X coordinate
		var X = 0;
				
		// Is this the first person of the family tree?
		if ((this.MotherID != -1) || (this.FatherID != -1)) {
			
			// ID number of the parent that will be used
			var id = -1;
			
			// Who are we gonna use?
			if (this.MotherID == -1) {
				// Use daddy if mommy isn't known
				id = this.FatherID;
			} else if (this.FatherID == -1) {
				// Use mommy if daddy isn't known
				id = this.MotherID;
			} else {
				// Both parents are known
				// Use the parent with the highest generation level.
				// So the parent that is placed the lowest
				var Mother = Peoples[this.MotherID];
				var Father = Peoples[this.FatherID];
				if (Father.level > Mother.level) {
					id = this.FatherID;
				} else {
					id = this.MotherID;
				}
			}
			
			var Parent = Peoples[id];
			var numChildren = Parent.ChildIDs.length;
			
			// Is it odd or even?
			var odd = numChildren % 2;
			
			// And which index do we have?
			var Index = 0
			if (this.MotherID == id) {
				// Use the one from mommy
				Index = this.ChildIndexM;
			} else {
				// Use the one from daddy
				Index = this.ChildIndexF;
			}
			
			// Now calculate where our position should be
			if (odd) {
				var middle = ((numChildren + 1) / 2) - 1;
				
				if (Index == middle) {
					// Are we in the middle? 
					// Then just use parents X coordinate
					X = Parent.Location[0];
				} else if (Index > middle) {
					// Are we on the right side of the middle?
					// Place the block on the right side of parents X coordinate
					var offset = Index - middle;
					X = Parent.Location[0] + offset*150;
				} else {
					// Are we on the left side of the middle?
					// Place the block on the left side of parents X coordinate
					var offset = middle - Index;
					X = Parent.Location[0] - offset*150;
				}
			} else {
				var middle = numChildren / 2;
				if (Index >= middle) {
					// Are we on the right side of the middle?
					// Place the block on the right side of parents X coordinate
					var offset = Index - middle;
					X = (Parent.Location[0] + 75) + offset*150;
				} else {
					// Are we on the left side of the middle?
					// Place the block on the left side of parents X coordinate
					var offset = middle - Index;
					X = (Parent.Location[0] + 75) - offset*150;
				}
			}
		}
		
		// This value is used, in case someone is overlapping with someone else
		this.Location[0] = X + this.offset;
		this.Location[1] = Y;
		
		return;
	}
	
	/** */
	this.getGenderColor = function () {
		var color = '';
		switch(this.Gender) {
			case 0:
			color = 'blue';
			break;
			
			case 1:
			color = 'pink';
			break;
			
			default:
			color = 'grey';
		}
		return color;
	}
	
	/** */
	this.drawPeople = function() {
		var svgns = "http://www.w3.org/2000/svg";
		var hrefns = "http://www.w3.org/1999/xlink";
		var Group = document.createElementNS(svgns, "g");
		
		// Move everything away from the left border
		var x = this.Location[0] + globalOffset;
		var y = this.Location[1];
		
		// TODO: Debug
		if (this.level > 90) {
			return null;
		}
		
		if (this.MotherID != -1) {
			// Draw the lines to the mother, to the middle of the bottom
			var Parent = Peoples[this.MotherID];
			
			// And only if the parents are drawn as well
			if ((Parent.Location[0] != -1) && (Parent.Location[1] != -1)) {
				var x_parent = Parent.Location[0] + 50 + globalOffset;
				var y_parent = Parent.Location[1] + 50;
				
				// Make three lines, to get nice 90 degree angles
				var LineMother1 = document.createElementNS(svgns, "line");
				var LineMother2 = document.createElementNS(svgns, "line");
				var LineMother3 = document.createElementNS(svgns, "line");
				
				var y_halfway1 = y_parent + (25 / 2);
				var y_halfway2 = y - (25 / 2);
				
				// The first line goes only vertical, and halfway
				LineMother1.setAttributeNS(null, 'x1', x_parent);
				LineMother1.setAttributeNS(null, 'y1', y_parent);
				LineMother1.setAttributeNS(null, 'x2', x_parent);
				LineMother1.setAttributeNS(null, 'y2', y_halfway1);
				
				// The second line goes only horizontal, or diagonal
				LineMother2.setAttributeNS(null, 'x1', x_parent);
				LineMother2.setAttributeNS(null, 'y1', y_halfway1);
				LineMother2.setAttributeNS(null, 'x2', x + 50);
				LineMother2.setAttributeNS(null, 'y2', y_halfway2);
				
				// The last line goes only vertical, the second half
				LineMother3.setAttributeNS(null, 'x1', x + 50);
				LineMother3.setAttributeNS(null, 'y1', y_halfway2);
				LineMother3.setAttributeNS(null, 'x2', x + 50);
				LineMother3.setAttributeNS(null, 'y2', y);
				
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
		
		if (this.FatherID != -1) {
			// Draw the lines to the father, to the middle of the bottom
			var Parent = Peoples[this.FatherID];
			
			// And only if the parents are drawn as well
			if ((Parent.Location[0] != -1) && (Parent.Location[1] != -1)) {
				var x_parent = Parent.Location[0] + 50 + globalOffset;
				var y_parent = Parent.Location[1] + 50;
				
				// Make three lines, to get nice 90 degree angles
				var LineFather1 = document.createElementNS(svgns, "line");
				var LineFather2 = document.createElementNS(svgns, "line");
				var LineFather3 = document.createElementNS(svgns, "line");
				
				var y_halfway1 = y_parent + (25 / 2);
				var y_halfway2 = y - (25 / 2);
				
				// The first line goes only vertical, and halfway
				LineFather1.setAttributeNS(null, 'x1', x_parent);
				LineFather1.setAttributeNS(null, 'y1', y_parent);
				LineFather1.setAttributeNS(null, 'x2', x_parent);
				LineFather1.setAttributeNS(null, 'y2', y_halfway1);
				
				// The second line goes only horizontal, or diagonal
				LineFather2.setAttributeNS(null, 'x1', x_parent);
				LineFather2.setAttributeNS(null, 'y1', y_halfway1);
				LineFather2.setAttributeNS(null, 'x2', x + 50);
				LineFather2.setAttributeNS(null, 'y2', y_halfway2);
				
				// The last line goes only vertical, the second half
				LineFather3.setAttributeNS(null, 'x1', x + 50);
				LineFather3.setAttributeNS(null, 'y1', y_halfway2);
				LineFather3.setAttributeNS(null, 'x2', x + 50);
				LineFather3.setAttributeNS(null, 'y2', y);
				
				
				if (this.level == (Parent.level + 1)) {
					LineFather1.setAttributeNS(null, 'stroke', 'blue');
					LineFather2.setAttributeNS(null, 'stroke', 'blue');
					LineFather3.setAttributeNS(null, 'stroke', 'blue');
				
					LineFather1.setAttributeNS(null, 'stroke-width', '5');
					LineFather2.setAttributeNS(null, 'stroke-width', '5');
					LineFather3.setAttributeNS(null, 'stroke-width', '5');
				} else {
					LineFather1.setAttributeNS(null, 'stroke', 'darkblue');
					LineFather2.setAttributeNS(null, 'stroke', 'darkblue');
					LineFather3.setAttributeNS(null, 'stroke', 'darkblue');
				
					LineFather1.setAttributeNS(null, 'stroke-width', '2');
					LineFather2.setAttributeNS(null, 'stroke-width', '2');
					LineFather3.setAttributeNS(null, 'stroke-width', '2');
				}
				
				Group.appendChild(LineFather1);
				Group.appendChild(LineFather2);
				Group.appendChild(LineFather3);
			}
		}
		
		var Rect = document.createElementNS(svgns, "rect");		
		Rect.setAttributeNS(null, 'width', 100);
		Rect.setAttributeNS(null, 'height', 50);
		
		Rect.setAttributeNS(null, 'x', x);
		Rect.setAttributeNS(null, 'y', y);
		
		Rect.setAttributeNS(null, 'stroke', 'black');
		Rect.setAttributeNS(null, 'fill', this.getGenderColor());
		
		var Text = document.createElementNS(svgns, "text");
		Text.setAttributeNS(null, 'x', x);
		Text.setAttributeNS(null, 'y', y + 25);
		Text.textContent = this.name;
		
		var newHref = updateURLParameter('<?php echo AddLangParam("peoples.php")?>', "id", this.ID);
		var Link = document.createElementNS(svgns, "a");
		Link.setAttributeNS(hrefns, 'xlink:href', newHref);
		Link.setAttributeNS(hrefns, 'xlink:title', '<?php echo $Content["link_people"]; ?>');
		Link.setAttributeNS(hrefns, 'target', "_top");
		Link.appendChild(Rect);
		Link.appendChild(Text);
		
		Group.appendChild(Link);		
		return Group;
	}
	
	/** */
	this.drawFamilyTree = function(SVG) {	
		
		// TODO: Debug
		var Group = this.drawPeople();
		if (Group != null) {
			SVG.appendChild(Group);
		}
		
		if (this.ChildIDs.length != 0)
		{
			for (this.counter = 0; this.counter < this.ChildIDs.length; this.counter++) {
				// Update all children as well
				var Idx = this.ChildIDs[this.counter];
				var Child = Peoples[Idx];
				
				Child.drawFamilyTree(SVG);
			}

		}
	}
}

function setPeoples() {	
	// Create all connections
	for (i = 0; i < Peoples.length; i++) {
		var People = Peoples[i];
	
		if (People.MotherID != -1) {
			var Mother = Peoples[People.MotherID];
			People.ChildIndexM = Mother.ChildIDs.length;
			Mother.ChildIDs.push(People.ID);
		}
		
		if (People.FatherID != -1) {
			var Father = Peoples[People.FatherID];
			People.ChildIndexF = Father.ChildIDs.length;
			
			if (People.FatherID != People.MotherID) {
				Father.ChildIDs.push(People.ID);
			}
		}
	}
	
	for (i = 0; i < Peoples.length; i++) {
		var People = Peoples[i];
		if ((People.MotherID == -1) && (People.FatherID == -1) && (People.ChildIDs.length > 0)) {
			// This person is on top of a family tree
			PeoplesList.push(People.ID);
		}
	}
}
	
/** setLevels function */
function resetLevels(ID) {
	
	for (var m = 0; m < Peoples.length; m++)
	{		
		var Person = Peoples[m];
			
		// Reset levelIndex
		Person.level = -1;
		Person.Location = [-1, -1];
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
		// alert("People with level " + levelCount + " : " + IDset);
		
		var newIDset = [];
		for (i = 0; i < IDset.length; i++) {
			var Person = Peoples[IDset[i]];
			var childSet = Person.setLevel(levelCount);
			
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

function resetIndexes() {
	
	// Reset all numbers and levelIndexes to recalculate
	levelIDs = [];
	levelCounter = [];
	
	for (var m = 0; m < Peoples.length; m++)
	{		
		var Person = Peoples[m];
			
		// Reset levelIndex
		Person.levelIndex = -1;
		Person.offset = 0;
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
			var Person = Peoples[IDset[i]];
			var level = Person.level;
			
			var childSet = [];
			
			// Only use the children of the direct next generation to get the correct numbers
			for (var j = 0; j < Person.ChildIDs.length; j++) {
				Child = Peoples[Person.ChildIDs[j]];
				
				if (Child.level == (Person.level + 1)) {
					childSet.push(Child.ID);
				}
			}
		
			// Store all the unique IDs and keep track on the level they are on
			// alert("Adding " + Person.name + " with ID " + Person.ID + " to array of level " + level + "\nArray: " + levelIDs[level]);
			
			// Keep track of the amount of people on a certain level
			// Only if the levelIndex is not already set
			if (Person.levelIndex == -1) {
				var currentLevelIDs = levelIDs[level];
				currentLevelIDs.push(Person.ID);
				levelIDs[level] = currentLevelIDs;
			
				Person.levelIndex = levelCounter[level];
			} else {
				// alert("We have a double!!");
				// alert("Person " + Person.name + " already has it's levelIndex set to " + Person.levelIndex);
				// alert("It is requested to set it from " + Person.levelIndex + " to " + levelCounter[level]);
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
	
/** calcLocations function */
function calcLocations(firstID, highestLevel) {
	
	// This breaks the while loop
	var done = 0;
	var MaxLevel = levelCounter.length;
	// var MaxLevel = 38;
	var debugFrom = 90;
	
	while (done == 0)
	{
		var collision = 0;
		// alert("Start while loop");
		
		// Draw the tree per level
		for (level = 0; level < MaxLevel; level++) {
			
			// The IDs of the people of the current level
			var IDset = levelIDs[level];
			
			for (i = 0; i < IDset.length; i++) {
				var Person = Peoples[IDset[i]];
				Person.calcLocation();
				
				// To get the width, keep the highest X coordinate we can find
				if (Person.Location[0] > globalWidth) {
					globalWidth = Person.Location[0];
				}
				
				// Do a check on the location of the person
				if (Person.Location[0] < 50) {
					// Person seems to fall out of boundary
					// What offset do we need?
					var offset = 50 - Person.Location[0];
					
					if (offset > globalOffset) {
						// Take the highest offset found
						globalOffset = offset;
					}
				}
				
				if ((Person.level > 0) && (Person.levelIndex > 0)) {
					// alert("Name: " + Person.name + "\nIndex: " + Person.ID + "\nLevel: " + Person.level + "\nLevelIndex: " + Person.levelIndex);
					// alert("set of IDs of level " + Person.level + " :" + IDset);
					
					// Find the neighbour.
					// This is the person who has the same level, but levelIndex - 1
					var idNeighbour = IDset[Person.levelIndex - 1];
					// alert("ID of Neighbour: " + idNeighbour);
					var Neighbour = Peoples[idNeighbour];
					
					// alert("Neighbour Name: " + Neighbour.name + "\nLevel: " + Neighbour.level + "\nLevelIndex: " + Neighbour.levelIndex);
					
					// If we get in the if function, these two people are overlapping.
					// Or the right person is too far left and needs to move right
					if (Person.Location[0] < (Neighbour.Location[0] + 150)) {
						// alert("set of IDs of level " + Person.level + " :" + IDset);
						if (Person.level >= debugFrom) {
						alert("Is people " + Person.name + " on location (" + Person.Location[0] + ", " + Person.Location[1] + ") overlapping with neighbour " + Neighbour.name + " on location (" + Neighbour.Location[0] + ", " + Neighbour.Location[1] + ")?");
						alert("Idx of " + Person.name + " :" + Person.levelIndex);
						alert("Idx of " + Neighbour.name + " :" + Neighbour.levelIndex);
						}
						
						// Now find the parent that connects these two peoples
						// Actually, find the two children (ancestors) of that parent.
						// if ((Person.MotherID != -1) && (Person.MotherID == Neighbour.MotherID)) {
							// // alert("The mother is the connecting source");
							// Person.offset = (Neighbour.Location[0] + 150) - Person.Location[0];
							// // alert("Setting offset to: " + Person.offset);
							// collision = 1;
						// } else if ((Person.FatherID != -1) && (Person.FatherID == Neighbour.FatherID)) {
							// // alert("The father is the connecting source");
							// Person.offset = (Neighbour.Location[0] + 150) - Person.Location[0];
							// // alert("Setting offset to: " + Person.offset);
							// collision = 1;
						// } else {
						
							var found = 0;
							var FoundID = -1;
							
							// Us
							var currentAncestorsR = [Person.ID];
							
							// The neighbour
							var currentAncestorsL = [Neighbour.ID];
							
							// Our starting level
							var currentLevel = Person.level;
							
							// found = 1;
							while (found == 0) {
								// Get a list with people that are a generation level lower (placed higher)
								if (Person.level >= debugFrom) {
								alert("Trying level: " + currentLevel);
								}
								var currentIDset = levelIDs[currentLevel];
								if (Person.level >= debugFrom) {
								alert("Set of people: " + currentIDset);
								}
								var currentIDset = levelIDs[currentLevel - 1];
								if (Person.level >= debugFrom) {
								alert("Set of people: " + currentIDset);
								}
								var newAncestorsR = [];
								var newAncestorsL = [];
								
								// Find all the possible ancestors for the right person
								for (var j = 0; j < currentAncestorsR.length; j++) {
									var PersonR = Peoples[currentAncestorsR[j]];
									if (Person.level >= debugFrom) {
									alert("Working with " + PersonR.name);
									}
									
									for (var k = 0; k < currentIDset.length; k++) {
										var ID = currentIDset[k];
										
										// Remember the list of ancestors that we find for this person
										if ((ID == PersonR.MotherID) || (ID == PersonR.FatherID)) {
											newAncestorsR.push(ID);
											if (Person.level >= debugFrom) {
											alert("Found parentR with ID: " + ID);
											}
										}
									}
								}
								
								// Find all the possible ancestors for the left person
								for (var j = 0; j < currentAncestorsL.length; j++) {
									var PersonL = Peoples[currentAncestorsL[j]];
									if (Person.level >= debugFrom) {
									alert("Working with " + PersonL.name);
									}
									
									for (var k = 0; k < currentIDset.length; k++) {
										var ID = currentIDset[k];
										
										// Remember the list of ancestors that we find for this person
										if ((ID == PersonL.MotherID) || (ID == PersonL.FatherID)) {
											newAncestorsL.push(ID);
											if (Person.level >= debugFrom) {
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
								
								if (Person.level >= debugFrom) {
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
							
							var Parent = Peoples[FoundID];
							var Child = null;
							if (Person.level >= debugFrom) {
							alert("The connecting parent is: " + Parent.name + " with ID: " + Parent.ID);
							}
							
							var specialCase = 0;
							// It seems that the two parents of a kid are actually related..
							if (count > 1) {
								var Children = [];
								
								// Does left or right have related parents?
								if (newAncestorsR.length > 1) {
									
									for (var k = 0; k < currentAncestorsR.length; k++) {
										var ID = currentAncestorsR[k];
										
										for (var j = 0; j < Parent.ChildIDs.length; j++) {
											var ChildID = Parent.ChildIDs[j];

											if (ID == ChildID) {
												// Find all the matching children
												Children.push(ID);
												// alert("Right parent " + Children.length + " has ID " + ID);
											}
										}
									}
									// alert("Right ancesters are related: " + count + ", " + newAncestorsR.length);
									
								} else if (newAncestorsL.length > 1) {
									
									for (var k = 0; k < currentAncestorsL.length; k++) {
										var ID = currentAncestorsL[k];
										
										for (var j = 0; j < Parent.ChildIDs.length; j++) {
											var ChildID = Parent.ChildIDs[j];

											if (ID == ChildID) {
												// Find all the matching children
												Children.push(ID);
												// alert("Left parent " + Children.length + " has ID " + ID);
											}
										}
									}
									// alert("Left ancesters are related: " + count + ", " + newAncestorsL.length);
									
								}
								
								if (Children.length == 2) {
									if (Person.level >= debugFrom) {
									alert("Amount of related parents: " + Children.length);
									alert("Related parents: " + Children);
									}
									
									var Child1 = Peoples[Children[0]];
									var Child2 = Peoples[Children[1]];
									var difLevelIndex = Child2.levelIndex - Child1.levelIndex;
									
									if (Person.level >= debugFrom) {
									alert("The levelIndex has a difference of " + difLevelIndex);
									}
									
									// Are they next to each other?
									if (difLevelIndex != 1) {
										// There are not next to each other..
										// Change the order of the children to put them next to each other
										
										// Are we using mommy or daddy?
										if (Child1.MotherID == Parent.ID) {
											// Use the one from mommy
											var Index1 = Child1.ChildIndexM + 1;
											var Index2 = Child2.ChildIndexM + 1;
										} else {
											// Use the one from daddy
											var Index1 = Child1.ChildIndexF + 1;
											var Index2 = Child2.ChildIndexF + 1;
										}
										
										// The first slice is from the beginning of the list to Child1.
										var firstSlice = Parent.ChildIDs.slice(0, Index1);
										
										// The second slice is from Child1 to Child2 (but not including Child2)
										var secondSlice = Parent.ChildIDs.slice(Index1, Index2 - 1);
										
										// The last slice is from Child2 to the end of the list (but not including Child2)
										var thirdSlice = Parent.ChildIDs.slice(Index2, Parent.ChildIDs.length);
										
										// Add Child2 right after Child1
										firstSlice.push(Child2.ID);
										
										// Add it all together as a new list and make that the new ChildIDs list of the parent
										newChildIDs = firstSlice.concat(secondSlice, thirdSlice);
										if (Person.level >= debugFrom) {
										alert("Updating parents ChildIDs from: " + Parent.ChildIDs + "\nto: " + newChildIDs);
										}
										Parent.ChildIDs = newChildIDs;
										
										specialCase = 1;
										
									}
									// set specialCase to one and fix it in the specialCase part
									// do this by changing the levelIndex and the levelIDs to set
									// these two parents next to each other.
									// this should fix the problem..
									// re-try without setting any offset, let the code decide for itself
								}
							}							
							
							// This is just a normal clash, fix in the normal way
							if (specialCase == 0) {
								for (var k = currentAncestorsR.length; k > 0; k--) {
									var ID = currentAncestorsR[k - 1];
									
									for (var j = 0; j < Parent.ChildIDs.length; j++) {
										var ChildID = Parent.ChildIDs[j];

										if (ID == ChildID) {
											// Find the child that needs to be moved
											Child = Peoples[ID];
										}
									}
								}
								
								Child.offset += (Neighbour.Location[0] + 150) - Person.Location[0];
								if (Person.level >= debugFrom) {
								alert("The child is: " + Child.name + " with ID: " + Child.ID + " and moved with: " + Child.offset);
								}
							} else if (specialCase == 1) {
								// Special case! It seems that the two parents of a kid are actually related..
								// Some changes were made, now recalculate!
								resetIndexes();
								setIndexes(firstID, highestLevel);
							}
							collision = 1;
						// }
						
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

function SetSVG(Event) {
	var PeopleId = Event.target.value;
	
	// The FamilyTree div
	var FamilyTree = document.getElementById("familytree");
	
	// Remove the default text
	var defaultText = document.getElementById("default");
	if (defaultText != null) {
		FamilyTree.removeChild(defaultText);
	}
	
	// Set all the generation levels of all people
	// Start out clean
	resetLevels();
	var highestLevel = setLevels(PeopleId);
	
	resetIndexes();
	setIndexes(PeopleId, highestLevel);
	
	// Make the calculations to see where everyone should be placed
	globalOffset = 0;
	globalWidth = 0;
	calcLocations(PeopleId, highestLevel);
	
	// Start out clean, remove the current SVG
	var SVG = document.getElementById("svg");
	if (SVG != null) {
		FamilyTree.removeChild(SVG);
	}
	
	// Create this element
	var svgns = "http://www.w3.org/2000/svg";
	SVG = document.createElementNS(svgns, "svg");
	SVG.id = "svg";
	FamilyTree.appendChild(SVG);
	
	// Set the height and the width
	SVG.setAttribute('height', (highestLevel + 1)*75);	
	SVG.setAttribute('width', globalWidth + globalOffset + 150);
	
	// Draw the current family tree
	var People = Peoples[PeopleId];
	People.drawFamilyTree(SVG);
}

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

window.onerror = function(msg, url, linenumber) {
    alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
    return true;
}
</script>