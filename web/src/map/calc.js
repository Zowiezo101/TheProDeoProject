/* global onZoomIn, onZoomOut, onZoomFit, onZoomReset, onDownload */

var ALIGNMENT_VERTICAL = 0;
var ALIGNMENT_HORIZONTAL = 1;

var PARENTS = 0;
var PARENT_ID = 2;

var g_MapItems = null;
var g_Options = null;
var g_ClashedItems = [];

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
        date: map.hasOwnProperty('date') ? map.date : null,
        length: map.hasOwnProperty('length') ? map.length : null,
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
        
        // Add it to the end of the mapItems array
        mapItems.push(mapItem);
        
        // Return the array for the new round
        return mapItems;
    }, []);
    
    // Make sure parents have the highest level possible for the best readability in case of timelines
    if(parent.id === "-999") {
        // In this case it's a timeline, let's go by level
        for (var i = 0; i < topLevel; i++) {
            // Get all the parents of this level
            var parents = filterMapItems('level', i);
            
            parents.forEach(function(mapItem) {
                if (mapItem.children.length !== 0) {
                    var lowestLevelChild = mapItem.children.reduce(function(lowestLevel, childIdx) {
                        var child = getMapItem(childIdx);
                        if (lowestLevel === -1) {
                            return child.level;
                        } else {
                            return child.level < lowestLevel ? child.level : lowestLevel;
                        }
                    }, -1);
                    
                    mapItem.level = lowestLevelChild - 1;
                }
            });
        }
    }
    
    // Lets go per level
    for (var i = 0; i < topLevel; i++) {
        // Per level, check the level_index of the parents and sort by that
        var items = filterMapItems('level', i).sort(function(a, b) {
            return a.level_index - b.level_index;
        });
        
        // Now get the children
        var items = items.reduce(function (array, item) {
            var children = getChildren(item.id, PARENTS)
                                .map(child => getMapItem(child));           
            return array.concat(
                children.filter((child) => child.level === (item.level + 1))
            );
    
        }, []);
        
        // Now set the level indexes in that order
        var level_index = 0;
        items.forEach(child => child.level_index = level_index++);
    }
    
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
    
    return g_MapItems;
}

function getMapItems() {
    return g_MapItems;
}

function calcMapItems(options = new Object()) {
    
    // Standard settings if some are not set
    if(!options || !options.hasOwnProperty('align')) 
        options.align = ALIGNMENT_VERTICAL;
    if(!options || !options.hasOwnProperty('width')) 
        // Length when horizontal
        // Width when vertical
        options.x_length = options.align === ALIGNMENT_VERTICAL ? 100 : 50;
    if(!options || !options.hasOwnProperty('height')) 
        // Width when horizontal
        // Length when vertical
        options.y_length = options.align === ALIGNMENT_HORIZONTAL ? 300 : 50;
    
    if(!options || !options.hasOwnProperty('x_dist')) 
        options.x_dist = 25;
    if(!options || !options.hasOwnProperty('y_dist')) 
        options.y_dist = 30;
    
    g_Options = options;
    
    g_MapItems.forEach(function(item) { 
        
        item.x_length = g_Options.x_length;
        item.y_length = g_Options.y_length;
        
        item.X = calcX(item);
        item.Y = calcY(item);
    
        // We calculated its coordinates
        item.calculated = true;
    });
    
    // Sort the array by ancestors, since those need to be moved
    sortByAncestor();
    
    // Try to solve all the clashes we found
    g_ClashedItems.forEach(item => solveClash(item));
    
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

function getChildren(id, calc) {
    // Get the children of this item
    // First get all the items with this id as parent
    var items = filterMapItems(calc === PARENTS ? 'parents' : 'parent_id', id);
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
        
        var newParent = parentItem.parent_id;
        if (newParent !== "-1") {
            ancestors.push(newParent);
            parents.push(newParent);
        }
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
        var ancestor = getChildren(commonAncestor, PARENT_ID).indexOf(item);
        if ((ancestor !== -1) && (rightAncestor === -1)) {
            rightAncestor = item;
        }
    });
    
    return rightAncestor !== -1 ? getMapItem(rightAncestor) : getMapItem(rightId);
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
        items = items.concat(getChildren(item.id, PARENT_ID));
    }
    
    // Now offset the parents on the right as well until we've reached the 
    // true ancestor, unless none of these have children..
    // Right side level siblings don't always need to be moved..
    // Only those who have children and those on the right of these
    var ancestors = getAncestors(parent.id);
    
    var siblingParents = [];
    ancestors.forEach(function(id) {
        // The siblings of these ancestors
        var siblings = getRightLevelSiblings(id);
        
        // Only move when any has children
        var child = false;        
        var newParents = [];
        
        siblings.forEach(function(sibling) {
            // Not the actual ancestor, just its siblings
            if (!ancestors.includes(sibling)) {
                // The actual items themselves
                var item = getMapItem(sibling);
                
                // Check if they have children
                var children = getChildren(item.id, PARENT_ID);
                if (children.length > 0) {
                    // Only update it when this item has children
                    // Otherwise just leave it be
                    child = true;
                }
                
                if (child || (siblingParents.indexOf(item.parent_id) !== -1)) {
                    item.X = item.X + offset;
                    newParents.push(sibling);
                }
            }
        });
        
        siblingParents = newParents;
    });
}

