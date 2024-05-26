<?php

    class ContactForm extends Module {
        public function getContent() {
            global $dict;
            
            /**
             * There are three possible situations for this page:
             * 1. Feedback has successfully been sent and a success message is shown
             * 2. Sending feedback has failed and the error message is shown
             * 3. No feedback has been sent, show the feedback form
             */
            $sent = isset($_SESSION["sent"]) ? $_SESSION["sent"] : false;
            $error = isset($_SESSION["error"]) ? $_SESSION["error"] : false;

            // TODO: Does it work with emoticons too? And weird characters
            
            if ($sent !== false) {
                $content = '<!-- Feedback has successfully been sent and a success message is shown -->
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4">'.$dict["contact.sent_title"].'</h2>
            <p>'.$dict["contact.sent_message"].'</p>
        </div>';
                
                unset($_SESSION["sent"]);
                unset( $_SESSION["error"]);
            } else if ($error !== false) {
                $content = '<!-- Sending feedback has failed and the error message is shown -->
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4">'.$dict["contact.error_title"].'</h2>
            <p>'.$dict["contact.error_message"].$error.'</p>
        </div>';
                
                unset($_SESSION["sent"]);
                unset( $_SESSION["error"]);
            } else {
                $content = '<!-- No feedback has been sent, show the feedback form -->
        <div class="mx-auto p-4 col-md-6">
            <h2 class="mb-4">'.$dict["contact.form"].'</h2>
            <form method="post" action="src/tools/send_feedback.php">
                <div class="form-group"> <input type="text" class="form-control" name="name" placeholder="'.$dict["contact.name"].'"> </div>
                <div class="form-group"> <input type="text" class="form-control" name="subject" required placeholder="'.$dict["contact.subject"].'"> </div>
                <div class="form-group"> <textarea class="form-control" name="message" rows="3" required placeholder="'.$dict["contact.message"].'"></textarea> </div>
                <button type="submit" class="btn btn-primary" name="send_feedback">'.$dict["contact.send"].'</button>
            </form>
        </div>';
            }
            
            return $content;
        }
    }

