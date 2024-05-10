<?php 
    require "src/tools/database.php";
    require "src/pages/{$page_id}_static.php"; 
    
    // Some basic stuff
    $page_count = 0;
    $page_size = 10;
    $page_focus = false;
    
    $SORT_0_to_9 = "0_to_9";
    $SORT_9_to_0 = "9_to_0";
    $SORT_A_to_Z = "a_to_z";
    $SORT_Z_to_A = "z_to_a";
    
    // Options for pages
    $search = isset($_SESSION["search"]) ? htmlspecialchars($_SESSION["search"]) : "";
    $sort = isset($_SESSION["sort"]) ? $_SESSION["sort"] : $SORT_0_to_9;
    $page = isset($_SESSION["page"]) ? $_SESSION["page"] : 0;
    
    // The pagination for the sidebar.
    // Updates are done in javascript, but the initial loading is done in PHP
    $data_page = getPage($type, $page, [
        "filter" => $search,
        "sort" => $sort
    ]);
    
    $items = [];
    // There there was an error, keep the page empty
    if (isset($data_page->records) && (!isset($data_page->error) || ($data_page->error == ""))) {
        $items = $data_page->records;
        $page_count = $data_page->paging;
    }
    
    $id = filter_input(INPUT_GET, "id");
    if ($id !== null) {
        $data_item = getItem($type_item, $id);
    }
?>
            <!-- This is for content that remains the same while using this page -->
            <div class="container-fluid">
                <div class="row">
                    <!-- The column with the menu -->
                    <nav id="item_bar" class="col-md-4 col-lg-2 py-3 shadow d-none d-md-block">
                         <!-- Search bar and sorting -->
                        <div class="row mb-2">
                            <div class="col-8 col-md-6">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control" id="item_search" placeholder="<?= $dict["database.search"]; ?>" onkeyup="onSearch()" value="<?= $search; ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" onclick="onSearch()">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 col-md-6">
                                <div class="btn-group w-100">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?= $dict["database.order"]; ?></button>
                                    <div class="dropdown-menu" id="item_sort"> 
                                        <a id="0_to_9" class="dropdown-item <?= $sort == $SORT_0_to_9 ? " active" : ""; ?>" onclick="onSortUpdate()"> <?= $dict["order.0_to_9"]; ?> </a>
                                        <a id="9_to_0" class="dropdown-item <?= $sort == $SORT_9_to_0 ? " active" : ""; ?>" onclick="onSortUpdate()"> <?= $dict["order.9_to_0"]; ?> </a>
                                        <a id="a_to_z" class="dropdown-item <?= $sort == $SORT_A_to_Z ? " active" : ""; ?>" onclick="onSortUpdate()"> <?= $dict["order.a_to_z"]; ?> </a>
                                        <a id="z_to_a" class="dropdown-item <?= $sort == $SORT_Z_to_A ? " active" : ""; ?>" onclick="onSortUpdate()"> <?= $dict["order.z_to_a"]; ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- The list of items -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="list-group text-center" id="item_list">
<?php 
    for ($i = 0; $i < $page_size; $i++) {
        // We want a full page of items inserted. 
        // If there aren't enough items, fill the rest up with blanks
        if ($i < count($items)) { 
            $item = $items[$i];
            
            // The link to refer to
            $href = setParameters("{$page_base_url}/{$item->id}");
            
            // If an option in the sidebar is selected, it needs to be highlighted
            $classes = "list-group-item list-group-item-action";
            if ($id == $item->id) {
                $classes = $classes." active";
            }
            
            // The name to be shown in the sidebar
            $value = $item->name;
            if ($value == "timeline.global") {
                // In case of the timeline, there is a global timeline
                // consisting of all the events
                $value = $dict[$value];
            }
            
            if (isset($item->aka) && $item->aka != "") {
                // The AKA value is only given when searching for a name and there is a hit
                // with an AKA value.
                $value = $value." ({$item->aka})";
            }
            
            $onclick = "showLoadingScreen()";
            if ($page_id === "worldmap") {
                $onclick = "getLinkToMap({$item->id})";
                $href = "javascript: void(0)";
            }
            
            $button = '
                                    <a href="'.$href.'" class="'.$classes.'" onclick="'.$onclick.'">'.$value.'</a>';
        } else {
            $button = '
                                    <a class="list-group-item list-group-item-action invisible"> empty </a>';
        }
        
        echo $button;
    } 
