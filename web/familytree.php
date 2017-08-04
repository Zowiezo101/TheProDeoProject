<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div id="familytree">
		<h1><?php echo $NavBar["Familytree"]; ?> (<?php echo $Content["tbd"]; ?>)</h1>
		<svg width="100%" height="1500px" id='svg'>
		
		</svg>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
// List of peoples
var Peoples = [<?php echo FindPeoples(); ?>];
	
// Create all the connections between parents and children
var highestLevel = setPeoples();

// This is a global variable, used calculate the level index
var levelCounter = [0];
for (var i = 0; i < highestLevel; i++) {
	levelCounter.push(0);
}

window.onload = function createFamilyTree() {
	var SVG = document.getElementById("svg");
	
	// List with all peoples who have generation level 0
	var PeopleId = 0;
	
	var People = Peoples[PeopleId];
	// Draw the current family tree
	People.drawFamilyTree(SVG);
}

function CreatePeople(name, ID, MotherID, FatherID, Gender) {
	this.name = name;
	this.ID = ID;
	this.FatherID = FatherID;
	this.MotherID = MotherID;
	this.Gender = Gender;
	
	// Own loop counters to prevent the counters messing each other up
	this.counter1 = 0;
	this.counter2 = 0;
	this.counter3 = 0;
	this.counter4 = 0;
	
	// Children of this person
	this.ChildIDs = [];
	// Generations from the first ancestor
	this.level = 0;
	// Which child is this
	this.ChildIndexM = 0;
	this.ChildIndexF = 0;
	// Which Person on this level is this
	this.levelIndex = 0;
	this.Location = [0, 0];
	
	/** setLevel function */
	this.setLevel = function (level) {
		if (level > this.level) {
			// Take the highest level possible
			this.level = level;
		}
		
		if (this.ChildIDs.length != 0)
		{
			for (this.counter1 = 0; this.counter1 < this.ChildIDs.length; this.counter1++) {
				// Update all children as well
				var Idx = this.ChildIDs[this.counter1];
				var Child = Peoples[Idx];
				
				Child.setLevel(this.level + 1);
			}
		}
		
		return this.level;
	}
	
	this.setLevelIndex = function() {
		var levelIndex = levelCounter[this.level];
		this.levelIndex = levelIndex;
		levelCounter[this.level]++;
		
		if (this.ChildIDs.length != 0)
		{
			for (this.counter2 = 0; this.counter2 < this.ChildIDs.length; this.counter2++) {
				// Update all children as well
				var Idx = this.ChildIDs[this.counter2];
				var Child = Peoples[Idx];
				
				Child.setLevelIndex();
			}
		}
	}
	
	/** CalcLocation function */
	this.calcLocations = function (id) {		
		// Calculate the Y coordinate
		var Y = this.level*75;
		this.Location[1] = Y;
		
		// Calculate the X coordinate
		var X = 300;
		
		// Check ID to be childs ID
		// If so, check location of child and resume normal operations
				
		// Is this the first person of the family tree?
		if (id != -1) {
			// If not, now many children does parent have?
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
				var middle = (numChildren + 1) / 2;
				
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
		
		this.Location[0] = X;
		
		// Do some addictional location checks
		// If something fails, use calcLocation on parent 
		// after new location for child has been established
		
		if (this.ChildIDs.length > 0) {
			for (this.counter3 = 0; this.counter3 < this.ChildIDs.length; this.counter3++) {
				// Update all children as well
				var Idx = this.ChildIDs[this.counter3];
				var Child = Peoples[Idx];
				
				Child.calcLocations(this.ID);
			}
		}
		
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
		var Group = document.createElementNS(svgns, "g");
		
		var x = this.Location[0];
		var y = this.Location[1];
		
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
		Text.innerHTML = this.name;
		
		Group.appendChild(Rect);
		Group.appendChild(Text);
		
		return Group;
	}
	
	/** */
	this.drawFamilyTree = function(SVG) {
		if (this.level == 0) {
			// Calculate some more stuff for the current family tree
			this.setLevelIndex();
			this.calcLocations(-1);
		}
		
		var Group = this.drawPeople();
		SVG.appendChild(Group);
		
		if (this.ChildIDs.length != 0)
		{
			for (this.counter4 = 0; this.counter4 < this.ChildIDs.length; this.counter4++) {
				// Update all children as well
				var Idx = this.ChildIDs[this.counter4];
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
		
	// Now set the generation levels
	var highestLevel = 0, returnedLevel = 0;
	for (i = 0; i < Peoples.length; i++) {
		var People = Peoples[i];
		
		// Start with zero, if the person has kids, they will be updated accordingly
		var returnedLevel = People.setLevel(0);
		if (returnedLevel > highestLevel) {
			highestLevel = returnedLevel;
		}
	}
	
	return highestLevel;
}
</script>