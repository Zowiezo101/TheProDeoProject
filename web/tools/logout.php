<?php
session_start();
// Remove the login data
// When this is unset, no login is found
unset($_SESSION["login"]);
?>

<script>
window.onload = function Return2Settings() {
    window.location.href = "../settings.php";
};
</script>
