/* global page_id, dict */

function getTimelineMenu() {
    // T.B.D.
    var menu = $("<div id='search_menu'>").addClass("col-md-4 col-lg-3").append(`
        
    `);
    
    return menu;
}

function getTimelineContent() {
    // var content = $("<div>").addClass("col-md-8 col-lg-9").append(`
    var content = $("<div>").addClass("col-12").append(`
        <div class="row mb-5 pb-5 text-center">
            <!-- <div class="col-lg-11 px-lg-5 px-md-3"> -->
            <div class="col-12 px-lg-5 px-md-3">
                <h1 class="mb-3">` + dict["navigation." + page_id] + `</h1>
                <p class="lead">` + dict[page_id + ".overview"] + `</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%">0%</div>
                </div>
            </div>
        </div>
    `);
    
    return content;
}