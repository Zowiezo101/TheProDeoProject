/* global onZoomIn, onZoomOut, onZoomFit, onZoomReset, onDownload */

var TYPE_FAMILYTREE = "familytree";
var TYPE_TIMELINE = "timeline";

// The coordinate system is either {depth, offset} or {offset, depth}
// depending on the type of map we're generating
var DEPTH_COORD = {};
DEPTH_COORD[TYPE_TIMELINE] = "X";
DEPTH_COORD[TYPE_FAMILYTREE] = "Y";

var OFFSET_COORD = {};
OFFSET_COORD[TYPE_FAMILYTREE] = "X";
OFFSET_COORD[TYPE_TIMELINE] = "Y";

var PARENTS = 0;
var PARENT_ID = 1;

var g_MapItems = null;
var g_Options = null;
var g_ClashedItems = [];
var g_Map = null;

// The offsets
var g_Offsets = {
    width_min: 0, 
    width_max: 0, 
    height_min: 0, 
    height_max: 0
};

function setMapItems (map) {
    g_Map = map;
    
    var parent = {
        id: map.id,
        name: map.name,
        meaning_name: map.hasOwnProperty('meaning_name') ? map.meaning_name : null,
        descr: map.hasOwnProperty('descr') ? map.descr : null,
        aka: map.hasOwnProperty('aka') ? map.aka : null,
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
    
    // Make sure parents and children have the highest level possible for the best readability in case of timelines
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
        
        // In this case it's a timeline, let's go by level
        for (var i = 0; i < topLevel; i++) {
            // Get all the children of this level
            var children = filterMapItems('level', i);
            
            children.forEach(function(mapItem) {
                if (mapItem.parents.length !== 0) {
                    var highestLevelParent = mapItem.parents.reduce(function(highestLevel, parentIdx) {
                        var parent = getMapItem(parentIdx);
                        if (highestLevel === -1) {
                            return parent.level;
                        } else {
                            return parent.level > highestLevel ? parent.level : highestLevel;
                        }
                    }, -1);
                    
                    mapItem.level = highestLevelParent + 1;
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
        items.forEach((child, index) => child.level_index = index);
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
    
    // The size of the items
    if(!options || !options.hasOwnProperty('item_width')) 
        options.item_width = options.type === TYPE_FAMILYTREE ? 100 : 300;
    if(!options || !options.hasOwnProperty('item_height')) 
        options.item_height = 50;
    
    // The distance between the items
    if(!options || !options.hasOwnProperty('hori_dist')) 
        options.hori_dist = 25;
    if(!options || !options.hasOwnProperty('vert_dist')) 
        options.vert_dist = 30;
    
    // The global settings used
    g_Options = {
        "length": {X: options.item_width, Y: options.item_height},
        "dist": {X: options.hori_dist, Y: options.vert_dist},
        "type": options.type
    };
    
    g_MapItems.forEach(function(item) { 
        
        item.width = options.item_width;
        item.height = options.item_height;
        
        item[DEPTH_COORD[g_Options.type]] = calcDepth(item);
        item[OFFSET_COORD[g_Options.type]] = calcOffset(item);
    
        // We calculated its coordinates
        item.calculated = true;
    });
    
    // Sort the array by ancestors, since those need to be moved
    sortByAncestor();
    
    // Try to solve all the clashes we found, 
    // then get the heighest X and Y values 
    // and use it to shift the entire thing
    g_ClashedItems.forEach(item => solveClash(item));
    g_MapItems.forEach(item => getOffsets(item));
    g_MapItems.forEach(item => setOffsets(item));
    
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
    return items.length > 0 ? items[0] : null;
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
    // Get the item we want to siblings of
    var item = getMapItem(id);
    
    // The items on the same level
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
        item[OFFSET_COORD[g_Options.type]] = item[OFFSET_COORD[g_Options.type]] + offset;
        
        // Get the children as well (only the calculated ones)
        items = items.concat(getChildren(item.id, PARENT_ID));
    }
    
    // Now offset the parents on the right as well until we've reached the 
    // true ancestor, unless none of these have children..
    // Right side level siblings don't always need to be moved..
    // Only those who have children and those on the right of these
    var ancestors = getAncestors(parent.id);
    
    var siblingParents = [];
    var trueAncestor = false;
    ancestors.forEach(function(id) {
        // As long as we haven't found the true ancestor yet
        if (trueAncestor === false) {
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
                        // This person has children, put them in the newParents
                        // And set their offset
                        item[OFFSET_COORD[g_Options.type]] = item[OFFSET_COORD[g_Options.type]] + offset;
                        newParents.push(sibling);
                    }
                }
            });
        
            if (getLevelSiblings(id).length === 1) {
                // No siblings to work with, 
                // meaning that we reached the true ancestor
                trueAncestor = true;
            }
        
            siblingParents = newParents;
        }
    });
}

function filterMapItems(prop, value) {
//    return g_MapItems.filter(item => (prop == "parents") ? (item[prop].includes(value)) : (item[prop] === value));
    return g_MapItems.filter(function(item) {
        return ["parents", "children"].includes(prop) ? (item[prop].includes(value)) : (item[prop] === value);
    });
}

