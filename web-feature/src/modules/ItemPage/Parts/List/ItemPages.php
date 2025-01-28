<?php

    namespace List;
    
    use Shapes\Module;

    class ItemPages extends Module {        
        public function __construct() { 
            parent::__construct();
        }
        
        public function getContent() {
            // The pagination content
            $content = '
                        <!-- Pagination -->
                        <div class="row mt-2 invisible" id="item_pagination">
                            <div class="col-md-12">
                                <ul class="pagination mt-2 mb-2 justify-content-center" id="item_pages">
                                    <li class="page-item font-weight-bold">
                                        <a id="first_page" class="page-link" onclick="onFirstPage()">
                                            <span class="text-primary">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold mr-1">
                                        <a id="prev_page" class="page-link" onclick="onPrevPage()">
                                            <span class="text-primary">‹</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <div class="input-group">
                                            <input 
                                                id="curr_page" 
                                                class="form-control text-center"
                                                style="width: 60px;"
                                                type="number" 
                                                maxlength="3" 
                                                onkeyup="onCustomPage()">
                                            </input>
                                            <div class="input-group-append">
                                                <span 
                                                    class="input-group-text"
                                                    style="width: 60px;" 
                                                    id="num_pages">
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="page-item font-weight-bold ml-1">
                                        <a id="next_page" class="page-link" onclick="onNextPage()">
                                            <span class="text-primary">›</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold">
                                        <a id="last_page" class="page-link" onclick="onLastPage()">
                                            <span class="text-primary">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                        
            return $content;
        }
    }