?>    
                                </div>
                            </div>
                        </div>

<?php 
    $disable_pagination = $page_count > 1 ? "visible" : "invisible";
?>

                        <!-- Pagination -->
                        <div class="row mt-2 <?= $disable_pagination; ?>" id="item_pagination">
                            <div class="col-md-12">
                                <ul class="pagination mt-2 mb-2 justify-content-center" id="item_pages">
<?php 
    $first_page = $page == 0;
    $last_page = $page == $page_count - 1;
    
    $disable_prev = $first_page ? "disabled" : "";
    $disable_next = $last_page ? "disabled" : "";
?>
                                    <li class="page-item font-weight-bold <?= $disable_prev; ?>" <?= $disable_prev; ?>>
                                        <a id="first_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary"><?= $dict["database.first"]; ?></span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold <?= $disable_prev; ?> mr-1" <?= $disable_prev; ?>>
                                        <a id="prev_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <div class="form-inline">
                                            <input id="curr_page" class="form-control text-center mx-auto" style="width: 60px;" value="<?= $page + 1; ?>" type="number" maxlength="3" id="page_search" onkeyup="onPageUpdate()">
                                            <!-- Put this somewhere else, so it has the proper space to not fold -->
                                            <label class="text-center mx-1"><?= $dict["database.out_of"]; ?><span id="num_pages" class="ml-1"><?= $page_count; ?></span></label>
                                        </div>
                                    </li>
                                    <li class="page-item font-weight-bold <?= $disable_next; ?> ml-1" <?= $disable_next; ?>>
                                        <a id="next_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary">»</span>
                                        </a>
                                    </li>
                                    <li class="page-item font-weight-bold <?= $disable_next; ?>" <?= $disable_next; ?>>
                                        <a id="last_page" class="page-link" onclick="onPageUpdate()">
                                            <span class="text-primary"><?= $dict["database.last"]; ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
        
                    <!-- The column with the selected content -->
                    <div id="content_col" class="col pt-3 pb-5">
                        <div id="content_row" class="row h-100 d-none d-md-flex">
<?php
    if (($id == null) && ($page_id !== "worldmap")) { 
        // TODO: Explain about the dutch names when other language names aren't available
?>
                            <div id="item_content" class="col-12 h-100">
                                <div class="row text-center justify-content-center">
                                    <div class="col-lg-11 px-lg-5 px-md-3">
                                        <h1 class="mb-3"><?= $dict["navigation.{$page_id}"]; ?></h1>
                                        <p class="lead"><?= $dict["{$page_id}.overview"]; ?></p>
                                    </div>
                                </div>
                            </div>
<?php 
    } else if ($page_id === "worldmap") {
?>
                            <div id="item_content" class="col-12 h-100">
                            </div>
<?php
    } else {
?>
                            <div id="item_content" class="col-12 h-100 invisible">
                                <?= insertContent($data_item); ?>
                            </div>
                            <div id="loading_screen" class="col-12 w-75 text-center" style="
                                            position: absolute;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                            -webkit-transform: translate(-50%, -50%) ">
                                <p><?= $dict["loading.{$page_id}"]; ?></p>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only"><?= $dict["loading"]?></span>
                                </div>
                            </div>
<?php 
    }
?>
                            
                            <!-- This button is used to collapse the sidebar -->
                            <button id="toggle_menu" class="btn btn-secondary show_menu d-none d-md-block" onclick="onMenuToggle()" style="
                                            margin-top: 15px;
                                            position: absolute;
                                            border-top-left-radius: 0px;
                                            border-bottom-left-radius: 0px; ">
                                <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="d-md-none">
                            <div class="row text-center justify-content-center">
                                <div class="col-lg-11 px-lg-5 px-md-3">
                                    <h1 class="mb-3"><?= $dict["navigation.{$page_id}"]; ?></h1>
                                    <p class="lead"><?= $dict["small_screen.descr"]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
