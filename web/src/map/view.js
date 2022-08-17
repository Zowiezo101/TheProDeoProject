/* global get_settings, pzInstance, g_Options, g_MapItems, g_svg, g_Offsets, TYPE_FAMILYTREE */

var pzInstance = null;

// These two are used to get smooth zooming/panning
// with the correct values, by taking over the actual
// Zoom/pan function for a little while
var onSmoothPanning = false;
var onSmoothZooming = false;
var intervalPan = null;
var intervalZoom = null;
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
    if (intervalZoom !== null) {
        // Still busy, use a callback
        callbackZoom = function() {
            // After the callback, check if the panning function is also done
            if (intervalPan !== null) {
                // Not yet done, use a callback
                callbackPan = f;
            } else {
                // Done, execute it right away
                f();
            }
        };
    } else if(intervalPan !== null) {
        // Still busy, use a callback
        callbackPan = function() {
            // After the callback, check if the panning function is also done
            if (intervalZoom !== null) {
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
    pzInstance = svgPanZoom(g_svg.node, {
        fit: false,
        center: false,
        maxZoom: 2,
        minZoom: 0.01,
        beforeZoom: onBeforeZoom,
        beforePan: onBeforePan
    });
    
    jQuery.Color.hook("stroke");
    
    // When resizing the window, 
    // make sure the bouding box is recalculated and such.
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

function panToItem() {
    // Default ID to pan to is the first item of the timeline
    var id = g_MapItems[0].id;
    if (get_settings.hasOwnProperty("panTo")) {
    
        // Get the item to pan to
        id = get_settings["panTo"];
    }
    
    // Get the item
    var item = getMapItem(id);
    if (item === null) {
        // Let's assume the first item is on 0,0
        item = {X: 0, Y: 0};
    } 
    
    // The height and width of the SVG parent
    var map = $("#map_div");
    var outerHeight = map.outerHeight(true);
    var outerWidth = map.outerWidth(true);

    // Calculate the desired location
    if (g_Options.type === TYPE_FAMILYTREE) {
        var newX = (outerWidth / 2) - (item.X + (g_Options.x_length / 2));
        var newY = (outerHeight / 2) - (item.Y + (g_Options.y_length / 2));
    } else {
        var newX = (outerWidth / 2) - (item.Y + (g_Options.y_length / 2));
        var newY = (outerHeight / 2) - (item.X + (g_Options.x_length / 2));     
    }

    // Move to the desired location
    panSmooth(pzInstance.getPan(), {x: newX, y: newY});
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

    // Some settings for the interval
    var animationTime = 400;
    var animationSteps = 24;
    var animationStepTime = animationTime / animationSteps;
    var animationStep = 0;
    
    intervalPan = setInterval(function() {
        var t = bezierEasing[animationStep];
        current.x = diff.x * t + source.x;
        current.y = diff.y * t + source.y;
        
        if (animationStep++ < animationSteps) {
            pzInstance.panBy({
                x: current.x - previous.x, 
                y: current.y - previous.y
            });

            previous.x = current.x;
            previous.y = current.y;
        } else {
            // Cancel interval
            cancel();
            
            // Execute callback if set
            if (callbackPan !== null && typeof callbackPan === "function") {
                callbackPan();
                callbackPan = null;
            }
        }
    }, animationStepTime);
    
    function cancel() {
        // Cancel interval
        clearInterval(intervalPan);
        intervalPan = null;
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

    // Some settings for the interval
    var animationTime = 400;
    var animationSteps = 24;
    var animationStepTime = animationTime / animationSteps;
    var animationStep = 0;
    
    intervalZoom = setInterval(function() {
        var t = bezierEasing[animationStep];
        current = diff * t + source;
        
        if (animationStep++ < animationSteps) {
            pzInstance.zoom(current);
        } else {
            cancel();
            
            // Execute callback if set
            if (callbackZoom !== null && typeof callbackZoom === "function") {
                callbackZoom();
                callbackZoom = null;
            }
        }
    }, animationStepTime);
    
    function cancel() {
        // Cancel interval
        clearInterval(intervalZoom);
        intervalZoom = null;
    }
}

function onZoomFit() {    
    onSmoothPanning = true;
    onSmoothZooming = true;
    pzInstance.fit();
    
    onAfterPanZoom(function () {
        onSmoothPanning = true;
        pzInstance.center();
    });
}

function onZoomReset() {
    onSmoothZooming = true;
    onSmoothPanning = true;
    pzInstance.resetZoom();
    
    onAfterPanZoom(function () {
        panToItem();
    });
}

function onDownload (title) {  
            
    // creates a new instance
    var svgsaver = new SvgSaver();
    
    // Create a SVG element for downloading
    var map_svg = $(g_svg.node).clone()
            .attr("width", g_Offsets.width_max - g_Offsets.width_min + g_Options.x_length)
            .attr("height", g_Offsets.height_max - g_Offsets.height_min + g_Options.y_length)
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
    
    // Get the correct rectangle to set focus on
    $("#rect_" + id).animate({
        "stroke": "red",
        "stroke-width": "5px"
    }, 100, function() {
        $("#rect_" + id).animate({
            "stroke": "black",
            "stroke-width": "1px"
        }, 2000);
    });
}