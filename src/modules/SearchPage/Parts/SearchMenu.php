<?php

    namespace Parts;
    
    use Shapes\Module;
    use Shapes\InputText;
    use Shapes\InputBook;
//    use Shapes\InputSelect;
//    use Shapes\InputSlider;

    class SearchMenu extends Module {
        
        // TODO: Implement suggestion while typing
        // Basically filtering a specific property while typing
        
        private const HORIZONTAL_RULE = "<hr class='my-1'/>";
        
        public function __construct() {
            parent::__construct();
            $this->addContent($this->searchName());
            $this->addContent(self::HORIZONTAL_RULE);
            $this->addContent($this->searchMeaningName());
            $this->addContent(self::HORIZONTAL_RULE);
            $this->addContent($this->searchDescription());
            $this->addContent(self::HORIZONTAL_RULE);
            $this->addContent($this->searchFirstAppearance());
            $this->addContent(self::HORIZONTAL_RULE);
            $this->addContent($this->searchLastAppearance());
//            $this->addContent(self::HORIZONTAL_RULE);
//            $this->addContent($this->searchSpecific());
    
//    // The elements that need initializing
//    var elementInit = {
//        "start": false,
//        "end": false,
//        "specific": false,
//        "num_chapters": false,
//        "age": false,
//        "age_parents": false
//    };
//
//    // The elements that can be disabled
//    var elementEnabled = {
//        "num_chapters": false,
//        "age": false,
//        "age_parents": false
//    };
//
        }
        
        private function searchName() {            
            return new InputText("name");
        }
        
        private function searchMeaningName() {
            return new InputText("meaning_name");
        }
        
        private function searchDescription() {
            return new InputText("descr");
        }
        
        private function searchFirstAppearance() {
            return new InputBook("start");
        }
        
        private function searchLastAppearance() {
            return new InputBook("end");
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
                        <option value="'. TYPE_BOOK .'">'.$dict["navigation.books"].'</option>
                        <option value="'. TYPE_EVENT .'">'.$dict["navigation.events"].'</option>
                        <option value="'. TYPE_PEOPLE .'">'.$dict["navigation.peoples"].'</option>
                        <option value="'. TYPE_LOCATION .'">'.$dict["navigation.locations"].'</option>
                        <option value="'. TYPE_SPECIAL .'">'.$dict["navigation.specials"].'</option>
                    </select>
                </div>
                
                '.$this->searchBookProperties().'
                '.$this->searchEventProperties().'
                '.$this->searchPeopleProperties().'
                '.$this->searchLocationProperties().'
                '.$this->searchSpecialProperties().'
                
            </div>';
            
            return $content;
        }
        
        private function searchBookProperties() {
            global $dict;
            
            return '<div class="col-md-12 d-none" id="item_specifics_books">
    
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
                </div>';
        }
        
        private function searchEventProperties() {
            global $dict;
            
            return '<div class="col-md-12 d-none" id="item_specifics_events">
    
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
                </div>';
        }
        
        private function searchPeopleProperties() {
            global $dict;
            
            return '<div class="col-md-12 d-none" id="item_specifics_peoples">
    
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
                </div>';
        }
        
        private function searchLocationProperties() {
            global $dict;
            
            return '<div class="col-md-12 d-none" id="item_specifics_locations">    
    
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
                </div>';
        }
        
        private function searchSpecialProperties() {
            global $dict;
            
            return '<div class="col-md-12 d-none" id="item_specifics_specials">
    
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
                </div>';
            
        }
        
        public function getContent() {
            $content = '<div id="search_menu" class="col-md-4 col-lg-3">
                '.parent::getContent().'
            </div>';
            
            return $content;
        }
    }
