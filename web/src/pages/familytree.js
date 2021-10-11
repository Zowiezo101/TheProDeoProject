/* global dict */

function getFamilytreeContent(familytree) {
    
    if (familytree) {
        // A person has been selected, show it's information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">` + familytree.name + `</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <div id="familytree_div">
                    </div>
                </div>
            </div>
        `);
        
        var data = [];
        
        familytree.peoples.unshift({
            "id": familytree.id,
            "name": familytree.name,
            "gender": familytree.gender,
            "parent_id": -1,
        })
        familytree.peoples.forEach(function(people) {
            var person = {
                "id": people.id,
                "name": people.name,
                "parents": getParents(people.id, familytree.peoples),
                "spouses": getSpouses(people.id, familytree.peoples),
                "children": getChildren(people.id, familytree.peoples),
                "siblings": getSiblings(people.id, familytree.peoples),
            }
            
            data.push(person);
        });
        
        const tree = window.generateTree(document.getElementById("familytree_div"), data, { rootId: familytree.id });

    } else {
        // TODO Foutmelding, niet kunnen vinden?
    }
}

function getParents(id, peoples) {
    // Return the parent_ids of this person
    var result = peoples.filter(item => {
        return item.id === id && item.parent_id !== -1;
    });
     
    var parents = result.map(item => {
        return {
            "id": item.parent_id,
            "type": "blood"
        };
    });
    
    return parents.filter((item, pos, self) =>
        pos === self.findIndex((t) => (
            t.id === item.id
        ))
    );
}

function getSpouses(id, peoples) {
    
    if (getParents(id, peoples).length === 0) {
        return [];
    }
    // First get the children of this id
    var children = getChildren(id, peoples);
    
    // Now get all the parents of these children
    var parents = [];
    children.forEach(child => {
        parents = parents.concat(getParents(child.id, peoples));
    });
    
    // Filter out any parents that already have this id
    var spouses = parents.filter(parent => {
        return parent.id !== id;
    });
    
    return spouses.filter((item, pos, self) =>
        pos === self.findIndex((t) => (
            t.id === item.id
        ))
    ).map(function(item) {
        return {
            "id": item.id,
            "type": "married"
        };
    });
}

function getChildren(id, peoples) {
    // Return everything that has this id as parent id
    var result = peoples.filter(item => {
        return item.parent_id === id;
    });
    
    var children = result.map(item => {
        return {
            "id": item.id,
            "type": "blood"
        };
    });
    
    return children.filter((item, pos, self) =>
        pos === self.findIndex((t) => (
            t.id === item.id
        ))
    );
}

function getSiblings(id, peoples) {
    // First get the parents of this id
    var parents = getParents(id, peoples);
    
    // Now get all the cildren of these parents
    var children = [];
    parents.forEach(parent => {
        children = children.concat(getChildren(parent.id, peoples));
    });
    
    // Filter out any children that already have this id
    var siblings = children.filter(child => {
        return child.id !== id;
    });
    
    return siblings.filter((item, pos, self) =>
        pos === self.findIndex((t) => (
            t.id === item.id
        ))
    );
}