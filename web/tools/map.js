/* global session_settings, uniq, dict, prep_DrawLegenda, prep_appendGroup, MapList, Items, calcLocation, getText, DEPTH_OFFSET, DEPTH_SIZE, WIDTH, calcTime, WIDTH_SIZE, WIDTH_OFFSET, DEPTH */
var globalItemId = -1;
var globalMapId = -1;

function CreateItem(item) {
    this.id = Number(item["id"]);
    this.name = item["name"] ? item["name"] : "";
    this.descr = item["descr"] ? item["descr"] : "";
    this.length = item["length"] ? item["length"] : "";
    this.data = item["data"] ? item["data"] : "";
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
    this.lengthIndex = (session_settings["table"] === "timeline") ? -1 : 1;
    this.lengthType = -1;

    // Location of this person
    this.Location = [-1, -1];
    this.offset = 0;

    // Make sure this person isn't duplicated
    this.drawn = 0;
    
    // We don't want to work with everything
    this.current_map = (session_settings["table"] === "timeline") ? 1 : 0;

    /** CalcLocation function */
    this.calcLocation = function () {

        if (typeof calcTime === "function") {
            calcTime(this.id);
        }

        // Calculate the depth location
        var itemDepth = this.level * (DEPTH_SIZE + DEPTH_OFFSET);

        // Calculate the width location
        var itemWidth = (session_settings["table"] === "timeline") ? 25 : 0;

        // If this event has parents, get the average height..
        // Also, get the parent with the heighest level to use for the X
        // coordinate
        if (this.parents.length !== 0) {    
            var TotalWidth = 0;
            var Parent = getItemById(this.parents[0]);

            for (var i = 0; i < this.parents.length; i++) {
                var tempParent = getItemById(this.parents[i]);
                TotalWidth += tempParent.Location[WIDTH];

                // Take the parent with the heighest level
                if (tempParent.level > Parent.level) {
                    var Parent = tempParent;
                }
            }

            var AvgWidth = TotalWidth / this.parents.length;

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
                    itemWidth = AvgWidth;
                } else if (Index > middle) {
                    // Are we on the right side of the middle?
                    // Place the block on the right side of parents X coordinate
                    var offset = Index - middle;
                    itemWidth = AvgWidth + offset*(WIDTH_SIZE + WIDTH_OFFSET);
                } else {
                    // Are we on the left side of the middle?
                    // Place the block on the left side of parents X coordinate
                    var offset = middle - Index;
                    itemWidth = AvgWidth - offset*(WIDTH_SIZE + WIDTH_OFFSET);
                }
            } else {
                var middle = numChildren / 2;
                if (Index >= middle) {
                    // Are we on the right side of the middle?
                    // Place the block on the right side of parents X coordinate
                    var offset = Index - middle;
                    itemWidth = (AvgWidth + ((WIDTH_SIZE + WIDTH_OFFSET) / 2)) + offset*(WIDTH_SIZE + WIDTH_OFFSET);
                } else {
                    // Are we on the left side of the middle?
                    // Place the block on the left side of parents X coordinate
                    var offset = middle - Index;
                    itemWidth = (AvgWidth + ((WIDTH_SIZE + WIDTH_OFFSET) / 2)) - offset*(WIDTH_SIZE + WIDTH_OFFSET);
                }
            }

            itemDepth = Parent.Location[DEPTH] + Parent.lengthIndex*DEPTH_SIZE + DEPTH_OFFSET;
        }

        // This value is used, in case someone is overlapping with someone else
        this.Location[DEPTH] = Math.round(itemDepth);
        this.Location[WIDTH] = Math.round(itemWidth + this.offset);

        return;
    };
    this.getText = getText;
}

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