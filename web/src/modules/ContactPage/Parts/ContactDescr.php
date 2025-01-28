<?php

    namespace Parts;
    
    use Shapes\Module;

    class ContactDescr extends Module {
        public function getContent() {
            global $dict;
            
            $content = '<div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4">'.$dict["navigation.contact_us"].'</h2>
            <p>'.$dict["contact.overview"].'</p>
            <p class="mb-0 lead">
                <a href="mailto:prodeoproductions2u@gmail.com" target="_blank">ProDeoProductions2U@gmail.com</a>
            </p>
        </div>';
            
            return $content;
        }
    }
