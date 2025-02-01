
class MapMaker {    
    map;
    map_items;
    sub_items;
    archive;
    options;
    
    constructor(options) {
        this.options = options;
    }
    
    setMap(map) {
        this.map = map;
    }
    
    setItems(data) {
        items = data;
        root = items.shift();
        root.root = true;
        items = [root].concat(items);
        
        // Convert the generations and levels to integers if they are strings
        items.forEach(function(item) {
            item.gen = parseInt(item.gen, 10);
            item.level = parseInt(item.level, 10);
        });

        // Set the parents and the children/sublevels
        items = setParents(items);

        // Remove the duplicates
        items = removeDuplicates(items);

        items = sortMapItems(items);
    
        // Archive the subs for later use
        this.archive = filterMapItems('level', 2);

        // Now remove the other levels from the official map items array
        this.map_items = filterMapItems('level', 1);
    }

    setSubMapItems(id) {
        var ancestor = getMapItem(id);

        var parent = {
            id: ancestor.id,
            name: ancestor.name,
            meaning_name: ancestor.hasOwnProperty('meaning_name') ? ancestor.meaning_name : null,
            descr: ancestor.hasOwnProperty('descr') ? ancestor.descr : null,
            aka: ancestor.hasOwnProperty('aka') ? ancestor.aka : null,
            gender: ancestor.hasOwnProperty('gender') ? ancestor.gender : null,
            date: ancestor.hasOwnProperty('date') ? ancestor.date : null,
            length: ancestor.hasOwnProperty('length') ? ancestor.length : null,
            book_start_id: ancestor.hasOwnProperty('book_start_id') ? ancestor.book_start_id : null, 
            book_start_chap: ancestor.hasOwnProperty('book_start_chap') ? ancestor.book_start_chap : null,
            book_start_vers: ancestor.hasOwnProperty('book_start_vers') ? ancestor.book_start_vers : null,
            book_end_id: ancestor.hasOwnProperty('book_end_id') ? ancestor.book_end_id : null,
            book_end_chap: ancestor.hasOwnProperty('book_end_chap') ? ancestor.book_end_chap : null,
            book_end_vers: ancestor.hasOwnProperty('book_end_vers') ? ancestor.book_end_vers : null,
            parent_id: -1,
            gen: 0,
            gen_index: 0,
            level: 2,
            notes: ancestor.hasOwnProperty("notes") ? ancestor.notes : [],
            root: true,
            parents: [],
            children: Array.from(ancestor.subChildren),
            subChildren: Array.from(ancestor.subChildren)
        };

        this.sub_items = [parent];

        // The children of the item
        var children = Array.from(parent.children);
        var newChildren = null;

        while (children.length > 0) {
            // The child to currently work with
            var childId = children.shift();
            var childItem = getArchiveItem(childId);

            // Add this child to the list
            g_SubMapItems.push(childItem);

            // Find the next generation
            var newChildren = childItem.children;
            if (newChildren.length > 0) {
                children = children.concat(newChildren);
            }
        }

        // Remove the duplicates
        this.sub_items = removeDuplicates(this.sub_items);

        this.sub_items = sortMapItems(this.sub_items);
    }

    getMapItems() {
        return g_Options.sub ? g_SubMapItems : g_MapItems;
    }
    
    setParents(items) {
        // Go through all the items
        items.forEach(function(item) {
            // Get the item ID and the parent_id
            id = item.id;
            parent_id = item.parent_id;
            
            // Get all the items with this item ID
            var children = filterMapItems('id', id);

            // There might be duplicates
            children.forEach(function(child) {
                if (!child.hasOwnProperty('parents'))
                    child.parents = [];
                if (!child.hasOwnProperty('children'))
                    child.children = [];

                // SubParents.Children = When a child has a lower level then it's parent
                if (!child.hasOwnProperty('subParents'))
                    child.subParents = [];
                if (!child.hasOwnProperty('subChildren'))
                    child.subChildren = [];

                // Set the child of this parent
                var parents = filterMapItems('id', parent_id);

                // There might be duplicates
                parents.forEach(function(parent) {
                    // Set the parents
                    if (!child.parents.includes(parent_id) && parent_id !== -1) {
                        if (child.level === parent.level) {
                            // We are the child of this parent
                            child.parents.push(parent_id);
                        } else if (child.level < parent.level) {
                            // Keep going up until the parent is found with a higher level and use that as the new parent_id
                            child.parent_id = getSubParent(child.level, parent);
                            if (!child.parents.includes(child.parent_id) && child.parent_id !== -1) {
                                child.parents.push(child.parent_id);
                            }

                            // Work with the new parent
                            parent = getMapItem(child.parent_id);
                        } else {
                            // We are a sub of this parent, don't add it as a child
                            child.parents.push(parent_id);
                        }
                    }

                    if (parent) {
                        if (!parent.hasOwnProperty('parents'))
                            parent.parents = [];
                        if (!parent.hasOwnProperty('children'))
                            parent.children = [];
                        if (!parent.hasOwnProperty('subParents'))
                            parent.subParents = [];
                        if (!parent.hasOwnProperty('subChildren'))
                            parent.subChildren = [];

                        // Set the children
                        if (!parent.children.includes(id)) {
                            if (parent.level === child.level) {
                                // We are the parent of this child
                                parent.children.push(id);
                            } else if (parent.level < child.level) {
                                // This child is a sub of this parent, add it as a sub
                                parent.subChildren.push(id);
                            } else {
                                // This parent is a sub and the child is not actually
                                // a child. 
                            }
                        }
                    }
                });
            });
        });

    }

    filterMapItems(prop, value, data) {
        if (typeof data === "undefined") {
            data = getMapItems();
        }

        return data.filter(function(item) {
            return ["parents", "children"].includes(prop) ? (item[prop].includes(value)) : (item[prop] === value);
        });
    }

    getSubParents(level) {
        if (typeof data === "undefined") {
            data = getMapItems();
        }
        
        // Find every parent with subchildren
        return data.filter(function(item) {
            return item.subChildren.length > 0 && item.level === level;
        });
    }
        
}
