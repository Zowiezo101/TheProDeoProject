<?php

    namespace Shapes;

    class InputSelect extends Module {
        private $name;
        private $types;
        private $session_val;
        
        public function __construct($name, $options) {
            parent::__construct();

            if (isset($options[$name."_types"])) {
                $this->types = $options[$name."_types"];
            } 
            
            // The name of this textbox
            $this->name = $name;
            if ($name === "location" || $name === "special") {
                // The special types select box and locations type select box are just named "type"
                $this->name = "type";
            }
            
            // If there is a value stored in the session, get that value
            $this->session_val = isset($_SESSION[$this->name]) ? htmlspecialchars($_SESSION[$this->name]) : "-1";
        }
        
        public function getContent() {
            global $dict;

            $type_list = [];
            if (isset($this->types)) {
                foreach($this->types as $type) {
                    $type_list[] = "
                            <option value='{$type->type_id}'>
                                ".$dict[$type->type_name]."
                            </option>";
                }
            }
        
            $content = "
            <!-- ".ucfirst($this->name)." -->
            <div class='row pb-2'>
                <div class='col-md-12'>
                    <label class='font-weight-bold'>
                        {$dict["items.{$this->name}"]}
                    </label>
                </div>
                <div class='col-md-12'>
                    <select id='{$this->name}'
                            class='custom-select search-field' 
                            data-type='select'
                            data-val='{$this->session_val}'>
                        <option selected value='-1'>{$dict["search.all"]}</option>
                        ".implode("", $type_list)."
                    </select>
                </div>
            </div>";
            
            return $content;
        }
    }
