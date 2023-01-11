

/* global dict */

function getPeopleContent(peoples) {
    if (peoples.hasOwnProperty('id')) {
        // A person has been selected, show its information
        $("#item_content").append(`
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <h1 class="mb-3">` + peoples.name + `</h1>
                    <p class="lead">` + peoples.descr + `</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <p class="lead font-weight-bold mt-4">` + dict["items.details"] + `</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <tbody>` +
                                insertDetail(peoples, 'meaning_name') + 
                                insertDetail(peoples, 'aka') + 
                                insertDetail(peoples, 'father_age') + 
                                insertDetail(peoples, 'mother_age') +
                                insertDetailLink(peoples, 'parents') +
                                insertDetailLink(peoples, 'children') +
                                insertDetailLink(peoples, 'events') +
                                insertDetailLink(peoples, 'locations') +
                                insertDetail(peoples, 'age') + 
                                insertDetail(peoples, 'gender') +
                                insertDetail(peoples, 'tribe') +
                                insertDetail(peoples, 'profession') +
                                insertDetail(peoples, 'nationality') +
                                insertDetail(peoples, 'book_start') +
                                insertDetail(peoples, 'book_end') +
                            `</tbody>
                        </table>
                    </div>` + 
                    insertDetailMaps(peoples, 'familytree') + 
                `</div>
            </div>
        `);        
        
    } else {
        // Error message, because database can't be reached
        $("#item_content")
                .addClass("text-center")
                .append(dict["settings.database_err"]);
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