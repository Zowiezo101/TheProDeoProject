/* global session_settings, dict, getBooks, getEvents, getPeoples, getLocations, getSpecials */

var chapterInit = {
    "start": true,
    "end": true
};
var sliderInit = {
    "specific": true
};

function getSearchMenu() {
    
    var menu = $("<div id='search_menu'>").addClass("col-md-4 col-lg-3").append(`
            <!-- Search bar -->
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" id="item_name" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="searchItems()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Meaning name -->
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_meaning_name" placeholder="` + dict["items.meaning_name"] + `" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
            <!-- Description -->
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_descr" placeholder="` + dict["items.descr"] + `" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
            <!-- First appearance -->    
            <div class="row pb-2">
                <div class="col-md-12 text-center">
                    <label class="font-weight-bold" id="item_start_label">` + dict["items.book_start"] + `:
                    </label>
                </div>
    
                <div class="col-md-6">
                    <select class="custom-select" id="item_start_book" onchange="insertChapters('start')">
                        <option selected disabled value="-1">` + dict["books.book"] + `</option>
                        <option data-num-chapters="50" value="1">` + dict["books.book_1"] + `</option>
                        <option data-num-chapters="40" value="2">` + dict["books.book_2"] + `</option>
                        <option data-num-chapters="27" value="3">` + dict["books.book_3"] + `</option>
                        <option data-num-chapters="36" value="4">` + dict["books.book_4"] + `</option>
                        <option data-num-chapters="34" value="5">` + dict["books.book_5"] + `</option>
                        <option data-num-chapters="24" value="6">` + dict["books.book_6"] + `</option>
                        <option data-num-chapters="21" value="7">` + dict["books.book_7"] + `</option>
                        <option data-num-chapters="4" value="8">` + dict["books.book_8"] + `</option>
                        <option data-num-chapters="31" value="9"> ` + dict["books.book_9"] + `</option>
                        <option data-num-chapters="24" value="10">` + dict["books.book_10"] + `</option>
                        <option data-num-chapters="22" value="11">` + dict["books.book_11"] + `</option>
                        <option data-num-chapters="25" value="12">` + dict["books.book_12"] + `</option>
                        <option data-num-chapters="29" value="13">` + dict["books.book_13"] + `</option>
                        <option data-num-chapters="36" value="14">` + dict["books.book_14"] + `</option>
                        <option data-num-chapters="10" value="15">` + dict["books.book_15"] + `</option>
                        <option data-num-chapters="13" value="16">` + dict["books.book_16"] + `</option>
                        <option data-num-chapters="10" value="17">` + dict["books.book_17"] + `</option>
                        <option data-num-chapters="42" value="18">` + dict["books.book_18"] + `</option>
                        <option data-num-chapters="150" value="19">` + dict["books.book_19"] + `</option>
                        <option data-num-chapters="31" value="20">` + dict["books.book_20"] + `</option>
                        <option data-num-chapters="12" value="21">` + dict["books.book_21"] + `</option>
                        <option data-num-chapters="8" value="22">` + dict["books.book_22"] + `</option>
                        <option data-num-chapters="66" value="23">` + dict["books.book_23"] + `</option>
                        <option data-num-chapters="52" value="24">` + dict["books.book_24"] + `</option>
                        <option data-num-chapters="5" value="25">` + dict["books.book_25"] + `</option>
                        <option data-num-chapters="48" value="26">` + dict["books.book_26"] + `</option>
                        <option data-num-chapters="12" value="27">` + dict["books.book_27"] + `</option>
                        <option data-num-chapters="14" value="28">` + dict["books.book_28"] + `</option>
                        <option data-num-chapters="4" value="29">` + dict["books.book_29"] + `</option>
                        <option data-num-chapters="9" value="30">` + dict["books.book_30"] + `</option>
                        <option data-num-chapters="1" value="31">` + dict["books.book_31"] + `</option>
                        <option data-num-chapters="4" value="32">` + dict["books.book_32"] + `</option>
                        <option data-num-chapters="7" value="33">` + dict["books.book_33"] + `</option>
                        <option data-num-chapters="3" value="34">` + dict["books.book_34"] + `</option>
                        <option data-num-chapters="3" value="35">` + dict["books.book_35"] + `</option>
                        <option data-num-chapters="3" value="36">` + dict["books.book_36"] + `</option>
                        <option data-num-chapters="2" value="37">` + dict["books.book_37"] + `</option>
                        <option data-num-chapters="14" value="38">` + dict["books.book_38"] + `</option>
                        <option data-num-chapters="3" value="39">` + dict["books.book_39"] + `</option>
                        <option data-num-chapters="28" value="40">` + dict["books.book_40"] + `</option>
                        <option data-num-chapters="16" value="41">` + dict["books.book_41"] + `</option>
                        <option data-num-chapters="24" value="42">` + dict["books.book_42"] + `</option>
                        <option data-num-chapters="21" value="43">` + dict["books.book_43"] + `</option>
                        <option data-num-chapters="28" value="44">` + dict["books.book_44"] + `</option>
                        <option data-num-chapters="16" value="45">` + dict["books.book_45"] + `</option>
                        <option data-num-chapters="16" value="46">` + dict["books.book_46"] + `</option>
                        <option data-num-chapters="13" value="47">` + dict["books.book_47"] + `</option>
                        <option data-num-chapters="6" value="48">` + dict["books.book_48"] + `</option>
                        <option data-num-chapters="6" value="49">` + dict["books.book_49"] + `</option>
                        <option data-num-chapters="4" value="50">` + dict["books.book_50"] + `</option>
                        <option data-num-chapters="4" value="51">` + dict["books.book_51"] + `</option>
                        <option data-num-chapters="5" value="52">` + dict["books.book_52"] + `</option>
                        <option data-num-chapters="3" value="53">` + dict["books.book_53"] + `</option>
                        <option data-num-chapters="6" value="54">` + dict["books.book_54"] + `</option>
                        <option data-num-chapters="4" value="55">` + dict["books.book_55"] + `</option>
                        <option data-num-chapters="3" value="56">` + dict["books.book_56"] + `</option>
                        <option data-num-chapters="1" value="57">` + dict["books.book_57"] + `</option>
                        <option data-num-chapters="13" value="58">` + dict["books.book_58"] + `</option>
                        <option data-num-chapters="5" value="59">` + dict["books.book_59"] + `</option>
                        <option data-num-chapters="5" value="60">` + dict["books.book_60"] + `</option>
                        <option data-num-chapters="3" value="61">` + dict["books.book_61"] + `</option>
                        <option data-num-chapters="5" value="62">` + dict["books.book_62"] + `</option>
                        <option data-num-chapters="1" value="63">` + dict["books.book_63"] + `</option>
                        <option data-num-chapters="1" value="64">` + dict["books.book_64"] + `</option>
                        <option data-num-chapters="1" value="65">` + dict["books.book_65"] + `</option>
                        <option data-num-chapters="22" value="66">` + dict["books.book_66"] + `</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <select class="custom-select" id="item_start_chap" onchange="searchItems()">
                        <option selected disabled value="-1">` + dict["books.chapter"] + `</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>
    
            <!-- Last appearance -->
            <div class="row pb-2">
                <div class="col-md-12 text-center">
                    <label class="font-weight-bold" id="item_end_label">` + dict["items.book_end"] + `:
                    </label>
                </div>
    
                <div class="col-md-6">
                    <select class="custom-select" id="item_end_book" onchange="insertChapters('end')">
                        <option selected disabled value="-1">` + dict["books.book"] + `</option>
                        <option data-num-chapters="50" value="1">` + dict["books.book_1"] + `</option>
                        <option data-num-chapters="40" value="2">` + dict["books.book_2"] + `</option>
                        <option data-num-chapters="27" value="3">` + dict["books.book_3"] + `</option>
                        <option data-num-chapters="36" value="4">` + dict["books.book_4"] + `</option>
                        <option data-num-chapters="34" value="5">` + dict["books.book_5"] + `</option>
                        <option data-num-chapters="24" value="6">` + dict["books.book_6"] + `</option>
                        <option data-num-chapters="21" value="7">` + dict["books.book_7"] + `</option>
                        <option data-num-chapters="4" value="8">` + dict["books.book_8"] + `</option>
                        <option data-num-chapters="31" value="9"> ` + dict["books.book_9"] + `</option>
                        <option data-num-chapters="24" value="10">` + dict["books.book_10"] + `</option>
                        <option data-num-chapters="22" value="11">` + dict["books.book_11"] + `</option>
                        <option data-num-chapters="25" value="12">` + dict["books.book_12"] + `</option>
                        <option data-num-chapters="29" value="13">` + dict["books.book_13"] + `</option>
                        <option data-num-chapters="36" value="14">` + dict["books.book_14"] + `</option>
                        <option data-num-chapters="10" value="15">` + dict["books.book_15"] + `</option>
                        <option data-num-chapters="13" value="16">` + dict["books.book_16"] + `</option>
                        <option data-num-chapters="10" value="17">` + dict["books.book_17"] + `</option>
                        <option data-num-chapters="42" value="18">` + dict["books.book_18"] + `</option>
                        <option data-num-chapters="150" value="19">` + dict["books.book_19"] + `</option>
                        <option data-num-chapters="31" value="20">` + dict["books.book_20"] + `</option>
                        <option data-num-chapters="12" value="21">` + dict["books.book_21"] + `</option>
                        <option data-num-chapters="8" value="22">` + dict["books.book_22"] + `</option>
                        <option data-num-chapters="66" value="23">` + dict["books.book_23"] + `</option>
                        <option data-num-chapters="52" value="24">` + dict["books.book_24"] + `</option>
                        <option data-num-chapters="5" value="25">` + dict["books.book_25"] + `</option>
                        <option data-num-chapters="48" value="26">` + dict["books.book_26"] + `</option>
                        <option data-num-chapters="12" value="27">` + dict["books.book_27"] + `</option>
                        <option data-num-chapters="14" value="28">` + dict["books.book_28"] + `</option>
                        <option data-num-chapters="4" value="29">` + dict["books.book_29"] + `</option>
                        <option data-num-chapters="9" value="30">` + dict["books.book_30"] + `</option>
                        <option data-num-chapters="1" value="31">` + dict["books.book_31"] + `</option>
                        <option data-num-chapters="4" value="32">` + dict["books.book_32"] + `</option>
                        <option data-num-chapters="7" value="33">` + dict["books.book_33"] + `</option>
                        <option data-num-chapters="3" value="34">` + dict["books.book_34"] + `</option>
                        <option data-num-chapters="3" value="35">` + dict["books.book_35"] + `</option>
                        <option data-num-chapters="3" value="36">` + dict["books.book_36"] + `</option>
                        <option data-num-chapters="2" value="37">` + dict["books.book_37"] + `</option>
                        <option data-num-chapters="14" value="38">` + dict["books.book_38"] + `</option>
                        <option data-num-chapters="3" value="39">` + dict["books.book_39"] + `</option>
                        <option data-num-chapters="28" value="40">` + dict["books.book_40"] + `</option>
                        <option data-num-chapters="16" value="41">` + dict["books.book_41"] + `</option>
                        <option data-num-chapters="24" value="42">` + dict["books.book_42"] + `</option>
                        <option data-num-chapters="21" value="43">` + dict["books.book_43"] + `</option>
                        <option data-num-chapters="28" value="44">` + dict["books.book_44"] + `</option>
                        <option data-num-chapters="16" value="45">` + dict["books.book_45"] + `</option>
                        <option data-num-chapters="16" value="46">` + dict["books.book_46"] + `</option>
                        <option data-num-chapters="13" value="47">` + dict["books.book_47"] + `</option>
                        <option data-num-chapters="6" value="48">` + dict["books.book_48"] + `</option>
                        <option data-num-chapters="6" value="49">` + dict["books.book_49"] + `</option>
                        <option data-num-chapters="4" value="50">` + dict["books.book_50"] + `</option>
                        <option data-num-chapters="4" value="51">` + dict["books.book_51"] + `</option>
                        <option data-num-chapters="5" value="52">` + dict["books.book_52"] + `</option>
                        <option data-num-chapters="3" value="53">` + dict["books.book_53"] + `</option>
                        <option data-num-chapters="6" value="54">` + dict["books.book_54"] + `</option>
                        <option data-num-chapters="4" value="55">` + dict["books.book_55"] + `</option>
                        <option data-num-chapters="3" value="56">` + dict["books.book_56"] + `</option>
                        <option data-num-chapters="1" value="57">` + dict["books.book_57"] + `</option>
                        <option data-num-chapters="13" value="58">` + dict["books.book_58"] + `</option>
                        <option data-num-chapters="5" value="59">` + dict["books.book_59"] + `</option>
                        <option data-num-chapters="5" value="60">` + dict["books.book_60"] + `</option>
                        <option data-num-chapters="3" value="61">` + dict["books.book_61"] + `</option>
                        <option data-num-chapters="5" value="62">` + dict["books.book_62"] + `</option>
                        <option data-num-chapters="1" value="63">` + dict["books.book_63"] + `</option>
                        <option data-num-chapters="1" value="64">` + dict["books.book_64"] + `</option>
                        <option data-num-chapters="1" value="65">` + dict["books.book_65"] + `</option>
                        <option data-num-chapters="22" value="66">` + dict["books.book_66"] + `</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <select class="custom-select" id="item_end_chap" onchange="searchItems()">
                        <option selected disabled value="-1">` + dict["books.chapter"] + `</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>
    
            <!-- Specific search options for -->
            <div class="row mt-2 pb-2">
                <div class="col-md-12 text-center">
                    <label class="font-weight-bold" id="item_specific_label">` + dict["search.specific_for"] + `
                    </label>
                </div>
    
                <div class="col-md-12">
                    <select class="custom-select" id="item_specific" onchange="insertSpecifics()">
                        <option selected disabled value="-1">` + dict["search.select"] + `</option>
                        <option value="0">` + dict["navigation.books"] + `</option>
                        <option value="1">` + dict["navigation.events"] + `</option>
                        <option value="2">` + dict["navigation.peoples"] + `</option>
                        <option value="3">` + dict["navigation.locations"] + `</option>
                        <option value="4">` + dict["navigation.specials"] + `</option>
                    </select>
                </div>
    
                <div class="col-md-12 d-none" id="item_specifics_books">
                    <!-- Number of chapters -->
                    <div class="row my-2">
                        <div class="col-md-12 text-center">
                            <label class="font-weight-bold" id="item_specific_label">` + dict["items.num_chapters"] + `
                            </label>
                        </div>

                        <div class="col-md-12 mt-3">
                            <input  id="item_num_chapters" 
                                    type="text" 
                                    value="" 
                                    onchange="onSliderChange('num_chapters')"
                                    data-slider-id="slider_num_chapters"
                                    data-slider-tooltip-split="true"
                                    data-slider-tooltip="always"
                                    data-slider-min="1" 
                                    data-slider-max="150" 
                                    data-slider-step="1" 
                                    data-slider-value="[1,150]"/>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-12 d-none" id="item_specifics_events">
                    <!-- Length -->
                    <div class="row my-2">
                        <div class="col-md-12 text-center">
                            <label class="font-weight-bold" id="item_specific_label">` + dict["items.length"] + `
                            </label>
                        </div>

                        <div class="col-md-12 mt-3">
                            <input  id="item_length" 
                                    type="text" 
                                    value="" 
                                    onchange="onSliderChange('length')"
                                    data-slider-id="slider_length"
                                    data-slider-tooltip-split="true"
                                    data-slider-tooltip="always"
                                    data-slider-min="1" 
                                    data-slider-max="1000" 
                                    data-slider-step="1" 
                                    data-slider-value="[1,1000]"/>
                        </div>
                    </div>
                    
                    <!-- Date -->
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline">
                                <input type="text" class="form-control w-100" id="item_date" placeholder="` + dict["items.date"] + `" onkeyup="searchItems()">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    `);
    
    $(function(){                
        //code that needs to be executed when DOM is ready, after manipulation
        // Insert the search terms from the session
        insertSearch();
    });
    
    return menu;
}

