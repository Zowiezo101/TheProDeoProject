<?php

    namespace Shapes;

    class InputSlider extends Module {
        private $name;
        private $session_slider_val;
        private $session_check_val;
        
        public function __construct($name) {
            parent::__construct();
            
            // The name of this textbox
            $this->name = $name;
            
            // If there is a value stored in the session, get that value
            // TODO: Get the checkbox value too
            $this->session_slider_val = isset($_SESSION[$name]) ? htmlspecialchars($_SESSION[$name]) : "0,0";
            $this->session_check_val = isset($_SESSION["check_$name"]) ? htmlspecialchars($_SESSION["check_$name"]) : "";
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
                            class='d-none search-field'
                            type='text' 
                            value=''
                            onchange='onFilterChange()'
                            data-slider-id='slider_{$this->name}'
                            data-slider-tooltip-split='true'
                            data-slider-step='1'
                            data-slider-value='[{$this->session_slider_val}]'
                            data-slider-range='true'
                            data-type='slider'/>
                </div>
                
                <!-- A checkbox to include unknown values as well -->
                
                <div class='col-md-12'>
                    <div class='form-check'>
                        <input  id='{$this->name}_nan'
                                class='form-check-input search-field'
                                type='checkbox' 
                                value='{$this->session_check_val}'
                                onchange='onFilterChange()'
                                data-type='checkbox' 
                                checked />

                        <label class='form-check-label'>
                            TODO: Allow unknown values too:
                        </label>
                    </div>
                </div>
            </div>";
            
            return $content;
        }
    }
