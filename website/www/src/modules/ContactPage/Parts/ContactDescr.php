<?php

    namespace Parts;
    
    use Shapes\Module;

    class ContactDescr extends Module {
        public function getContent() {
            global $dict, $email_to;
            
            $content = '<div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4">'.$dict["navigation.contact_us"].'</h2>
            <p>'.$dict["contact.overview"].'</p>
            <p class="mb-0 lead">
                <a href="mailto:'.$email_to.'" target="_blank">'.$email_to.'</a>
            </p>
        </div>';
            
            return $content;
        }
    }
