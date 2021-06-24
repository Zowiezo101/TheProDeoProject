<?php
    // We need it to be started at the beginning
    session_start();
    
    /**
     * This example shows settings to use when sending via Google's Gmail servers.
     * This uses traditional id & password authentication - look at the gmail_xoauth.phps
     * example to see how to use XOAUTH2.
     * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
     */

    //Import PHPMailer classes into the global namespace
    require "src/phpmailer/PHPMailer.php";
    require "src/phpmailer/Exception.php";
    require "src/phpmailer/SMTP.php";
    require "src/phpmailer/POP3.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    if (filter_input(INPUT_POST, 'send_feedback') !== null) {
        //PHPMailer Object
        $mail = new PHPMailer(true); //Argument true in constructor enables exceptions

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "Info.ProDeoProjects@gmail.com";

        //Password to use for SMTP authentication
        $mail->Password = "Info@Notifier";

        //Set who the message is to be sent from
        $mail->setFrom('Info.ProDeoProjects@gmail.com', 'ProDeo Projects');

        //Set an alternative reply-to address
        //$mail->addReplyTo('replyto@example.com', 'First Last');

        //Set who the message is to be sent to
        $mail->addAddress('ProDeoProductions2U@gmail.com', 'ProDeo Projects');

        //Set the subject line
        $name = (filter_input(INPUT_POST, 'name') !== null && trim(filter_input(INPUT_POST, 'name')) !== "") ? filter_input(INPUT_POST, 'name') : "Unknown";
        $mail->Subject = 'Received feedback: "'.filter_input(INPUT_POST, 'subject').' @ '.$name.'"';

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(filter_input(INPUT_POST, 'message'));

        try {
            $mail->send();
            $_SESSION["sent"] = true;
        } catch (Exception $e) {
            $_SESSION["error"] = "Mailer Error: " . $mail->ErrorInfo;
        }
        
        header('Location: '.filter_input(INPUT_SERVER, 'HTTP_REFERER'));
        exit;
    }
    
    // Make it easier to copy/paste code or make a new file
    // Less change of errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    // Function to load the content in the content div
    function onLoadContact() {
        if (session_settings["sent"] && session_settings["sent"] === '1') {
            var contact_form = 
                            '<div class="mx-auto p-4 col-md-6">' +
                            '    <h2 class="mb-4">' + dict["contact.sent"] + '</h2>' + 
                            '    <p>Je bericht is verstuurd, dankjewel!</p>' + 
                            '</div>';
                                    
            updateSession({sent: null, error: null});
        } else if (session_settings["error"] && session_settings["error"] !== "") {
            contact_form = 
                        '<div class="mx-auto p-4 col-md-6">' + 
                        '    <h2 class="mb-4">' + dict["contact.error"] + '</h2>' + 
                        '    <p>Tijdens het versturen van je anonieme bericht, kregen we de volgende foutmelding: ' + session_settings["error"] + '</p>' + 
                        '</div>';
            updateSession({sent: null, error: null});
        } else {
            contact_form = 
                        '<div class="mx-auto p-4 col-md-6">' + 
                        '    <h2 class="mb-4">' + dict["contact.form"] + '</h2>' + 
                        '    <form method="post">' + 
                        '        <div class="form-group"> <input type="text" class="form-control" name="name" placeholder="' + dict["contact.name"] + '"> </div>' +
                        '        <div class="form-group"> <input type="text" class="form-control" name="subject" required placeholder="' + dict["contact.subject"] + '"> </div>' +
                        '        <div class="form-group"> <textarea class="form-control" name="message" rows="3" required placeholder="' + dict["contact.message"] + '"></textarea> </div>' +
                        '        <div class="d-none"> <input type="text" name="href" value="' + window.location.href + '"></div>' + 
                        '        <button type="submit" class="btn btn-primary" name="send_feedback">' + dict["contact.send"] + '</button>' +
                        '    </form>' + 
                        '</div>';
        }
    
        $("#content").append(
            $("<div>").addClass("container").append(
                $("<div>").addClass("row")
                    // The column with the menu
                    .append(
                        // The explanation message
                        '<div class="mx-auto p-4 col-md-6">' + 
                        '    <h2 class="mb-4">' + dict["navigation.contact_us"] + '</h2>' + 
                        '    <p>' + dict["contact.overview"] + '</p>' + 
                        '    <p class="mb-0 lead">' + 
                        '        <a href="mailto:prodeoproductions2u@gmail.com" target="_blank">ProDeoProductions2U@gmail.com</a>' + 
                        '    </p>' + 
                        '</div>'
                    )
                    .append(contact_form)
            )
        );  
    }
</script>