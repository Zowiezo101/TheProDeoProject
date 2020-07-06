/* global levelCounter, levelIDs, session_settings, globalOffset, dict */

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
                var id = IDset[i];
                drawItem(id, SVG);
            }
        }
        
        // There are no more children to update
        if (level === MaxLevel) {
            done = 1;
        }
    }
    
    return;
}

/** 
 * @param {Integer} id
 * @param {SVGElement} SVG */
function drawItem (id, SVG) {
    var Item = getItemById(id);
    var IDset = [];

    var svgns = "http://www.w3.org/2000/svg";
    var hrefns = "http://www.w3.org/1999/xlink";
    var Group = document.createElementNS(svgns, "g");

    if (session_settings["table"] === "timeline") {
        // Move everything away from the upper border
        var x = Item.Location[0];
        var y = Item.Location[1] + globalOffset;
    } else {
        // Move everything away from the left border
        var x = Item.Location[0] + globalOffset;
        var y = Item.Location[1];
    }

    // This object has multiple parents, draw them all
    if(Item.parents.length > 0) {
        for (var i = 0; i < Item.parents.length; i++) {
            // Draw the lines to the mother, to the middle of the bottom
            var Parent = getItemById(Item.parents[i]);

            // And only if the parents are drawn as well
            if ((Parent.Location[0] !== -1) && (Parent.Location[1] !== -1)) {

                // Make three lines, to get nice 90 degree angles
                var LineMother1 = document.createElementNS(svgns, "line");
                var LineMother2 = document.createElementNS(svgns, "line");
                var LineMother3 = document.createElementNS(svgns, "line");

                if (session_settings["table"] === "timeline") {
                    var x_parent = Parent.Location[0] + Parent.lengthIndex*100;
                    var y_parent = Parent.Location[1] + 25 + globalOffset;

                    var x_halfway1 = x_parent + (50 / 2) - 10;
                    var x_halfway2 = x - (50 / 2) + 10;

                    // The first line goes only vertical, and halfway
                    LineMother1.setAttributeNS(null, 'x1', x_parent);
                    LineMother1.setAttributeNS(null, 'y1', y_parent);
                    LineMother1.setAttributeNS(null, 'x2', x_halfway1);
                    LineMother1.setAttributeNS(null, 'y2', y_parent);

                    // The second line goes only horizontal, or diagonal
                    LineMother2.setAttributeNS(null, 'x1', x_halfway1);
                    LineMother2.setAttributeNS(null, 'y1', y_parent);
                    LineMother2.setAttributeNS(null, 'x2', x_halfway2);
                    LineMother2.setAttributeNS(null, 'y2', y + 25);

                    // The last line goes only vertical, the second half
                    LineMother3.setAttributeNS(null, 'x1', x_halfway2);
                    LineMother3.setAttributeNS(null, 'y1', y + 25);
                    LineMother3.setAttributeNS(null, 'x2', x);
                    LineMother3.setAttributeNS(null, 'y2', y + 25);

                } else {
                    var x_parent = Parent.Location[0] + 50 + globalOffset;
                    var y_parent = Parent.Location[1] + 50;

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
                }

                if (Item.level === (Parent.level + 1)) {
                    LineMother1.setAttributeNS(null, 'stroke', Item.data ? 'pink' : 'blue');
                    LineMother2.setAttributeNS(null, 'stroke', Item.data ? 'pink' : 'blue');
                    LineMother3.setAttributeNS(null, 'stroke', Item.data ? 'pink' : 'blue');

                    LineMother1.setAttributeNS(null, 'stroke-width', '5');
                    LineMother2.setAttributeNS(null, 'stroke-width', '5');
                    LineMother3.setAttributeNS(null, 'stroke-width', '5');
                } else {
                    LineMother1.setAttributeNS(null, 'stroke', Item.data ? 'deeppink' : 'darkblue');
                    LineMother2.setAttributeNS(null, 'stroke', Item.data ? 'deeppink' : 'darkblue');
                    LineMother3.setAttributeNS(null, 'stroke', Item.data ? 'deeppink' : 'darkblue');

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
    Rect.setAttributeNS(null, 'width', Item.lengthIndex*100);
    Rect.setAttributeNS(null, 'height', 50);

    Rect.setAttributeNS(null, 'x', x);
    Rect.setAttributeNS(null, 'y', y);

    Rect.setAttributeNS(null, 'rx', 5);
    Rect.setAttributeNS(null, 'ry', 5);

    Rect.setAttributeNS(null, 'stroke', 'black');
    Rect.setAttributeNS(null, 'fill', getItemColor(Item.id));

    Rect.className.baseVal = "Rect";
    Rect.id = "Rect" + Item.id;
    Rect.RectID = Item.id;

    var Text = document.createElementNS(svgns, "text");        
    Text.setAttributeNS(null, 'width', Item.lengthIndex*100);
    Text.setAttributeNS(null, 'height', 50);

    Text.setAttributeNS(null, 'x', x);
    if (session_settings["table"] === "timeline") {
        Text.setAttributeNS(null, 'y', y);
    } else {
        Text.setAttributeNS(null, 'y', y + 25);
    }

    Item.getText(Text, Item.name);
    Text.RectID = Item.id;

    if (Item.id !== -999) {
        var Link = document.createElementNS(svgns, "a");
        Link.setAttributeNS(hrefns, 'xlink:title', dict["link"]);
        Link.setAttributeNS(hrefns, 'target', "_top");

        Link.appendChild(Rect);
        Link.appendChild(Text);

        Link.RectID = Item.id;
        if (session_settings["table"] === "timeline") {
            Link.setAttributeNS(null, 'onclick', 'updateSessionSettings("keep", true).then(goToPage("events.php", "", session_settings["map"] === "global_id" ? event.target.RectID : session_settings["map"]), console.log)');
        } else {
            Link.setAttributeNS(null, 'onclick', 'updateSessionSettings("keep", true).then(goToPage("peoples.php", "", event.target.RectID), console.log)');
        }
        Link.setAttributeNS(null, 'onmouseover', 'setBorder(evt)');
        Link.setAttributeNS(null, 'onmouseout',  'clearBorder(evt)');

        Group.appendChild(Link);        
    } else {
        // This ID doens't exist, it's just the global activity item
        Group.appendChild(Rect);
        Group.appendChild(Text);
    }
    
    if ((Group !== null) && (Item.drawn === 0)) {
        SVG.appendChild(Group);
        Item.drawn = 1;
    }

    if (Item.ChildIDs.length !== 0)
    {
        IDset = Item.ChildIDs;
    }

    // This would have been much easier using recursive functions
    // But there is too much recursion for the browser to handle..
    return IDset;
};


