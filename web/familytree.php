<?php
// Make it easier to copy/paste code or make a new file
if (!isset($id)) {
    $id = "familytree";
}

require_once "layout/template.php"; 

function familytree_Helper_layout() {
    _Map_Helper_layout();
} 
?>

<script>


    function CreatePeople(name, ID, MotherID, FatherID, Gender) {
        this.name = name;
        this.ID = ID;
        this.FatherID = FatherID;
        this.MotherID = MotherID;
        this.Gender = Gender;
        
        // Own loop counter to prevent the counters messing each other up
        this.counter = 0;
        
        // Ancestors of this person
        this.AncestorIDs = [];
        
        // Children of this person
        this.ChildIDs = [];
        // Which child is this
        this.ChildIndexM = 0;
        this.ChildIndexF = 0;
        
        // Generations from the first ancestor
        this.level = -1;
        // Which Item on this level is this
        this.levelIndex = -1;
        
        // Location of this person
        this.Location = [-1, -1];
        this.offset = 0;
        
        // Make sure this person isn't duplicated
        this.drawn = 0;
        
        /** setLevel function */
        this.setLevel = function (level) {
            var IDset = [];
            
            if (level > this.level) {
                // Take the highest level possible
                this.level = level;
            }
            
            if (this.ChildIDs.length !== 0)
            {
                IDset = this.ChildIDs;
            }
            
            // This would have been much easier using recursive functions
            // But there is too much recursion for the browser to handle..
            return IDset;
        };
        
        /** CalcLocation function */
        this.calcLocation = function () {
            
            // Calculate the Y coordinate
            var Y = this.level*75;
            
            // Calculate the X coordinate
            var X = 0;
                    
            // Is this the first person of the family tree?
            if ((this.MotherID !== -1) || (this.FatherID !== -1)) {
                
                // ID number of the parent that will be used
                var id = -1;
                
                // Who are we gonna use?
                if (this.MotherID === -1) {
                    // Use daddy if mommy isn't known
                    id = this.FatherID;
                } else if (this.FatherID === -1) {
                    // Use mommy if daddy isn't known
                    id = this.MotherID;
                } else {
                    // Both parents are known
                    // Use the parent with the highest generation level.
                    // So the parent that is placed the lowest
                    var Mother = Items[this.MotherID];
                    var Father = Items[this.FatherID];
                    if (Father.level > Mother.level) {
                        id = this.FatherID;
                    } else {
                        id = this.MotherID;
                    }
                }
                
                var Parent = Items[id];
                var numChildren = Parent.ChildIDs.length;
                
                // Is it odd or even?
                var odd = numChildren % 2;
                
                // And which index do we have?
                var Index = 0;
                if (this.MotherID === id) {
                    // Use the one from mommy
                    Index = this.ChildIndexM;
                } else {
                    // Use the one from daddy
                    Index = this.ChildIndexF;
                }
                
                // Now calculate where our position should be
                if (odd) {
                    var middle = ((numChildren + 1) / 2) - 1;
                    
                    if (Index === middle) {
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
        };
        
        this.getAncestors = function () {
            var ListOfIDs = [];
            
            if ((this.MotherID === -1) && (this.FatherID === -1)) {
                if (this.ChildIDs.length === 0) {
                    // We do not have a family tree for this person..
                } else {
                    // We are ancestors
                    ListOfIDs = [ItemsList.indexOf(this.ID)];
                }
            } else {
                // We must have ancestors
                // The set of people to work with
                var IDset = [this.ID];
                
                // This breaks the while loop
                var done = 0;
                
                while (done === 0)
                {    
                    var newIDset = [];
                    for (i = 0; i < IDset.length; i++) {
                        var Item = Items[IDset[i]];
                        
                        // Create the ID set of the next generation
                        if (Item.MotherID !== -1) {
                            newIDset.push(Item.MotherID);
                        } else if (Item.FatherID === -1) {
                            // This is an ancestor
                            var AncestorID = ItemsList.indexOf(Item.ID);
                            ListOfIDs.push(AncestorID);
                        }
                        
                        if (Item.FatherID !== -1) {
                            newIDset.push(Item.FatherID);
                        }
                    }
                    
                    // There are no more children to update
                    IDset = uniq(newIDset);
                    if (IDset.length === 0) {
                        done = 1;
                    }
                }
            }
            
            return uniq(ListOfIDs);
        };
        
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
        };
        
        /** */
        this.drawPeople = function() {
            var svgns = "http://www.w3.org/2000/svg";
            var hrefns = "http://www.w3.org/1999/xlink";
            var Group = document.createElementNS(svgns, "g");
            
            // Move everything away from the left border
            var x = this.Location[0] + globalOffset;
            var y = this.Location[1];
            
            if (this.MotherID !== -1) {
                // Draw the lines to the mother, to the middle of the bottom
                var Parent = Items[this.MotherID];
                
                // And only if the parents are drawn as well
                if ((Parent.Location[0] !== -1) && (Parent.Location[1] !== -1)) {
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
                    
                    if (this.level === (Parent.level + 1)) {
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
                    
                        LineMother1.setAttributeNS(null, 'stroke-opacity', '0.7');
                        LineMother2.setAttributeNS(null, 'stroke-opacity', '0.7');
                        LineMother3.setAttributeNS(null, 'stroke-opacity', '0.7');
                    
                        LineMother1.setAttributeNS(null, 'stroke-dasharray', '5, 10');
                        LineMother2.setAttributeNS(null, 'stroke-dasharray', '5, 10');
                        LineMother3.setAttributeNS(null, 'stroke-dasharray', '5, 10');
                    }
                    
                    Group.appendChild(LineMother1);
                    Group.appendChild(LineMother2);
                    Group.appendChild(LineMother3);
                }
            }
            
            if (this.FatherID !== -1) {
                // Draw the lines to the father, to the middle of the bottom
                var Parent = Items[this.FatherID];
                
                // And only if the parents are drawn as well
                if ((Parent.Location[0] !== -1) && (Parent.Location[1] !== -1)) {
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
                    
                    
                    if (this.level === (Parent.level + 1)) {
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
                    
                        LineFather1.setAttributeNS(null, 'stroke-opacity', '0.7');
                        LineFather2.setAttributeNS(null, 'stroke-opacity', '0.7');
                        LineFather3.setAttributeNS(null, 'stroke-opacity', '0.7');
                    
                        LineFather1.setAttributeNS(null, 'stroke-dasharray', '5, 10');
                        LineFather2.setAttributeNS(null, 'stroke-dasharray', '5, 10');
                        LineFather3.setAttributeNS(null, 'stroke-dasharray', '5, 10');
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
            
            Rect.setAttributeNS(null, 'rx', 5);
            Rect.setAttributeNS(null, 'ry', 5);
            
            Rect.setAttributeNS(null, 'stroke', 'black');
            Rect.setAttributeNS(null, 'fill', this.getGenderColor());
            
            Rect.className.baseVal = "Rect";
            Rect.id = "Rect" + this.ID;
            Rect.RectID = this.ID;
            
            var Text = document.createElementNS(svgns, "text");
            Text.setAttributeNS(null, 'x', x);
            Text.setAttributeNS(null, 'y', y + 25);
            Text.textContent = this.name;
            Text.RectID = this.ID;
            
            var newHref = updateURLParameter("peoples.php", "id", this.ID);
            var Link = document.createElementNS(svgns, "a");
            Link.setAttributeNS(hrefns, 'xlink:href', newHref);
            Link.setAttributeNS(hrefns, 'xlink:title', '<?php echo $dict_Familytree["link_people"]; ?>');
            Link.setAttributeNS(hrefns, 'target', "_top");
            
            Link.appendChild(Rect);
            Link.appendChild(Text);
            
            Link.RectID = this.ID;
            Link.setAttributeNS(null, 'onmouseover', 'setBorder(evt)');
            Link.setAttributeNS(null, 'onmouseout',  'clearBorder(evt)');
            
            Group.appendChild(Link);        
            return Group;
        };
        
        /** */
        this.drawFamilyTree = function(SVG) {
            
            var Group = this.drawPeople();
            if ((Group !== null) && (this.drawn === 0)) {
                SVG.appendChild(Group);
                this.drawn = 1;
            }
            
            if (this.ChildIDs.length !== 0)
            {
                for (this.counter = 0; this.counter < this.ChildIDs.length; this.counter++) {
                    // Update all children as well
                    var Idx = this.ChildIDs[this.counter];
                    var Child = Items[Idx];
                    
                    Child.drawFamilyTree(SVG);
                }

            }
        };
    }

    
    function setItems() {    
        // Create all connections
        for (i = 0; i < Items.length; i++) {
            var Item = Items[i];
        
            if (Item.MotherID !== -1) {
                var Mother = Items[Item.MotherID];
                Item.ChildIndexM = Mother.ChildIDs.length;
                Mother.ChildIDs.push(Item.ID);
            }
            
            if (Item.FatherID !== -1) {
                var Father = Items[Item.FatherID];
                Item.ChildIndexF = Father.ChildIDs.length;
                
                if (Item.FatherID !== Item.MotherID) {
                    Father.ChildIDs.push(Item.ID);
                }
            }
        }
        
        for (i = 0; i < Items.length; i++) {
            var Item = Items[i];
            if ((Item.MotherID === -1) && (Item.FatherID === -1) && (Item.ChildIDs.length > 0)) {
                // This person is on top of a family tree
                ItemsList.push(Item.ID);
            }
        }
    }
    
/** calcLocations function */
function calcLocations(firstID, highestLevel) {
    
    // This breaks the while loop
    var done = 0;
    var MaxLevel = levelCounter.length;
    
    while (done === 0)
    {
        // Is there any collision between two people?
        var collision = 0;
        
        // The offset that is needed to get all people of that tree in the SVG.
        //  We need to move the SVG to the right with this value
        globalOffset = 0;
        
        // Draw the tree per level
        for (level = 0; level < MaxLevel; level++) {
            
            // The IDs of the people of the current level
            var IDset = levelIDs[level];
            
            for (i = 0; i < IDset.length; i++) {
                var Item = Items[IDset[i]];
                Item.calcLocation();
                
                // To get the width, keep the highest X coordinate we can find
                if (Item.Location[0] > globalWidth) {
                    globalWidth = Item.Location[0];
                }
                
                // Do a check on the location of the person
                if (Item.Location[0] < 50) {
                    // Item seems to fall out of boundary
                    // What offset do we need?
                    var offset = 50 - Item.Location[0];
                    
                    if (offset > globalOffset) {
                        // Take the highest offset found
                        globalOffset = offset;
                    }
                }
                
                if ((Item.level > 0) && (Item.levelIndex > 0)) {
                    // Find the neighbour.
                    // This is the person who has the same level, but levelIndex - 1
                    var idNeighbour = IDset[Item.levelIndex - 1];
                    var Neighbour = Items[idNeighbour];
                    
                    // If we get in the if function, these two people are overlapping.
                    // Or the right person is too far left and needs to move right
                    if (Item.Location[0] < (Neighbour.Location[0] + 150)) {
                        // Searching for the shared ancestor that these two people have
                        var found = 0;
                        var FoundID = -1;
                        
                        // Us
                        var currentAncestorsR = [Item.ID];
                        
                        // The neighbour
                        var currentAncestorsL = [Neighbour.ID];
                        
                        // Our starting level
                        var currentLevel = Item.level;
                        
                        while (found === 0) {
                            // Get a list with people that are a generation level lower (placed higher)
                            var currentIDset = levelIDs[currentLevel - 1];
                            
                            var newAncestorsR = [];
                            var newAncestorsL = [];
                            
                            // Find all the possible ancestors for the right person
                            for (var j = 0; j < currentAncestorsR.length; j++) {
                                var ItemR = Items[currentAncestorsR[j]];
                                
                                for (var k = 0; k < currentIDset.length; k++) {
                                    var ID = currentIDset[k];
                                    
                                    // Remember the list of ancestors that we find for this person
                                    if ((ID === ItemR.MotherID) || (ID === ItemR.FatherID)) {
                                        newAncestorsR.push(ID);
                                    }
                                }
                            }
                            
                            // Find all the possible ancestors for the left person
                            for (var j = 0; j < currentAncestorsL.length; j++) {
                                var ItemL = Items[currentAncestorsL[j]];
                                
                                for (var k = 0; k < currentIDset.length; k++) {
                                    var ID = currentIDset[k];
                                    
                                    // Remember the list of ancestors that we find for this person
                                    if ((ID === ItemL.MotherID) || (ID === ItemL.FatherID)) {
                                        newAncestorsL.push(ID);
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
                                    // This is the ancestor that connects the two colliding people
                                    if (RightID === LeftID) {
                                        FoundID = RightID;
                                        found = 1;
                                        count++;
                                    }
                                }
                            }
                            
                            if (found === 0) {
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
                        
                        // The connecting ancestor
                        var Parent = Items[FoundID];
                        var Child = null;
                        
                        var specialCase = 0;
                        // It seems that the two parents of a kid are actually related in some way..
                        // Like a guy marrying the daughter of his brother..
                        if (count > 1) {
                            var Children = [];
                            
                            // Does left or right have related parents?
                            if (newAncestorsR.length > 1) {
                                
                                for (var k = 0; k < currentAncestorsR.length; k++) {
                                    var ID = currentAncestorsR[k];
                                    
                                    for (var j = 0; j < Parent.ChildIDs.length; j++) {
                                        var ChildID = Parent.ChildIDs[j];

                                        if (ID === ChildID) {
                                            // Find all the matching children
                                            Children.push(ID);
                                        }
                                    }
                                }
                                
                            } else if (newAncestorsL.length > 1) {
                                
                                for (var k = 0; k < currentAncestorsL.length; k++) {
                                    var ID = currentAncestorsL[k];
                                    
                                    for (var j = 0; j < Parent.ChildIDs.length; j++) {
                                        var ChildID = Parent.ChildIDs[j];

                                        if (ID === ChildID) {
                                            // Find all the matching children
                                            Children.push(ID);
                                        }
                                    }
                                }
                                
                            }
                            
                            if (Children.length === 2) {                                
                                var Child1 = Items[Children[0]];
                                var Child2 = Items[Children[1]];
                                var difLevelIndex = Child2.levelIndex - Child1.levelIndex;
                                
                                // Are they next to each other?
                                if (difLevelIndex !== 1) {
                                    // There are not next to each other..
                                    // Change the order of the children to put them next to each other
                                    
                                    // Are we using mommy or daddy?
                                    if (Child1.MotherID === Parent.ID) {
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
                        if (specialCase === 0) {
                            for (var k = currentAncestorsR.length; k > 0; k--) {
                                var ID = currentAncestorsR[k - 1];
                                
                                for (var j = 0; j < Parent.ChildIDs.length; j++) {
                                    var ChildID = Parent.ChildIDs[j];

                                    if (ID === ChildID) {
                                        // Find the child that needs to be moved
                                        Child = Items[ID];
                                    }
                                }
                            }
                            
                            Child.offset += (Neighbour.Location[0] + 150) - Item.Location[0];
                        } else if (specialCase === 1) {
                            // Special case! It seems that the two parents of a kid are actually related..
                            // Some changes were made, now recalculate!
                            resetIndexes();
                            setIndexes(firstID, highestLevel);
                        }
                        collision = 1;
                        
                        if (collision === 1) {
                            break;
                        }
                    }
                }
            }
            
            if (collision === 1) {
                // Break out of the loop and start again
                break;
            }
        }
        
        // There are no more children to update
        if (level === MaxLevel) {
            done = 1;
        }
    }
    
    return;
}

function panItem(item) {
    FamilyTree = document.getElementById("familytree_div");
    scrollTop = (item.Location[1] + 75) - (FamilyTree.offsetHeight / 2);
    scrollLeft = (item.Location[0] + globalOffset + 50) - (FamilyTree.offsetWidth / 2);
    
    updateViewbox(-scrollLeft, -scrollTop, -1);
}

function prep_appendGroup() {
    var svgns = "http://www.w3.org/2000/svg";
    var SVG = document.getElementById("svg");
    
    var Group = document.createElementNS(svgns, "g");    
    Group.id = "familytree_svg";
    
    SVG.appendChild(Group);
    UpdateProgress(45);
    
    // Now add it to the screen
    setTimeout(prep_AddControlButtons, 1);
}

function prep_DrawMap() {
    // The FamilyTree div
    var FamilyTree = document.getElementById("familytree_div");
    var SVG = document.getElementById("svg");
    var Group = document.getElementById("familytree_svg");
    
    // Set the height and the width
    ActualHeight = (highestLevel + 1)*75;
    ActualWidth = globalWidth + globalOffset + 150;
    
    SVG.setAttribute('height', FamilyTree.offsetHeight);    
    SVG.setAttribute('width',  FamilyTree.offsetWidth);
    
    // Draw the current family tree
    var Item = Items[ItemsList[globalMapId]];    
    Item.drawFamilyTree(Group);
    UpdateProgress(75);
    
    // Add the drawn part to the SVG
    setTimeout(prep_SetInterrupts, 1);
}

</script>