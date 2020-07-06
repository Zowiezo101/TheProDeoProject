/* global Items, ItemsList, levelCounter, levelIDs, prep_AddControlButtons, globalMapId, prep_SetInterrupts, highestLevel, MapList, dict_Familytree, session_settings */

function CreateItem(item) {
    this.id = Number(item["id"]);
    this.name = item["name"];
    this.data = item["data"];
    this.parents = item["parent_id"] ? [Number(item["parent_id"])] : [];

    // Own loop counter to prevent the counters messing each other up
    this.counter = 0;

    // Children of this person
    this.ChildIDs = [];

    // Generations from the first ancestor
    this.level = -1;
    // Which Item on this level is this
    this.levelIndex = -1;

    // The length variables and types
    this.lengthIndex = 1;

    // Location of this person
    this.Location = [-1, -1];
    this.offset = 0;

    // Make sure this person isn't duplicated
    this.drawn = 0;
    
    // We don't want to work with everything
    this.current_map = 0;

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
    
    this.getText = function(Text, value) {
        Text.textContent = value;
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
                            var newAncestorsR = [];
                            var newAncestorsL = [];
                            
                            // Find all the possible ancestors for the right person
                            currentAncestorsR.forEach(function(id) {
                                var item = getItemById(id);
                                newAncestorsR = newAncestorsR.concat(item.parents);
                            });
                                
                            // Find all the possible ancestors for the left person
                            currentAncestorsL.forEach(function (id) {
                                var item = getItemById(id);
                                newAncestorsL = newAncestorsL.concat(item.parents);
                            });
                            
                            // Now check if we have a match on this level!
                            var count = 0;
                            newAncestorsR.forEach(function(id) {
                                 if (newAncestorsL.indexOf(id) !== -1) {
                                     FoundID = id;
                                     found = 1;
                                     count++;
                                 }
                            });
                            
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
                                    // They're are not next to each other..
                                    // Change the order of the children to put them next to each other
                                    var Index1 = Parent.ChildIDs.indexOf(Child1.id);
                                    var Index2 = Parent.ChildIDs.indexOf(Child2.id);
                                    
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

/** @param {Integer} id */
function getItemColor (id) {
    var item = getItemById(id);
    var gender = item.data;
    
    var color = '';
    switch(gender) {
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

