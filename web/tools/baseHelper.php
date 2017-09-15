<?php 
if (!isset($_GET["lang"])) {
	$page_lang = "nl";
} else {
	$page_lang = $_GET["lang"];
}

require "translations/translation_".$page_lang.".php"; 
require "../login_data.php";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function AddLangParam($href) {
	global $page_lang;
	$return_val = "";
	
	if ($page_lang == "nl") {
		$return_val = $href;
	} else {
		$return_val = $href."?lang=".$page_lang;
	}
	
	return $return_val;
}

function AddPageParam($page_nr) {
	global $page_lang;
	$return_val = "";
	
	if (($page_lang == "nl") && ($page_nr > 0)) {
		# When the page language is dutch, there is no parameter in the URL
		$return_val = "?page=".$page_nr;
	} else if ($page_nr > 0) {
		# When the page language is not dutch, there is already a parameter in the URL
		# Now use & to add this parameter as well.
		$return_val = "&page=".$page_nr;
	}
	
	return $return_val;
}

function AddIdParam($id_nr) {
	global $page_lang;
	$return_val = "";
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	if ((!($page_lang == "nl")) || ($page_nr > 0)) {
		# When the page language is dutch, there is no parameter in the URL
		$return_val = "&id=".$id_nr;
	} else  {
		# When the page language is not dutch, there is already a parameter in the URL
		# Now use & to add this parameter as well.
		$return_val = "?id=".$id_nr;
	}
	
	return $return_val;
}
?>

<script>

//https://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
function uniq(a) {
    var prims = {"boolean":{}, "number":{}, "string":{}}, objs = [];

    return a.filter(function(item) {
        var type = typeof item;
        if(type in prims)
            return prims[type].hasOwnProperty(item) ? false : (prims[type][item] = true);
        else
            return objs.indexOf(item) >= 0 ? false : objs.push(item);
    });
}
	
/**
* http://stackoverflow.com/a/10997390/11236
*/
function updateURLParameter(url, param, paramVal){
// function updateURLParameter(url, param){
	var newAdditionalURL = "";
	var tempArray = url.split("?");
	var baseURL = tempArray[0];
	var additionalURL = tempArray[1];
	var temp = "";
	
	if (additionalURL) {
		tempArray = additionalURL.split("&");
		
		for (var i=0; i<tempArray.length; i++){
			if(tempArray[i].split('=')[0] != param){
				newAdditionalURL += temp + tempArray[i];
				temp = "&";
			}
		}
	}

	var rows_txt = temp + "" + param + "=" + paramVal;
	return baseURL + "?" + newAdditionalURL + rows_txt;
}
	
function removeURLParameter(url, param){
	var newAdditionalURL = "";
	var tempArray = url.split("?");
	var baseURL = tempArray[0];
	var additionalURL = tempArray[1];
	var temp = "?";
	
	if (additionalURL) {
		tempArray = additionalURL.split("&");
		
		for (var i=0; i<tempArray.length; i++){
			if(tempArray[i].split('=')[0] != param){
				newAdditionalURL += temp + tempArray[i];
				temp = "&";
			}
		}
	}
	return baseURL + newAdditionalURL;
}

window.onerror = function(msg, url, linenumber) {
	alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
	return true;
}
</script>