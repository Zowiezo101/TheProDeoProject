<?php

    class ItemPages extends Module {
        private $visible;
        private $disable_prev;
        private $count;
        private $page;
        private $disable_next;
        
        public function __construct($count) { 
            parent::__construct();
            
            // The amount of pages
            $this->count = $count;
            
            // If there's only one page available, make the pagination invisible
            $this->visible = $this->count > 1 ? "visible" : "invisible";
            
            // The current selected page
            $this->page = isset($_SESSION["page"]) ? $_SESSION["page"] + 1 : 1;
            
            // Disable the previous buttons if we're already on the first page
            $first_page = $this->page == 0;
            $this->disable_prev = $first_page ? "disabled" : "";
            
            // Disable the next buttons if we're already on the last page
            $last_page = $this->page == $this->count - 1;
            $this->disable_next = $last_page ? "disabled" : "";
        }
        
        public function getContent() {
            global $dict;

            // The pagination content
            $content = '
                        <!-- Pagination -->
                        <div class="row mt-2 '.$this->visible.'" id="item_pagination">
                            <div class="col-md-12">
                                <ul class="pagination mt-2 mb-2 justify-content-center" id="item_pages">
                                    <li class="page-item font-weight-bold '.$this->disable_prev.'" '.$this->disable_prev.'>
                                        <a id="first_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">'.$dict["database.first"].'</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold '.$this->disable_prev.' mr-1" '.$this->disable_prev.'>
                                        <a id="prev_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <div class="form-inline">
                                            <input id="curr_page" class="form-control text-center mx-auto" style="width: 60px;" value="'.$this->page.'" type="number" maxlength="3" id="page_search" onkeyup="onPageUpdate()">
                                            <!-- TODO: Put this somewhere else, so it has the proper space to not fold -->
                                            <label class="text-center mx-1">'.$dict["database.out_of"].'<span id="num_pages" class="ml-1">'.$this->count.'</span></label>
                                        </div>
                                    </li>
                                    <li class="page-item font-weight-bold '.$this->disable_next.' ml-1" '.$this->disable_next.'>
                                        <a id="next_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">»</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold '.$this->disable_next.'" '.$this->disable_next.'>
                                        <a id="last_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">'.$dict["database.last"].'</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                        
            return $content;
        }
    }
