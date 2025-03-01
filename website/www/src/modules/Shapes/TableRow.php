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
            $this->title = $title;
        }

        public function setData($data) {
            $this->data = $data;
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
