/* global onZoomIn, onZoomOut, onZoomFit, onZoomReset, onDownload */

var ALIGNMENT_VERTICAL = 0;
var ALIGNMENT_HORIZONTAL = 1;

var CALC_NONE = 0;
var CALC_LOCATION = 1;
var CALC_INDEX = 2;

var g_MapItems = null;
var g_Options = null;

//function prep_DrawLegenda() {
//    //Legenda
//    var svgns = "http://www.w3.org/2000/svg";
//    var SVG = document.getElementById("svg");
//    
//    var Group = document.createElementNS(svgns, "g");    
//    Group.id = "Legenda";
//    
//    var LegendaStr = ['s', 'i', 'h', 'd', 'w', 'm', 'y', 'D', 'C', 'M', 'a'];
//    LegendaStr.forEach(function(str, idx) {
//        var Rect = document.createElementNS(svgns, "rect");        
//        Rect.setAttributeNS(null, 'width', 10);
//        Rect.setAttributeNS(null, 'height', 10);
//        Rect.setAttributeNS(null, 'x', 15 + (100*Math.floor(idx / 5)));
//        Rect.setAttributeNS(null, 'y', 15*((idx % 5) + 1));
//        Rect.setAttributeNS(null, 'stroke', 'black');
//        Rect.setAttributeNS(null, 'fill', getItemColor(null, idx));
//        
//        var Text = document.createElementNS(svgns, "text");        
//        Text.setAttributeNS(null, 'x', 30 + (100*Math.floor(idx / 5)));
//        Text.setAttributeNS(null, 'y', 15*((idx % 5) + 1) + 10);
//        Text.textContent = StringToType(str, 0);
//        
//        Group.appendChild(Rect);
//        Group.appendChild(Text);
//    });
//    
//    // Now add it to the screen
//    SVG.appendChild(Group);
//    showMap(PREP_DRAWLEGENDA);
//}
//

function setMapItems (map) {
    var parent = {
        id: map.id,
        name: map.name,
        gender: map.hasOwnProperty('gender') ? map.gender : null,
        parent_id: "-1",
        level: 0,
        level_index: 0,
        root: true
    };
    
    // Set the initial items
    g_MapItems = [parent].concat(map.items);
    
    // Convert the levels to integers if they are strings
    g_MapItems.forEach(function(item) {
        item.level = parseInt(item.level, 10);
    
        // Set the parents and the children
        setParents(item.id, item.parent_id);
    });
    
    // Max level
    var topLevel = g_MapItems[g_MapItems.length - 1].level;
    
    // Lets go per level
    for (var i = 0; i < topLevel; i++) {
        // Per level, check the level_index of the parents and sort by that
        var items = filterMapItems('level', i + 1).sort(function(a, b) {
            var parent_a = getMapItem(a.parent_id);
            var parent_b = getMapItem(b.parent_id);
            return parent_a.level_index - parent_b.level_index;
        });
        
        // Now set the level indexes in that order
        var level_index = 0;
        items.forEach(item => item.level_index = level_index++);
    }
    
    // Remove the duplicates
    g_MapItems = g_MapItems.reduce(function(mapItems, mapItem) {
        
        // Get the duplicates
        var dupl = mapItems.filter(item => item.id === mapItem.id);
        
        if (dupl.length > 0) {
            // There are duplicutes, get the index of it
            var idx = mapItems.indexOf(dupl[0]);

            // Get the element with the highest level
            mapItem = dupl.reduce(function(item, newItem) {
                return item.level < newItem.level ? newItem : item;
            }, mapItem);
            
            // Remove the duplicate from the array
            mapItems.splice(idx, 1);
        }
        
        delete mapItem.parent_id;
        
        // Add it to the end of the mapItems array
        mapItems.push(mapItem);
        
        // Return the array for the new round
        return mapItems;
    }, []);
    
    // Reorder the mapItems by level and level index
    g_MapItems = g_MapItems.sort(function(a, b) {
        if ((a.level > b.level) || (a.level === b.level && a.level_index > b.level_index)) {
            return 1;
        }
        if ((a.level < b.level) || (a.level === b.level && a.level_index < b.level_index)) {
            return -1;
        }
        return 0;
    });
    
    console.log(g_MapItems);
    
    return g_MapItems;
}

function getMapItems() {
    return g_MapItems;
}

function calcMapItems(options = new Object()) {
    
    // Standard settings if some are not set
    if(!options || !options.hasOwnProperty('width')) 
        options.width = 100;
    if(!options || !options.hasOwnProperty('height')) 
        options.height = 50;
    if(!options || !options.hasOwnProperty('x_dist')) 
        options.x_dist = 25;
    if(!options || !options.hasOwnProperty('y_dist')) 
        options.y_dist = 30;
    if(!options || !options.hasOwnProperty('align')) 
        options.align = ALIGNMENT_VERTICAL;
    g_Options = options;
    
    g_MapItems.forEach(function(item) { 
        
        item.width = g_Options.width;
        item.height = g_Options.height;
        
        item.Y = calcY(item);
        item.X = calcX(item);
    
        // We calculated its coordinates
        item.calculated = true;
    });
    
    return g_MapItems;
    
}

