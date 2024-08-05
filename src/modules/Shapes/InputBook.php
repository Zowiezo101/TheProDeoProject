<?php

    namespace Shapes;

    class InputBook extends Module {
        private $name;
        private $session_val;
        private $item_type;
        
        public function __construct($name, $item_type = null) {
            parent::__construct();
            
            // The name of this textbox
            $this->name = $name;
            
            // If there is a value stored in the session, get that value
            $this->session_val = isset($_SESSION[$name]) ? htmlspecialchars($_SESSION[$name]) : "";
            
            // The item type this textbox will apply to
            // If none is given, it applies to all
            $this->item_type = isset($item_type) ? $item_type : "";
        }
        
        public function getContent() {
            global $dict;
            
            $content = "
            <!-- ".ucfirst($this->name)." appearance -->    
            <div class='row pb-2'>
                <div class='col-md-12'>
                    <label class='font-weight-bold'>{$dict["items.book_{$this->name}"]}:
                    </label>
                </div>
    
                <div class='col-md-6'>
                    <select id='book_{$this->name}'
                            class='custom-select search-field'
                            onchange='insertChapters()'
                            data-item-type='{$this->item_type}'
                            data-input-type='select'>
                        <option selected disabled value='-1'>{$dict["books.book"]}</option>
                        <!-- Filled in later -->
                        <!--<option data-num-chapters='50' value='1'>{$dict["books.book_1"]}</option>-->
                    </select>
                </div>

                <div class='col-md-6'>
                    <select id='chap_{$this->name}'
                            class='custom-select search-field'  
                            onchange='onSearch()'>
                            data-item-type='{$this->item_type}'
                            data-input-type='select'>
                        <option selected disabled value='-1'>{$dict["books.chapter"]}</option>
                        <!-- Filled in later -->
                    </select>
                </div>
            </div>";
            
            return $content;
        }
    }
