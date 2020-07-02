/* global Items, ItemsList, levelCounter, levelIDs, prep_AddControlButtons, globalMapId, prep_SetInterrupts, highestLevel, MapList, dict_Familytree */

function CreateItem(item) {
    this.id = Number(item["id"]);
    this.name = item["name"];
    this.parents = item["parent_id"] ? [Number(item["parent_id"])] : [];
    this.Gender = item["data"];

    // Own loop counter to prevent the counters messing each other up
    this.counter = 0;

    // Children of this person
    this.ChildIDs = [];

    // Generations from the first ancestor
    this.level = -1;
    // Which Item on this level is this
    this.levelIndex = -1;

    // Location of this person
    this.Location = [-1, -1];
    this.offset = 0;

    // Make sure this person isn't duplicated
    this.drawn = 0;
    
    // We don't want to work with everything
    this.current_map = 0;

    this.checkForDuplicates = function () { 
        var matchingItems = getItemsById(this.id);
        if (matchingItems.length > 1) {
            // This event has multiple objects, meaning that multiple events
            // are linking to this event as their next event
            // When searching for a single event with this ID, it will always
            // return the first match in it's array
            var firstItem = getItemById(this.id);
            
            // Since we always get the same first item when looking with the ID
            // We can easily see if we already handled this item and are
            // currently looking at a duplicate
            if (firstItem.parents.length === 1) {
                for (var i = 0; i < matchingItems.length; i++) {                    
                    // Link all the previous values to the first of this event
                    firstItem.parents.push(matchingItems[i].parents[0]);
                    firstItem.parents = uniq(firstItem.parents);
                }
            }
        }
    };

    /** 
     * @param {Integer} level */
    this.setLevel = function (level) {
        var IDset = [];

        console.log("setLevel! Level " + level + " for child " + this.name);
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
        if (this.parents.length !== 0) {
            var TotalXCoord = 0;
            var Parent = getItemById(this.parents[0]);
            
            for (var i = 0; i < this.parents.length; i++) {
                var tempParent = getItemById(this.parents[i]);
                TotalXCoord += tempParent.Location[0];

                // Take the parent with the heighest level
                if (tempParent.level > Parent.level) {
                    var Parent = tempParent;
                }
            }

            var AvgXCoord = TotalXCoord / this.parents.length;
            var Location_0 = AvgXCoord;
            
            console.log("Used parent for " + this.name + " is " + Parent.name + " with " + Parent.ChildIDs.length + " children.");
            console.log("Parent width for " + this.name + " is " + Parent.Location[0] + " with a used width of " + Location_0);
            

            var numChildren = Parent.ChildIDs.length;

            // Is it odd or even?
            var odd = numChildren % 2;

            // And which index do we have?
            var Index = Parent.ChildIDs.indexOf(this.id);

            // Now calculate where our position should be
            if (odd) {
                var middle = ((numChildren + 1) / 2) - 1;

                if (Index === middle) {
                    // Are we in the middle? 
                    // Then just use parents X coordinate
                    X = Location_0;
                } else if (Index > middle) {
                    // Are we on the right side of the middle?
                    // Place the block on the right side of parents X coordinate
                    var offset = Index - middle;
                    X = Location_0 + offset*150;
                } else {
                    // Are we on the left side of the middle?
                    // Place the block on the left side of parents X coordinate
                    var offset = middle - Index;
                    X = Location_0 - offset*150;
                }
            } else {
                var middle = numChildren / 2;
                if (Index >= middle) {
                    // Are we on the right side of the middle?
                    // Place the block on the right side of parents X coordinate
                    var offset = Index - middle;
                    X = (Location_0 + 75) + offset*150;
                } else {
                    // Are we on the left side of the middle?
                    // Place the block on the left side of parents X coordinate
                    var offset = middle - Index;
                    X = (Location_0 + 75) - offset*150;
                }
            }
        }

        // This value is used, in case someone is overlapping with someone else
        this.Location[0] = Math.round(X + this.offset);
        this.Location[1] = Math.round(Y);

        return;
    };

    this.getAncestors = function () {
        var ListOfIDs = [];

        if (this.parents.length === 0) {
            if (this.ChildIDs.length === 0) {
                // We do not have a family tree for this person..
            } else {
                // We are ancestors
                ListOfIDs = [MapList.indexOf(this.id)];
            }
        } else {
            // We must have ancestors
            // The set of people to work with
            var IDset = [this.id];

            // This breaks the while loop
            var done = 0;

            while (done === 0)
            {    
                var newIDset = [];
                for (i = 0; i < IDset.length; i++) {
                    var Item = getItemById(IDset[i]);

                    // Create the ID set of the next generation
                    if (Item.parents.length !== 0) {
                        for (j = 0; j < Item.parents.length; j++) {
                            newIDset.push(Item.parents[j]);
                        }
                    } else {
                        // This is an ancestor
                        var AncestorID = MapList.indexOf(Item.ID);
                        ListOfIDs.push(AncestorID);
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

        // This object has multiple parents, draw them all
        if(this.parents.length > 0) {
            for (var i = 0; i < this.parents.length; i++) {
                // Draw the lines to the mother, to the middle of the bottom
                var Parent = getItemById(this.parents[i]);

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
                        LineMother1.setAttributeNS(null, 'stroke', this.Gender ? 'pink' : 'blue');
                        LineMother2.setAttributeNS(null, 'stroke', this.Gender ? 'pink' : 'blue');
                        LineMother3.setAttributeNS(null, 'stroke', this.Gender ? 'pink' : 'blue');

                        LineMother1.setAttributeNS(null, 'stroke-width', '5');
                        LineMother2.setAttributeNS(null, 'stroke-width', '5');
                        LineMother3.setAttributeNS(null, 'stroke-width', '5');
                    } else {
                        LineMother1.setAttributeNS(null, 'stroke', this.Gender ? 'deeppink' : 'darkblue');
                        LineMother2.setAttributeNS(null, 'stroke', this.Gender ? 'deeppink' : 'darkblue');
                        LineMother3.setAttributeNS(null, 'stroke', this.Gender ? 'deeppink' : 'darkblue');

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
        Rect.id = "Rect" + this.id;
        Rect.RectID = this.id;

        var Text = document.createElementNS(svgns, "text");
        Text.setAttributeNS(null, 'x', x);
        Text.setAttributeNS(null, 'y', y + 25);
        
        Text.textContent = this.name;
        Text.RectID = this.id;

        var Link = document.createElementNS(svgns, "a");
        Link.setAttributeNS(hrefns, 'xlink:title', dict_Familytree["link_people"]);
        Link.setAttributeNS(hrefns, 'target', "_top");

        Link.appendChild(Rect);
        Link.appendChild(Text);

        Link.RectID = this.id;
        Link.setAttributeNS(null, 'onclick', 'updateSessionSettings("keep", true).then(goToPage("peoples.php", "", event.target.RectID), console.log)');
        Link.setAttributeNS(null, 'onmouseover', 'setBorder(evt)');
        Link.setAttributeNS(null, 'onmouseout',  'clearBorder(evt)');

        Group.appendChild(Link);        
        return Group;
    };

    /** 
     * @param {SVGElement} SVG */
    this.drawFamilyTree = function(SVG) {
        var IDset = [];

        var Group = this.drawPeople();
        if ((Group !== null) && (this.drawn === 0)) {
            SVG.appendChild(Group);
            this.drawn = 1;
        }

        if (this.ChildIDs.length !== 0)
        {
            IDset = this.ChildIDs;
        }

        // This would have been much easier using recursive functions
        // But there is too much recursion for the browser to handle..
        return IDset;
    };
}
    
/** 
* @param {Integer} firstID
* @param {Integer} highestLevel */
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
        for (var level = 0; level < MaxLevel; level++) {
            
            // The IDs of the people of the current level
            var IDset = levelIDs[level];
            
            for (var i = 0; i < IDset.length; i++) {
                var Item = getItemById(IDset[i]);
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
                    var Neighbour = getItemById(idNeighbour);
                    
                    // If we get in the if function, these two people are overlapping.
                    // Or the right person is too far left and needs to move right
                    if (Item.Location[0] < (Neighbour.Location[0] + 150)) {
                        // Searching for the shared ancestor that these two people have
                        var found = 0;
                        var FoundID = -1;
                        
                        // Us
                        var currentAncestorsR = [Item.id];
                        
                        // The neighbour
                        var currentAncestorsL = [Neighbour.id];
                        
                        // Our starting level
                        var currentLevel = Item.level;
                        
                        while (found === 0) {
                            // Get a list with people that are a generation level lower (placed higher)
                            var currentIDset = levelIDs[currentLevel - 1];
                            
                            var newAncestorsR = [];
                            var newAncestorsL = [];
                            
                            // Find all the possible ancestors for the right person
                            for (var j = 0; j < currentAncestorsR.length; j++) {
                                var ItemR = getItemById(currentAncestorsR[j]);
                                
                                for (var k = 0; k < currentIDset.length; k++) {
                                    var ID = currentIDset[k];
                                    
                                    // Get all the previous IDs (in case there is more than one)
                                    var previousIDs = [];
                                    if (ItemR.parents.length !== -1) {
                                        for (var l = 0; l < ItemR.parents.length; l++) {
                                            previousIDs.push(ItemR.parents[l]);
                                        }
                                    }
                                    
                                    // Remember the list of ancestors that we find for this person
                                    for (var l = 0; l < previousIDs.length; l++) {
                                        if (ID === previousIDs[l]) {
                                            newAncestorsR.push(ID);
                                        }
                                    }
                                }
                            }
                            
                            // Find all the possible ancestors for the left person
                            for (var j = 0; j < currentAncestorsL.length; j++) {
                                var ItemL = getItemById(currentAncestorsL[j]);
                                
                                for (var k = 0; k < currentIDset.length; k++) {
                                    var ID = currentIDset[k];
                                    
                                    // Get all the previous IDs (in case there is more than one)
                                    var previousIDs = [];
                                    if (ItemL.parents.length !== -1) {
                                        for (var l = 0; l < ItemL.parents.length; l++) {
                                            previousIDs.push(ItemL.parents[l]);
                                        }
                                    }
                                    
                                    // Remember the list of ancestors that we find for this person
                                    for (var l = 0; l < previousIDs.length; l++) {   
                                        // Remember the list of ancestors that we find for this person
                                        if (ID === previousIDs[l]) {
                                            newAncestorsL.push(ID);
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
                                alert("Could not find the connecting parent?");
                                break;
                            }
                        }
                        
                        // The connecting ancestor
                        var Parent = getItemById(FoundID);
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
                                var Child1 = getItemById(Children[0]);
                                var Child2 = getItemById(Children[1]);
                                var difLevelIndex = Child2.levelIndex - Child1.levelIndex;
                                
                                // Are they next to each other?
                                if (difLevelIndex !== 1) {
                                    // There are not next to each other..
                                    // Change the order of the children to put them next to each other
                                    
                                    // Are we using mommy or daddy?
                                    if (Child1.MotherID === Parent.id) {
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
                                    firstSlice.push(Child2.id);
                                    
                                    // Add it all together as a new list and make that the new ChildIDs list of the parent
                                    var newChildIDs = firstSlice.concat(secondSlice, thirdSlice);
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
                                        Child = getItemById(ID);
                                    }
                                }
                            }
                            
                            Child.offset += (Neighbour.Location[0] + 150) - Item.Location[0];
                        } else if (specialCase === 1) {
                            // Special case! It seems that the two parents of a kid are actually related..
                            // Some changes were made, now recalculate!
                            console.log("Starting over!!");
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
    var ItemMap = document.getElementById("item_info");
    scrollTop = (item.Location[1] + 75) - (ItemMap.offsetHeight / 2);
    scrollLeft = (item.Location[0] + globalOffset + 50) - (ItemMap.offsetWidth / 2);
    
    updateViewbox(-scrollLeft, -scrollTop, -1);
}
    
/** 
* @param {SVGElement} SVG */
function drawMap(SVG) {    
    
    // This breaks the while loop
    var done = 0;
    var MaxLevel = levelCounter.length;
    
    while (done === 0)
    {        
        // Draw the timeline per level
        for (var level = 0; level < MaxLevel; level++) {
            
            var IDset = levelIDs[level];
            
            for (var i = 0; i < IDset.length; i++) {
                var Item = getItemById(IDset[i]);
                Item.drawFamilyTree(SVG);
            }
        }
        
        // There are no more children to update
        if (level === MaxLevel) {
            done = 1;
        }
    }
    
    return;
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
    var FamilyTree = document.getElementById("item_info");
    var SVG = document.getElementById("svg");
    var Group = document.getElementById("familytree_svg");
    
    // Set the height and the width
    ActualHeight = (highestLevel + 1)*75;
    ActualWidth = globalWidth + globalOffset + 150;
    
    SVG.setAttribute('height', FamilyTree.offsetHeight);    
    SVG.setAttribute('width',  FamilyTree.offsetWidth);
    
    // Draw the current family tree
    drawMap(Group);
    UpdateProgress(75);
    
    // Add the drawn part to the SVG
    setTimeout(prep_SetInterrupts, 1);
}

