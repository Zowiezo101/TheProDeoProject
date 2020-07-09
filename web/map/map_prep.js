
/* global globalMapId, session_settings, MULTS, dict, globalWidth, GetDelta, GetMouseMov, GetMouseOut, GetTouchMov, globalItemId */


function UpdateProgress(value) {
    var ProgressBar = document.getElementById("progress");
    if (!ProgressBar) {
        var defaultText = document.getElementById("default");
        defaultText.innerHTML = dict["loading"];
    
        // The progress bar
        ProgressBar = document.createElement("div");
        defaultText.appendChild(ProgressBar);

        // Set its attributes
        ProgressBar.id = "progress_bar";

        // The progress in the progress bar
        var progress = document.createElement("div");
        ProgressBar.appendChild(progress);

        // Set its attributes
        progress.id = "progress";
        progress.innerHTML = "1%";
    }

    ProgressBar.style.width = value + "%";
    ProgressBar.innerHTML = value + "%";
}


function showMap() {
    // Preparing the map
    prep_SetAllLevels();
    UpdateProgress(5);

    // Get all the information of the peoples included
    prep_SetAllIndexes();
    UpdateProgress(15);

    // Make the calculations to see where everyone should be placed
    prep_CalcAllLocations();
    UpdateProgress(35);

    // The SVG that will contain all drawn elements
    prep_appendSVG();
    UpdateProgress(40);

    // The actual map itself
    prep_appendGroup();
    UpdateProgress(45);
    
  
    if (session_settings["table"] === "timeline") {
        // The legenda for the timeline
        prep_DrawLegenda();
        UpdateProgress(55);
    } 
    
    // The buttons for viewing the map
    prep_AddControlButtons();
    UpdateProgress(65);

    // Draw the map
    prep_DrawMap();
    UpdateProgress(75);
    
    // All clicky things
    prep_SetInterrupts();
    UpdateProgress(85);

    // Update the width and the height of the viewbox and move to the person
    prep_SetView();
    UpdateProgress(95);

    // Show the map
    prep_MakeVisible();
}

function prep_SetAllLevels() {
    // Set all the generation levels of all people.
    highestLevel = setLevels(globalMapId);
}

function prep_SetAllIndexes() {
    // And all the indexes of all people
    setIndexes(globalMapId, highestLevel);
}

function prep_CalcAllLocations() {    
    // Make the calculations to see where everyone should be placed
    globalOffset = 0;
    globalHeight = 0;

    calcLocations(globalMapId, highestLevel);
}

function prep_appendSVG() {
    var svgns = "http://www.w3.org/2000/svg";
    var ItemMap = document.getElementById("item_info");

    // Create this element
    var SVG = document.createElementNS(svgns, "svg");
    SVG.id = "svg";

    SVG.setAttributeNS(null, "transform", "matrix(1 0 0 1 0 0)");
    SVG.setAttributeNS(null, "display", "none");

    // Now add it to the screen
    ItemMap.appendChild(SVG);
}

function prep_appendGroup() {
    var svgns = "http://www.w3.org/2000/svg";
    var SVG = document.getElementById("svg");
    
    var Group = document.createElementNS(svgns, "g");    
    Group.id = session_settings["table"] + "_svg";
    
    SVG.appendChild(Group);
}

function prep_DrawLegenda() {
    //Legenda
    var svgns = "http://www.w3.org/2000/svg";
    var SVG = document.getElementById("svg");
    
    var Group = document.createElementNS(svgns, "g");    
    Group.id = "Legenda";
    
    var LegendaStr = ['s', 'i', 'h', 'd', 'w', 'm', 'y', 'D', 'C', 'M', 'a'];
    LegendaStr.forEach(function(str, idx) {
        var Rect = document.createElementNS(svgns, "rect");        
        Rect.setAttributeNS(null, 'width', 10);
        Rect.setAttributeNS(null, 'height', 10);
        Rect.setAttributeNS(null, 'x', 15 + (100*Math.floor(idx / 5)));
        Rect.setAttributeNS(null, 'y', 15*((idx % 5) + 1));
        Rect.setAttributeNS(null, 'stroke', 'black');
        Rect.setAttributeNS(null, 'fill', getItemColor(null, idx));
        
        var Text = document.createElementNS(svgns, "text");        
        Text.setAttributeNS(null, 'x', 30 + (100*Math.floor(idx / 5)));
        Text.setAttributeNS(null, 'y', 15*((idx % 5) + 1) + 10);
        Text.textContent = StringToType(str, 0);
        
        Group.appendChild(Rect);
        Group.appendChild(Text);
    });
    
    // Now add it to the screen
    SVG.appendChild(Group);
}

function prep_AddControlButtons() {
    var ItemMap = document.getElementById("item_info");

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
    ZoomOutButton.className.baseVal = "svg_" + session_settings["theme"];

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
    ZoomFitButton.className.baseVal = "svg_" + session_settings["theme"];

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
    ZoomResetButton.className.baseVal = "svg_" + session_settings["theme"];

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
    DownloadButton.className.baseVal = "svg_" + session_settings["theme"];

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
    return;
}

function prep_DrawMap() {
    // The FamilyTree div
    var Map = document.getElementById("item_info");
    var SVG = document.getElementById("svg");
    var Group = document.getElementById(session_settings["table"] + "_svg");
    
    // Set the height and the width
    if (session_settings["table"] === "timeline") {
        // Set the height and the width Plus x pixel border
        ActualWidth = globalWidth + (highestLevel + 1)*50;
        ActualHeight = globalHeight + globalOffset + 75;
    } else {
        ActualWidth = globalWidth + globalOffset + 150;
        ActualHeight = (highestLevel + 1)*75;
    }
    
    SVG.setAttribute('width', Map.offsetWidth);
    SVG.setAttribute('height', Map.offsetHeight);
    
    // Draw the current family tree
    drawMap(Group);
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
}

function prep_SetView() {
    // Update the width and the height of the viewbox
    updateViewbox(0, 0, 1);

    // Move to the event
    var Item = getItemById(globalItemId);
    panItem(Item);
}

function prep_MakeVisible() {
    // The Map div
    var ItemMap = document.getElementById("item_info");

    // Remove the default text
    var defaultText = document.getElementById("default");

    if (defaultText !== null) {
        ItemMap.removeChild(defaultText);

        // Make the SVG visible
        var SVG = document.getElementById("svg");
        SVG.setAttributeNS(null, "display", "inline");
    }
}

