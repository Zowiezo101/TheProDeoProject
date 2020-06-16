/* global session_settings, dict, prep_DrawLegenda */

// This is the list of items that will be used to create a map with
var Items = [];

// This is a global variable, used calculate the level index
var levelCounter = [];
var levelIDs = [];

// Global sizes, used to get everything on the SVG within the borders
var globalOffset = 0;
var globalHeight = 0;
var globalWidth = 0;

var ActualHeight = 0;
var ActualWidth = 0;

var ZoomFactor = 1.00;
var transMatrix = [1,0,0,1,0,0];

var viewX = 0;
var viewY = 0;

var globalItemId = -1;
var globalMapId = -1;

var highestLevel = 0;

    
function setItems() {    
    // Create all connections
    for (var i = 0; i < Items.length; i++) {
        var Item = Items[i];
        Item.checkForDuplicates();

        if (Item.parents.length > 0) {
            for (var j = 0; j < Item.parents.length; j++) {
                var Parent = getItemById(Item.parents[j]);
                Parent.ChildIDs.push(Item.id);
            }
        }
    }
}
    
// setLevels function
function setLevels(id) {            
    // The set of people that will be updated 
    // in the iteration of the while loop
    var IDset = [id];

    // This breaks the while loop
    var lastSet = 0;

    // The current generation level we are in
    var levelCount = 0;

    while (lastSet === 0)
    {
        var newIDset = [];
        for (i = 0; i < IDset.length; i++) {
            var Item = getItemById(IDset[i]);
            var childSet = Item.setLevel(levelCount);

            // Create the ID set of the next generation
            newIDset = newIDset.concat(childSet);
        }
        levelCount++;

        // There are no more children to update
        IDset = uniq(newIDset);
        if (IDset.length === 0) {
            lastSet = 1;
        }
    }


    // Use minus one, since the levelcount was incremented on the last iteration
    return levelCount - 1;
}


// resetLevels function
function resetLevels() {

    highestLevel = 0;

    for (var m = 0; m < Items.length; m++)
    {        
        var Item = Items[m];

        // Reset levelIndex
        Item.level = -1;
        Item.Location = [-1, -1];
    }

    return;
}


function setIndexes(id, highestLevel) {
    // The set of people that will be updated 
    // in the iteration of the while loop
    var IDset = [id];

    // This breaks the while loop
    var lastSet = 0;

    for (var i = 0; i < highestLevel + 1; i++) {
        // Initialization
        levelIDs.push([]);
        levelCounter.push(0);
    }

    while (lastSet === 0)
    {        
        var newIDset = [];
        for (i = 0; i < IDset.length; i++) {
            var Item = getItemById(IDset[i]);
            var level = Item.level;

            var childSet = [];

            // Only use the children of the direct next generation to get the correct numbers
            for (var j = 0; j < Item.ChildIDs.length; j++) {
                var Child = getItemById(Item.ChildIDs[j]);

                if (Child.level === (Item.level + 1)) {
                    childSet.push(Child.id);
                }
            }

            // Store all the unique IDs and keep track on the level they are on
            // alert("Adding " + Item.name + " with ID " + Item.ID + " to array of level " + level + "\nArray: " + levelIDs[level]);

            // Keep track of the amount of people on a certain level
            // Only if the levelIndex is not already set
            if (Item.levelIndex === -1) {
                var currentLevelIDs = levelIDs[level];
                currentLevelIDs.push(Item.id);
                levelIDs[level] = currentLevelIDs;

                Item.levelIndex = levelCounter[level];
            } else {
                // alert("We have a double!!");
                // alert("Item " + Item.name + " already has it's levelIndex set to " + Item.levelIndex);
                // alert("It is requested to set it from " + Item.levelIndex + " to " + levelCounter[level]);
            }

            levelCounter[level] = levelIDs[level].length;

            // Create the ID set of the next generation
            newIDset = newIDset.concat(childSet);
        }

        // There are no more children to update
        IDset = uniq(newIDset);
        if (IDset.length === 0) {
            lastSet = 1;
        }
    }

    return;
}


function resetIndexes() {

    // Reset all numbers and levelIndexes to recalculate
    levelIDs = [];
    levelCounter = [];

    for (var m = 0; m < Items.length; m++)
    {        
        var Item = Items[m];

        // Reset levelIndex
        Item.levelIndex = -1;
        Item.offset = 0;
    }

    return;
}


