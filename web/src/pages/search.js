
/* global dict, session_settings, searchBooks, searchEvents, searchPeoples, searchLocations, searchSpecials */

// The elements that need initializing
var elementInit = {
    "start": false,
    "end": false,
    "specific": false,
    "num_chapters": false,
    "age": false,
    "age_parents": false
};

// The elements that can be disabled
var elementEnabled = {
    "num_chapters": false,
    "age": false,
    "age_parents": false
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
   
            <hr class="my-1"/>
 
            <!-- Meaning name -->
            <div class="row people_prop location_prop special_prop">
                <div class="col-md-12">
                    <label class="font-weight-bold">` + dict["items.meaning_name"] + `:
                    </label>
                </div>
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_meaning_name" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
            <hr class="my-1"/>
    
            <!-- Description -->
            <div class="row event_prop people_prop location_prop special_prop">
                <div class="col-md-12">
                    <label class="font-weight-bold">` + dict["items.descr"] + `:
                    </label>
                </div>
                <div class="col-md-12">
                    <form class="form-inline">
                        <input type="text" class="form-control w-100" id="item_descr" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                    </form>
                </div>
            </div>
    
            <hr class="my-1"/>
    
            <!-- First appearance -->    
            <div class="row pb-2 event_prop people_prop location_prop special_prop">
                <div class="col-md-12">
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
    
            <hr class="my-1"/>
    
            <!-- Last appearance -->
            <div class="row pb-2 event_prop people_prop location_prop special_prop">
                <div class="col-md-12">
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
    
            <hr class="my-1"/>
    
            <!-- Specific search options for -->
            <div class="row">
                <div class="col-md-12">
                    <label class="font-weight-bold" id="item_specific_label">` + dict["search.specific_for"] + `
                    </label>
                </div>
    
                <div class="col-md-12 pb-2">
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
    
                    <hr class="my-1"/>
    
                    <!-- Number of chapters -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_num_chapters_label">` + dict["items.num_chapters"] + `
                            </label>
                        </div>

                        <div class="col-md-12">
                            <input  id="item_num_chapters" 
                                    class="d-none"
                                    type="text" 
                                    value="" 
                                    data-slider-id="slider_num_chapters"
                                    data-slider-tooltip-split="true"
                                    data-slider-step="1"
                                    data-slider-value="1"
                                    data-slider-range="true" />
                        </div>
                    </div>
                </div>
    
                <div class="col-md-12 d-none" id="item_specifics_events">
    
                    <hr class="my-1"/>
    
                    <!-- Length -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_length_label">` + dict["items.length"] + `
                            </label>
                        </div>

                        <div class="col-md-12">
                            <form class="form-inline">
                                <input type="text" class="form-control w-100" id="item_length" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                            </form>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
                    
                    <!-- Date -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_date_label">` + dict["items.date"] + `
                            </label>
                        </div>
    
                        <div class="col-md-12">
                            <form class="form-inline">
                                <input type="text" class="form-control w-100" id="item_date" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                            </form>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-12 d-none" id="item_specifics_peoples">
    
                    <hr class="my-1"/>
    
                    <!-- Reached age -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_age_label">` + dict["items.age"] + `
                            </label>
                        </div>

                        <div class="col-md-12">
                            <input  id="item_age" 
                                    class="d-none"
                                    type="text" 
                                    value="" 
                                    data-slider-id="slider_age"
                                    data-slider-tooltip-split="true"
                                    data-slider-step="1"
                                    data-slider-value="1"
                                    data-slider-range="true" />
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Parents age -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_parent_age_label">` + dict["items.parent_age"] + `
                            </label>
                        </div>

                        <div class="col-md-12">
                            <input  id="item_parent_age" 
                                    class="d-none"
                                    type="text" 
                                    value="" 
                                    data-slider-id="slider_parent_age"
                                    data-slider-tooltip-split="true"
                                    data-slider-step="1"
                                    data-slider-value="1"
                                    data-slider-range="true" />
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Gender -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_gender_label">` + dict["items.gender"] + `
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_gender" onchange="onSelectChange('gender')">
                                <option selected disabled value="-1">` + dict["search.select"] + `</option>
                                <option value="0">` + getGenderString(0) + `</option>
                                <option value="1">` + getGenderString(1) + `</option>
                                <option value="2">` + getGenderString(2) + `</option>
                                <option value="3">` + dict["search.all"] + `</option>
                            </select>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Tribe -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_tribe_label">` + dict["items.tribe"] + `
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_tribe" onchange="onSelectChange('tribe')">
                                <option selected disabled value="-1">` + dict["search.select"] + `</option>
                                <option value="0">` + getTribeString(0) + `</option>
                                <option value="1">` + getTribeString(1) + `</option>
                                <option value="2">` + getTribeString(2) + `</option>
                                <option value="3">` + getTribeString(3) + `</option>
                                <option value="4">` + getTribeString(4) + `</option>
                                <option value="5">` + getTribeString(5) + `</option>
                                <option value="6">` + getTribeString(6) + `</option>
                                <option value="7">` + getTribeString(7) + `</option>
                                <option value="8">` + getTribeString(8) + `</option>
                                <option value="9">` + getTribeString(9) + `</option>
                                <option value="10">` + getTribeString(10) + `</option>
                                <option value="11">` + getTribeString(11) + `</option>
                                <option value="12">` + getTribeString(12) + `</option>
                                <option value="13">` + dict["search.all"] + `</option>
                            </select>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Profession -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_profession_label">` + dict["items.profession"] + `
                            </label>
                        </div>
                        <div class="col-md-12">
                            <form class="form-inline">
                                <input type="text" class="form-control w-100" id="item_profession" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                            </form>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Nationality -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_nationality_label">` + dict["items.nationality"] + `
                            </label>
                        </div>
                        <div class="col-md-12">
                            <form class="form-inline">
                                <input type="text" class="form-control w-100" id="item_nationality" placeholder="` + dict["database.search"] + `" onkeyup="searchItems()">
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 d-none" id="item_specifics_locations">    
    
                    <hr class="my-1"/>
    
                    <!-- Type -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_type_location_label">` + dict["items.type"] + `
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_type_location" onchange="onSelectChange('type_location')">
                                <option selected disabled value="-1">` + dict["search.select"] + `</option>
                                <option value="0">` + getTypeLocationString(0) + `</option>
                                <option value="1">` + getTypeLocationString(1) + `</option>
                                <option value="2">` + getTypeLocationString(2) + `</option>
                                <option value="3">` + getTypeLocationString(3) + `</option>
                                <option value="4">` + getTypeLocationString(4) + `</option>
                                <option value="5">` + getTypeLocationString(5) + `</option>
                                <option value="6">` + getTypeLocationString(6) + `</option>
                                <option value="7">` + getTypeLocationString(7) + `</option>
                                <option value="8">` + getTypeLocationString(8) + `</option>
                                <option value="9">` + getTypeLocationString(9) + `</option>
                                <option value="10">` + dict["search.all"] + `</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 d-none" id="item_specifics_specials">
    
                    <hr class="my-1"/>
    
                    <!-- Type -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_type_special_label">` + dict["items.type"] + `
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_type_special" onchange="onSelectChange('type_special')">
                                <option selected disabled value="-1">` + dict["search.select"] + `</option>
                                <option value="0">` + getTypeSpecialString(0) + `</option>
                                <option value="1">` + getTypeSpecialString(1) + `</option>
                                <option value="2">` + getTypeSpecialString(2) + `</option>
                                <option value="3">` + getTypeSpecialString(3) + `</option>
                                <option value="4">` + getTypeSpecialString(4) + `</option>
                                <option value="5">` + getTypeSpecialString(5) + `</option>
                                <option value="6">` + getTypeSpecialString(6) + `</option>
                                <option value="7">` + getTypeSpecialString(7) + `</option>
                                <option value="8">` + dict["search.all"] + `</option>
                            </select>
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
    var content = $("<div>").addClass("col-md-8 col-lg-9").append(` 
            <!-- Search results -->
            <div class="row">
                <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                    <!-- Tab selection -->
                    <ul class="nav nav-tabs justify-content-center font-weight-bold" id="search_tabs">
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
                            <p>` + dict["search.descr"] + `</p>
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
    
    return content;
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
    if (!elementInit[type]) {
        // Setting back the selected chapter from the session
        $("#item_" + type + "_chap").val(
                session_settings["search_" + type + "_chap"] ? 
                session_settings["search_" + type + "_chap"] : -1);
                
        // Done initializing this dropdown
        elementInit[type] = true;
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
    
    if (elementInit["specific"]) {
        // Update the query to the session
        // Only if this was an actual change and not the initializing
        updateSession({
            "search_specific": type,

            // Set all the search options to zero
            "search_num_chapters": null,
            "search_length": null,
            "search_date": null,
            "search_age": null,
            "search_parent_age": null,
            "search_gender": null,
            "search_tribe": null,
            "search_profession": null,
            "search_nationality": null,
            "search_type_location": null,
            "search_type_special": null
        });
        
        removeFilter("num_chapters", null, true);
        removeFilter("length", null, true);
        removeFilter("date", null, true);
        removeFilter("age", null, true);
        removeFilter("parent_age", null, true);
        removeFilter("gender", null, true);
        removeFilter("tribe", null, true);
        removeFilter("profession", null, true);
        removeFilter("nationality", null, true);
        removeFilter("type_location", null, true);
        removeFilter("type_special", null, true);
        
        searchItems();
    } else {
        elementInit["specific"] = true;
    }

    // Make all the specific filters invisible
    $("#item_specifics_books").addClass("d-none");
    $("#item_specifics_events").addClass("d-none");
    $("#item_specifics_peoples").addClass("d-none");
    $("#item_specifics_locations").addClass("d-none");
    $("#item_specifics_specials").addClass("d-none");
    
    if (type !== "-1") {
        // Option to remove the filter
        removeFilter("specific", "#item_specific_label");
        
        // Remove the tabs on the top to only show the selected specific item
        $("#search_tabs").addClass("d-none");
        
        // Also, remove all the main filters that are not related to 
        // the specific filter type..
        $(".book_prop input, \n\
           .event_prop input, \n\
           .people_prop input, \n\
           .location_prop input, \n\
           .special_prop input").addClass("disabled").attr("disabled", "true");
        $(".book_prop select, \n\
           .event_prop select, \n\
           .people_prop select, \n\
           .location_prop select, \n\
           .special_prop select").addClass("disabled").attr("disabled", "true");
    
        // Only show the selected specifics
        switch(type) {
            case "0":
                // Books
                $("#item_specifics_books").removeClass("d-none");
                $(".book_prop input").removeClass("disabled").removeAttr("disabled");
                $(".book_prop select").removeClass("disabled").removeAttr("disabled");
                $("a[data-target='#tabbooks']").trigger('click');
                break;
            case "1":
                // Events
                $("#item_specifics_events").removeClass("d-none");
                $(".event_prop input").removeClass("disabled").removeAttr("disabled");
                $(".event_prop select").removeClass("disabled").removeAttr("disabled");
                $("a[data-target='#tabevents']").trigger('click');
                break;
            case "2":
                // Peoples
                $("#item_specifics_peoples").removeClass("d-none");
                $(".people_prop input").removeClass("disabled").removeAttr("disabled");
                $(".people_prop select").removeClass("disabled").removeAttr("disabled");
                $("a[data-target='#tabpeoples']").trigger('click');
                break;
            case "3":
                // Locations
                $("#item_specifics_locations").removeClass("d-none");
                $(".location_prop input").removeClass("disabled").removeAttr("disabled");
                $(".location_prop select").removeClass("disabled").removeAttr("disabled");
                $("a[data-target='#tablocations']").trigger('click');
                break;
            case "4":
                // Specials
                $("#item_specifics_specials").removeClass("d-none");
                $(".special_prop input").removeClass("disabled").removeAttr("disabled");
                $(".special_prop select").removeClass("disabled").removeAttr("disabled");
                $("a[data-target='#tabspecials']").trigger('click');
                break;
        }
    } else {
        
        // Show the tabs again
        $("#search_tabs").removeClass("d-none");
        
        // Add back the main filters
        $(".book_prop input, \n\
           .event_prop input, \n\
           .people_prop input, \n\
           .location_prop input, \n\
           .special_prop input").removeClass("disabled").removeAttr("disabled");
        $(".book_prop select, \n\
           .event_prop select, \n\
           .people_prop select, \n\
           .location_prop select, \n\
           .special_prop select").removeClass("disabled").removeAttr("disabled");
    }
}


function removeFilter(type, label, force) {
    if (typeof force === "undefined") {
        force = false;
    }
    
    if ((typeof label !== "undefined") && (label !== null)) {
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
                break;
                
            case "specific":
            case "gender":
            case "tribe":
            case "type_location":
            case "type_special":
                // Reset the specifics
                $("#item_" + type).val(-1);
                if (!force) {
                    $("#item_" + type).change();
                }
                break
                
            case "num_chapters":
            case "age":
            case "parent_age":
                // Reset the sliders
                var slider = $("#item_" + type).slider();
                slider.slider('refresh');
                    
                // Set back the color to disabled
                $("#slider_" + type)
                    .find(".slider-selection")
                    .css("background-color", "");
            
                // To make a different between enabled and disabled values
                elementEnabled[type] = false;
                break;
                
            case "length":
            case "date":
            case "profession":
            case "nationality":
                $("#item_" + type).val("");
                break;
        }

        // Remove the [x]
        $("#item_" + type + "_label a").remove();
    
        // Search again (unless we're clearing some filters)
        if (!force) {
            searchItems();
        }
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
    $("#item_length").val(
            session_settings["search_length"] ? 
            session_settings["search_length"] : "");
    $("#item_date").val(
            session_settings["search_date"] ? 
            session_settings["search_date"] : "");
    $("#item_profession").val(
            session_settings["search_profession"] ? 
            session_settings["search_profession"] : "");
    $("#item_nationality").val(
            session_settings["search_nationality"] ? 
            session_settings["search_nationality"] : "");
            
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
    $("#item_gender").val(
            session_settings["search_gender"] ? 
            session_settings["search_gender"] : -1);
    $("#item_tribe").val(
            session_settings["search_tribe"] ? 
            session_settings["search_tribe"] : -1);
    $("#item_type_location").val(
            session_settings["search_type_location"] ? 
            session_settings["search_type_location"] : -1);
    $("#item_type_special").val(
            session_settings["search_type_special"] ? 
            session_settings["search_type_special"] : -1);
            
    // Sliders     
    searchBooks(JSON.stringify({"sliders": ["chapters"]})).then(function(result) {
        
        // No errors and at least 1 item of data
        if (result.records) {
            var data = result.records[0];
            var max = parseInt(data["max_num_chapters"], 10);
            var min = parseInt(Math.max(data["min_num_chapters"], 1), 10);
            
            // Set the max and min values
            var slider_num_chapters = $("#item_num_chapters").slider({
                max: max,
                min: min
            });
            
            // Set the onSlideStop event
            slider_num_chapters.on("slideStop", onSliderChangeNumChapters);

            if (session_settings["search_num_chapters"]) {
                // Initialize the sliders and set their values
                slider_num_chapters.slider("setValue", 
                    [parseInt(session_settings["search_num_chapters"].split('-')[0], 10),
                     parseInt(session_settings["search_num_chapters"].split('-')[1], 10)]);

                // Activate the onchange function
                onSliderChangeNumChapters({value: session_settings["search_num_chapters"].split("-")});
            } else {
                // Initialize the sliders and set their values
                slider_num_chapters.slider("setValue", 
                    [min, max]);
            }
        }
    });
    
    searchPeoples(JSON.stringify({'sliders': 
            ["age",
             "parent_age"]
    })).then(function(result) {
        
        // No errors and at least 1 item of data
        if (result.records) { 
            var data = result.records[0];
            var max1 = parseInt(Math.max(data["max_age"], 1), 10);
            var min1 = parseInt(Math.max(data["min_age"], 1), 10);
            var max2 = parseInt(Math.max(data["max_parent_age"], 1), 10);
            var min2 = parseInt(Math.max(data["min_parent_age"], 1), 10);
            
            // Set the max and min values
            var slider_age = $("#item_age").slider({
                max: max1,
                min: min1
            });
            
            // Set the max and min values
            var slider_parent_age = $("#item_parent_age").slider({
                max: max2,
                min: min2
            });
            
            // Set the onSlideStop event
            slider_age.on("slideStop", onSliderChangeAge);
            slider_parent_age.on("slideStop", onSliderChangeParentAge);

            if (session_settings["search_age"]) {
                slider_age.slider('setValue',
                  [parseInt(session_settings["search_age"].split('-')[0], 10),
                   parseInt(session_settings["search_age"].split('-')[1], 10)]);
                 
                // Activate the onchange function
                onSliderChangeAge({value: session_settings["search_age"].split("-")});
            } else {
                slider_age.slider('setValue',
                  [min1, max1]);
            }

            if (session_settings["search_parent_age"]) {
                slider_parent_age.slider('setValue',
                  [parseInt(session_settings["search_parent_age"].split('-')[0], 10),
                   parseInt(session_settings["search_parent_age"].split('-')[1], 10)]);
                 
                // Activate the onchange function
                onSliderChangeParentAge({value: session_settings["search_parent_age"].split("-")});
            } else {
                slider_parent_age.slider('setValue',
                  [min2, max2]);
            }
        }
    });

    // On change for the different select boxes
    $("#item_start_book").change();
    $("#item_end_book").change();
    $("#item_specific").change();
    $("#item_gender").change();
    $("#item_tribe").change();
    $("#item_type_location").change();
    $("#item_type_special").change();
}

/** Insert the search results of the session */
function insertResults() {
    // Get the data of the books, events, peoples, locations & specials 
    // using the search terms
    searchBooks(getSearchTerms("books")).then(function(result) { insertItems("books", result); });
    searchEvents(getSearchTerms("events")).then(function(result) { insertItems("events", result); });
    searchPeoples(getSearchTerms("peoples")).then(function(result) { insertItems("peoples", result); });
    searchLocations(getSearchTerms("locations")).then(function(result) { insertItems("locations", result); });
    searchSpecials(getSearchTerms("specials")).then(function(result) { insertItems("specials", result); });
}

/** Get all the filters in API compatible format */
function getFilters() {
    // Get all the search terms, and use them to filter out results
    var name =          session_settings["search_name"] ? 
                        session_settings["search_name"] : "";
    var meaning_name =  session_settings["search_meaning_name"] ? 
                        session_settings["search_meaning_name"] : "";
    var descr =         session_settings["search_descr"] ? 
                        session_settings["search_descr"] : "";
            
    // First appearance
    var start_book =    session_settings["search_start_book"] ? 
                        session_settings["search_start_book"] : "";
    var start_chap =    session_settings["search_start_chap"] ? 
                        session_settings["search_start_chap"] : "";
    
    // Last appearance
    var end_book =  session_settings["search_end_book"] ? 
                    session_settings["search_end_book"] : "";
    var end_chap =  session_settings["search_end_chap"] ? 
                    session_settings["search_end_chap"] : "";
            
    // Sliders
    var num_chapters =  session_settings["search_num_chapters"] ? 
                        session_settings["search_num_chapters"] : "";
    var age =   session_settings["search_age"] ? 
                session_settings["search_age"] : "";
    var parent_age =    session_settings["search_parent_age"] ? 
                        session_settings["search_parent_age"] : "";
            
    // String searches
    var length =    session_settings["search_length"] ? 
                    session_settings["search_length"] : "";
    var date =  session_settings["search_date"] ? 
                session_settings["search_date"] : "";
    var profession =    session_settings["search_profession"] ? 
                        session_settings["search_profession"] : "";
    var nationality =   session_settings["search_nationality"] ? 
                        session_settings["search_nationality"] : "";
            
    // Dropdown searches
    var gender =    session_settings["search_gender"] ? 
                    session_settings["search_gender"] : "";
    var tribe = session_settings["search_tribe"] ? 
                session_settings["search_tribe"] : "";
    var type_location = session_settings["search_type_location"] ? 
                        session_settings["search_type_location"] : "";
    var type_special =  session_settings["search_type_special"] ? 
                        session_settings["search_type_special"] : "";
            
    return {
        "name": name,
        "meaning_name": meaning_name,
        "descr": descr,
        "start_book": start_book,
        "start_chap": start_chap,
        "end_book": end_book,
        "end_chap": end_chap,
        "num_chapters": num_chapters,
        "length": length,
        "date": date,
        "age": age,
        "parent_age": parent_age,
        "gender": gender,
        "tribe": tribe,
        "profession": profession,
        "nationality": nationality,
        "type_location": type_location,
        "type_special": type_special
    };
}

/** Get the columns and filters to send to the API 
 * @param {String} type
 * */
function getSearchTerms(type) {
    var search_terms = {};    
    var filter = getFilters();
    
    switch(type) {
        case "books":
            search_terms["name"] = filter.name;
            search_terms["id"] = filter.book_ids;
            search_terms["num_chapters"] = filter.num_chapters;
            break;
            
        case "events":
            search_terms["name"] = filter.name;
            search_terms["descr"] = filter.descr;
            search_terms["length"] = filter.length;
            search_terms["date"] = filter.date;
            search_terms["start_book"] = filter.start_book;
            search_terms["start_chap"] = filter.start_chap;
            search_terms["end_book"] = filter.end_book;
            search_terms["end_chap"] = filter.end_chap;
            break;
            
        case "peoples":
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["age"] = filter.age;
            search_terms["parent_age"] = filter.parent_age;
            search_terms["gender"] = filter.gender;
            search_terms["tribe"] = filter.tribe;
            search_terms["profession"] = filter.profession;
            search_terms["nationality"] = filter.nationality;
            search_terms["start_book"] = filter.start_book;
            search_terms["start_chap"] = filter.start_chap;
            search_terms["end_book"] = filter.end_book;
            search_terms["end_chap"] = filter.end_chap;
            break;
            
        case "locations":
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["type"] = filter.type_location;
            search_terms["start_book"] = filter.start_book;
            search_terms["start_chap"] = filter.start_chap;
            search_terms["end_book"] = filter.end_book;
            search_terms["end_chap"] = filter.end_chap;
            break;
            
        case "specials":
            search_terms["name"] = filter.name;
            search_terms["meaning_name"] = filter.meaning_name;
            search_terms["descr"] = filter.descr;
            search_terms["type"] = filter.type_special;
            search_terms["start_book"] = filter.start_book;
            search_terms["start_chap"] = filter.start_chap;
            search_terms["end_book"] = filter.end_book;
            search_terms["end_chap"] = filter.end_chap;
            break;
    }
    
    // Filter out anything that isn't filled
    for (var key in search_terms) {
        if (search_terms[key] === "") {
            delete search_terms[key];
        }
    }
    
    return JSON.stringify(search_terms);
}

/** Updating the session settings and performing the search */
function searchItems() {
    // The search terms inserted in input boxes or dropdowns    
    var params = {
        "search_name": $("#item_name").val(),
        "search_meaning_name": $("#item_meaning_name").val(),
        "search_descr": $("#item_descr").val(),
        "search_start_book": $("#item_start_book").val(),
        "search_end_book": $("#item_end_book").val(),
        "search_specific": $("#item_specific").val(),
        "search_length": $("#item_length").val(),
        "search_date": $("#item_date").val(),
        "search_gender": $("#item_gender").val(),
        "search_tribe": $("#item_tribe").val(),
        "search_profession": $("#item_profession").val(),
        "search_nationality": $("#item_nationality").val(),
        "search_type_location": $("#item_type_location").val(),
        "search_type_special": $("#item_type_special").val()
    };
    
    // Only if it is initialized to prevent overwriting
    if (elementInit["num_chapters"]) {
        var num_chapters = $("#item_num_chapters").slider('getValue');
        params["search_num_chapters"] = 
                elementEnabled["num_chapters"] ? 
                num_chapters.join('-') : "";
    }
    
    if (elementInit["age"]) {
        var age = $("#item_age").slider('getValue');
        params["search_age"] = 
                elementEnabled["age"] ? 
                age.join('-') : "";
    }
    
    if (elementInit["parent_age"]) {
        var parent_age = $("#item_parent_age").slider('getValue');
        params["search_parent_age"] = 
                elementEnabled["parent_age"] ? 
                parent_age.join('-') : "";
    }
    
    if (elementInit["start"]) {
        var start_chap = $("#item_start_chap").val();
        params["search_start_chap"] = start_chap;
    }
    
    if (elementInit["end"]) {
        var end_chap = $("#item_end_chap").val();
        params["search_end_chap"] = end_chap;
    }
    
    // Update the query to the session
    updateSession(params);
    
    // Recalculate the search results
    insertResults();
}

function onSliderChangeNumChapters(value) {
    onSliderChange('num_chapters', value.value);
}

function onSliderChangeAge(value) {
    onSliderChange('age', value.value);
}

function onSliderChangeParentAge(value) {
    onSliderChange('parent_age', value.value);
}

function onSliderChange(type, value) {
    if (value === "") {
        return;
    }
    
    // Update the query to the session
    var params = {};
    params["search_" + type] = value.join('-');
    updateSession(params);

    // Set the slider as active
    $("#slider_" + type)
            .find(".slider-selection")
            .css("background-color", "#46c1fe");
    
    // Add the [x] to disable the slider
    removeFilter(type, "#item_" + type + "_label");

    // Set the slider as enabled
    elementEnabled[type] = true;
    elementInit[type] = true;
    
    // Recalculate the search results
    insertResults();
    
    return;
}

function onSelectChange(type) {    
    // Update the query to the session
    var value = $("#item_" + type).val();
    if (!value || value === "-1") {
        return;
    }
    
    var params = {};
    params["search_" + type] = value;
    updateSession(params);
    
    // Add the [x] to disable the slider
    removeFilter(type, "#item_" + type + "_label");
    
    // Recalculate the search results
    insertResults();
    
    return;
}

/** Inserting the results in a readable table format 
 * @param {String} type
 * @param {Object} result * 
 * */
function insertItems(type, result) {
    // Start out clean
    $("#tab" + type).empty();
    
    // No errors and at least 1 item of data
    if (result.records) {
        
        // Table header is the name
        var table_header = insertHeader(type, "name");
        table_header += insertHeader(type, "meaning_name");
        table_header += insertHeader(type, "descr");
        table_header += insertHeader(type, "length");
        table_header += insertHeader(type, "date");
        table_header += insertHeader(type, "age");
        table_header += insertHeader(type, "parent_age");
        table_header += insertHeader(type, "gender");
        table_header += insertHeader(type, "tribe");
        table_header += insertHeader(type, "profession");
        table_header += insertHeader(type, "nationality");
        table_header += insertHeader(type, "type");
        table_header += insertHeader(type, "book_start");
        table_header += insertHeader(type, "book_end");
        table_header += insertHeader(type, "num_chapters");
        table_header += insertHeader(type, "link");
        
        var table_row = [];
        for (var i = 0; i < result.records.length; i++) {
            var data = result.records[i];
            
            // Table header is the name
            var table_data = insertData(type, "name", data);
            table_data += insertData(type, "meaning_name", data);
            table_data += insertData(type, "descr", data);
            table_data += insertData(type, "length", data);
            table_data += insertData(type, "date", data);
            table_data += insertData(type, "age", data);
            table_data += insertData(type, "parent_age", data);
            table_data += insertData(type, "gender", data);
            table_data += insertData(type, "tribe", data);
            table_data += insertData(type, "profession", data);
            table_data += insertData(type, "nationality", data);
            table_data += insertData(type, "type", data);
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
        
        var num_columns = table_header.split("><").length;
        
        // This is to sort the results
        $("#tab" + type + " table").DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "order": [[num_columns - (type === "books" ? 1 : 3), 'asc']]
        });
    } else {
        // Error message, because database can't be reached
        $("#tab" + type).append(dict["database.no_results"]);
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
    if (types.includes(type)) {
        if (name === "name") {
            table_data = '<th scope="row">' + data["name"] + (data["aka"] ? " ("+data["aka"] +")" : "") + '</th>';
        } else if (name === "link") {
            table_data = '<td data-order="' + data["id"] + '">' + getLinkToItem(type, data["id"], "self") + '</td>';
        } else if (name === "length") {
            table_data = '<td>' + data["length"] + '</td>';
        } else if (name === "parent_age") {
            if ((data["father_age"] !== "-1") && (data["mother_age"] !== "-1")) {
                table_data = '<td>' + data["father_age"] + ', ' + data["mother_age"] + '</td>';
            } else {
                table_data = '<td>' + Math.max(data["father_age"], data["mother_age"]) + '</td>';
            } 
        } else if (name === "gender") {
            table_data = '<td>' + getGenderString(data["gender"]) + '</td>';
        } else if (name === "tribe") {
            table_data = '<td>' + getTribeString(data["tribe"]) + '</td>';
        } else if (name === "type") {
            table_data = '<td>' + ((type === "locations") ? getTypeLocationString(data["type"]) : getTypeSpecialString(data["type"])) + '</td>';
        } else if (name === "book_start") {
            // Data to order by
            var data_order =
                        ((data["book_start_id"].length < 3) ? 
                            ("0".repeat(3 - data["book_start_id"].length) + data["book_start_id"]) : 
                                                                            data["book_start_id"]) + 
                        ((data["book_start_chap"].length < 3) ? 
                            ("0".repeat(3 - data["book_start_chap"].length) + data["book_start_chap"]) : 
                                                                              data["book_start_chap"]) + 
                        ((data["book_start_vers"].length < 3) ? 
                            ("0".repeat(3 - data["book_start_vers"].length) + data["book_start_vers"]) : 
                                                                              data["book_start_vers"]);
                    
            table_data = '<td data-order="' + data_order + '">' + 
                    dict["books.book_" + data["book_start_id"]] + 
                    " " + data["book_start_chap"] + 
                    ":" + data["book_start_vers"] + 
                '</td>';
        } else if (name === "book_end") {
            // Data to order by
            var data_order =
                        ((data["book_end_id"].length < 3) ? 
                            ("0".repeat(3 - data["book_end_id"].length) + data["book_end_id"]) : 
                                                                          data["book_end_id"]) + 
                        ((data["book_end_chap"].length < 3) ? 
                            ("0".repeat(3 - data["book_end_chap"].length) + data["book_end_chap"]) : 
                                                                            data["book_end_chap"]) + 
                        ((data["book_end_vers"].length < 3) ? 
                            ("0".repeat(3 - data["book_end_vers"].length) + data["book_end_vers"]) : 
                                                                            data["book_end_vers"]);
                                                                      
            table_data = '<td data-order="' + data_order + '">' + 
                    dict["books.book_" + data["book_end_id"]] + 
                    " " + data["book_end_chap"] + 
                    ":" + data["book_end_vers"] + 
                '</td>';
        } else {
            table_data = '<td>' + data[name] + '</td>';
        }
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
                
            case "length":
            case "date":
                types = ["events"];
                break;
                
            case "age":
            case "parent_age":
            case "gender":
            case "tribe":
            case "profession":
            case "nationality":
                types = ["peoples"];
                break;
        }
    }  else if ((name === "type") && (session_settings["search_" + name + "_location"])) {
        types = ["locations"];
    } else if ((name === "type") && (session_settings["search_" + name + "_special"])) {
        types = ["specials"];
    }
    
    return types;
}
    