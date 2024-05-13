<?php

    class Pagination extends Module {
        private $count;
        
        public function __construct($params = []) { 
            // Parse the parameters given   
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "count":
                        $this->setCount($value);
                        break;
                }
            }
        }
        
        public function setCount($count) {
            if (true) {
                // TODO: Check this is a valid value
                $this->count = $count;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            global $dict;
            
            // If there's only one page available, make the pagination invisible
            $visible = $this->count > 1 ? "visible" : "invisible";
            
            // The current selected page
            $page = isset($_SESSION["page"]) ? $_SESSION["page"] : 0;
            
            // Disable the previous buttons if we're already on the first page
            $first_page = $page == 0;
            $disable_prev = $first_page ? "disabled" : "";
            
            // Disable the next buttons if we're already on the last page
            $last_page = $page == $this->count - 1;
            $disable_next = $last_page ? "disabled" : "";

            // The pagination content
            $content = '
                        <!-- Pagination -->
                        <div class="row mt-2 '.$visible.'" id="item_pagination">
                            <div class="col-md-12">
                                <ul class="pagination mt-2 mb-2 justify-content-center" id="item_pages">
                                    <li class="page-item font-weight-bold '.$disable_prev.'" '.$disable_prev.'>
                                        <a id="first_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">'.$dict["database.first"].'</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold '.$disable_prev.' mr-1" '.$disable_prev.'>
                                        <a id="prev_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <div class="form-inline">
                                            <input id="curr_page" class="form-control text-center mx-auto" style="width: 60px;" value="'.($page + 1).'" type="number" maxlength="3" id="page_search" onkeyup="onPageUpdate()">
                                            <!-- TODO: Put this somewhere else, so it has the proper space to not fold -->
                                            <label class="text-center mx-1">'.$dict["database.out_of"].'<span id="num_pages" class="ml-1">'.$this->count.'</span></label>
                                        </div>
                                    </li>
                                    <li class="page-item font-weight-bold '.$disable_next.' ml-1" '.$disable_next.'>
                                        <a id="next_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">»</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold '.$disable_next.'" '.$disable_next.'>
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