function getSearchContent() {
    var menu = $("<div>").addClass("col-md-8 col-lg-9").append(` 
            <!-- Search results -->
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <!-- Tab selection -->
                    <ul class="nav nav-tabs font-weight-bold">
                        <li class="nav-item"> <a class="active nav-link" data-toggle="tab" href="" data-target="#tabsearch">` + dict["navigation.search"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabbooks">` + dict["navigation.books"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabevents">` + dict["navigation.events"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabpeoples">` + dict["navigation.peoples"] + `</a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tablocations">` + dict["navigation.locations"] + `</a></li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabspecials">` + dict["navigation.specials"] + `</a></li>
                    </ul>
    
                    <!-- The different tabs -->
                    <div class="tab-content mt-2">
                        <!-- Search explanation -->
                        <div class="tab-pane fade show active" id="tabsearch" role="tabpanel">
                            <h1>` + dict["search.title"] + `</h1>
                            <p>` + dict["search.description"] + `</p>
                        </div>
    
                        <!-- Tab for books -->
                        <div class="tab-pane fade" id="tabbooks" role="tabpanel">
                        </div>
    
                        <!-- Tab for events -->
                        <div class="tab-pane fade" id="tabevents" role="tabpanel">
                        </div>
    
                        <!-- Tab for peoples -->
                        <div class="tab-pane fade" id="tabpeoples" role="tabpanel">
                        </div>
                        
                        <!-- Tab for locations -->
                        <div class="tab-pane fade" id="tablocations" role="tabpanel">
                        </div>
                        
                        <!-- Tab for specials -->
                        <div class="tab-pane fade" id="tabspecials" role="tabpanel">
                        </div>
                    </div>
                </div>
            </div>
    `);
    
    $(function(){
        //code that needs to be executed when DOM is ready, after manipulation        
        // Insert the results with the search terms
        insertResults();
    });
    
    return menu;
}

function insertChapters(type) {
    // Get the selected book and its amount of chapters
    var book = $("#item_" + type + "_book option:selected");
    var num_chapters = book.data("numChapters");
    
    // Insert all the options
    $("#item_" + type + "_chap").empty();
    $("#item_" + type + "_chap").append(
                // Default option, is not selectable
                '<option selected disabled value="-1">' + 
                    dict["books.chapter"] + 
                '</option>'
            );
    for (var i = 0; i < num_chapters; i++) {
        // Inserting the chapters
        $("#item_" + type + "_chap").append(
                '<option value="' + (i+1) + '">' + 
                    (i+1) + 
                '</option>'
            );
    }
    
    // Need to initialize First/Last appearance chapters?
    if (chapterInit[type]) {
        // Setting back the selected chapter from the session
        $("#item_" + type + "_chap").val(
                session_settings["search_start_chap"] ? 
                session_settings["search_start_chap"] : -1);
                
        // Done initializing this dropdown
        chapterInit[type] = false;
    } else {
        // When changing books, preset it to the first/last chapter
        $("#item_" + type + "_chap").val(type === "start" ? 1 : num_chapters);
    }
        
    // Take over the changes
    $("#item_" + type + "_chap").change();
    
    // Set the filter if a value is set
    if ($("#item_" + type + "_chap").val() !== -1 &&
        $("#item_" + type + "_chap").val() !== null) {
        removeFilter(type, "#item_" + type + "_label");
    }
}

function insertSpecifics() {
    // Get the selected book and its amount of chapters
    var type = $("#item_specific option:selected").val();
    
    if (!sliderInit["specific"]) {
        // Update the query to the session
        // Only if this was an actual change and not the initializing
        updateSession({
            "search_specific": type,

            // Set all the search options to zero
            "search_num_chapters": null,
            "search_length": null,
            "search_date": null
        });
    } else {
        sliderInit["specific"] = false;
    }
    
    if (type !== "-1") {
        // Option to remove the filter
        removeFilter("specific", "#item_specific_label");
        
        // Make all the specific filters invisible
        $("#item_specifics_books").addClass("d-none");
        $("#item_specifics_events").addClass("d-none");
        $("#item_specifics_peoples").addClass("d-none");
        $("#item_specifics_locations").addClass("d-none");
        $("#item_specifics_specials").addClass("d-none");
    
        switch(type) {
            case "0":
                // Books
                $("#item_specifics_books").removeClass("d-none");
                break;
            case "1":
                // Events
                $("#item_specifics_events").removeClass("d-none");
                break;
            case "2":
                // Peoples
                $("#item_specifics_peoples").removeClass("d-none");
                
                // - Father age (Scroller)
                // - Mother age (Scroller)
                // - Age (Scroller)
                // - Gender (unknown, male, female)
                // - Tribe (Dropdown)
                // - Profession (String)
                // - Nationality (String)
                break;
            case "3":
                // Locations
                $("#item_specifics_locations").removeClass("d-none");
                
                // - Type (Dropdown)
                // - Inhabitants (Scroller)
                // - Coordinates (Scrollers, X & Y)
                break;
            case "4":
                // Specials
                $("#item_specifics_specials").removeClass("d-none");
                
                // - Type (Dropdown)
                break;
        }
    }
}


function removeFilter(type, label) {
    if (typeof label !== "undefined") {
        // Option to remove the filter
        $(label + " a").remove();
        $(label).append(
                '<a tabindex=0 onclick="removeFilter(\'' + type + '\')" data-toggle="tooltip" data-placement="top" title="' + dict["search.remove_filter"] + '">' + 
                    '<i class="fa fa-times-circle" aria-hidden="true"></i>' + 
                '</a>');
    } else {
        switch(type) {
            case "start":
            case "end":
                // Reset the book and chapter
                $("#item_" + type + "_book").val(-1);
                $("#item_" + type + "_chap").val(-1);

                // Remove the [x]
                $("#item_" + type + "_label a").remove();
                break;
                
            case "specific":
                // Reset the book and chapter
                $("#item_specific").val(-1);

                // Remove the [x]
                $("#item_specific_label a").remove();
                break
        }
    
        // Search again
        searchItems();
    }
}


/** Insert the search term from the session */
function insertSearch() {
    
    // Search strings
    $("#item_name").val(
            session_settings["search_name"] ? 
            session_settings["search_name"] : "");
    $("#item_meaning_name").val(
            session_settings["search_meaning_name"] ? 
            session_settings["search_meaning_name"] : "");
    $("#item_descr").val(
            session_settings["search_descr"] ? 
            session_settings["search_descr"] : "");
            
    // First and Last appearance books
    $("#item_start_book").val(
            session_settings["search_start_book"] ? 
            session_settings["search_start_book"] : -1);
    $("#item_end_book").val(
            session_settings["search_end_book"] ? 
            session_settings["search_end_book"] : -1);
    
    // Dropdown for specific stuff
    $("#item_specific").val(
            session_settings["search_specific"] ? 
            session_settings["search_specific"] : -1);
            
    // Initialize the sliders and set their values
    var slider = $("#item_num_chapters").slider();
    slider.slider('setValue', 
                session_settings["search_num_chapters"] ? 
      [parseInt(session_settings["search_num_chapters"].split('-')[0], 10),
       parseInt(session_settings["search_num_chapters"].split('-')[1], 10)] : 
            [1, 150]);

    // On change for the different select boxes
    $("#item_start_book").change();
    $("#item_end_book").change();
    $("#item_specific").change();
}

/** Insert the search results of the session */
function insertResults() {
    // Get the data of the books, events, peoples, locations & specials 
    // using the search terms
    getBooks(null, {
        "columns": getSearchTerms("books").columns,
        "filters": getSearchTerms("books").filters
    }).then(function(result) { insertItems("books", result); });

    getEvents(null, {
        "columns": getSearchTerms("events").columns,
        "filters": getSearchTerms("events").filters
    }).then(function(result) { insertItems("events", result); });
    
    getPeoples(null, {
        "columns": getSearchTerms("peoples").columns,
        "filters": getSearchTerms("peoples").filters
    }).then(function(result) { insertItems("peoples", result); });
    
    getLocations(null, {
        "columns": getSearchTerms("locations").columns,
        "filters": getSearchTerms("locations").filters
    }).then(function(result) { insertItems("locations", result); });
    
    getSpecials(null, {
        "columns": getSearchTerms("specials").columns,
        "filters": getSearchTerms("specials").filters
    }).then(function(result) { insertItems("specials", result); });
}

/** Get all the filters in API compatible format */
function getFilters() {
    // Get all the search terms, and use them to filter out results
    var name =          session_settings["search_name"] ? 
            "name % " + session_settings["search_name"] : "";
    var meaning_name =          session_settings["search_meaning_name"] ? 
            "meaning_name % " + session_settings["search_meaning_name"] : "";
    var descr =          session_settings["search_descr"] ? 
            "descr % " + session_settings["search_descr"] : "";
            
    // First appearance
    var start_book =              session_settings["search_start_book"] ? 
            "book_start_id >= " + session_settings["search_start_book"] : "";
    var start_chap =                session_settings["search_start_chap"] ? 
            "book_start_chap >= " + session_settings["search_start_chap"] : "";
    
    // Last appearance
    var end_book =              session_settings["search_end_book"] ? 
            "book_end_id <= " + session_settings["search_end_book"] : "";
    var end_chap =                session_settings["search_end_chap"] ? 
            "book_end_chap <= " + session_settings["search_end_chap"] : "";
            
    // Sliders
    var num_chapters =           session_settings["search_num_chapters"] ? 
            "num_chapters <> " + session_settings["search_num_chapters"] : "";
            
    // First & Last appearance
    var book_ids = "";
    if (session_settings["search_start_book"] && 
        session_settings["search_end_book"]) {
        var book_ids = "id <> " + 
                session_settings["search_start_book"] + "-" + 
                session_settings["search_end_book"];
    } else if (session_settings["search_start_book"]) {
        var book_ids =     session_settings["search_start_book"] ? 
                "id >= " + session_settings["search_start_book"] : "";
    } else if (session_settings["search_end_book"]) {
        var book_ids =     session_settings["search_end_book"] ? 
                "id <= " + session_settings["search_end_book"] : "";
    }
            
    return {
        "name": name,
        "meaning_name": meaning_name,
        "descr": descr,
        "book_ids": book_ids,
        "start_book": start_book,
        "start_chap": start_chap,
        "end_book": end_book,
        "end_chap": end_chap,
        "num_chapters": num_chapters
    };
}

/** Get the columns and filters to send to the API 
 * @param {String} type
 * */
function getSearchTerms(type) {
    var search_terms = {};
    var extra_columns = [];
    
    var filter = getFilters();
    
    switch(type) {
        case "books":
            extra_columns = ["num_chapters"];
            search_terms["name"] = filter.name;
            search_terms["id"] = filter.book_ids;
            search_terms["num_chapters"] = filter.num_chapters;
            break;
            
        case "events":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
            
        case "peoples":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
            
        case "locations":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
            
        case "specials":
            extra_columns = [
                "book_start_id", "book_start_chap", "book_start_vers",
                "book_end_id", "book_end_chap", "book_end_vers"
            ];
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["book_start_id"] = filter.start_book;
            search_terms["book_start_chap"] = filter.start_chap;
            search_terms["book_end_id"] = filter.end_book;
            search_terms["book_end_chap"] = filter.end_chap;
            break;
    }
    
    // Filter out anything that isn't filled
    for (var key in search_terms) {
        if (search_terms[key] === "") {
            delete search_terms[key];
        }
    }
    
    return {
        "columns": extra_columns.concat(
                    Object.keys(search_terms)
                ).join(", "),
        "filters": Object.values(search_terms).join(", ")
    };
}

/** Updating the session settings and performing the search */
function searchItems() {
    // The search terms inserted in input boxes or dropdowns
    var name = $("#item_name").val();
    var meaning_name = $("#item_meaning_name").val();
    var descr = $("#item_descr").val();
    var start_book = $("#item_start_book").val();
    var start_chap = $("#item_start_chap").val();
    var end_book = $("#item_end_book").val();
    var end_chap = $("#item_end_chap").val();
    var specific = $("#item_specific").val();
    
    // Update the query to the session
    var num_chapters = $("#item_num_chapters").slider('getValue');
    
    // Update the query to the session
    updateSession({
        "search_name": name,
        "search_meaning_name": meaning_name,
        "search_descr": descr,
        "search_start_book": start_book,
        "search_start_chap": start_chap,
        "search_end_book": end_book,
        "search_end_chap": end_chap,
        "search_specific": specific,
        "search_num_chapters": num_chapters.join('-')
    });
    
    // Recalculate the search results
    insertResults();
}

function onSliderChange(type) {
    switch(type) {
        case "num_chapters":
            // Update the query to the session
            var num_chapters = $("#slider_num_chapters")[0].innerText;
            updateSession({
                "search_num_chapters": num_chapters.replace('\n', '-')
            });
            break;
    }
    
    // Recalculate the search results
    insertResults();
    
}

/** Inserting the results in a readable table format 
 * @param {String} type
 * @param {Object} result * 
 * */
function insertItems(type, result) {
    // Start out clean
    $("#tab" + type).empty();
    
    // No errors and at least 1 item of data
    if ((result.error === null) && result.data && result.data.length > 0) {
        
        // Table header is the name
        var table_header = insertHeader(type, "name");
        table_header += insertHeader(type, "meaning_name");
        table_header += insertHeader(type, "descr");
        table_header += insertHeader(type, "book_start");
        table_header += insertHeader(type, "book_end");
        table_header += insertHeader(type, "num_chapters");
        table_header += insertHeader(type, "link");
        
        table_row = [];
        for (var i = 0; i < result.data.length; i++) {
            var data = result.data[i];
            
            // Table header is the name
            table_data = insertData(type, "name", data);
            table_data += insertData(type, "meaning_name", data);
            table_data += insertData(type, "descr", data);
            table_data += insertData(type, "book_start", data);
            table_data += insertData(type, "book_end", data);
            table_data += insertData(type, "num_chapters", data);
            table_data += insertData(type, "link", data);
            
            // The row for every item we've got
            table_row.push('<tr>' + table_data + '</tr>');
        }
        
        $("#tab" + type).append(`
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>`
                            + table_header +
                        `</tr>
                    </thead>
                    <tbody>`
                        + table_row.join("") +
                    `</tbody>
                </table>
            </div>
        `);
    } else {
        // TODO:
        // Error melding geven dat database niet bereikt kan worden
        $("#tab" + type).append(result.error ? result.error : "No results found");
    }
}

/**
 * Inserting a header into the table of results
 * @param {String} type
 * @param {String} name
 * */
function insertHeader(type, name) {
    var types = getTypes(name);
    
    var table_header = "";
    if (types.includes(type)) {
        table_header = '<th scope="col">' + dict["items." + name] + '</th>';
    }
    
    return table_header;
}

/**
 * Inserting data into the table of results
 * @param {String} type
 * @param {String} name
 * @param {Object} data
 * */
function insertData(type, name, data) {
    var types = getTypes(name);
    
    var table_data = "";
    if (types.includes(type) && (name === "name")) {
        table_data = '<th scope="row">' + data[name] + '</th>';
    } else if (types.includes(type) && (name === "link")) {
        table_data = '<td>' + getLinkToItem(type, data.id, "self") + '</td>';
    } else if (types.includes(type) && (name === "book_start")) {
        table_data = '<td>' + 
                dict["books.book_" + data["book_start_id"]] + 
                " " + data["book_start_chap"] + 
                ":" + data["book_start_vers"] + 
            '</td>';
    } else if (types.includes(type) && (name === "book_end")) {
        table_data = '<td>' + 
                dict["books.book_" + data["book_end_id"]] + 
                " " + data["book_end_chap"] + 
                ":" + data["book_end_vers"] + 
            '</td>';
    } else if (types.includes(type)) {
        table_data = '<td>' + data[name] + '</td>';
    }
    
    return table_data;
}

function getTypes(name) {
    var types = [];
    if ($.inArray(name, ["name", "link", "book_start", "book_end", "num_chapters"]) !== -1) {
        switch(name) {
            case "name":
            case "link":
                types = ["books", "events", "peoples", "locations", "specials"];
                break;
                
            case "book_start":
            case "book_end":
                types = ["events", "peoples", "locations", "specials"];
                break;
                
            case "num_chapters":
                types = ["books"];
                break;
        }
    } else if (session_settings["search_" + name]) {
        // If this value saved in the session?
        switch(name) {
            case "meaning_name":
                types = ["peoples", "locations", "specials"];
                break;

            case "descr":
                types = ["events", "peoples", "locations", "specials"];
                break;
        }
    } 
    
    return types;
}
    