function download_png () {
    // Get the SVG
    var SVG = document.getElementById("svg");
    var Controls = document.getElementById("controls");

    // Temporarily remove these..
    SVG.removeChild(Controls);

    // Use an invisible SVG
    var svg = document.getElementById('hidden_svg');

    svg.setAttribute("version", 1.1);
    svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    svg.setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");

    // Get the entire SVG
    svg.setAttribute('width',  ActualWidth);
    svg.setAttribute('height', ActualHeight);    

    updateViewbox(0, 0, 1);

    if (window.navigator.msSaveOrOpenBlob !== undefined) {            
        // Temporary div to save the svg in
        var tempDiv = document.createElement("div");
        var svgParent = svg.parentNode;

        // The child group of SVG
        var group = document.getElementById("<?php echo $id; ?>_svg");

        // Get the group of SVG
        SVG.removeChild(group);
        svg.appendChild(group);

        // And save it in svg
        svgParent.removeChild(svg);
        tempDiv.appendChild(svg);

        var URL = tempDiv.innerHTML;

        // We don't want these empty name spaces!
        var newURL = URL.replace(/ ?\S*NS1[^\d]\S*[>"] ?/g, ">");

        // No links anymore for the downloaded file..
        newURL = newURL.replace(/<a[^>]*>/g, "");
        newURL = newURL.replace(/<\/a>+/g, "");

        // Or double namespace..
        if (countOcurrences(newURL, "http://www.w3.org/2000/svg") > 1) {
            newURL = newURL.replace(' xmlns="http://www.w3.org/2000/svg"', '');
        }

        // Clean up our mess
        newURL = newURL.replace(">>", ">");

        // Get the link and download the file
        var topItem = getItemById(globalMapId);
        var blobObject = new Blob([newURL]);
        window.navigator.msSaveOrOpenBlob(blobObject, topItem.name + ".svg");

        // Now get the group back
        svg.removeChild(group);
        SVG.appendChild(group);

        // And the controls
        SVG.appendChild(Controls);

        // And the link to the svg..
        tempDiv.removeChild(svg);
        svgParent.appendChild(svg);

    } else {


        svg.innerHTML = SVG.innerHTML;

        // No links anymore for the downloaded file..
        svg.innerHTML = svg.innerHTML.replace(/<a[^>]*>/g, "");
        svg.innerHTML = svg.innerHTML.replace(/<\/a>+/g, "");

        // Now turn it into a URL for downloading
        var Serialilzer = new XMLSerializer();
        var string = Serialilzer.serializeToString(svg);

        var URL = "data:image/svg+xml;base64," + b64EncodeUnicode(string);

        // Release the link
        svg.innerHTML = "";

        // Now add these back
        SVG.appendChild(Controls);

        // Get the link and download the file
        var topItem = getItemById(globalMapId);
        var link = document.getElementById('hidden_a');
        link.href = URL;
        link.download = topItem.name + ".svg";
        link.click();
    }

    // Reset the zoom to the selected people
    ZoomReset();
}

// https://stackoverflow.com/questions/4009756/how-to-count-string-occurrence-in-string
function countOcurrences(str, value) {
    var regExp = new RegExp(value, "gi");
    return (str.match(regExp) || []).length;
}

// https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    var PercentEncoded = encodeURIComponent(str);

    var RawBytes = PercentEncoded.replace(
        /%([0-9A-F]{2})/g,
        function (match, p1) {
            return String.fromCharCode('0x' + p1);
        }
    );

    var BToAString = btoa(RawBytes);

    return BToAString;
}

function UpdateLink() {
    var Link = this.newLink;
    goToPage(Link);
    return;
}

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

function UpdateProgress(value) {    
    var ProgressBar = document.getElementById("progress");

    ProgressBar.style.width = value + "%";
    ProgressBar.innerHTML = value + "%";
}


// Preparing the map
function prep_SetSVG() {    
    setTimeout(prep_SetAllLevels, 1);
}

function prep_SetAllLevels() {
    // Set all the generation levels of all people. Start out clean
    resetLevels();
    highestLevel = setLevels(globalMapId);
    UpdateProgress(5);

    // Get all the information of the peoples included
    setTimeout(prep_SetAllIndexes, 1);
}

function prep_SetAllIndexes() {
    // And all the indexes of all people
    resetIndexes();
    setIndexes(globalMapId, highestLevel);
    UpdateProgress(15);

    // Make the calculations to see where everyone should be placed
    setTimeout(prep_CalcAllLocations, 1);
}

function prep_CalcAllLocations() {    
    // Make the calculations to see where everyone should be placed
    globalOffset = 0;
    globalHeight = 0;

    calcLocations(globalMapId, highestLevel);
    UpdateProgress(35);

    setTimeout(prep_appendSVG, 1);
}

function prep_appendSVG() {
    var svgns = "http://www.w3.org/2000/svg";
    var ItemMap = document.getElementById(session_settings["table"] + "_div");

    // Create this element
    SVG = document.createElementNS(svgns, "svg");
    SVG.id = "svg";

    SVG.setAttributeNS(null, "transform", "matrix(1 0 0 1 0 0)");
    SVG.setAttributeNS(null, "display", "none");

    // Now add it to the screen
    ItemMap.appendChild(SVG);
    UpdateProgress(40);

    setTimeout(prep_appendGroup, 1);
}

function prep_appendGroup() {
    var svgns = "http://www.w3.org/2000/svg";
    var SVG = document.getElementById("svg");
    
    var Group = document.createElementNS(svgns, "g");    
    Group.id = session_settings["table"] + "_svg";
    
    SVG.appendChild(Group);
    UpdateProgress(45);
    
    setTimeout(prep_DrawLegenda, 1);
}


function prep_AddControlButtons() {
    var ItemMap = document.getElementById(session_settings["table"] + "_div");

    // Show the controls to move around in the SVG
    var svgns = "http://www.w3.org/2000/svg";
    var SVG = document.getElementById("svg");

    var Controls = document.createElementNS(svgns, "g");
    Controls.id = "controls";

    // The zoom-in button in SVG
    var Button = document.createElementNS(svgns, "g");
    Button.setAttributeNS(null, "onclick", "ZoomIn(1.4)");
    Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
    Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');

    var ZoomInButton = document.createElementNS(svgns, "rect");
    ZoomInButton.setAttributeNS(null, 'width', 40);
    ZoomInButton.setAttributeNS(null, 'height', 40);
    ZoomInButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 75);
    ZoomInButton.setAttributeNS(null, 'rx', 12);
    ZoomInButton.setAttributeNS(null, 'y', ItemMap.offsetHeight - 100);
    ZoomInButton.setAttributeNS(null, 'ry', 6);
    ZoomInButton.setAttributeNS(null, 'stroke', 'black');
    ZoomInButton.setAttributeNS(null, 'fill', 'white');
    ZoomInButton.id = "ZoomIn";
    ZoomInButton.ID = "ZoomIn";
    ZoomInButton.className.baseVal = "svg_" + session_settings["theme"];

    // Horizontal line of the plus sign
    var ZoomInPlus1 = document.createElementNS(svgns, "line");
    ZoomInPlus1.setAttributeNS(null, "x1", ItemMap.offsetWidth - 65);
    ZoomInPlus1.setAttributeNS(null, "y1", ItemMap.offsetHeight - 80);
    ZoomInPlus1.setAttributeNS(null, "x2", ItemMap.offsetWidth - 45);
    ZoomInPlus1.setAttributeNS(null, "y2", ItemMap.offsetHeight - 80);
    ZoomInPlus1.setAttributeNS(null, "stroke", "black");
    ZoomInPlus1.setAttributeNS(null, "stroke-width", 5);
    ZoomInPlus1.ID = "ZoomIn";

    // Vertical line of the plus sign
    var ZoomInPlus2 = document.createElementNS(svgns, "line");
    ZoomInPlus2.setAttributeNS(null, "x1", ItemMap.offsetWidth - 55);
    ZoomInPlus2.setAttributeNS(null, "y1", ItemMap.offsetHeight - 90);
    ZoomInPlus2.setAttributeNS(null, "x2", ItemMap.offsetWidth - 55);
    ZoomInPlus2.setAttributeNS(null, "y2", ItemMap.offsetHeight - 70);
    ZoomInPlus2.setAttributeNS(null, "stroke", "black");
    ZoomInPlus2.setAttributeNS(null, "stroke-width", 5);
    ZoomInPlus2.ID = "ZoomIn";

    Button.appendChild(ZoomInButton);
    Button.appendChild(ZoomInPlus1);
    Button.appendChild(ZoomInPlus2);
    Controls.appendChild(Button);


    // The zoom-out button in SVG
    var Button = document.createElementNS(svgns, "g");
    Button.setAttributeNS(null, "onclick", "ZoomOut(1.4)");
    Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
    Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');

    var ZoomOutButton = document.createElementNS(svgns, "rect");
    ZoomOutButton.setAttributeNS(null, 'width', 40);
    ZoomOutButton.setAttributeNS(null, 'height', 40);
    ZoomOutButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 75);
    ZoomOutButton.setAttributeNS(null, 'rx', 12);
    ZoomOutButton.setAttributeNS(null, 'y', ItemMap.offsetHeight - 50);
    ZoomOutButton.setAttributeNS(null, 'ry', 6);
    ZoomOutButton.setAttributeNS(null, 'stroke', 'black');
    ZoomOutButton.setAttributeNS(null, 'fill', 'white');
    ZoomOutButton.id = "ZoomOut";
    ZoomOutButton.ID = "ZoomOut";
    ZoomOutButton.className.baseVal = "svg_<?php echo $$id; ?>";

    // Horizontal line of the minus sign
    var ZoomOutMinus = document.createElementNS(svgns, "line");
    ZoomOutMinus.setAttributeNS(null, "x1", ItemMap.offsetWidth - 65);
    ZoomOutMinus.setAttributeNS(null, "y1", ItemMap.offsetHeight - 30);
    ZoomOutMinus.setAttributeNS(null, "x2", ItemMap.offsetWidth - 45);
    ZoomOutMinus.setAttributeNS(null, "y2", ItemMap.offsetHeight - 30);
    ZoomOutMinus.setAttributeNS(null, "stroke", "black");
    ZoomOutMinus.setAttributeNS(null, "stroke-width", 5);
    ZoomOutMinus.ID = "ZoomOut";

    Button.appendChild(ZoomOutButton);
    Button.appendChild(ZoomOutMinus);
    Controls.appendChild(Button);


    // The zoom-fit button in SVG
    var Button = document.createElementNS(svgns, "g");
    Button.setAttributeNS(null, "onclick", "ZoomFit()");
    Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
    Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');

    var ZoomFitButton = document.createElementNS(svgns, "rect");
    ZoomFitButton.setAttributeNS(null, 'width', 200);
    ZoomFitButton.setAttributeNS(null, 'height', 40);
    ZoomFitButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 225);
    ZoomFitButton.setAttributeNS(null, 'rx', 10);
    ZoomFitButton.setAttributeNS(null, 'y', 10);
    ZoomFitButton.setAttributeNS(null, 'ry', 10);
    ZoomFitButton.setAttributeNS(null, 'stroke', 'black');
    ZoomFitButton.setAttributeNS(null, 'fill', 'white');
    ZoomFitButton.id = "ZoomFit";
    ZoomFitButton.ID = "ZoomFit";
    ZoomFitButton.className.baseVal = "svg_<?php echo $$id; ?>";

    var ZoomFitTitle = document.createElementNS(svgns, "text");
    ZoomFitTitle.setAttributeNS(null, 'x', ItemMap.offsetWidth - 220);
    ZoomFitTitle.setAttributeNS(null, 'y', 35);
    ZoomFitTitle.textContent = dict['zoomfit'];
    ZoomFitTitle.ID = "ZoomFit";

    Button.appendChild(ZoomFitButton);
    Button.appendChild(ZoomFitTitle);
    Controls.appendChild(Button);

    // The zoom-reset button in SVG
    var Button = document.createElementNS(svgns, "g");
    Button.setAttributeNS(null, "onclick", "ZoomReset()");
    Button.setAttributeNS(null, 'onmouseover', 'setBorderButton(evt)');
    Button.setAttributeNS(null, 'onmouseout',  'clearBorderButton(evt)');

    var ZoomResetButton = document.createElementNS(svgns, "rect");
    ZoomResetButton.setAttributeNS(null, 'width', 200);
    ZoomResetButton.setAttributeNS(null, 'height', 40);
    ZoomResetButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 225);
    ZoomResetButton.setAttributeNS(null, 'rx', 10);
    ZoomResetButton.setAttributeNS(null, 'y', 60);
    ZoomResetButton.setAttributeNS(null, 'ry', 10);
    ZoomResetButton.setAttributeNS(null, 'stroke', 'black');
    ZoomResetButton.setAttributeNS(null, 'fill', 'white');
    ZoomResetButton.id = "ZoomReset";
    ZoomResetButton.ID = "ZoomReset";
    ZoomResetButton.className.baseVal = "svg_<?php echo $$id; ?>";

    var ZoomResetTitle = document.createElementNS(svgns, "text");
    ZoomResetTitle.setAttributeNS(null, 'x', ItemMap.offsetWidth - 220);
    ZoomResetTitle.setAttributeNS(null, 'y', 85);
    ZoomResetTitle.textContent = dict['zoomreset'];
    ZoomResetTitle.ID = "ZoomReset";

    Button.appendChild(ZoomResetButton);
    Button.appendChild(ZoomResetTitle);
    Controls.appendChild(Button);

    // The download button in SVG
    var Button = document.createElementNS(svgns, "g");
    Button.setAttributeNS(null, "onclick", "download_png()");
    Button.setAttributeNS(null, 'onmouseover', 'extraInfo(evt)');
    Button.setAttributeNS(null, 'onmouseout',  'lessInfo(evt)');

    var DownloadButton = document.createElementNS(svgns, "rect");
    DownloadButton.setAttributeNS(null, 'width', 200);
    DownloadButton.setAttributeNS(null, 'height', 40);
    DownloadButton.setAttributeNS(null, 'x', ItemMap.offsetWidth - 225);
    DownloadButton.setAttributeNS(null, 'rx', 10);
    DownloadButton.setAttributeNS(null, 'y', 110);
    DownloadButton.setAttributeNS(null, 'ry', 10);
    DownloadButton.setAttributeNS(null, 'stroke', 'black');
    DownloadButton.setAttributeNS(null, 'fill', 'white');
    DownloadButton.id = "Download";
    DownloadButton.ID = "Download";
    DownloadButton.className.baseVal = "svg_<?php echo $$id; ?>";

    var DownloadTitle = document.createElementNS(svgns, "text");
    DownloadTitle.setAttributeNS(null, 'x', ItemMap.offsetWidth - 220);
    DownloadTitle.setAttributeNS(null, 'y', 135);
    DownloadTitle.textContent = dict['download'];
    DownloadTitle.id = "DownloadText";
    DownloadTitle.ID = "Download";

    Button.appendChild(DownloadButton);
    Button.appendChild(DownloadTitle);
    Controls.appendChild(Button);

    // Add everything to the SVG
    SVG.appendChild(Controls);
    UpdateProgress(65);

    // Get all the information of the peoples included
    setTimeout(prep_DrawMap, 1);
    return;
}

