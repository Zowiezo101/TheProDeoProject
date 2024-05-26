/* global get_settings, pzInstance, g_Options, g_MapItems, g_Offsets, TYPE_FAMILYTREE, getSVG */

var pzInstance = null;
var pzSubInstance = null;

// These two are used to get smooth zooming/panning
// with the correct values, by taking over the actual
// Zoom/pan function for a little while
var onSmoothPanning = false;
var onSmoothZooming = false;
var timeoutPan = null;
var timeoutZoom = null;
var callbackPan = null;
var callbackZoom = null;

function onBeforePan(oldPan, newPan) {
    if (onSmoothPanning === true) {
        onSmoothPanning = false;
        panSmooth(oldPan, newPan);
        return false;
    } else {
        return true;  
    }
}

function onBeforeZoom(oldZoom, newZoom) {
    if (onSmoothZooming === true) {
        onSmoothZooming = false;
        zoomSmooth(oldZoom, newZoom);
        return false;
    } else {
        return true;  
    }
}

function onAfterPanZoom(f) {    
    // These animations take a max of 400 ms
    if (timeoutZoom !== null) {
        // Still busy, use a callback
        callbackZoom = function() {
            // After the callback, check if the panning function is also done
            if (timeoutPan !== null) {
                // Not yet done, use a callback
                callbackPan = f;
            } else {
                // Done, execute it right away
                f();
            }
        };
    } else if(timeoutPan !== null) {
        // Still busy, use a callback
        callbackPan = function() {
            // After the callback, check if the panning function is also done
            if (timeoutZoom !== null) {
                // Not yet done, use a callback
                callbackZoom = f;
            } else {
                // Done, execute it right away
                f();
            }
        };
    } else {
        // Done, execute it right away
        f();
    }
}

function setViewSettings() {    
    // Setting the panZoom settings
    pzInstance = svgPanZoom(getSVG().node, {
        fit: false,
        center: false,
        maxZoom: 2,
        minZoom: 0.01,
        beforeZoom: onBeforeZoom,
        beforePan: onBeforePan
    });
    
    jQuery.Color.hook("stroke");
    
    // When resizing the window, 
    // make sure the bounding box is recalculated and such.
    $(window).resize(function() {
        if (pzInstance !== null) {
            // Get the current location and zoom
            var pan = pzInstance.getPan();
            var zoom = pzInstance.getZoom();
            
            // Reset and resize
            pzInstance.reset();
            pzInstance.resize();
            
            // Set the current location and zoom
            pzInstance.pan(pan);
            pzInstance.zoom(zoom);
        }
    });
}

function setSubViewSettings() {
    // Setting the panZoom settings
    pzSubInstance = svgPanZoom(getSVG().node, {
        fit: false,
        center: false,
        maxZoom: 2,
        minZoom: 0.01,
        beforeZoom: onBeforeZoom,
        beforePan: onBeforePan
    });
    
    jQuery.Color.hook("stroke");
    
    // When resizing the window, 
    // make sure the bounding box is recalculated and such.
    $(window).resize(function() {
        if (pzSubInstance !== null) {
            // Get the current location and zoom
            var pan = pzSubInstance.getPan();
            var zoom = pzSubInstance.getZoom();
            
            // Reset and resize
            pzSubInstance.reset();
            pzSubInstance.resize();
            
            // Set the current location and zoom
            pzSubInstance.pan(pan);
            pzSubInstance.zoom(zoom);
        }
    });
}

function getPZInstance() {
    return g_Options.sub ? pzSubInstance : pzInstance;
}

function panToItem() {
    // Default ID to pan to is the first item of the timeline
    var id = g_MapItems[0].id;
    // TODO:
//    if (get_settings.hasOwnProperty("panTo")) {
//    
//        // Get the item to pan to
//        id = get_settings["panTo"];
//    }
    
    panToId(id);
}

function panToId(id) {
    // Get the item
    var item = getMapItem(id);
    if (item === null) {
        // The first item is on 0,0
        item = {X: 0, Y: 0};
    } 
    
    // The height and width of the SVG parent
    var map = getMapDiv();
    var outerHeight = map.outerHeight(true);
    var outerWidth = map.outerWidth(true);

    // Calculate the desired location
    var newX = (outerWidth / 2) - (item.X + (g_Options.length.X / 2));
    var newY = (outerHeight / 2) - (item.Y + (g_Options.length.Y / 2));

    // Move to the desired location
    panSmooth(getPZInstance().getPan(), {x: newX, y: newY});
    onAfterPanZoom(function() {
        focusOnRect(item.id);
    });
}

