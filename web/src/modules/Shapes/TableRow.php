<?php

    namespace Shapes;

    class TableRow extends Module {
        private $title;
        private $data;
        
        public function __construct($params = []) {
            parent::__construct();
            
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "title":
                        $this->setTitle($value);
                        break;
                    case "data":
                        $this->setData($value);
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

        public function setData($data) {
            if (true) {
                // TODO: Check this is a valid value
                $this->data = $data;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {  
            $title = '';
            if (isset($this->title))
            {
                $title = '<th scope="row">'.$this->title.'</th>
                ';
            }            
            return '
            <tr>
                '.$title.'<td>
                    '.$this->data.'
                </td>
            </tr>';
        }
    }