function filterMapItems(prop, value) {
//    return g_MapItems.filter(item => (prop == "parents") ? (item[prop].includes(value)) : (item[prop] === value));
    return g_MapItems.filter(function(item) {
        return (prop === "parents") ? (item[prop].includes(value)) : (item[prop] === value);
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
        Y = parent.Y + g_Options.y_length + g_Options.y_dist;
    }
    return Y;
}

function calcX(item) {
    var X = 0;
    
    // The X depends on the parent
    if(item.parent_id !== "-1") {
        var parent = getMapItem(item.parent_id);
        
        // Get the average X coordinate of the parents
        var avgX = parent.X;
        
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
                X = avgX + offset*(g_Options.x_length + g_Options.x_dist);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents X coordinate
                var offset = middle - index;
                X = avgX - offset*(g_Options.x_length + g_Options.x_dist);
            }
        } else { // even
            var middle = parent.children.length / 2;
            var index = parent.children.indexOf(item.id);
            if (index >= middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents X coordinate
                var offset = index - middle;
                X = (avgX + ((g_Options.x_length + g_Options.x_dist) / 2)) + offset*(g_Options.x_length + g_Options.x_dist);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents X coordinate
                var offset = middle - index;
                X = (avgX + ((g_Options.x_length + g_Options.x_dist) / 2)) - offset*(g_Options.x_length + g_Options.x_dist);
            }
        }
    }
    
    // Does this  X coordinate cause an overlap with the left level sibling?
    var sibling = getLeftLevelSibling(item.id);

    if (sibling) {
        // The distance needed between left and right
        var offset = (sibling.X + g_Options.x_length + g_Options.x_dist) - X; 
        if (offset > 0) { 
            var ancestor = getCommonAncestor(sibling.id, item.id);
            
            // Save it to solve it later
            g_ClashedItems.push({
                right: item.id,
                left: sibling.id,
                ancestor: ancestor.id
            });
        }
    }
    
    return X;
}

function sortByAncestor() {
    g_ClashedItems.sort(function(left, right) {
        // Get the levels
        var levelL = getMapItem(left.ancestor).level;
        var levelR = getMapItem(right.ancestor).level;
        
        // Get the level indexes
        var indexL = getMapItem(left.ancestor).level_index;
        var indexR = getMapItem(right.ancestor).level_index;
        
        // Sort by level (desc) and then by level index (asc)
        if (levelL !== levelR) {
            return levelR - levelL;
        } else if (levelL === levelR) {
            return indexL - indexR;
        }
    });
}

function solveClash(item) {
    // Get the items that are clashing
    var left = getMapItem(item.left);
    var right = getMapItem(item.right);
    
    // Make sure the clash is still present
    var offset = (left.X + g_Options.x_length + g_Options.x_dist) - right.X;
    if (offset > 0) {
        // Step 1: Find a common ancestor, and get the child on the 
        // right side of the clash
        var ancestor = getMapItem(item.ancestor);

        // Step 2: Per child of the ancester, move child and siblings to the right
        moveCommonAncestor(offset, ancestor);

        // Step 3: Check again
        // The distance needed between left and right
        var new_offset = (left.X + g_Options.x_length + g_Options.x_dist) - right.X;
        if (new_offset > 0) {
            // Something's not right.. We've just moved right,
			// and right is still not far enough..
            console.log("There is an overlap detected! Again.." + "(offset: " + offset + ", new offset: " + new_offset + ")");
            console.log("Left: ");
            console.log(left);
            console.log("Right: ");
            console.log(right);
            console.log("Ancestor: ");
            console.log(ancestor);
        }
    }
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