function panSmooth(oldPan, newPan) {
    
    cancel();
    
    // Where we come from
    var source = {
        x: oldPan.x,
        y: oldPan.y
    };
    
    // Where we are
    var current = {
        x: source.x, 
        y: source.y
    };
    
    // Where we were
    var previous = {
        x: source.x, 
        y: source.y
    };
    
    // Where we go to
    var diff = {
        x: newPan.x - source.x,
        y: newPan.y - source.y
    };
    
    // This is used for the easing in and out, making it more smooth
    var bezierEasing = [
        0,
        0.025555679605604214,
        0.07080587352390412,
        0.136888414855361,
        0.22067374458182937,
        0.31429178469607527,
        0.4085105913553958,
        0.4967156399891718,
        0.5758623175776229,
        0.6453214475092404,
        0.7056111253727028,
        0.7576478399443315,
        0.802403387584857,
        0.8407733270130865,
        0.8735391468536807,
        0.9013677794477065,
        0.9248240118839989,
        0.9443858337485936,
        0.960458978348974,
        0.9733894877072984,
        0.983474147668719,
        0.990969002410069,
        0.9960962541257425,
        0.9990498476756933
    ];

    // Some settings for the animation
    var animationTime = 400;
    var animationSteps = 24;
    var animationStepTime = animationTime / animationSteps;
    var animationStep = 0;
    
    // Start the first animation frame
    timeoutPan = setTimeout(animationFrame, animationStepTime);
    
    function animationFrame() {
        var t = bezierEasing[animationStep];
        current.x = diff.x * t + source.x;
        current.y = diff.y * t + source.y;
        
        if (animationStep++ < animationSteps) {
            getPZInstance().panBy({
                x: current.x - previous.x, 
                y: current.y - previous.y
            });

            previous.x = current.x;
            previous.y = current.y;
            
            timeoutPan = setTimeout(animationFrame, animationStepTime);
        } else {
            // Cancel timeout
            cancel();
            
            // Execute callback if set
            if (callbackPan !== null && typeof callbackPan === "function") {
                callbackPan();
                callbackPan = null;
            }
        }
    }
    
    function cancel() {
        // Cancel interval
        clearTimeout(timeoutPan);
        timeoutPan = null;
    }
}

function zoomSmooth(oldZoom, newZoom) {
    
    cancel();
    
    // Where we come from
    var source = oldZoom;
    
    // Where we are
    var current = source;
    
    // Where we were
    var previous = source;
    
    // Where we go to
    var diff = newZoom - oldZoom;
    
    // This is used for the easing in and out, making it more smooth
    var bezierEasing = [
        0,
        0.025555679605604214,
        0.07080587352390412,
        0.136888414855361,
        0.22067374458182937,
        0.31429178469607527,
        0.4085105913553958,
        0.4967156399891718,
        0.5758623175776229,
        0.6453214475092404,
        0.7056111253727028,
        0.7576478399443315,
        0.802403387584857,
        0.8407733270130865,
        0.8735391468536807,
        0.9013677794477065,
        0.9248240118839989,
        0.9443858337485936,
        0.960458978348974,
        0.9733894877072984,
        0.983474147668719,
        0.990969002410069,
        0.9960962541257425,
        0.9990498476756933
    ];

    // Some settings for the animation
    var animationTime = 400;
    var animationSteps = 24;
    var animationStepTime = animationTime / animationSteps;
    var animationStep = 0;
    
    // Start the first animation frame
    timeoutZoom = setTimeout(animationFrame, animationStepTime);
    
    function animationFrame() {
        var t = bezierEasing[animationStep];
        current = diff * t + source;
        
        if (animationStep++ < animationSteps) {
            getPZInstance().zoom(current);
            
            timeoutZoom = setTimeout(animationFrame, animationStepTime);
        } else {
            cancel();
            
            // Execute callback if set
            if (callbackZoom !== null && typeof callbackZoom === "function") {
                callbackZoom();
                callbackZoom = null;
            }
        }
    }
    
    function cancel() {
        // Cancel timeout
        clearTimeout(timeoutZoom);
        timeoutZoom = null;
    }
}

function onZoomFit() {    
    onSmoothPanning = true;
    onSmoothZooming = true;
    getPZInstance().fit();
    
    onAfterPanZoom(function () {
        onSmoothPanning = true;
        getPZInstance().center();
    });
}

function onZoomReset() {
    onSmoothZooming = true;
    onSmoothPanning = true;
    getPZInstance().resetZoom();
    
    onAfterPanZoom(function () {
        panToItem();
    });
}

function onDownload (title) {  
            
    // creates a new instance
    var svgsaver = new SvgSaver();
    
    // Create a SVG element for downloading
    var map_svg = $(getSVG().node).clone()
            .attr("width", g_Offsets.width_max - g_Offsets.width_min + g_Options.length.X)
            .attr("height", g_Offsets.height_max - g_Offsets.height_min + g_Options.length.Y)
            .css("overflow", "scroll");

    // Add it to the invisble div
    var map_div = $("#map_download").empty().append(map_svg);
    
    // Set the matrix to the default values
    $("#map_download #map")
            .attr("transform", "matrix(1, 0, 0, 1, 0, 0)")
            .css("transform", "matrix(1, 0, 0, 1, 0, 0)");

    // Wait for it to be added
    $(function() {
        // save as SVG
        svgsaver.asSvg(map_svg.get(0), title + ".svg");
    });
}

function focusOnRect(id) {   
    
    var originalWidth = $("#rect_" + id).attr("stroke-width");
    
    // Get the correct rectangle to set focus on
    $("#rect_" + id).animate({
        "stroke": "red",
        "stroke-width": "5px"
    }, 100, function() {
        $("#rect_" + id).animate({
            "stroke": "black",
            "stroke-width": originalWidth
        }, 2000);
    });
}