function prep_DrawMap() {
    // The TimeLine div
    var Map = document.getElementById(session_settings["table"] + "_div");
    var SVG = document.getElementById("svg");
    var Group = document.getElementById("timeline_svg");
    
    // Set the height and the width Plus x pixel border
    ActualWidth = globalWidth + (highestLevel + 1)*50;
    ActualHeight = globalHeight + globalOffset + 75;
    
    SVG.setAttribute('width', Map.offsetWidth);
    SVG.setAttribute('height', Map.offsetHeight);
    
    // Draw the current timeline
    drawMap(Group);
    UpdateProgress(75);
        
    setTimeout(prep_SetInterrupts, 1);
}

function prep_SetInterrupts() {
    // The FamilyTree div
    var SVG = document.getElementById("svg");

    // And some functions for mouse or keyboard panning/scrolling
    SVG.setAttributeNS(null, 'onmousedown', "GetMousePos(evt)");
    SVG.setAttributeNS(null, 'ontouchstart', "GetTouchPos(evt)");

    if (SVG.addEventListener) {
        // IE9, Chrome, Safari, Opera
        SVG.addEventListener("mousewheel", GetDelta, false);
        // Firefox
        SVG.addEventListener("DOMMouseScroll", GetDelta, false);
    }
    // IE 6/7/8
    else 
        SVG.attachEvent("onmousewheel", GetDelta);

    window.onmousemove = GetMouseMov;
    window.ontouchmove = GetTouchMov;

    window.onmouseup = GetMouseOut;
    window.ontouchend = GetMouseOut;
    UpdateProgress(85);

    // Update the width and the height of the viewbox and move to the person
    setTimeout(prep_SetView, 1);
}

