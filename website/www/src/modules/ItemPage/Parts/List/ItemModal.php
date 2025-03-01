<?php

    namespace List;
    
    use Shapes\Module;
    use Shapes\InputText;
    use Shapes\InputBook;
    use Shapes\InputSelect;
    use Shapes\InputSlider;

    class ItemModal extends Module {
        // Properties for this Module
        private $options = null;
        private $filters = [];
        
        private const HORIZONTAL_RULE = "<hr class='my-1'/>";
        
        public const INPUT_TEXT = "input_text";
        public const INPUT_BOOK = "input_book";
        public const INPUT_SELECT = "input_select";
        public const INPUT_SLIDER = "input_slider";
        
        public function __construct($params = []) {
            parent::__construct();
            
            // Parse the parameters given
            $this->getParams($params);
        }
        
        private function getParams($params) {
            foreach($params as $param => $value) {
                switch($param) {
                    case "options":
                        $this->setOptions($value);
                        break;
                    case "filters":
                        $this->setFilters($value);
                        break;
                }
            }
        }

        private function setOptions($options) {
            $this->options = $options;
        }
        
        private function setFilters($filters) {
            $this->filters = $filters;
        }
        
        private function getFilters() {
            $filter_modules = [];
            
            // For each filter
            foreach($this->filters as $filter) {
                // The title and label for the filter
                $name = $filter["name"];
                
                // The type defined the module used for this filter
                $type = $filter["type"];
                
                switch($type) {
                    case self::INPUT_TEXT:
                        $module = new InputText($name);
                        break;
                    
                    case self::INPUT_BOOK:
                        $module = new InputBook($name);
                        break;
                    
                    case self::INPUT_SELECT:
                        $module = new InputSelect($name, $this->options);
                        break;
                    
                    case self::INPUT_SLIDER:
                        $module = new InputSlider($name, $this->options);
                        break;
                }
                
                $filter_modules[] = $module->getContent();
            }
                
            // Put it all together
            $content = implode(self::HORIZONTAL_RULE, $filter_modules);
            return $content;
        }
        
        public function getContent() {
            global $dict;
            $content = '
            <!-- Modal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="height: 100%;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">TODO: (Translate) Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="filter_div">
                                '.$this->getFilters().'
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="onFilterChange()" aria-label="Apply">
                                <span aria-hidden="true">'.$dict["database.search"].'</span>
                            </button>
                            <button type="button" class="btn btn-light" onclick="onFilterReset()" aria-label="Reset">
                                <span aria-hidden="true">TODO: (Translate) Reset filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
            
            return $content;
        }
    }
