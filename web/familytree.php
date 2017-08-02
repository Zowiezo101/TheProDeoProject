<!DOCTYPE html>
<html>
	<?php require "layout/header.php"; ?>
	
	<div id="familytree">
		<h1><?php echo $NavBar["Familytree"]; ?></h1>
		<svg width="100%" height="1500px" id='svg'>
		
		</svg>
	</div>
	
	<?php require "layout/footer.php" ?>
</html>

<script>
// List of peoples
var Peoples = [<?php echo FindPeoples(); ?>];

window.onload = function CreateFamilyTree() {
	setPeoples();
	
	var SVG = document.getElementById("svg");
	
	for (k = 0; k < Peoples.length; k++) {
		var People = Peoples[k];
		People.CalcLocation();
		
		var Group = People.DrawPerson();
		SVG.appendChild(Group);
	}
}

function CreatePeople(name, ID, MotherID, FatherID, Gender) {
	this.name = name;
	this.ID = ID;
	this.FatherID = FatherID;
	this.MotherID = MotherID;
	this.ChildIDs = [];
	this.Gender = Gender;
	this.level = 0;
	
	this.Location = [0, 0];
	this.counter = 0;
	
	this.setLevel = function (level) {
		if (level > this.level) {
			// Take the highest level possible
			this.level = level;
		}
		// alert("Setting level of " + this.name + " to: " + this.level);
		
		if (this.ChildIDs.length != 0)
		{
			for (this.counter = 0; this.counter < this.ChildIDs.length; this.counter++) {
				// Update all children as well
				Idx = this.ChildIDs[this.counter];
				Child = Peoples[Idx];
				
				// alert("Setting level for " + this.name + " and child: " + Child.name + " to: " + (this.level + 1) + "\n");
				Child.setLevel(this.level + 1);
			}
		}
		
		return;
	}
	
	this.CalcLocation = function () {
		// alert("Name: " + this.name + "\nID: " + this.ID + "\nDaddy: " + this.FatherID + "\nMommy: " + this.MotherID + "\nNum of children: " + this.ChildIDs.length + "\nLevel: " + this.level + "\n");
		
		// Calculate the Y coordinate
		this.Location[1] = this.level*75;
		
		return;
	}
	
	this.getGenderColor = function () {
		color = '';
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
	
	this.DrawPerson = function() {
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
}

function setPeoples() {
	// Create all connections
	for (i = 0; i < Peoples.length; i++) {
		var People = Peoples[i];
	
		if (People.MotherID != -1) {
			Mother = Peoples[People.MotherID];
			Mother.ChildIDs.push(People.ID);
		}
		
		if (People.FatherID != -1) {
			Father = Peoples[People.FatherID];
			Father.ChildIDs.push(People.ID);
		}
	}
		
	// Now set the generation levels
	for (i = 0; i < Peoples.length; i++) {
		var People = Peoples[i];
		
		// Start with zero, if the person has kids, they will be updated accordingly
		// alert(People.name + " setlevel with ID " + People.ID + " (" + i +") (" + People.level + ")\n");
		People.setLevel(0);
	}
		
	// for (i = 0; i < Peoples.length; i++) {
		// var People = Peoples[i];
		// alert(People.name + " setlevel done! (" + People.level + ")\n");
	// }
}
</script>