function prep_SetView() {
    // Update the width and the height of the viewbox
    updateViewbox(0, 0, 1);

    // Move to the event
    var Item = getItemById(globalItemId);
    panItem(Item);
    UpdateProgress(95);

    setTimeout(prep_MakeVisible, 1);
}

function prep_MakeVisible() {
    // The Map div
    var ItemMap = document.getElementById(session_settings["table"] + "_div");

    // Remove the default text
    var defaultText = document.getElementById("default");

    if (defaultText !== null) {
        ItemMap.removeChild(defaultText);

        // Make the SVG visible
        var SVG = document.getElementById("svg");
        SVG.setAttributeNS(null, "display", "inline");
    }
}


// Zooming and panning
function updateViewbox(x, y, zoom) {
    var SVG = document.getElementById(session_settings["table"] + "_svg");
    if ((x !== -1) || (y !== -1)) {
        viewX = x;
        viewY = y;

        transMatrix[4] = viewX;
        transMatrix[5] = viewY;
    }

    if (zoom !== -1) {
        ZoomFactor = zoom;

        transMatrix[0] = ZoomFactor;
        transMatrix[3] = ZoomFactor;
    }


    var newMatrix = "matrix(" + transMatrix.join(' ') + ")";
    SVG.setAttributeNS(null, "transform", newMatrix);
    SVG.setAttributeNS(null, "webkitTransform", newMatrix);
    SVG.setAttributeNS(null, "MozTransform", newMatrix);
    SVG.setAttributeNS(null, "msTransform", newMatrix);
    SVG.setAttributeNS(null, "OTransform", newMatrix);

    return;
}

