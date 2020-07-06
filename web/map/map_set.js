// This is the list of items that will be used to create a map with
var Items = [];

// This is a global variable, used calculate the level index
var levelCounter = [];
var levelIDs = [];

var highestLevel = 0;
    
function setItems() {    
    // Create all connections
    for (var i = 0; i < Items.length; i++) {
        var Item = Items[i];
        checkForDuplicates(Item.id);

        if (Item.parents.length > 0) {
            for (var j = 0; j < Item.parents.length; j++) {
                var Parent = getItemById(Item.parents[j]);
                
                if (Parent) {
                    // If not an object, it's excluded from this family tree
                    Parent.ChildIDs.push(Item.id);
                    Parent.ChildIDs = uniq(Parent.ChildIDs);
                }
            }
        }
    }
}
    
// setLevels function
function setLevels(id) {

    // Start clean with the highest level
    highestLevel = 0;

    // Start clean with all the levels and locations
    for (var m = 0; m < Items.length; m++)
    {        
        var Item = Items[m];

        // Reset levelIndex
        Item.level = -1;
        Item.Location = [-1, -1];
    }
    
    // The set of people that will be updated 
    // in the iteration of the while loop
    var IDset = [id];

    // This breaks the while loop
    var lastSet = 0;

    // The current generation level we are in
    var levelCount = 0;

    while (lastSet === 0)
    {
        var newIDset = [];
        for (i = 0; i < IDset.length; i++) {
            var Item = getItemById(IDset[i]);
            
            // Take the highest level possible
            if (levelCount > Item.level) {
                Item.level = levelCount;
            }

            // Create the ID set of the next generation
            if (Item.ChildIDs.length !== 0) {
                newIDset = newIDset.concat(Item.ChildIDs);
            }
            
            // This is only needed for family trees
            Item.current_map = 1;
        }
        levelCount++;

        // There are no more children to update
        IDset = uniq(newIDset);
        if (IDset.length === 0) {
            lastSet = 1;
        }
    }
    
    // Remove everything that is not needed for this map
    Items = Items.filter(function (Item) {
        return Item.current_map === 1;
    });
    
    // And now make sure the links are correct as well
    Items.forEach(function (Item, idx) {
        // Parent links
        var parents = Item.parents.filter(function(id) {
            return getItemById(id) !== null;
        });
        Items[idx].parents = parents;
        
        // Child links
        var children = Item.ChildIDs.filter(function(id) {
            return getItemById(id) !== null;
        });
        Items[idx].ChildIDs = children;
    });

    // Use minus one, since the levelcount was incremented on the last iteration
    return levelCount - 1;
}


function setIndexes(id, highestLevel) {

    // Reset all numbers and levelIndexes to recalculate
    levelIDs = [];
    levelCounter = [];

    for (var m = 0; m < Items.length; m++)
    {        
        var Item = Items[m];

        // Reset levelIndex
        Item.levelIndex = -1;
        Item.offset = 0;
    }
    
    // The set of people that will be updated 
    // in the iteration of the while loop
    var IDset = [id];

    // This breaks the while loop
    var lastSet = 0;

    for (var i = 0; i < highestLevel + 1; i++) {
        // Initialization
        levelIDs.push([]);
        levelCounter.push(0);
    }

    while (lastSet === 0)
    {        
        var newIDset = [];
        for (i = 0; i < IDset.length; i++) {
            var Item = getItemById(IDset[i]);
            var level = Item.level;

            var childSet = [];

            // Only use the children of the direct next generation to get the correct numbers
            for (var j = 0; j < Item.ChildIDs.length; j++) {
                var Child = getItemById(Item.ChildIDs[j]);

                if (Child.level === (Item.level + 1)) {
                    childSet.push(Child.id);
                }
            }

            // Store all the unique IDs and keep track on the level they are on
            // alert("Adding " + Item.name + " with ID " + Item.ID + " to array of level " + level + "\nArray: " + levelIDs[level]);

            // Keep track of the amount of people on a certain level
            // Only if the levelIndex is not already set
            if (Item.levelIndex === -1) {
                var currentLevelIDs = levelIDs[level];
                currentLevelIDs.push(Item.id);
                levelIDs[level] = currentLevelIDs;

                Item.levelIndex = levelCounter[level];
            } else {
                // alert("We have a double!!");
                // alert("Item " + Item.name + " already has it's levelIndex set to " + Item.levelIndex);
                // alert("It is requested to set it from " + Item.levelIndex + " to " + levelCounter[level]);
            }

            levelCounter[level] = levelIDs[level].length;

            // Create the ID set of the next generation
            newIDset = newIDset.concat(childSet);
        }

        // There are no more children to update
        IDset = uniq(newIDset);
        if (IDset.length === 0) {
            lastSet = 1;
        }
    }

    return;
}

