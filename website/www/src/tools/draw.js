/* global g_MapItems, g_Options, onBeforeZoom, onBeforePan, dict, g_Map, TYPE_FAMILYTREE, getMapItems, itemHasSubChildren, page_base_url */

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
                    <button class="btn btn-primary" onclick="onDownload('` + map[0].name + `')" title="` + dict["map.download." + type] + `"><i class="fa fa-download" aria-hidden="true"></i></button>
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
    // The title and the close button
    var popover_header = dict["map.info.title"] + "\"" + item.name + "\"" + 
                `<a class="float-right" tabindex="-1">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>`;
    
    // Template for the body
    var popover_body = $("<div>");
    
    // The link to the object
    if (g_Options.type === TYPE_FAMILYTREE) {
    
        // The button to see the popover
        var href = page_base_url + item.id;
        var link = group.link(href);
            link.target('_blank');
        
        // Explain how to reach the detail page
        popover_body.append("<p class='font-weight-bold'>" + 
                    dict["map.info.people.details"].replace("LINK", "<a href='" + href + "' target='_blank'>" + 
                    dict["map.info.here"] + "</a>") + 
                "</p>");

        // Add the details of this item
        var popover_details = insertIntoTemplate(item);
        popover_body.append(popover_details);
    
        // Draw the rectangle
        link.rect(item.width, 
                  item.height)
                .attr("id", "rect_" + item.id)
                .fill(getGenderColor(item.gender))
                .stroke('black')
                .radius(10, 10)
                .move(item.X, item.Y);

        //Insert the text
        var text = link.text(item.name)
                .font({size: 20})
                .center(item.X + item.width / 2, 
                        item.Y + item.height / 2);
                        
    } else {
        // The Map ID
        var map_id = parseInt($("#item_list").attr("data-id"), 10);
        
        if ((map_id === -999 && item.id === -999) ||
            (map_id !== -999 && item.id !== -999)) {
            // We don't need a link when there's nothing to go to
            var link = group.group();
        } else {
            // The link depends on whether it is a global timeline or not
            href = page_base_url + map_id;
            if (map_id === -999) {
                href = page_base_url + item.id;
            }
                
            // The button to see the popover
            var link = group.link(href);
                link.target('_blank');
        }

        // The popover itself
        if (item.id === -999) {
            // Global timeline
            popover_body.append("<p>" + dict["map.info.global"] + "</p>");
        }
            
        if ((map_id === -999 && item.id !== -999) ||
            (map_id !== -999 && item.id === -999)) {
            // There actually is a link to go to
            // Explain how to reach the detail page
            popover_body.append("<p class='font-weight-bold'>" + 
                                    dict["map.info.event.details"].replace("LINK", "<a href='" + href + "' target='_blank'>" +
                                    dict["map.info.here"] + "</a>") + 
                                "</p>");
        } else if (map_id !== -999 && item.id !== -999 && itemHasSubChildren(item)) {
            // There is a modal showing another sub timeline
            // Explain how to reach the modal
            popover_body.append("<p class='font-weight-bold'>" + 
                                    dict["map.info.sub"].replace("LINK", "<a tabindex='-1' data-toggle='modal' data-target='#subMapModal' id='" + item.id + "'>" + 
                                    dict["map.info.here"] + "</a>") + 
                                "</p>");
        }

        // Add the details of this item
        var popover_details = insertIntoTemplate(item);
        popover_body.append(popover_details);
        
        if (itemHasSubChildren(item)) {
            $(link.node).attr("data-toggle", "modal");
            $(link.node).attr("data-target", "#subMapModal");
            $(link.node).attr("id", item.id);
        }
        
        // Draw the rectangle
        link.rect(item.width, 
                  item.height)
                .attr("id", "rect_" + item.id)
                .fill(getDataColor(item))
                .stroke({width: itemHasSubChildren(item) ? 5 : 1, color: 'black'})
                .radius(10, 10)
                .move(item.X, item.Y);

        //Insert the text
        text = link.text(item.name)
                .font({size: 20})
                .center(item.X + item.width / 2, 
                        item.Y + item.height / 2);
    }
    
    var timerId = null;

    $(link.node).popover({
        animation: true,
        placement: "top",
        html: true,
        title: popover_header,
        content: popover_body.get(0)
    }).mouseenter(function() {
        // Set a timer, after the selected time, the popover will be shown
        timerId = setTimeout(function() {
            // Hide all popovers
            $(".popover").popover("hide");

            // Show the selected popover
            $(link.node).popover("show");
        }, 250, link.node);
    }).mouseleave(function() {
        // Timer is cleared when mouse moves away
        clearTimeout(timerId);
    }).on("shown.bs.popover", function() {
        // Now's the time to make the close button working
        $(".popover-header a").click(function() {
            // Hide all popovers
            $(".popover").popover("hide");
        });
    });
    
    // The text is reaching outside of the bubble, 
    // mask what is falling ouside of it and make sure it's left aligned now
    if (text.length() > (item.width - 10)) {
        var ellipse = link.text("...")
                        .font({size: 20});
                
        var rect = group.rect(item.width - 10 - ellipse.length(),
                              item.height)
                             .move(item.X + 5, item.Y);
        var clip = group.clip().add(rect);

        text.clipWith(clip);
        text.center(item.X + (text.length() / 2) + 5, 
                    item.Y + (item.height / 2));
        ellipse.center(item.X + (item.width - 5) - (ellipse.length() / 2), 
                       item.Y + (item.height / 2));
    }
    
}

