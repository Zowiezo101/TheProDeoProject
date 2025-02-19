<?php

    namespace Content;

    use Shapes\Module;
    use Shapes\TableRow;

    class MapPopup extends Module {
        
        private $type = false;

        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "type":
                        $this->setType($value);
                        break;
                }
            }
        }

        public function setType($type) {
            $this->type = $type;
        }

        public function getContent() {
            global $dict;

            // The content is different for every map type
            $content = '';

            switch($this->type) {
                case TYPE_TIMELINE:
                    $content = '
                    <div id="popup_template">
                        <table class="pt-1 table table-striped d-none"> 
                            <tbody>
                                '.(new TableRow(["title" => "descr", "data" => "descr"]))->getContent().'
                                '.(new TableRow(["title" => "length", "data" => "length"]))->getContent().'
                                '.(new TableRow(["title" => "date", "data" => "date"]))->getContent().'
                                '.(new TableRow(["title" => "notes", "data" => "notes"]))->getContent().'
                                '.(new TableRow(["title" => "books", "data" => "books"]))->getContent().'
                            </tbody>
                        </table>
                    </div>';
                    break;

                case TYPE_FAMILYTREE:
                    $content = '
                    <div id="popup_template">
                        <table class="pt-1 table table-striped d-none"> 
                            <tbody>
                                '.(new TableRow(["title" => "descr", "data" => "descr"]))->getContent().'
                                '.(new TableRow(["title" => "meaning_name", "data" => "meaning_name"]))->getContent().'
                                '.(new TableRow(["title" => "aka", "data" => "aka"]))->getContent().'
                                '.(new TableRow(["title" => "father_age", "data" => "father_age"]))->getContent().'
                                '.(new TableRow(["title" => "mother_age", "data" => "mother_age"]))->getContent().'
                                '.(new TableRow(["title" => "age", "data" => "age"]))->getContent().'
                                '.(new TableRow(["title" => "gender", "data" => "gender"]))->getContent().'
                                '.(new TableRow(["title" => "tribe", "data" => "tribe"]))->getContent().'
                                '.(new TableRow(["title" => "profession", "data" => "profession"]))->getContent().'
                                '.(new TableRow(["title" => "nationality", "data" => "nationality"]))->getContent().'
                                '.(new TableRow(["title" => "notes", "data" => "notes"]))->getContent().'
                            </tbody>
                        </table>
                    </div>';
                    break;

                case TYPE_WORLDMAP:
                    //            TODO:
                    //                insertDetail(location, "meaning_name") + 
                    //                insertDetail(location, "aka") + 
                    //                insertDetail(location, "descr") + 
                    //                insertDetail(location, "type") + 
                    //                insertDetail(location, "notes") + 
                    $content = '
                    <div id="popup_template">
                        <table class="pt-1 table table-striped d-none"> 
                            <tbody>
                                '.(new TableRow(["title" => "descr", "data" => "descr"]))->getContent().'
                                '.(new TableRow(["title" => "meaning_name", "data" => "meaning_name"]))->getContent().'
                                '.(new TableRow(["title" => "aka", "data" => "aka"]))->getContent().'
                                '.(new TableRow(["title" => "type", "data" => "type"]))->getContent().'
                                '.(new TableRow(["title" => "notes", "data" => "notes"]))->getContent().'
                                '.(new TableRow(["title" => "coordinates", "data" => "coordinates"]))->getContent().'
                            </tbody>
                        </table>
                    </div>';
                    break;
            }

            return $content;
        }

    }
