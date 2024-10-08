<?php

    namespace List;
    
    use Shapes\Module;

    class ItemListToggle extends Module {
        public function getContent() {
            // A simple module used to create a button
            // This button can be used to either show or hide the PageList
            $content = '
                            <!-- This button is used to collapse the sidebar -->
                            <button id="toggle_menu" class="btn btn-secondary show_menu d-none d-md-block" onclick="onMenuToggle()" style="
                                            margin-top: 15px;
                                            position: absolute;
                                            border-top-left-radius: 0px;
                                            border-bottom-left-radius: 0px; ">
                                <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                            </button>';
            
            return $content;
        }
    }
