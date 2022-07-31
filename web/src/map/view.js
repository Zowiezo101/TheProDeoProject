/* global get_settings, pzInstance, g_Options, ALIGNMENT_VERTICAL */

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

function panToItem(map) {
    // Default ID to pan to is the first item of the timeline
    var id = map.id;
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

    // Reset the SVG to 0,0 and then move to the desired location
    pzInstance.moveTo(0, 0);
    pzInstance.smoothMoveTo(newX, newY);
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
//function onZoomFit() {
//    var ItemMap = document.getElementById("item_info");
//
//    // To zoom out, we need to increase the size of the viewHeight and viewWidth
//    // Keep the ratio between X and Y axis aligned
//    // Find the biggest ratio and use that!
//    var dX = ActualWidth / ItemMap.offsetWidth;
//    var dY = ActualHeight / ItemMap.offsetHeight;
//
//    if (dX > dY) { 
//        var newZoom = ItemMap.offsetWidth / ActualWidth;
//
//        // Now zoom out untill the whole family tree is visible
//        updateViewbox(0, (ItemMap.offsetHeight - (ActualHeight * newZoom)) / 2, newZoom);
//    } else {
//        newZoom = ItemMap.offsetHeight / ActualHeight;
//
//        // Now zoom out untill the whole family tree is visible
//        updateViewbox((ItemMap.offsetWidth - (ActualWidth * newZoom)) / 2, 0, newZoom);
//    }
//}
//
//function onZoomReset() {
//    // Get the ID number
//    // Could also be map id
//    var ItemId = session_settings["id"] ? session_settings["id"] : session_settings["map"];
//
//    var newZoom = 1;
//
//    // Now pan to this item
//    var Item = getItemById(Number(ItemId));
//    panItem(Item);
//
//    // And zoom to the default zoom level (1)
//    updateViewbox(-1, -1, newZoom);
//}
//
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
