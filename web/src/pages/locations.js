

/* global dict */

function getLocationContent(locations) {
    if (locations) {
        // A location has been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">` + locations.name + `</h1>
                    <p class="lead">` + locations.description + `</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">` + dict["items.details"] + `</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>` +
                                insertDetail(locations, 'meaning_name') + 
                                insertDetail(locations, 'aka') + 
                                insertDetail(locations, 'type') + 
                                insertDetailMaps(locations, 'worldmap') +
                                insertDetailLink(locations, 'events') +
                                insertDetailLink(locations, 'peoples') +
                                insertDetail(locations, 'book_start') +
                                insertDetail(locations, 'book_end') +
                            `</tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);
        
    } else {
        // Error message, because database can't be reached
        $("#item_content").append(dict["settings.database_err"]);
    }
    
    /*
     * With image
     *  <div class="row mb-2">
            <div class="px-lg-5 d-flex flex-column justify-content-center col-lg-6 text-center">
                <h1>O my friend</h1>
                <p class="mb-3 lead">I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects and flies</p>
            </div>
            <div class="col-lg-4"> <img class="img-fluid d-block" src="https://static.pingendo.com/cover-moon.svg"> </div>
        </div>
     * 
     * Without image
     * <div class="row">
            <div class="col-md-10 text-center">
                <h1 class="mb-3">O my friend</h1>
                <p class="lead">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</p>
            </div>
        </div>
     */
}