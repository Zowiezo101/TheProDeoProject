<?php

    namespace List;
    
    use Shapes\Module;

    class ItemSearch extends Module {
        
        public function getContent() {
            global $dict;
            
            $search = isset($_SESSION["search"]) ? htmlspecialchars($_SESSION["search"]) : "";
            $sort = isset($_SESSION["sort"]) ? $_SESSION["sort"] : SORT_0_TO_9;
            
            // The search bar
            $content = '
                        <!-- Search bar and sorting -->
                        <div class="row mb-2">
                            <div class="col-8 col-md-6">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control" id="item_search" placeholder="'.$dict["database.search"].'" onkeyup="onSearchUpdate()" value="'.$search.'">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" onclick="onFilter()">
                                            <i class="fa fa-filter"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-md-6">
                                <div class="btn-group w-100">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.$dict["database.order"].'</button>
                                    <div class="dropdown-menu" id="item_sort"> 
                                        <a id="0_to_9" class="dropdown-item '.($sort == SORT_0_TO_9 ? "active" : "").'" onclick="onSortUpdate()"> '.$dict["order.0_to_9"].' </a>
                                        <a id="9_to_0" class="dropdown-item '.($sort == SORT_9_TO_0 ? "active" : "").'" onclick="onSortUpdate()"> '.$dict["order.9_to_0"].' </a>
                                        <a id="a_to_z" class="dropdown-item '.($sort == SORT_A_TO_Z ? "active" : "").'" onclick="onSortUpdate()"> '.$dict["order.a_to_z"].' </a>
                                        <a id="z_to_a" class="dropdown-item '.($sort == SORT_Z_TO_A ? "active" : "").'" onclick="onSortUpdate()"> '.$dict["order.z_to_a"].' </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    
            return $content;
        }
    }
