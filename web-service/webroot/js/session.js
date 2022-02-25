'use strict'

// Set the auto refresh settings
var refreshIntervallMs = 8000;
window.setInterval(activeSessionSignal, refreshIntervallMs);
window.addEventListener("unload", closeUp);
window.addEventListener("pagehide", closeUp);
window.addEventListener("pageshow", function() {
    window.setInterval(activeSessionSignal, refreshIntervallMs);
});

function closeUp() { window.clearInterval(); }

var sessionId = getCookie("PHPSESSID");

// function to signal activity on the live player page
function activeSessionSignal()
{
    const xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			// read session id cookie for next request
            sessionId = getCookie("PHPSESSID");
			console.log("Session ID from Cookie: " + sessionId);
		}
	};
	
	xhttp.open("GET", "php/sessionTracker.php?sessionId=" + sessionId + "&watching=yes", true);
	xhttp.send();
}

// function from www schools to read local cookies
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }