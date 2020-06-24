<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "contact";
    require "layout/template.php"; 
?>

<script>
    function onLoadContact() {
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        var content = document.getElementById("content");
        
        // Title of the contents
        var p1 = document.createElement("p");
        content.appendChild(p1);
        
        // Set its attributes
        p1.innerHTML = dict_Contact["contact"];
        
        // Extra breakline
        content.appendChild(document.createElement("br"));
        
        // The form
        var form = document.createElement("form");
        content.appendChild(form);
        
        // Set its attributes
        form.method = "get";
        form.id = "contact_form";
        form.action = "tools/send_feedback.php";
        
        // Header of the form
        var h1 = document.createElement("h1");
        form.appendChild(h1);
        
        // Set its attributes
        h1.innerHTML = dict_Contact["contact_form"];
        
        // Subject textarea
        var subject = document.createElement("textarea");
        form.appendChild(subject);
        
        // Set its attributes
        subject.name = "subject";
        subject.required = "true";
        subject.placeholder = dict_Contact["contact_subject"];
        subject.rows = 1;
        
        // Subject textarea
        var text = document.createElement("textarea");
        form.appendChild(text);
        
        // Set its attributes
        text.name = "text";
        text.required = "true";
        text.placeholder = dict_Contact["contact_text"];
        text.rows = 10;
        
        // The submit button
        var submit = document.createElement("input");
        form.appendChild(submit);
        
        // Set its attributes
        submit.type = "submit";
        submit.name = "sendFeedback";
        submit.value = dict_Contact["contact_submit"];
        
        // Extra breakline
        form.appendChild(document.createElement("br"));


        // When the message had an error when sending
        if (session_settings.hasOwnProperty("error")) {
            if (session_settings["error"] !== "") {
                // Error header
                var h3 = document.createElement("h3");
                h3.innerHTML = dict_Contact["contact_failed"];
                content.appendChild(h3);
                
                // Error text
                var p = document.createElement("h3");
                p.innerHTML = session_settings["error"];
                content.appendChild(p);
                
                // Remove error
                updateSessionSettings("error", "");
            }
        } 


        // When the message is send correctly
        if (session_settings.hasOwnProperty("send")) {
            if (session_settings["send"] === true) {
                // Success header
                var h3 = document.createElement("h3");
                h3.innerHTML = dict_Contact["contact_succes"];
                content.appendChild(h3);
                
                // Remove success message
                updateSessionSettings("send", false);
            }
        }
        
        var p2 = document.createElement("p");
        content.appendChild(p2);
        
        // Set its attributes
        p2.innerHTML = dict_Contact["other"];
    }
</script>