<?php
    require "src/modules/ContactPage/Parts/ContactDescr.php";
    require "src/modules/ContactPage/Parts/ContactForm.php";

    class ContactPage extends Module {
        private $contact_descr;
        private $contact_form;
        
        public function __construct() {
            parent::__construct();
            
            // The description of the contact page
            $this->contact_descr = new ContactDescr();
            
            // The form that can be filled in (including the feedback after 
            // filling in the form)
            $this->contact_form = new ContactForm();
        }
        
        public function getContent() {
            $content = '<div class="row">
                    '.$this->contact_descr->getContent().'
                    '.$this->contact_form->getContent().'
                </div>';
            
            return $content;
        }
    }

