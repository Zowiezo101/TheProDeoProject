
/* global g_MapItems, g_Options, ALIGNMENT_VERTICAL, get_settings */

// The global variable for the SVG where everything will be drawn in
var g_svg = null;

function setSVG(svg) {
    
    if (svg)
        g_svg = svg;
    
    return g_svg !== null;
}

function drawControlButtons() {
    
//    // The height and width of the SVG parent
//    var map = $("#map_div");
//    var outerHeight = map.outerHeight(true);
//    var outerWidth = map.outerWidth(true);
//
//    // Show the controls to move around in the SVG
//    var group = svg.group({id: "controls"});
//    
//    // The zoom-fit button in SVG
//    var zoomfit = group.group({id: "zoomfit"});
//    zoomfit.click(onZoomFit);
//    zoomfit.mouseover(function() {this.first().stroke('red');});
//    zoomfit.mouseout(function() {this.first().stroke('black');});
//    zoomfit.rect(40, 40)
//            .radius(10, 10)
//            .fill('white')
//            .stroke('black')
//            .move(
//                outerWidth - 65, 
//                10);
//    zoomfit.html('<i class="fa fa-expand" aria-hidden="true"></i>').font({size: 36}).move(
//                outerWidth - 65, 
//                4);
//    
//    // The zoom-reset button in SVG
//    var zoomreset = group.group({id: "zoomreset"});
//    zoomreset.click(onZoomReset);
//    zoomreset.mouseover(function() {this.first().stroke('red');});
//    zoomreset.mouseout(function() {this.first().stroke('black');});
//    zoomreset.rect(40, 40)
//            .radius(10, 10)
//            .fill('white')
//            .stroke('black')
//            .move(
//                outerWidth - 65, 
//                60);
//    zoomreset.html('<i class="fa fa-compress" aria-hidden="true"></i>').font({size: 36}).move(
//                outerWidth - 65, 
//                54);
//    
//    // The download button in SVG
//    var download = group.group({id: "download"});
//    download.click(onDownload);
//    download.mouseover(function() {this.first().stroke('red');});
//    download.mouseout(function() {this.first().stroke('black');});
//    download.rect(40, 40)
//            .radius(10, 10)
//            .fill('white')
//            .stroke('black')
//            .move(
//                outerWidth - 65, 
//                110);
//    download.text("Download").font({size: 36}).move(
//                outerWidth - 65, 
//                104);
}
    
function drawMapItems() {
    
    // The height and width of the SVG parent
    var map = $("#map_div");
    var outerHeight = map.outerHeight(true);
    var outerWidth = map.outerWidth(true);

    // The root parent
    var group = g_svg.group({id: "map"});    
    g_MapItems.forEach(function(item) {
        drawLink(group, item);
        drawItem(group, item);
    });
    
    panzoom(group.node);
    
    // TODO:
//    g_svg.text(dict["familytree.link"]).attr("id", "tooltip").hide();
    group.element("title").words("Click to go to details page");
}

function drawItem(group, item) {
    
    // The link to the object
    if (g_Options.align === ALIGNMENT_VERTICAL) {
        var link = group.link(setParameters("peoples/people/" + item.id));
        link.target('_blank');
    
        // Draw the rectangle
        link.rect(item.x_length, 
                  item.y_length)
                .fill(item.gender === "-1" ? 'lightgrey' : (item.gender === "1" ? 'blue' : 'pink'))
                .stroke('black')
                .radius(10, 10)
                .move(item.X, item.Y)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });

        //Insert the text
        link.text(item.name)
                .font({size: 20})
                .center(item.X + item.x_length / 2, 
                        item.Y + item.y_length / 2)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });
    } else {
        // Turn it all counter clock wise
        var link = group.link(setParameters("events/event/" + get_settings["id"]));
        link.target('_blank');
    
        // Draw the rectangle
        link.rect(item.y_length, 
                  item.x_length)
                .fill(item.gender === "-1" ? 'lightgrey' : (item.gender === "1" ? 'blue' : 'pink'))
                .stroke('black')
                .radius(10, 10)
                .move(item.Y, item.X)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });

        //Insert the text
        link.text(item.name)
                .font({size: 20})
                .center(item.Y + item.y_length / 2, 
                        item.X + item.x_length / 2)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });
    }
    
}

function drawLink(group, child) {
    if (child.root !== true) {
        child.parents.forEach(function (parent_id) {
            var parent = getMapItem(parent_id);

            if (g_Options.align === ALIGNMENT_VERTICAL) {
                group.polyline([
                            [parent.X + parent.x_length / 2, 
                             parent.Y + parent.y_length], 
                            [parent.X + parent.x_length / 2, 
                             parent.Y + parent.y_length + g_Options.y_dist / 3], 
                            [child.X + child.x_length / 2, 
                             child.Y - g_Options.y_dist / 3], 
                            [child.X + child.x_length / 2, 
                             child.Y]])
                    .fill('none')
                    .stroke({ color: parent.gender === "-1" ? 'lightgrey' : (parent.gender === "1" ? 'blue' : 'pink'),
                              width: 4, linecap: 'round', linejoin: 'round' });
            } else {
                group.polyline([
                            [parent.Y + parent.y_length, 
                             parent.X + parent.x_length / 2], 
                            [parent.Y + parent.y_length + g_Options.y_dist / 3, 
                             parent.X + parent.x_length / 2], 
                            [child.Y - g_Options.y_dist / 3, 
                             child.X + child.x_length / 2], 
                            [child.Y, 
                             child.X + child.x_length / 2]])
                    .fill('none')
                    .stroke({ color: parent.gender === "-1" ? 'lightgrey' : (parent.gender === "1" ? 'blue' : 'pink'),
                              width: 4, linecap: 'round', linejoin: 'round' });
                 
            }
        });
    }
}