function calcDepth(item) {    
    var cDepth = 0;
    
    // The depth depends on the parents
    if(item.parents.length) {
        // Get the highest level parent
        var parent = item.parents.reduce(function(parent1, idx) {
            var parent2 = getMapItem(idx);
            
            return (parent1.level < parent2.level) ? parent2 : parent1;
        }, getMapItem(item.parents[0]));
        
        // Get the parent depth coordinate, add the height to it 
        // and the standard vertical offset
        cDepth = parent[DEPTH_COORD[g_Options.type]] + g_Options.length[DEPTH_COORD[g_Options.type]] + g_Options.dist[DEPTH_COORD[g_Options.type]];
    }
    return cDepth;
}

function calcOffset(item) {
    var cOffset = 0;
    
    // The offset depends on the parent
    if(item.parent_id !== "-1") {
        var parent = getMapItem(item.parent_id);
        
        // Get the average offset coordinate of the parents
        if (g_Options.type === TYPE_FAMILYTREE) {
            var avgOffset = parent[OFFSET_COORD[g_Options.type]];
        } else {
            var parentOffsets = [];
            
            // Get all the parents for this child 
            // a.k.a search for every child with this id
            var avgOffset = filterMapItems("children", item.id).reduce(function(carry, parent) {
                // Is it directly above us? Use it's X coordinate
                if ((parent.level + 1) === item.level) {
                    carry += parseInt(parent[OFFSET_COORD[g_Options.type]], 10);
                    parentOffsets.push(parent[OFFSET_COORD[g_Options.type]]);
                }
                return carry;
            }, 0);
            
            if (parentOffsets.length > 0) {
                // Parents directly above us
                var avgOffset = avgOffset / parentOffsets.length;
            } else {
                // No parents directly above us?
                var parent = getMapItem(item.parent_id);
                avgOffset = parent[OFFSET_COORD[g_Options.type]];
            }
        }
        
        // Number of children of parent
        if (parent.children.length % 2) {  // odd
            var middle = ((parent.children.length + 1) / 2) - 1;
            var index = parent.children.indexOf(item.id);

            if (index === middle) {
                // Are we in the middle? 
                // Then just use parents offset coordinate
                cOffset = avgOffset;
            } else if (index > middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents offset coordinate
                var offset = index - middle;
                cOffset = avgOffset + offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents X coordinate
                var offset = middle - index;
                cOffset = avgOffset - offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            }
        } else { // even
            var middle = parent.children.length / 2;
            var index = parent.children.indexOf(item.id);
            if (index >= middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents offset coordinate
                var offset = index - middle;
                cOffset = (avgOffset + ((g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]) / 2)) + 
                        offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents offset coordinate
                var offset = middle - index;
                cOffset = (avgOffset + ((g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]) / 2)) - 
                        offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            }
        }
    }
    
    // Does this offset coordinate cause an overlap with the left level sibling?
    var sibling = getLeftLevelSibling(item.id);

    if (sibling) {
        // The distance needed between left and right
        var offset = (sibling[OFFSET_COORD[g_Options.type]] + (g_Options.length[OFFSET_COORD[g_Options.type]]) + g_Options.dist[OFFSET_COORD[g_Options.type]]) - cOffset; 
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
    
    return cOffset;
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
    var offset = (left[OFFSET_COORD[g_Options.type]] + (g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]])) - right[OFFSET_COORD[g_Options.type]];
    if (offset > 0) {
        // Step 1: Find a common ancestor, and get the child on the 
        // right side of the clash
        var ancestor = getMapItem(item.ancestor);

        // Step 2: Per child of the ancester, move child and siblings to the right
        moveCommonAncestor(offset, ancestor);

        // Step 3: Check again
        // The distance needed between left and right
        var new_offset = (left[OFFSET_COORD[g_Options.type]] + (g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]])) - right[OFFSET_COORD[g_Options.type]];
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

function getOffsets(item) {
    g_Offsets.width_min = Math.min(item.X, g_Offsets.width_min);
    g_Offsets.width_max = Math.max(item.X, g_Offsets.width_max);
    g_Offsets.height_min = Math.min(item.Y, g_Offsets.height_min);
    g_Offsets.height_max = Math.max(item.Y, g_Offsets.height_max);
}

function setOffsets(item) {
    item.X = item.X - g_Offsets.width_min;
    item.Y = item.Y - g_Offsets.height_min;
}

function calcPolyLineCoords(items) {
    var child = items.child;
    var parent = items.parent;

        if (g_Options.type === TYPE_FAMILYTREE) {
            var coords = [
                [parent.X + parent.width / 2, 
                 parent.Y + parent.height], 
                [parent.X + parent.width / 2, 
                 parent.Y + parent.height + g_Options.dist.Y / 3], 
                [child.X + child.width / 2, 
                 child.Y - g_Options.dist.Y / 3], 
                [child.X + child.width / 2, 
                 child.Y]
            ];
        } else {
            coords = [
                [parent.X + parent.width, 
                 parent.Y + parent.height / 2], 
                [parent.X + parent.width + g_Options.dist.X / 3, 
                 parent.Y + parent.height / 2], 
                [child.X - g_Options.dist.X / 3, 
                 child.Y + child.height / 2], 
                [child.X, 
                 child.Y + child.height / 2]
            ];
        }
        
        return coords;
}