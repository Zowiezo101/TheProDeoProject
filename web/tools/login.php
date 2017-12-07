<?php
	session_start();
	require "../../login_data.php";
	
	// The login page
	if (isset($_POST['submitLogin'])) {
		
		// Check if username and password are correct
		$username = $_POST["user"];
		$password = $_POST["password"];
		
		// If they are, login with the username
		if (($username == $login_username) && ($password == $login_password)) {
			$_SESSION["login"] = $username;
		} 
		// If not, return an error
		elseif (($username != $login_username) || ($password != $login_password)) {
			$_SESSION["error"] = true;
		}
	}
?>

<script>
window.onload = function Return2Settings() {
	window.location.href = "../settings.php";
}
</script>
