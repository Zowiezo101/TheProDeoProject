<?php 
	// Make it easier to copy/paste code or make a new file
	$id = "contact";
	require "layout/layout.php"; 
?>
<?php

function contact_Helper_layout() {
	global $dict_Contact;
	
	PrettyPrint('<p>'.$dict_Contact["contact"].'</p>', 1);
	PrettyPrint('		<br>');
	PrettyPrint('	<form method="get" id="contact_form" action="tools/send_feedback.php">');
	PrettyPrint('		<h1>'.$dict_Contact["contact_form"].'</h1>');
	PrettyPrint('		<textarea name="subject" required="true"
							placeholder="'.$dict_Contact["contact_subject"].'"
							rows=1 ></textarea>');
	
	PrettyPrint('		<textarea name="text" required="true" 
							placeholder="'.$dict_Contact["contact_text"].'"
							rows=10 ></textarea>');
	
	PrettyPrint('		<input type="submit" name="sendFeedback" value="'.$dict_Contact["contact_submit"].'">');
	PrettyPrint('		<br>');
	PrettyPrint('	</form>');
		
	// When the entered data is incorrect
	if (isset($_SESSION["error"])) {
		if ($_SESSION["error"] != "") {
			PrettyPrint("	<h3>".$dict_Contact["contact_failed"]."</h3>");
			PrettyPrint("	<p>".$_SESSION["error"]."</p>");
			$_SESSION["error"] = "";
		}
	} 
	
	if (isset($_SESSION["send"])) {
		if ($_SESSION["send"] == true) {
			PrettyPrint("	<h3>".$dict_Contact["contact_succes"]."</h3>");
			$_SESSION["send"] = false;
		}
	}
	
	PrettyPrint('	<p>'.$dict_Contact["other"].'</p>');
}

?>