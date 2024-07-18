<?php

    namespace Parts;
    
    use Shapes\Module;

    class SearchContent extends Module {        
        
        public function getContent() {
            global $dict;
            
            $content = '<div class="col-md-8 col-lg-9">
                    <!-- Search results -->
                    <div class="row">
                        <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                            <!-- Tab selection -->
                            <ul class="nav nav-tabs justify-content-center font-weight-bold" id="search_tabs">
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabbooks">'.$dict["navigation.books"].'</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabevents">'.$dict["navigation.events"].'</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabpeoples">'.$dict["navigation.peoples"].'</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tablocations">'.$dict["navigation.locations"].'</a></li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="" data-target="#tabspecials">'.$dict["navigation.specials"].'</a></li>
                            </ul>

                            <!-- The different tabs -->
                            <div class="tab-content mt-2">
                                <!-- Search explanation -->
                                <div class="tab-pane fade show active" id="tabsearch" role="tabpanel">
                                    <h1>'.$dict["search.title"].'</h1>
                                    <p>'.$dict["search.description"].'</p>
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
                </div>';
            
            return $content;
        }
    }
