<?php

    class SVG extends Module {
        public function getContent() {
            $content = '<div class="row min-vh-75">
                <div class="col text-center">
                    <div id="map_div" style="height: 100%;">

                    </div>
                    <div id="map_download" class="d-none">
                        <!-- Used for downloading the SVG -->
                    </div>
                </div>
            </div>';
            
            return $content;
        }
    }

