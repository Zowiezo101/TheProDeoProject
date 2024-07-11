<?php

    namespace Content;
    
    use Shapes\Module;
    use Shapes\Table;
    use Shapes\TableRow;

    class MapTable extends Module {
        private $title;
        private $type;
        private $record;
        
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
                    case "type":
                        $this->setType($value);
                        break;
                    case "record":
                        $this->setRecord($value);
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

        public function setType($type) {
            if (true) {
                // Pass these parameters to the PageList and ItemContent
                $this->type = $type;
            } else {
                // TODO: Throw an error
            }
        }

        public function setRecord($record) {
            if (true) {
                // TODO: Check this is a valid value
                $this->record = $record;
            } else {
                // TODO: Throw an error
            }
        }
        
        public function getContent() {
            $table = new Table([
                "title" => $this->title
            ]);
            
            switch($this->type) {
                case TYPE_FAMILYTREE:
                    $data = $this->getFamilytrees();
                    break;
                
                case TYPE_TIMELINE:
                    $data = $this->getTimelines();
                    break;
                
                default:
                    $data = [];
                    break;
            }
            
            // All the maps this item is a part of
            foreach($data as $row_data) {
                // Get the link to this map
                $href = setParameters("{$this->type}/map/{$row_data["map_id"]}");
                if (isset($row_data["item_id"])) {
                    // Pan to this item if applicable
                    $href = $href."?panTo=".$row_data["item_id"];
                }
                
                // The name of this map
                $title = $row_data["title"];
                
                // Wrap the info in a link
                $link = '<a href="'.$href.'" target="_blank" class="font-weight-bold">
                        '.$title.'
                    </a>';
                
                $table->addContent(new TableRow([
                    "data" => $link
                ]));
            }
            
            $content = '';
            if ($data !== []) {
                $content = $table->getContent();
            }
            return $content;
            
        }
        
        private function getTimelines() {
            global $dict;
        
            // The timeline consists of the global timeline and the separate timelines
            // They don't overlap and thus there is always just two
            $data = [
                [
                    // The global timeline
                    "title" => $dict["timeline.global"],
                    "map_id" => "-999",
                    "item_id" => $this->record->id
                ],
                [
                    // The local timeline
                    "title" => $this->record->name,
                    "map_id" => $this->record->id
                ]
            ];
            
            return $data;
        }
        
        private function getFamilytrees() {
            global $dict;
            
            $data = [];
            
            $ancestors = getMaps(TYPE_PEOPLE, $this->record->id);
            if ($this->checkData($ancestors)) {
                foreach($ancestors->records as $ancestor) {
                    $data[] = [
                        "title" => $ancestor->name,
                        "map_id" => $ancestor->id,
                        "item_id" => $this->record->id
                    ];
                }
            }
            
            if (count($this->record->children) > 0 && count($this->record->parents) > 0) {
                $data[] = [
                    "title" => $this->record->name.$dict["items.parent.familytree"],
                    "map_id" => $this->record->id,
                    "item_id" => $this->record->id
                ];
            }
            
            return $data;
        }
    }
