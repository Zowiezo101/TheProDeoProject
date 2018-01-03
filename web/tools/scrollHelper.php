<script>

// https://stackoverflow.com/questions/7577897/javascript-page-reload-while-maintaining-current-window-position

var cookieName = "page_scroll";
var expdays = 365;

// An adaptation of Dorcht's cookie functions.

function setCookie(name, value, expires, path, domain, secure) {
    if (!expires) expires = new Date();
    document.cookie = name + "=" + escape(value) + 
        ((expires == null) ? "" : "; expires=" + expires.toGMTString()) +
        ((path    == null) ? "" : "; path=" + path) +
        ((domain  == null) ? "" : "; domain=" + domain) +
        ((secure  == null) ? "" : "; secure");
}

function getCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) {
            return getCookieVal(j);
        }
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}

function getCookieVal(offset) {
    var endstr = document.cookie.indexOf(";", offset);
    if (endstr == -1) endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
}

function deleteCookie(name, path, domain) {
    document.cookie = name + "=" +
        ((path   == null) ? "" : "; path=" + path) +
        ((domain == null) ? "" : "; domain=" + domain) +
        "; expires=Thu, 01-Jan-00 00:00:01 GMT";
}

function saveScroll(link) {
	itemBar = document.getElementById("item_choice");
	
	// Expiration date
    var expdate = new Date();
    expdate.setTime(expdate.getTime() + (expdays*24*60*60*1000)); // expiry date

	// Value that we want to store and retrieve
    var scrollValue = "" + itemBar.scrollTop;
	
	// Make the cookie
    setCookie(cookieName, scrollValue, expdate);
	
	// Now go to the desired link
	window.location.href = link;
}

function loadScroll() {
	itemBar = document.getElementById("item_choice");
	
	// The information that we want to retrieve
	var inf = getCookie(cookieName);
	
	// In case there is no information
	if (!inf) { 
		return; 
	}
	
	// Get the value to scroll to
	var scrollValue = parseInt(inf)
	itemBar.scrollTop = scrollValue;
	
	setCookie(cookieName, 0, null)
}

// source: http://stackoverflow.com/a/4770179/1428241

var keys = [37, 38, 39, 40];

function preventDefault(e) {
	e = e || window.event;
	if (e.preventDefault) {
		e.preventDefault();
	}
	e.returnValue = false;  
}

function wheel(e) { 
	preventDefault(e); 
}

function disableScroll() {
	if (window.addEventListener) {
		window.addEventListener('DOMMouseScroll', wheel, false);
	}
	window.onmousewheel = document.onmousewheel = wheel;
}

function enableScroll() {
	if (window.removeEventListener) {
		window.removeEventListener('DOMMouseScroll', wheel, false);
	}
	window.onmousewheel = document.onmousewheel = null;
}

</script>