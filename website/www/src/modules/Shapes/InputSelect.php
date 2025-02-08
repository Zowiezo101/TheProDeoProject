<?php

    namespace Shapes;

    class InputSelect extends Module {
        private $name;
        private $session_val;
        
        public function __construct($name) {
            parent::__construct();
            
            // The name of this textbox
            $this->name = $name;
            
            // If there is a value stored in the session, get that value
            $this->session_val = isset($_SESSION[$name]) ? htmlspecialchars($_SESSION[$name]) : "";
        }
        
        public function getContent() {
            global $dict;
            
            $content = "
            <!-- ".ucfirst($this->name)." -->
            <div class='row pb-2'>
                <div class='col-md-12'>
                    <label class='font-weight-bold'>
                        {$dict["items.{$this->name}"]}:
                    </label>
                </div>
                <div class='col-md-12'>
                    <select id='item_{$this->name}'
                            class='custom-select search-field' 
                            data-type='select'
                            data-val='{$this->session_val}'>
                        <option selected disabled value='-1'>{$dict["search.select"]}</option>
                    </select>
                </div>
            </div>";
            
            return $content;
        }
    }
