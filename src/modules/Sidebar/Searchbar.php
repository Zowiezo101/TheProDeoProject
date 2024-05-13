<?php

    class Searchbar extends Module {
        
        public function getContent() {
            global $dict,
                    $SORT_0_to_9, $SORT_9_to_0,
                    $SORT_A_to_Z, $SORT_Z_to_A;
            
            $search = isset($_SESSION["search"]) ? htmlspecialchars($_SESSION["search"]) : "";
            $sort = isset($_SESSION["sort"]) ? $_SESSION["sort"] : $SORT_0_to_9;
            
            // The search bar
            $content = '
                        <!-- Search bar and sorting -->
                        <div class="row mb-2">
                            <div class="col-8 col-md-6">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control" id="item_search" placeholder="'.$dict["database.search"].'" onkeyup="onSearch()" value="'.$search.'">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" onclick="onSearch()">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-md-6">
                                <div class="btn-group w-100">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.$dict["database.order"].'</button>
                                    <div class="dropdown-menu" id="item_sort"> 
                                        <a id="0_to_9" class="dropdown-item '.($sort == $SORT_0_to_9 ? " active" : "").'" onclick="onSortUpdate()"> '.$dict["order.0_to_9"].' </a>
                                        <a id="9_to_0" class="dropdown-item '.($sort == $SORT_9_to_0 ? " active" : "").'" onclick="onSortUpdate()"> '.$dict["order.9_to_0"].' </a>
                                        <a id="a_to_z" class="dropdown-item '.($sort == $SORT_A_to_Z ? " active" : "").'" onclick="onSortUpdate()"> '.$dict["order.a_to_z"].' </a>
                                        <a id="z_to_a" class="dropdown-item '.($sort == $SORT_Z_to_A ? " active" : "").'" onclick="onSortUpdate()"> '.$dict["order.z_to_a"].' </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    
            return $content;
        }
    }

