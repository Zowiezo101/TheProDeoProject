<?php
session_start();
unset($_SESSION["login"]);
?>

<script>
window.onload = function Return2Settings() {
	window.location.href = "../settings.php";
}
</script>
