/* global session_settings, uniq, dict, prep_DrawLegenda, prep_appendGroup, MapList, Items */
var globalItemId = -1;
var globalMapId = -1;

function checkForDuplicates (id) { 
    var matchingItems = getItemsById(id);
    if (matchingItems.length > 1) {
        // This event has multiple objects, meaning that multiple events
        // are linking to this event as their next event
        // When searching for a single event with this ID, it will always
        // return the first match in it's array
        var firstItem = getItemById(id);

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
}

function getAncestors (id) {
    var ListOfIDs = [];
    var item = getItemById(id);

    if (item.parents.length === 0) {
        if (item.ChildIDs.length === 0) {
            // We do not have a family tree for this person..
        } else {
            // We are ancestors
            ListOfIDs = [MapList.indexOf(item.id)];
        }
    } else {
        // We must have ancestors
        // The set of people to work with
        var IDset = [item.id];

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
}

/* Function to get any item using the ID of that item */
function getItemById(id) {
    var Item = Items.find(x => x.id === id);
    if (!Item)
        Item = null;
    return Item;
}

/* Function to get any item using the ID of that item */
function getItemsById(id) {
    var Matches = Items.filter(x => x.id === id);
    return Matches;
}

/* Function to get any item using the level of that item */
function getItemsByLevel(level) {
    var Matches = Items.filter(x => x.level === level);
    return Matches;
}