<?php

    namespace Shapes;

    class Table extends Module {
        private $title;
        
        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "title":
                        $this->setTitle($value);
                        break;
                }
            }
        }

        public function setTitle($title) {
            if (true) {
                // TODO: Check this is a valid value
                $this->title = $title;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            
            $content = parent::getContent();
            
            return '
                                <div class="row">
                                    <div class="col-lg-11 px-lg-5 px-md-3 text-center">
                                        <p class="lead font-weight-bold mt-4">'.$this->title.'</p>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-borderless">
                                                <tbody>
                                                    '.$content.'
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>';
        }
    }