function setParents(id, parent_id) {

    // Set the parent of this child
    var children = filterMapItems('id', id);
    
    // There might be duplicates
    children.forEach(function(child) {
        if (!child.hasOwnProperty('parents'))
            child.parents = [];
        if (!child.hasOwnProperty('children'))
            child.children = [];

        // Set the child of this parent
        var parents = filterMapItems('id', parent_id);
        
        // There might be duplicates
        parents.forEach(function(parent) {
            // Set the parents
            if (!child.parents.includes(parent_id) && parent_id !== "-1") 
                child.parents.push(parent_id);

            if (parent) {
                if (!parent.hasOwnProperty('parents'))
                    parent.parents = [];
                if (!parent.hasOwnProperty('children'))
                    parent.children = [];

                // Set the children
                if (!parent.children.includes(id))
                    parent.children.push(id);
            }
        });
    });
    
}

function getChildren(id, calc = CALC_NONE) {
    // Get the children of this item
    // First get all the items with this id as parent
    var items = filterMapItems('parents', id);
    if (calc === CALC_LOCATION) {
        items = items.filter(item => item.calculated === true);
    } else if (calc === CALC_INDEX) {
        items = items.filter(item => item.indexed !== true);
    }
    var children = items.map(item => item.id);
    
    // Return the (valid) children
    return children.filter(item => item !== "-1");
}

function getMapItem(id) {
    var items = filterMapItems('id', id);
    return items[0];
}

function getLeftLevelSibling(id) {
    // Get the item we want to siblings of on the left
    var item = getMapItem(id);
    
    // The items on the same level on the left (lower levelIndex)
    var items = filterMapItems('level', item.level).filter(levelSibling => levelSibling.level_index < item.level_index);
    
    // Return the most right sibling
    return items[items.length - 1]; 
}

function getRightLevelSiblings(id) {
    // Get the item we want to siblings of on the right
    var item = getMapItem(id);
    
    // The items on the same level on the right (higher levelIndex)
    var items = filterMapItems('level', item.level).filter(levelSibling => levelSibling.level_index >= item.level_index);
    
    // Only get the ids of these items
    var siblings = items.map(item => item.id);
    
    // Return these items
    return siblings; 
}

function getLevelSiblings(id) {
    // Get the item we want to siblings of on the right
    var item = getMapItem(id);
    
    // The items on the same level on the right (higher levelIndex)
    var items = filterMapItems('level', item.level);
    
    // Only get the ids of these items
    var siblings = items.map(item => item.id);
    
    // Return these items
    return siblings; 
}

function getAncestors(id) {
    // The parents of the item
    var parents = [id];
    var ancestors = [];
    
    while (parents.length > 0) {
        var parentId = parents.shift();
        var parentItem = getMapItem(parentId);
        
        var newParents = parentItem.parents;
        ancestors = ancestors.concat(newParents);
        parents = parents.concat(newParents);
    }
    
    return ancestors;
}

function getCommonAncestor(leftId, rightId) {
    var left = getAncestors(leftId);
    var right = getAncestors(rightId);
            
    // Get the common ancestor
    var commonAncestor = -1;
    left.forEach(function(item) {
        // Is this ancestor of the left side of the clash also on the 
        // right side of the clash?
        var ancestor = right.indexOf(item);
        if ((ancestor !== -1) && (commonAncestor === -1)) {
            commonAncestor = item;
        }
    });
    
    // Get the first child of this ancestor on the right side of the clash
    var rightAncestor = -1;
    right.forEach(function(item) {
        // Is this ancestor of the left side of the clash also on the 
        // right side of the clash?
        var ancestor = getChildren(commonAncestor).indexOf(item);
        if ((ancestor !== -1) && (rightAncestor === -1)) {
            rightAncestor = item;
        }
    });
    
    return rightAncestor !== -1 ? getMapItem(rightAncestor) : null;
}

function moveCommonAncestor(offset, parent) {    
    // Start offsetting the parent and everything on the right
    var items = getRightLevelSiblings(parent.id);
    
    while (items.length > 0) {
        // The ids of the items
        var id = items.shift();
        
        // The actual items themselves
        var item = getMapItem(id);
        item.X = item.X + offset;
        
        // Get the children as well (only the calculated ones)
        items = items.concat(getChildren(item.id, CALC_LOCATION));
    }
}

function filterMapItems(prop, value) {
//    return g_MapItems.filter(item => (prop == "parents") ? (item[prop].includes(value)) : (item[prop] === value));
    return g_MapItems.filter(function(item) {
        return (prop === "parents") ? (item[prop].includes(value)) : (item[prop] === value)
    });
}

function calcY(item) {
    var Y = 0;
    
    // The Y depends on the parents
    if(item.parents.length) {
        // Get the highest level parent
        var parent = item.parents.reduce(function(parent1, idx) {
            var parent2 = getMapItem(idx);
            
            return (parent1.level < parent2.level) ? parent2 : parent1;
        }, getMapItem(item.parents[0]));
        
        // Get the parent Y coordinate, add the height to it 
        // and the standard vertinal offset
        Y = parent.Y + parent.height + g_Options.y_dist;
    }
    return Y;
}

