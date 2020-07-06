
/* global dict */

function disable_select() {
    var element = document.body;
    element.classList.add('no_select');
}

function enable_select() {
    var element = document.body;
    element.classList.remove('no_select');
}

var MouseX = 0;
var MouseY = 0;
var Moving = false;
GetMousePos = function (event) {
    MouseX = event.clientX;
    MouseY = event.clientY;

    Moving = true;

    // Disable selecting text or any other element
    disable_select();
};

GetTouchPos = function (event) {
    MouseX = event.changedTouches[0].pageX;
    MouseY = event.changedTouches[0].pageY;

    Moving = true;

    // Disable selecting text or any other element
    disable_select();
};

GetMouseMov = function (event) {
    if (Moving === true) {
        var dX = event.clientX - MouseX;
        var dY = event.clientY - MouseY;

        panTo(dX, dY);

        MouseX = event.clientX;
        MouseY = event.clientY;
    }
};

GetTouchMov = function (event) {
    if (Moving === true) {
        var dX = event.changedTouches[0].pageX - MouseX;
        var dY = event.changedTouches[0].pageY - MouseY;

        panTo(dX, dY);

        MouseX = event.changedTouches[0].pageX;
        MouseY = event.changedTouches[0].pageY;
    }
};

GetMouseOut = function (event) {
    Moving = false;

    // Enable the disabled selections again
    enable_select();
};

// https://www.sitepoint.com/html5-javascript-mouse-wheel/
GetDelta = function (event) {
    // cross-browser wheel delta
    var event = window.event || event; // old IE support    
    var delta = Math.max(-1, Math.min(1, (event.wheelDelta || -event.detail)));

    if (delta > 0) {
        ZoomIn(1.4);
    } else {
        ZoomOut(1.4);
    }
};
setBorder = function (event) {
    var IDnum = event.target.RectID;
    var Rect = document.getElementById("Rect" + IDnum);
    Rect.setAttributeNS(null, "stroke", "red");
    Rect.setAttributeNS(null, "stroke-width", 5);
};

clearBorder = function (event) {
    var IDnum = event.target.RectID;
    var Rect = document.getElementById("Rect" + IDnum);
    Rect.setAttributeNS(null, "stroke", "black");
    Rect.setAttributeNS(null, "stroke-width", 1);
};

setBorderButton = function (event) {
    var ID = event.target.ID;
    var Rect = document.getElementById(ID);
    Rect.setAttributeNS(null, "stroke", "red");
    Rect.setAttributeNS(null, "stroke-width", 5);
};

clearBorderButton = function (event) {
    var ID = event.target.ID;
    var Rect = document.getElementById(ID);
    Rect.setAttributeNS(null, "stroke", "black");
    Rect.setAttributeNS(null, "stroke-width", 1);
};

extraInfo = function (event) {
    var Rect = document.getElementById("Download");
    var Text = document.getElementById("DownloadText");

    // Update rectangle
    Rect.setAttributeNS(null, "stroke", "red");
    Rect.setAttributeNS(null, "stroke-width", 5);
    Rect.setAttributeNS(null, "height", 75);

    // Create some descriptive text (if they are not already available)
    var svgns = "http://www.w3.org/2000/svg";
    if (document.getElementById("DownloadText2") === null) {
        var Text2 = document.createElementNS(svgns, "text");
        var Text3 = document.createElementNS(svgns, "text");

        // Prepare the text
        Text2.setAttributeNS(null, 'x', Text.getAttribute("x"));
        Text2.setAttributeNS(null, 'y', parseInt(Text.getAttribute("y")) + 20);
        Text2.setAttributeNS(null, 'font-style', 'italic');
        Text2.setAttributeNS(null, 'font-size', 15);
        Text2.textContent = dict['download_extra'];
        Text2.id = "DownloadText2";

        Text3.setAttributeNS(null, 'x', Text.getAttribute("x"));
        Text3.setAttributeNS(null, 'y', parseInt(Text.getAttribute("y")) + 40);
        Text3.setAttributeNS(null, 'font-style', 'italic');
        Text3.setAttributeNS(null, 'font-size', 15);
        Text3.textContent = dict['download_extra2'];
        Text3.id = "DownloadText3";

        // Add the text
        Text.parentNode.appendChild(Text2);
        Text.parentNode.appendChild(Text3);
    }
};

lessInfo = function (event) {
    var Rect = document.getElementById("Download");

    // Update rectangle
    Rect.setAttributeNS(null, "stroke", "black");
    Rect.setAttributeNS(null, "stroke-width", 1);
    Rect.setAttributeNS(null, "height", 40);

    if (document.getElementById("DownloadText2") !== null) {
        var Text2 = document.getElementById("DownloadText2");
        var Text3 = document.getElementById("DownloadText3");

        // Remove the text
        Text2.parentNode.removeChild(Text2);
        Text3.parentNode.removeChild(Text3);
    }
};