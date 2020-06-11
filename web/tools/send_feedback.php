<?php
    session_start();

/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */
 
//Import PHPMailer classes into the global namespace
require "../phpmailer/PHPMailer.php";
require "../phpmailer/Exception.php";
require "../phpmailer/SMTP.php";
require "../phpmailer/POP3.php";

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
    
if (isset(filter_input(INPUT_GET, 'sendFeedback'))) {

    // require '../vendor/autoload.php';

    //Create a new PHPMailer instance
    $mail = new PHPMailer;

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
    $mail->SMTPSecure = 'tls';

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "Info.ProDeoProjects@gmail.com";

    //Password to use for SMTP authentication
    $mail->Password = "Info@Notifier";

    //Set who the message is to be sent from
    $mail->setFrom('Info.ProDeoProjects@gmail.com', 'ProDeo Projects');

    //Set an alternative reply-to address
    // $mail->addReplyTo('replyto@example.com', 'First Last');

    //Set who the message is to be sent to
    $mail->addAddress('ProDeoProductions2U@gmail.com', 'ProDeo Projects');

    //Set the subject line
    $mail->Subject = 'Received feedback: "'.filter_input(INPUT_GET, 'subject').'"';

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML(filter_input(INPUT_GET, 'text'));

    //Replace the plain text body with one created manually
    // $mail->AltBody = 'This is a plain-text message body';

    //Attach an image file
    // $mail->addAttachment('images/phpmailer_mini.png');

    //send the message, check for errors
    if (!$mail->send()) {
        $_SESSION["error"] = "Mailer Error: " . $mail->ErrorInfo;
    } else {
        $_SESSION["send"] = true;
    }
}

?>

<script>
window.onload = function () {
    window.location.href = "../contact.php";
};
</script>