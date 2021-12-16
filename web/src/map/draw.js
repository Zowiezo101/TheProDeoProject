
/* global g_MapItems, g_Options */

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
}

function drawItem(group, item) {
    
    // The link to the object
    var link = group.link('https://google.com');
    
    // Draw the rectangle
    link.rect(item.width, 
              item.height)
            .fill(item.gender === "-1" ? 'lightgrey' : (item.gender === "1" ? 'blue' : 'pink'))
            .stroke('black')
            .radius(10, 10)
            .move(item.X, item.Y);
    
    //Insert the text
    link.text(item.name)
            .font({size: 20})
            .center(item.X + item.width / 2, 
                    item.Y + item.height / 2);
}

function drawLink(group, child) {
    if (child.root !== true) {
        child.parents.forEach(function (parent_id) {
            var parent = getMapItem(parent_id);

            group.polyline([[parent.X + parent.width / 3, parent.Y + parent.height], 
                        [parent.X + parent.width / 3, parent.Y + parent.height + g_Options.y_dist / 3], 
                        [child.X + child.width / 3, child.Y - g_Options.y_dist / 3], 
                        [child.X + child.width / 3, child.Y]])
                .fill('none')
                .stroke({ color: parent.gender === "-1" ? 'lightgrey' : (parent.gender === "1" ? 'blue' : 'pink'),
                          width: 4, linecap: 'round', linejoin: 'round' });
        });
    }
}