function calcX(item) {
    var X = 0;
    
    // The X depends on the parents to start with
    if(item.parents.length) {
        // Get the highest level parent
        var parent = item.parents.reduce(function(parent1, idx) {
            var parent2 = getMapItem(idx);
            
            return (parent1.level < parent2.level) ? parent2 : parent1;
        }, getMapItem(item.parents[0]));
        
        // Get the average X coordinate of the parents
        var avgX = item.parents.reduce(function(avg, idx) {
            return getMapItem(idx).X + avg;
        }, 0) / item.parents.length;
        
        // Number of children of parent
        if (parent.children.length % 2) {  // odd
            var middle = ((parent.children.length + 1) / 2) - 1;
            var index = parent.children.indexOf(item.id);

            if (index === middle) {
                // Are we in the middle? 
                // Then just use parents X coordinate
                X = avgX;
            } else if (index > middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents X coordinate
                var offset = index - middle;
                X = avgX + offset*(g_Options.width + g_Options.x_dist);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents X coordinate
                var offset = middle - index;
                X = avgX - offset*(g_Options.width + g_Options.x_dist);
            }
        } else { // even
            var middle = parent.children.length / 2;
            var index = parent.children.indexOf(item.id);
            if (index >= middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents X coordinate
                var offset = index - middle;
                X = (avgX + ((g_Options.width + g_Options.x_dist) / 2)) + offset*(g_Options.width + g_Options.x_dist);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents X coordinate
                var offset = middle - index;
                X = (avgX + ((g_Options.width + g_Options.x_dist) / 2)) - offset*(g_Options.width + g_Options.x_dist);
            }
        }
    }
    
    // Does this  X coordinate cause an overlap with the left level sibling?
    var sibling = getLeftLevelSibling(item.id);

    if (item.level < 5 /*|| (item.level == 4 && item.level_index < 25)*/) {
    if (sibling) {
        // The distance needed between left and right
        var offset = (sibling.X + sibling.width + g_Options.x_dist) - X;
        
        if (item.id == "685") {
            console.log("Abisai!");
        }
        if (offset > 0) {            
            // Move the siblings, and the parent & siblings 
            // until the overlap is no more
//                console.log("There is an overlap detected!");
//                console.log(item);
//                console.log(sibling);

            // Step 1: Find a common ancestor, and get the child on the 
            // right side of the clash
            var ancestor = getCommonAncestor(sibling.id, item.id);


            if (ancestor) {
                // Step 2: Per child of the ancester, move child and siblings to the right
                moveCommonAncestor(offset, ancestor);
            }

            // Now update the coordinates of the item itself
            X = X + offset;

            // Step 3: Check again
            // The distance needed between left and right
            var offset = (sibling.X + sibling.width + g_Options.x_dist) - X;
            if (offset > 0) {
                // Something's not right..
                console.log("There is an overlap detected! Again..");
                console.log(item);
                console.log(sibling);
                console.log(ancestor);

                var ancestor = getCommonAncestor(sibling.id, item.id);
            }
        }
    }
    }
    
    return X;
}



//function prep_SetInterrupts() {
//    // The FamilyTree div
//    var SVG = document.getElementById("svg");
//
//    // And some functions for mouse or keyboard panning/scrolling
//    SVG.setAttributeNS(null, 'onmousedown', "GetMousePos(evt)");
//    SVG.setAttributeNS(null, 'ontouchstart', "GetTouchPos(evt)");
//
//    if (SVG.addEventListener) {
//        // IE9, Chrome, Safari, Opera
//        SVG.addEventListener("mousewheel", GetDelta, false);
//        // Firefox
//        SVG.addEventListener("DOMMouseScroll", GetDelta, false);
//    }
//    // IE 6/7/8
//    else 
//        SVG.attachEvent("onmousewheel", GetDelta);
//
//    window.onmousemove = GetMouseMov;
//    window.ontouchmove = GetTouchMov;
//
//    window.onmouseup = GetMouseOut;
//    window.ontouchend = GetMouseOut;
//    showMap(PREP_SETINTER);
//}
//
//function prep_SetView() {
//    // Update the width and the height of the viewbox
//    updateViewbox(0, 0, 1);
//
//    // Move to the event
//    var Item = getItemById(globalItemId);
//    panItem(Item);
//    showMap(PREP_SETVIEW);
//}
//
//function prep_MakeVisible() {
//    // The Map div
//    var ItemMap = document.getElementById("item_info");
//
//    // Remove the default text
//    var defaultText = document.getElementById("default");
//
//    if (defaultText !== null) {
//        ItemMap.removeChild(defaultText);
//
//        // Make the SVG visible
//        var SVG = document.getElementById("svg");
//        SVG.setAttributeNS(null, "display", "inline");
//    }
//    showMap(PREP_MAKEVISIBLE);
//}