function panTo(x, y) {    
    var newX = viewX + x;
    var newY = viewY + y;    

    updateViewbox(newX, newY, -1);
}

function ZoomIn(factor) {
    var ItemMap = document.getElementById(session_settings["table"] + "_div");

    var newZoom = ZoomFactor * factor;

    var newX = viewX*factor + (1 - factor)*(ItemMap.offsetWidth / 2);
    var newY = viewY*factor + (1 - factor)*(ItemMap.offsetHeight / 2);
    updateViewbox(newX, newY, newZoom);
}

function ZoomOut(factor) {
    var ItemMap = document.getElementById(session_settings["table"] + "_div");

    newZoom = ZoomFactor / factor;

    newX = (viewX / factor) + (1 - (1 / factor))*(ItemMap.offsetWidth / 2);
    newY = (viewY / factor) + (1 - (1 / factor))*(ItemMap.offsetHeight / 2);
    updateViewbox(newX, newY, newZoom);
}

function ZoomFit() {
    var ItemMap = document.getElementById(session_settings["table"] + "_div");

    // To zoom out, we need to increase the size of the viewHeight and viewWidth
    // Keep the ratio between X and Y axis aligned
    // Find the biggest ratio and use that!
    var dX = ActualWidth / ItemMap.offsetWidth;
    var dY = ActualHeight / ItemMap.offsetHeight;

    if (dX > dY) { 
        var newZoom = ItemMap.offsetWidth / ActualWidth;

        // Now zoom out untill the whole family tree is visible
        updateViewbox(0, (ItemMap.offsetHeight - (ActualHeight * newZoom)) / 2, newZoom);
    } else {
        newZoom = ItemMap.offsetHeight / ActualHeight;

        // Now zoom out untill the whole family tree is visible
        updateViewbox((ItemMap.offsetWidth - (ActualWidth * newZoom)) / 2, 0, newZoom);
    }
}

function ZoomReset() {
    // Get the ID number
    // Could also be map id
    var ItemId = session_settings["id"] ? session_settings["id"] : session_settings["map"];

    var newZoom = 1;

    // Now pan to this item
    var Item = getItemById(ItemId);
    panItem(Item);

    // And zoom to the default zoom level (1)
    updateViewbox(-1, -1, newZoom);
}

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


//https://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
function uniq(a) {
    var prims = {"boolean":{}, "number":{}, "string":{}}, objs = [];

    return a.filter(function(item) {
        var type = typeof item;
        if(type in prims)
            return prims[type].hasOwnProperty(item) ? false : (prims[type][item] = true);
        else
            return objs.indexOf(item) >= 0 ? false : objs.push(item);
    });
}

/* Function to get any item using the ID of that item */
function getItemById(id) {
    var Item = Items.find(x => x.id === id);
    return Item;
}

/* Function to get any item using the ID of that item */
function getItemsById(id) {
    var Matches = Items.filter(x => x.id === id);
    return Matches;
}