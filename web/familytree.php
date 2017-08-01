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
window.onload = function CreateFamilyTree() {
	var Peoples = [<?php echo FindPeoples(); ?>];
	var SVG = document.getElementById("svg");
	
	for (i = 0; i < Peoples.length; i++) {
		var People = Peoples[i];
		People.Location = [0, 100*i];
		var Group = People.DrawPerson();
		SVG.appendChild(Group);
	}
}

function CreatePeople(name, ID, FatherID, MotherID, Gender) {
	this.name = name;
	this.ID = ID;
	this.FatherID = FatherID;
	this.MotherID = MotherID;
	this.Gender = Gender;
	
	this.Location = [0, 0];
	
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
</script>