function drawLink(group, child) {
    if (child.gen !== 0) {
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
                    .stroke({ color: getDataColor(parent),
                              width: 4, linecap: 'round', linejoin: 'round' });
                 
            }
        });
    }
}

function insertIntoTemplate(item) {   
    // Create a copy of the template
    var popup_template = $("#popup_template").clone();

    // Go through all the rows to see which information is actually filled in
    popup_template.find("tr").get().forEach(row => {
        // Get the header element and the data element
        var header = $(row).find("th")[0];
        var data = $(row).find("td")[0];

        // Get the current property name and value
        var name = $(header).text();
        var value = dict.hasOwnProperty(item[name]) ? dict[item[name]] : item[name];

        // Update the title
        $(header).text(dict["items." + name]);

        // Update the data
        $(data).html(value);

        if (value === "" || value === -1 || value === "-1" || value === null) {
            // Delete this row, as it has no information to show
            $(row).remove();
        }
    });

    // If there are no rows, no information is known
    if (popup_template.find("tr").length > 0) {
        // Make the table visible
        popup_template.find("table").removeClass("d-none");
    } else if (g_Options.type !== TYPE_WORLDMAP) {
        var type = g_Options.type === TYPE_FAMILYTREE ? "people" : "event";

        // No available information on this event
        popup_template.append("<p>" + dict["map.info." + type + ".unknown"] + "</p>");
    } 
    
    return popup_template;
}

function getGenderColor(int) {
    var color = "";
    
    switch(int) {
        case "gender.male":
            color = "lightblue";
            break;
            
        case "gender.female":
            color = "pink";
            break;
            
        case "gender.unknown":
        default:
            color = "lightgrey";
            break;
    }
    
    return color;
}

function getDataColor(object) {
    var color = "lightgrey";
    var colorCode = 0;
    
    // Descr and notes kinda fall under the same categorie for me
    if (object.hasOwnProperty("notes") && ![null, "", -1, []].includes(object.notes) && object.notes.length !== 0) {
        colorCode += 1;
    } else if (object.hasOwnProperty("descr") && ![null, "", -1].includes(object.descr)) {
        colorCode += 1;
    }
    
    if (object.hasOwnProperty("date") && ![null, "", -1].includes(object.date)) {
        colorCode += 2;
    } if (object.hasOwnProperty("length") && ![null, "", -1].includes(object.length)) {
        colorCode += 4;
    }
    
    switch(colorCode) {
        case 0:
            color = "lightgrey";
            break;
        case 1:
            color = "lightcoral";
            break;
        case 2:
            color = "lightblue";
            break;
        case 3:
            color = "mediumpurple";
            break;
        case 4:
            color = "gold";
            break;
        case 5:
            color = "lightsalmon";
            break;
        case 6:
            color = "lightgreen";
            break;
        case 7:
            color = "peru";
            break;
    }
    
    return color;
}
