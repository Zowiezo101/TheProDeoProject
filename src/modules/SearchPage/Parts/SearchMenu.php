<?php

    namespace Parts;
    
    use Shapes\Module;

    class SearchMenu extends Module {
        
        private $horizontal_rule = '<hr class="my-1"/>';
        
        public function __construct() {
            parent::__construct();
            $this->addContent($this->searchName());
            $this->addContent($this->horizontal_rule);
            $this->addContent($this->searchMeaningName());
            $this->addContent($this->horizontal_rule);
            $this->addContent($this->searchDescription());
            $this->addContent($this->horizontal_rule);
            $this->addContent($this->searchFirstAppearance());
            $this->addContent($this->horizontal_rule);
            $this->addContent($this->searchLastAppearance());
            $this->addContent($this->horizontal_rule);
            $this->addContent($this->searchSpecific());
            
            // TODO: End this with a insertSearch()
        }
        
        private function searchName() {
            global $dict;
            
            $content = '
            <!-- Search bar -->
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" id="item_name" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="onSearch()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
            
            return $content;
        }
        
        private function searchMeaningName() {
            global $dict;
            
            $content = '
            <!-- Meaning name -->
            <div class="row people_prop location_prop special_prop">
                <div class="col-md-12">
                    <label class="font-weight-bold">'.$dict["items.meaning_name"].':
                    </label>
                </div>
                <div class="col-md-12">
                    <form class="form-inline" onSubmit="return false;">
                        <input type="text" class="form-control w-100" id="item_meaning_name" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                    </form>
                </div>
            </div>';
            
            return $content;
        }
        
        private function searchDescription() {
            global $dict;
            
            $content = '
            <!-- Description -->
            <div class="row event_prop people_prop location_prop special_prop">
                <div class="col-md-12">
                    <label class="font-weight-bold">'.$dict["items.descr"].':
                    </label>
                </div>
                <div class="col-md-12">
                    <form class="form-inline" onSubmit="return false;">
                        <input type="text" class="form-control w-100" id="item_descr" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                    </form>
                </div>
            </div>';
            
            return $content;
        }
        
        private function searchFirstAppearance() {
            global $dict;
            
            $content = '
            <!-- First appearance -->    
            <div class="row pb-2 event_prop people_prop location_prop special_prop">
                <div class="col-md-12">
                    <label class="font-weight-bold" id="item_start_label">'.$dict["items.book_start"].':
                    </label>
                </div>
    
                <div class="col-md-6">
                    <select class="custom-select" id="item_start_book" onchange="insertChapters(\'start\')">
                        <option selected disabled value="-1">'.$dict["books.book"].'</option>
                        <option data-num-chapters="50" value="1">'.$dict["books.book_1"].'</option>
                        <option data-num-chapters="40" value="2">'.$dict["books.book_2"].'</option>
                        <option data-num-chapters="27" value="3">'.$dict["books.book_3"].'</option>
                        <option data-num-chapters="36" value="4">'.$dict["books.book_4"].'</option>
                        <option data-num-chapters="34" value="5">'.$dict["books.book_5"].'</option>
                        <option data-num-chapters="24" value="6">'.$dict["books.book_6"].'</option>
                        <option data-num-chapters="21" value="7">'.$dict["books.book_7"].'</option>
                        <option data-num-chapters="4" value="8">'.$dict["books.book_8"].'</option>
                        <option data-num-chapters="31" value="9"> '.$dict["books.book_9"].'</option>
                        <option data-num-chapters="24" value="10">'.$dict["books.book_10"].'</option>
                        <option data-num-chapters="22" value="11">'.$dict["books.book_11"].'</option>
                        <option data-num-chapters="25" value="12">'.$dict["books.book_12"].'</option>
                        <option data-num-chapters="29" value="13">'.$dict["books.book_13"].'</option>
                        <option data-num-chapters="36" value="14">'.$dict["books.book_14"].'</option>
                        <option data-num-chapters="10" value="15">'.$dict["books.book_15"].'</option>
                        <option data-num-chapters="13" value="16">'.$dict["books.book_16"].'</option>
                        <option data-num-chapters="10" value="17">'.$dict["books.book_17"].'</option>
                        <option data-num-chapters="42" value="18">'.$dict["books.book_18"].'</option>
                        <option data-num-chapters="150" value="19">'.$dict["books.book_19"].'</option>
                        <option data-num-chapters="31" value="20">'.$dict["books.book_20"].'</option>
                        <option data-num-chapters="12" value="21">'.$dict["books.book_21"].'</option>
                        <option data-num-chapters="8" value="22">'.$dict["books.book_22"].'</option>
                        <option data-num-chapters="66" value="23">'.$dict["books.book_23"].'</option>
                        <option data-num-chapters="52" value="24">'.$dict["books.book_24"].'</option>
                        <option data-num-chapters="5" value="25">'.$dict["books.book_25"].'</option>
                        <option data-num-chapters="48" value="26">'.$dict["books.book_26"].'</option>
                        <option data-num-chapters="12" value="27">'.$dict["books.book_27"].'</option>
                        <option data-num-chapters="14" value="28">'.$dict["books.book_28"].'</option>
                        <option data-num-chapters="4" value="29">'.$dict["books.book_29"].'</option>
                        <option data-num-chapters="9" value="30">'.$dict["books.book_30"].'</option>
                        <option data-num-chapters="1" value="31">'.$dict["books.book_31"].'</option>
                        <option data-num-chapters="4" value="32">'.$dict["books.book_32"].'</option>
                        <option data-num-chapters="7" value="33">'.$dict["books.book_33"].'</option>
                        <option data-num-chapters="3" value="34">'.$dict["books.book_34"].'</option>
                        <option data-num-chapters="3" value="35">'.$dict["books.book_35"].'</option>
                        <option data-num-chapters="3" value="36">'.$dict["books.book_36"].'</option>
                        <option data-num-chapters="2" value="37">'.$dict["books.book_37"].'</option>
                        <option data-num-chapters="14" value="38">'.$dict["books.book_38"].'</option>
                        <option data-num-chapters="3" value="39">'.$dict["books.book_39"].'</option>
                        <option data-num-chapters="28" value="40">'.$dict["books.book_40"].'</option>
                        <option data-num-chapters="16" value="41">'.$dict["books.book_41"].'</option>
                        <option data-num-chapters="24" value="42">'.$dict["books.book_42"].'</option>
                        <option data-num-chapters="21" value="43">'.$dict["books.book_43"].'</option>
                        <option data-num-chapters="28" value="44">'.$dict["books.book_44"].'</option>
                        <option data-num-chapters="16" value="45">'.$dict["books.book_45"].'</option>
                        <option data-num-chapters="16" value="46">'.$dict["books.book_46"].'</option>
                        <option data-num-chapters="13" value="47">'.$dict["books.book_47"].'</option>
                        <option data-num-chapters="6" value="48">'.$dict["books.book_48"].'</option>
                        <option data-num-chapters="6" value="49">'.$dict["books.book_49"].'</option>
                        <option data-num-chapters="4" value="50">'.$dict["books.book_50"].'</option>
                        <option data-num-chapters="4" value="51">'.$dict["books.book_51"].'</option>
                        <option data-num-chapters="5" value="52">'.$dict["books.book_52"].'</option>
                        <option data-num-chapters="3" value="53">'.$dict["books.book_53"].'</option>
                        <option data-num-chapters="6" value="54">'.$dict["books.book_54"].'</option>
                        <option data-num-chapters="4" value="55">'.$dict["books.book_55"].'</option>
                        <option data-num-chapters="3" value="56">'.$dict["books.book_56"].'</option>
                        <option data-num-chapters="1" value="57">'.$dict["books.book_57"].'</option>
                        <option data-num-chapters="13" value="58">'.$dict["books.book_58"].'</option>
                        <option data-num-chapters="5" value="59">'.$dict["books.book_59"].'</option>
                        <option data-num-chapters="5" value="60">'.$dict["books.book_60"].'</option>
                        <option data-num-chapters="3" value="61">'.$dict["books.book_61"].'</option>
                        <option data-num-chapters="5" value="62">'.$dict["books.book_62"].'</option>
                        <option data-num-chapters="1" value="63">'.$dict["books.book_63"].'</option>
                        <option data-num-chapters="1" value="64">'.$dict["books.book_64"].'</option>
                        <option data-num-chapters="1" value="65">'.$dict["books.book_65"].'</option>
                        <option data-num-chapters="22" value="66">'.$dict["books.book_66"].'</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <select class="custom-select" id="item_start_chap" onchange="onSearch()">
                        <option selected disabled value="-1">'.$dict["books.chapter"].'</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>';
            
            return $content;
        }
        
        private function searchLastAppearance() {
            global $dict;
            
            $content = '
            <!-- Last appearance -->
            <div class="row pb-2 event_prop people_prop location_prop special_prop">
                <div class="col-md-12">
                    <label class="font-weight-bold" id="item_end_label">'.$dict["items.book_end"].':
                    </label>
                </div>
    
                <div class="col-md-6">
                    <select class="custom-select" id="item_end_book" onchange="insertChapters(\'end\')">
                        <option selected disabled value="-1">'.$dict["books.book"].'</option>
                        <option data-num-chapters="50" value="1">'.$dict["books.book_1"].'</option>
                        <option data-num-chapters="40" value="2">'.$dict["books.book_2"].'</option>
                        <option data-num-chapters="27" value="3">'.$dict["books.book_3"].'</option>
                        <option data-num-chapters="36" value="4">'.$dict["books.book_4"].'</option>
                        <option data-num-chapters="34" value="5">'.$dict["books.book_5"].'</option>
                        <option data-num-chapters="24" value="6">'.$dict["books.book_6"].'</option>
                        <option data-num-chapters="21" value="7">'.$dict["books.book_7"].'</option>
                        <option data-num-chapters="4" value="8">'.$dict["books.book_8"].'</option>
                        <option data-num-chapters="31" value="9"> '.$dict["books.book_9"].'</option>
                        <option data-num-chapters="24" value="10">'.$dict["books.book_10"].'</option>
                        <option data-num-chapters="22" value="11">'.$dict["books.book_11"].'</option>
                        <option data-num-chapters="25" value="12">'.$dict["books.book_12"].'</option>
                        <option data-num-chapters="29" value="13">'.$dict["books.book_13"].'</option>
                        <option data-num-chapters="36" value="14">'.$dict["books.book_14"].'</option>
                        <option data-num-chapters="10" value="15">'.$dict["books.book_15"].'</option>
                        <option data-num-chapters="13" value="16">'.$dict["books.book_16"].'</option>
                        <option data-num-chapters="10" value="17">'.$dict["books.book_17"].'</option>
                        <option data-num-chapters="42" value="18">'.$dict["books.book_18"].'</option>
                        <option data-num-chapters="150" value="19">'.$dict["books.book_19"].'</option>
                        <option data-num-chapters="31" value="20">'.$dict["books.book_20"].'</option>
                        <option data-num-chapters="12" value="21">'.$dict["books.book_21"].'</option>
                        <option data-num-chapters="8" value="22">'.$dict["books.book_22"].'</option>
                        <option data-num-chapters="66" value="23">'.$dict["books.book_23"].'</option>
                        <option data-num-chapters="52" value="24">'.$dict["books.book_24"].'</option>
                        <option data-num-chapters="5" value="25">'.$dict["books.book_25"].'</option>
                        <option data-num-chapters="48" value="26">'.$dict["books.book_26"].'</option>
                        <option data-num-chapters="12" value="27">'.$dict["books.book_27"].'</option>
                        <option data-num-chapters="14" value="28">'.$dict["books.book_28"].'</option>
                        <option data-num-chapters="4" value="29">'.$dict["books.book_29"].'</option>
                        <option data-num-chapters="9" value="30">'.$dict["books.book_30"].'</option>
                        <option data-num-chapters="1" value="31">'.$dict["books.book_31"].'</option>
                        <option data-num-chapters="4" value="32">'.$dict["books.book_32"].'</option>
                        <option data-num-chapters="7" value="33">'.$dict["books.book_33"].'</option>
                        <option data-num-chapters="3" value="34">'.$dict["books.book_34"].'</option>
                        <option data-num-chapters="3" value="35">'.$dict["books.book_35"].'</option>
                        <option data-num-chapters="3" value="36">'.$dict["books.book_36"].'</option>
                        <option data-num-chapters="2" value="37">'.$dict["books.book_37"].'</option>
                        <option data-num-chapters="14" value="38">'.$dict["books.book_38"].'</option>
                        <option data-num-chapters="3" value="39">'.$dict["books.book_39"].'</option>
                        <option data-num-chapters="28" value="40">'.$dict["books.book_40"].'</option>
                        <option data-num-chapters="16" value="41">'.$dict["books.book_41"].'</option>
                        <option data-num-chapters="24" value="42">'.$dict["books.book_42"].'</option>
                        <option data-num-chapters="21" value="43">'.$dict["books.book_43"].'</option>
                        <option data-num-chapters="28" value="44">'.$dict["books.book_44"].'</option>
                        <option data-num-chapters="16" value="45">'.$dict["books.book_45"].'</option>
                        <option data-num-chapters="16" value="46">'.$dict["books.book_46"].'</option>
                        <option data-num-chapters="13" value="47">'.$dict["books.book_47"].'</option>
                        <option data-num-chapters="6" value="48">'.$dict["books.book_48"].'</option>
                        <option data-num-chapters="6" value="49">'.$dict["books.book_49"].'</option>
                        <option data-num-chapters="4" value="50">'.$dict["books.book_50"].'</option>
                        <option data-num-chapters="4" value="51">'.$dict["books.book_51"].'</option>
                        <option data-num-chapters="5" value="52">'.$dict["books.book_52"].'</option>
                        <option data-num-chapters="3" value="53">'.$dict["books.book_53"].'</option>
                        <option data-num-chapters="6" value="54">'.$dict["books.book_54"].'</option>
                        <option data-num-chapters="4" value="55">'.$dict["books.book_55"].'</option>
                        <option data-num-chapters="3" value="56">'.$dict["books.book_56"].'</option>
                        <option data-num-chapters="1" value="57">'.$dict["books.book_57"].'</option>
                        <option data-num-chapters="13" value="58">'.$dict["books.book_58"].'</option>
                        <option data-num-chapters="5" value="59">'.$dict["books.book_59"].'</option>
                        <option data-num-chapters="5" value="60">'.$dict["books.book_60"].'</option>
                        <option data-num-chapters="3" value="61">'.$dict["books.book_61"].'</option>
                        <option data-num-chapters="5" value="62">'.$dict["books.book_62"].'</option>
                        <option data-num-chapters="1" value="63">'.$dict["books.book_63"].'</option>
                        <option data-num-chapters="1" value="64">'.$dict["books.book_64"].'</option>
                        <option data-num-chapters="1" value="65">'.$dict["books.book_65"].'</option>
                        <option data-num-chapters="22" value="66">'.$dict["books.book_66"].'</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <select class="custom-select" id="item_end_chap" onchange="onSearch()">
                        <option selected disabled value="-1">'.$dict["books.chapter"].'</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>';
            
            return $content;
        }
        
        private function searchSpecific() {
            // TODO: Divide these into separate functions as well for better readability
            global $dict;
            
            $content = '
            <!-- Specific search options for -->
            <div class="row">
                <div class="col-md-12">
                    <label class="font-weight-bold" id="item_specific_label">'.$dict["search.specific_for"].'
                    </label>
                </div>
    
                <div class="col-md-12 pb-2">
                    <select class="custom-select" id="item_specific" onchange="insertSpecifics()">
                        <option selected disabled value="-1">'.$dict["search.select"].'</option>
                        <option value="0">'.$dict["navigation.books"].'</option>
                        <option value="1">'.$dict["navigation.events"].'</option>
                        <option value="2">'.$dict["navigation.peoples"].'</option>
                        <option value="3">'.$dict["navigation.locations"].'</option>
                        <option value="4">'.$dict["navigation.specials"].'</option>
                    </select>
                </div>
    
                <div class="col-md-12 d-none" id="item_specifics_books">
    
                    <hr class="my-1"/>
    
                    <!-- Number of chapters -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_num_chapters_label">'.$dict["items.num_chapters"].'
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
                            <label class="font-weight-bold" id="item_length_label">'.$dict["items.length"].'
                            </label>
                        </div>

                        <div class="col-md-12">
                            <form class="form-inline" onSubmit="return false;">
                                <input type="text" class="form-control w-100" id="item_length" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                            </form>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
                    
                    <!-- Date -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_date_label">'.$dict["items.date"].'
                            </label>
                        </div>
    
                        <div class="col-md-12">
                            <form class="form-inline" onSubmit="return false;">
                                <input type="text" class="form-control w-100" id="item_date" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                            </form>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-12 d-none" id="item_specifics_peoples">
    
                    <hr class="my-1"/>
    
                    <!-- Reached age -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_age_label">'.$dict["items.age"].'
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
                            <label class="font-weight-bold" id="item_parent_age_label">'.$dict["items.parent_age"].'
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
                            <label class="font-weight-bold" id="item_gender_label">'.$dict["items.gender"].'
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_gender" onchange="onSelectChange(\'gender\')">
                                <option selected disabled value="-1">'.$dict["search.select"].'</option>
                            </select>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Tribe -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_tribe_label">'.$dict["items.tribe"].'
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_tribe" onchange="onSelectChange(\'tribe\')">
                                <option selected disabled value="-1">'.$dict["search.select"].'</option>
                            </select>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Profession -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_profession_label">'.$dict["items.profession"].'
                            </label>
                        </div>
                        <div class="col-md-12">
                            <form class="form-inline" onSubmit="return false;">
                                <input type="text" class="form-control w-100" id="item_profession" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                            </form>
                        </div>
                    </div>
    
                    <hr class="my-1"/>
    
                    <!-- Nationality -->
                    <div class="row">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_nationality_label">'.$dict["items.nationality"].'
                            </label>
                        </div>
                        <div class="col-md-12">
                            <form class="form-inline" onSubmit="return false;">
                                <input type="text" class="form-control w-100" id="item_nationality" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()">
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 d-none" id="item_specifics_locations">    
    
                    <hr class="my-1"/>
    
                    <!-- Type -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_type_location_label">'.$dict["items.type"].'
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_type_location" onchange="onSelectChange(\'type_location\')">
                                <option selected disabled value="-1">'.$dict["search.select"].'</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 d-none" id="item_specifics_specials">
    
                    <hr class="my-1"/>
    
                    <!-- Type -->
                    <div class="row pb-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold" id="item_type_special_label">'.$dict["items.type"].'
                            </label>
                        </div>
                        <div class="col-md-12">
                            <select class="custom-select" id="item_type_special" onchange="onSelectChange(\'type_special\')">
                                <option selected disabled value="-1">'.$dict["search.select"].'</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>';
            
            return $content;
        }
        
        public function getContent() {
            $content = '<div id="search_menu" class="col-md-4 col-lg-3">
                '.parent::getContent().'
            </div>';
            
            return $content;
        }
    }
