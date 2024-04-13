<?php 
    // Make sure the session is started to pass on the session data
    // We need it to be started at the beginning
    session_start();

    //Import PHPMailer classes into the global namespace
    require "../phpmailer/PHPMailer.php";
    require "../phpmailer/Exception.php";
    require "../phpmailer/SMTP.php";
    require "../phpmailer/POP3.php";
    
    // Import the login details to reach the mailer account
    require "../../../settings.conf";

    use PHPMailer\PHPMailer\PHPMailer;
    
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
        $mail->Host = $email_host;

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
        $mail->Username = $email_user;

        //Password to use for SMTP authentication
        $mail->Password = $email_pass;

        //Set who the message is to be sent from
        $mail->setFrom($email_user, 'ProDeo Projects');

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
            $_SESSION["error"] = $mail->ErrorInfo;
        }
        
        header('Location: '.filter_input(INPUT_SERVER, 'HTTP_REFERER'));
        exit;
    }
?>