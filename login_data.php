<?php

// Are we locally or on server?
$debug = filter_input(INPUT_SERVER, "SERVER_NAME") === "localhost";

$servername = "localhost";
if ($debug == true) {
    // Database
    $username = "root";
    $password = "12345";
    $database = "bible";
    // PHP Mailer
    $email_host = "smtp.gmail.com";
    $email_user = "Info.ProDeoProjects@gmail.com";
    $email_pass = "Info@Notifier";
} else {
    // Database
    $username = "u158918040_prodeo";
    $password = "UAReFdz3tkZ5n7x";
    $database = "u158918040_bible";
    // PHP Mailer
    $email_host = "smtp.hostinger.com";
    $email_user = "info@prodeodatabase.com";
    $email_pass = "68ND2NG2Q2qsAKx";
}

// Settings account
$login_username = "Zowiezo101";
$login_password = "ProDeoUnlimited#";

?>