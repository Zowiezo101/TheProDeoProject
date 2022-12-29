
/* global g_MapItems, g_Options, get_settings, onBeforeZoom, onBeforePan, dict, g_Map, TYPE_FAMILYTREE, getMapItems, itemHasSubChildren */

// The global variable for the SVG where everything will be drawn in
var g_svg = null;
var g_subSVG = null;

function setSVG(svg) {
    
    if (svg)
        g_svg = svg;
    
    return g_svg !== null;
}

function setSubSVG(svg) {
    if (svg)
        g_subSVG = svg;
    
    return g_subSVG !== null;
}

function getSVG() {
    return g_Options.sub ? g_subSVG : g_svg;
}

function getMapDiv() {
    return g_Options.sub ? $("#map_sub_div") : $("#map_div");
}

function drawControlButtons(map, type) {    
    // The height and width of the SVG parent
    var div = getMapDiv().parent();
    div.append(`<div style="position: absolute; top: 0; right: 0; padding: inherit;" class="btn-group">
                    <button class="btn btn-primary" onclick="onZoomFit()" title="` + dict["map.zoom.fit"] + `"><i class="fa fa-expand" aria-hidden="true"></i></button>
                    <button class="btn btn-primary" onclick="onZoomReset()" title="` + dict["map.zoom.reset"] + `"><i class="fa fa-compress" aria-hidden="true"></i></button>
                    <button class="btn btn-primary" onclick="onDownload('` + map.name + `')" title="` + dict["map.download." + type] + `"><i class="fa fa-download" aria-hidden="true"></i></button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#infoModal" title="` + dict["map.info.controls"] + `"><i class="fa fa-info-circle" aria-hidden="true"></i></button>
                </div>`);
    
    // The modal for the information button
    div.append(`
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModal" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">` + dict["map.info.controls"] + `</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                ` + dict[type + ".overview"] + `<br><br>
              </div>
            </div>
          </div>
        </div>`);
}
    
function drawMapItems() {
    
    // Set the background of the entire thing
    getSVG().addClass('bg-light');

    // The root parent
    var group = getSVG().group({id: "map"});    
    getMapItems().forEach(function(item) {
        drawLink(group, item);
        drawItem(group, item);
    });
}

function drawItem(group, item) {
    
    // The link to the object
    if (g_Options.type === TYPE_FAMILYTREE) {
    
        // The button to see the popover
        var link = group.link(setParameters("peoples/people/" + item.id));
            link.target('_blank');

        var popover = $("<div>")
                .append("\
                    <table class='table table-striped'>" + 
                        "<tbody>" +
                        insertDetail(item, "meaning_name") + 
                        insertDetail(item, "aka") + 
                        insertDetail(item, "descr") + 
                        insertDetail(item, "gender") + 
                        "</tbody>" + 
                    "</table>" + 
                    "<p class='font-weight-bold'>" + dict["map.info.details"] + "</p>");

        $(link.node).popover({
            animation: true,
            trigger: "hover",
            placement: "top",
            title: dict["map.info.title"] + item.name,
            html: true,
            content: popover.get(0)
        });
    
        // Draw the rectangle
        link.rect(item.width, 
                  item.height)
                .attr("id", "rect_" + item.id)
                .fill(getGenderColor(item.gender))
                .stroke('black')
                .radius(10, 10)
                .move(item.X, item.Y);

        //Insert the text
        link.text(item.name)
                .font({size: 20})
                .center(item.X + item.width / 2, 
                        item.Y + item.height / 2);
                        
    } else {
        if ((get_settings["id"] === "-999" && item.id === "-999") ||
            (get_settings["id"] !== "-999" && item.id !== "-999")) {
            var link = group.group();
        } else {
            // The link depends on whether it is a global timeline or not
            var href = setParameters("events/event/" + (
                        (get_settings["id"] === "-999") ? 
                            item.id : 
                            get_settings["id"]));
                
            // The button to see the popover
            var link = group.link(href);
                link.target('_blank');
        }

        // The popover itself
        var popover = $("<div>")
                .append("\
                    <table class='table table-striped'>" + 
                        "<tbody>" +
                        insertDetail(item, "descr") + 
                        insertDetail(item, "length") + 
                        insertDetail(item, "date") + 
                        "</tbody>" + 
                    "</table>");
            
        if ((get_settings["id"] === "-999" && item.id !== "-999") ||
            (get_settings["id"] !== "-999" && item.id === "-999")) {
            // There actually is a link to go to
            popover.append("<p class='font-weight-bold'>" + dict["map.info.details"] + "</p>");
        } else if (get_settings["id"] !== "-999" && item.id !== "-999" && itemHasSubChildren(item)) {
            // There is a modal showing another sub timeline
            popover.append("<p class='font-weight-bold'>" + dict["map.info.sub"] + "</p>");
        }

        $(link.node).popover({
            animation: true,
            trigger: "hover",
            placement: "top",
            title: dict["map.info.title"] + item.name,
            html: true,
            content: popover.get(0)
        });
        $(link.node).attr("data-toggle", "modal");
        $(link.node).attr("data-target", "#subMapModal");
        $(link.node).attr("id", item.id);
        
        // Draw the rectangle
        link.rect(item.width, 
                  item.height)
                .attr("id", "rect_" + item.id)
                .fill(getLengthColor(item.length))
                .stroke({width: itemHasSubChildren(item) ? 5 : 1, color: 'black'})
                .radius(10, 10)
                .move(item.X, item.Y);

        //Insert the text
        link.text(item.name)
                .font({size: 20})
                .center(item.X + item.width / 2, 
                        item.Y + item.height / 2);
    }
    
}

function drawLink(group, child) {
    if (child.root !== true) {
        child.parents.forEach(function (parent_id) {
            var parent = getMapItem(parent_id);

            if (g_Options.type === TYPE_FAMILYTREE) {
                group.polyline(calcPolyLineCoords({"child": child, "parent": parent}))
                    .fill('none')
                    .stroke({ color: getGenderColor(parent.gender),
                              width: 4, linecap: 'round', linejoin: 'round' });
            } else {
                group.polyline(calcPolyLineCoords({"child": child, "parent": parent}))
                    .fill('none')
                    .stroke({ color: getLengthColor(parent.gender),
                              width: 4, linecap: 'round', linejoin: 'round' });
                 
            }
        });
    }
}


