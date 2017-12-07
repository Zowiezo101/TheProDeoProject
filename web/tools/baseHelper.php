<?php

// https://stackoverflow.com/questions/3770513/detect-browser-language-in-php	
function prefered_language(array $available_languages, $http_accept_language) {

	$available_languages = array_flip($available_languages);

	$langs;
	$langsSet;
	
	preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
	foreach($matches as $match) {

		list($a, $b) = explode('-', $match[1]) + array('', '');
		$value = isset($match[2]) ? (float) $match[2] : 1.0;

		if(isset($available_languages[$match[1]])) {
			$langs[$match[1]] = $value;
			continue;
		}

		if(isset($available_languages[$a])) {
			$langs[$a] = $value - 0.1;
		}

	}
	
	if (count($langs) > 0) {
		arsort($langs);
		foreach ($langs as $lang => $value) {
			$langsSet[] = $lang;
		}
	} else {
		$langsSet[] = "en";
	}

	return $langsSet;
}

function get_available_langs() {
	$langsSet;
	
	$langFiles = glob("./translations/*.php");
	foreach ($langFiles as $filename) {
		$lang = substr($filename, -6, 2);
		$langsSet[] = $lang;
	}

	return $langsSet;
}

// Set the language to a default
if (!isset($_SESSION["lang"])) {
	// Languages we support
	$available_languages = get_available_langs();

	$langs = prefered_language($available_languages, $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
	if ($langs) {
		$_SESSION["lang"] = $langs[0];
	} else {
		$_SESSION["lang"] = "en";
	}

} 
$page_lang = $_SESSION["lang"];

require "translations/translation_".$page_lang.".php"; 
require "../login_data.php";

function getLangList($page_lang) {
	foreach (get_available_langs() as $lang) {
		if ($lang != $page_lang) {
			echo '<input style=" 
							background-image: url(\'img/lang_'.$lang.'.svg\'); 
							background-size: auto 100%;" 
						  class="lang_option" 
						  type="submit" 
						  name="lang" 
						  value="'.$lang.'">';
		}
	}
}

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function AddParams($page, $id, $sort) {
	$return_val = "";
		
	// If values are not defined, define them now
	// Use the default value, if they are not in the address bar
	if ($page == -1) {
		if (isset($_GET["page"])) {
			$page = $_GET["page"];
		} else {
			$page = 0;
		}
	}
		
	if ($sort == -1) {
		if (isset($_GET["sort"])) {
			$sort = $_GET["sort"];
		} else {
			$sort = "app";
		}
	}
	
	if ($page != 0) {
		$return_val = "?page=".$page."&id=".$id;
	} else {
		$return_val = "?id=".$id;
	} 
	
	if ($sort != "app") {
		$return_val = $return_val."&sort=".$sort;
	}
		
	return $return_val;
}

function AddIdParam($id_nr) {
	$return_val = "";
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	if ($page_nr > 0) {
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