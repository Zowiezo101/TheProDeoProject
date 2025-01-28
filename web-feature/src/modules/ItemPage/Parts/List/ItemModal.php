<?php

    namespace List;
    
    use Shapes\Module;
    use Shapes\InputText;
    use Shapes\InputBook;
    use Shapes\InputSelect;
    use Shapes\InputSlider;

    class ItemModal extends Module {
        // Properties for this Module
        private $filters;
        
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
                    case "filters":
                        $this->setFilters($value);
                        break;
                }
            }
        }
        
        private function setFilters($filters) {
            $this->filters = $filters;
        }
        
        private function getFilters() {
            $filter_modules = [];
            
            /*
             * TODO PHP:
             * - For each filter
             * - Use title as label explaining the filter
             * - Use the type to define the used module
             * - Have an "apply selcted filters" button
             * - Have a cancel button
             * - Have a "remove all filters" button
             * 
             * TODO JS:
             * - For sliders and selects, get infomation from the table
             * - For the apply button, show the amount of results
             * - Apply filters directly when selecting them
             * - 
             */
            
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
                        $module = new InputSelect($name);
                        break;
                    
                    case self::INPUT_SLIDER:
                        $module = new InputSlider($name);
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
                            <h5 class="modal-title" id="filterModalLabel">Modal title</h5>
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
                            <button type="button" class="btn btn-primary" onclick="onFilterChange()" data-dismiss="modal" aria-label="Apply">
                                <span aria-hidden="true">'.$dict["database.search"].'</span>
                            </button>
                            <button type="button" class="btn btn-light" onclick="onFilterReset()" aria-label="Reset">
                                <span aria-hidden="true">TODO: Reset filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
            
            return $content;
        }
    }
