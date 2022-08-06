/* global get_settings, pzInstance, g_Options, ALIGNMENT_VERTICAL, g_MapItems */

///* global session_settings, globalMapId, SVG */
//
//// Global sizes, used to get everything on the SVG within the borders
//var globalOffset = 0;
//var globalHeight = 0;
//var globalWidth = 0;
//
//var ActualHeight = 0;
//var ActualWidth = 0;
//
//var ZoomFactor = 1.00;
//var transMatrix = [1,0,0,1,0,0];
//
//var viewX = 0;
//var viewY = 0;
//
//// Zooming and panning
//function updateViewbox(x, y, zoom) {
//    var SVG = document.getElementById("map_svg");
//    if ((x !== -1) || (y !== -1)) {
//        viewX = x;
//        viewY = y;
//
//        transMatrix[4] = viewX;
//        transMatrix[5] = viewY;
//    }
//
//    if (zoom !== -1) {
//        ZoomFactor = zoom;
//
//        transMatrix[0] = ZoomFactor;
//        transMatrix[3] = ZoomFactor;
//    }
//
//
//    var newMatrix = "matrix(" + transMatrix.join(' ') + ")";
//    SVG.setAttributeNS(null, "transform", newMatrix);
//    SVG.setAttributeNS(null, "webkitTransform", newMatrix);
//    SVG.setAttributeNS(null, "MozTransform", newMatrix);
//    SVG.setAttributeNS(null, "msTransform", newMatrix);
//    SVG.setAttributeNS(null, "OTransform", newMatrix);
//
//    return;
//}
//
//function panItem(item) {
//    var ItemMap = document.getElementById("item_info");
//    if (session_settings["table"] === "timeline") {
//        var scrollTop = (item.Location[1] + globalOffset + 50) - (ItemMap.offsetHeight / 2);
//        var scrollLeft = (item.Location[0] + 75) - (ItemMap.offsetWidth / 2);        
//    } else {
//        var scrollTop = (item.Location[1] + 75) - (ItemMap.offsetHeight / 2);
//        var scrollLeft = (item.Location[0] + globalOffset + 50) - (ItemMap.offsetWidth / 2);
//    }
//    
//    updateViewbox(-scrollLeft, -scrollTop, -1);
//}
//
//function panTo(x, y) {    
//    var newX = viewX + x;
//    var newY = viewY + y;    
//
//    updateViewbox(newX, newY, -1);
//}
//

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
    if (intervalZoom !== null || intervalPan !== null) {
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
    } else {
        // Done, execute it right away
        f();
    }
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
    if (g_Options.align === ALIGNMENT_VERTICAL) {
        var newX = (outerWidth / 2) - (item.X + (g_Options.x_length / 2));
        var newY = (outerHeight / 2) - (item.Y + (g_Options.y_length / 2));
    } else {
        var newX = (outerWidth / 2) - (item.Y + (g_Options.y_length / 2));
        var newY = (outerHeight / 2) - (item.X + (g_Options.x_length / 2));     
    }

    // Move to the desired location
    panSmooth(pzInstance.getPan(), {x: newX, y: newY});
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

