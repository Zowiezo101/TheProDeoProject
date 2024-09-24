<?php

    namespace Shapes;

    class InputText extends Module {
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
                    <input  id='{$this->name}'
                            class='form-control w-100 search-field'
                            type='text' 
                            value='{$this->session_val}'
                            placeholder='{$dict["database.search"]}' 
                            onkeyup='onTextChange(\"{$this->name}\")'
                            data-type='text' />
                </div>
            </div>";
            
            return $content;
        }
    }
