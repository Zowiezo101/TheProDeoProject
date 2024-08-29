<?php

    namespace Shapes;

    class InputSelect extends Module {
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
            <!-- ".ucfirst($this->name)." -->
            <div class='row pb-2'>
                <div class='col-md-12'>
                    <label class='font-weight-bold'>
                        {$dict["items.{$this->name}"]}:
                    </label>
                </div>
                <div class='col-md-12'>
                    <select class='custom-select search-field' id='item_{$this->name}'>
                        <option selected disabled value='-1'>{$dict["search.select"]}</option>
                    </select>
                </div>
            </div>";
            
            return $content;
        }
    }