//function onZoomIn() {
//    var map = $("#map_div");
//    var outerHeight = map.outerHeight();
//    var outerWidth = map.outerWidth();
//    
//    var matrix = SVG($("#map")[0]).transform();
//    var ZoomFactor = matrix["a"];
//    var viewX = matrix["e"];
//    var viewY = matrix["f"];
//    
//    var factor = 1.4;
//
//    var newZoom = ZoomFactor * factor;
//
//    var newX = viewX*factor + (1 - factor)*(outerWidth / 2);
//    var newY = viewY*factor + (1 - factor)*(outerHeight / 2);
//    updateViewbox(newX, newY, newZoom);
//}
//
//function onZoomOut() {
//    var ItemMap = document.getElementById("item_info");
//
//    newZoom = ZoomFactor / factor;
//
//    newX = (viewX / factor) + (1 - (1 / factor))*(ItemMap.offsetWidth / 2);
//    newY = (viewY / factor) + (1 - (1 / factor))*(ItemMap.offsetHeight / 2);
//    updateViewbox(newX, newY, newZoom);
//}
//
function onZoomFit() {
    // To make sure it always uses the right size
    pzInstance.resize();
    
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

//function onDownload () {
//    // Get the SVG
//    var SVG = document.getElementById("svg");
//    var Controls = document.getElementById("controls");
//
//    // Temporarily remove these..
//    SVG.removeChild(Controls);
//
//    // Use an invisible SVG
//    var svg = document.getElementById('hidden_svg');
//
//    svg.setAttribute("version", 1.1);
//    svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
//    svg.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");
//
//    // Get the entire SVG
//    svg.setAttribute('width',  ActualWidth);
//    svg.setAttribute('height', ActualHeight);    
//
//    updateViewbox(0, 0, 1);
//
//    if (window.navigator.msSaveOrOpenBlob !== undefined) {            
//        // Temporary div to save the svg in
//        var tempDiv = document.createElement("div");
//        var svgParent = svg.parentNode;
//
//        // The child group of SVG
//        var group = document.getElementById(session_settings["table"]);
//
//        // Get the group of SVG
//        SVG.removeChild(group);
//        svg.appendChild(group);
//
//        // And save it in svg
//        svgParent.removeChild(svg);
//        tempDiv.appendChild(svg);
//
//        var URL = tempDiv.innerHTML;
//
//        // We don't want these empty name spaces!
//        var newURL = URL.replace(/ ?\S*NS1[^\d]\S*[>"] ?/g, ">");
//
//        // No links anymore for the downloaded file..
//        newURL = newURL.replace(/<a[^>]*>/g, "");
//        newURL = newURL.replace(/<\/a>+/g, "");
//
//        // Or double namespace..
//        if (countOcurrences(newURL, "http://www.w3.org/2000/svg") > 1) {
//            newURL = newURL.replace(' xmlns="http://www.w3.org/2000/svg"', '');
//        }
//
//        // Clean up our mess
//        newURL = newURL.replace(">>", ">");
//
//        // Get the link and download the file
//        var topItem = getItemById(globalMapId);
//        var blobObject = new Blob([newURL]);
//        window.navigator.msSaveOrOpenBlob(blobObject, topItem.name + ".svg");
//
//        // Now get the group back
//        svg.removeChild(group);
//        SVG.appendChild(group);
//
//        // And the controls
//        SVG.appendChild(Controls);
//
//        // And the link to the svg..
//        tempDiv.removeChild(svg);
//        svgParent.appendChild(svg);
//
//    } else {
//
//
//        svg.innerHTML = SVG.innerHTML;
//
//        // No links anymore for the downloaded file..
//        svg.innerHTML = svg.innerHTML.replace(/<a[^>]*>/g, "");
//        svg.innerHTML = svg.innerHTML.replace(/<\/a>+/g, "");
//
//        // Now turn it into a URL for downloading
//        var Serialilzer = new XMLSerializer();
//        var string = Serialilzer.serializeToString(svg);
//
//        var URL = "data:image/svg+xml;base64," + b64EncodeUnicode(string);
//
//        // Release the link
//        svg.innerHTML = "";
//
//        // Now add these back
//        SVG.appendChild(Controls);
//
//        // Get the link and download the file
//        var topItem = getItemById(globalMapId);
//        var link = document.getElementById('hidden_a');
//        link.href = URL;
//        link.download = topItem.name + ".svg";
//        link.click();
//    }
//
//    // Reset the zoom to the selected people
//    ZoomReset();
//}
//
//// https://stackoverflow.com/questions/4009756/how-to-count-string-occurrence-in-string
//function countOcurrences(str, value) {
//    var regExp = new RegExp(value, "gi");
//    return (str.match(regExp) || []).length;
//}
//
//// https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
//function b64EncodeUnicode(str) {
//    // first we use encodeURIComponent to get percent-encoded UTF-8,
//    // then we convert the percent encodings into raw bytes which
//    // can be fed into btoa.
//    var PercentEncoded = encodeURIComponent(str);
//
//    var RawBytes = PercentEncoded.replace(
//        /%([0-9A-F]{2})/g,
//        function (match, p1) {
//            return String.fromCharCode('0x' + p1);
//        }
//    );
//
//    var BToAString = btoa(RawBytes);
//
//    return BToAString;
//}
//
//
