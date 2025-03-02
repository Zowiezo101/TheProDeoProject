<?php

    namespace Shapes;

    class InputSlider extends Module {
        private $name;
        private $range;
        private $session_slider_val;
        private $session_check_val;
        
        public function __construct($name, $options) {
            parent::__construct();
            
            // The name of this textbox
            $this->name = $name;

            if (isset($options[$name."_min_max"])) {
                $this->range = $options[$name."_min_max"][0];
            }
            
            // If there is a value stored in the session, get that value
            $this->session_slider_val = isset($_SESSION[$name]) ? htmlspecialchars($_SESSION[$name]) : "-1,-1";
            $this->session_check_val = isset($_SESSION[$name."_nan"]) ? htmlspecialchars($_SESSION[$name."_nan"]) : "";
        }
        
        public function getContent() {
            global $dict;

            // The range of this slider, if it's given
            $range = "";
            if (isset($this->range)) {
                $range = "data-slider-min='".$this->range->min."' 
                            data-slider-max='".$this->range->max."'
                ";
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
                    <input  id='{$this->name}'
                            class='d-none search-field'
                            type='text' 
                            value=''
                            data-slider-id='slider_{$this->name}'
                            data-slider-tooltip-split='true'
                            ".$range."
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
                                data-type='checkbox' 
                                checked />

                        <label class='form-check-label'>
                            ".$dict["search.unknown"]."
                        </label>
                    </div>
                </div>
            </div>";
            
            return $content;
        }
    }
