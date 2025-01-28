<?php

    namespace Content;
    
    use Shapes\Module;

    class Modal extends Module {
        
        public function getContent() {
            $content = '<!-- Modal -->
            <div class="modal fade pr-0 vh-100" id="subMapModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="subMapModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" style="min-width:90%; height: 90%;">
                    <div class="modal-content" style="height: 100%;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subMapModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0" style="height: 100%;">
                            <div id="map_sub_div" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>';
            
            return $content;
        }
    }
