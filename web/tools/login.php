<?php
	require "../../login_data.php";
	session_start();
	
	if (isset($_POST['submitLogin'])) {
		// Check if username and password are correct
		$username = $_POST["user"];
		$password = $_POST["password"];
		
		if (($username == $login_username) && ($password == $login_password)) {
			$_SESSION["login"] = $username;
		} elseif (($username != $login_username) || ($password != $login_password)) {
			$_SESSION["error"] = true;
		}
	}
?>

<script>
window.onload = function Return2Settings() {
	window.location.href = "../settings.php";
}
</script>
