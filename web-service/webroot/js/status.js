'use strict'

var liveStatus = "inactive";
var lastLiveStatus = "active";
var playerBox = document.getElementById("player-box");

// Set the auto refresh settings
var refreshIntervallMs = 5000;
var videoListIntervall = 12000;
window.setInterval(updateStatus, refreshIntervallMs);
window.setInterval(updateVideoList, videoListIntervall);
window.addEventListener("pagehide", closeUp);
window.addEventListener("pageshow", function() {
    window.setInterval(updateStatus, refreshIntervallMs);
	window.setInterval(updateVideoList, videoListIntervall);
});

function closeUp()
{
	window.clearInterval();
}

function updateStatus()
{
	const xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			var resultStr = this.responseText;
			var resultArray = resultStr.split("::");

			// Fetch the hls stream url
			var streamUrl = getValueByIdentifier("stream-url", resultArray);

			// Fetch the news text line 
			var newsText = getValueByIdentifier("news-text", resultArray);
			if(newsText === "not found") { newsText = ""; }
			
			// Store last status before fetching current one
			lastLiveStatus = liveStatus;
			liveStatus = getValueByIdentifier("livestream", resultArray);
			var label = document.getElementById("statusLabel");
			if(label != null) {
				if(liveStatus === "active") {
					document.getElementById("live-indicator").style.color = "green";
					label.innerHTML = "Zum ";
				} else {
					document.getElementById("live-indicator").style.color = "grey";
					label.innerHTML = "Momentan kein ";
				}
			}
			
			// fetch and display current viewer count on live stream page
			var viewerCount = getValueByIdentifier("viewerCount",  resultArray);
			var viewerLabel = document.getElementById("viewerCount");
			if(viewerLabel != null) {
				if(liveStatus === "active" && viewerCount != "not found") {
					// Display the viewer counter with updated value
					viewerLabel.innerHTML = "aktuelle Zuschauer: <b>" + viewerCount + "</b><i class='fas fa-user' style='color:black;font-size:18px;'></i>";
				} else {
					// hide the counter while offline
					viewerLabel.innerHTML = "";
				}
			}
			var viewerLabelSmall = document.getElementById("viewerCount-small");
			if(viewerLabelSmall != null) {
				if(liveStatus === "active" && viewerCount != "not found") {
					// Display the viewer counter with updated value
					viewerLabelSmall.innerHTML = " (<b>" + viewerCount + "</b><i class='fas fa-user' style='color:black;font-size:18px;'></i>)";
				} else {
					// hide the counter while offline
					viewerLabelSmall.innerHTML = "";
				}
			}

			if(playerBox != null)
			{
				// When the live stream starts, show the video player
				if(lastLiveStatus === "inactive" && liveStatus === "active") {
					playerBox.innerHTML = "<video-js id='live-player' class='video-js vjs-default-skin vjs-big-play-centered' poster='poster.png'></video-js>";

					// create the video JS live player and make it fluid
					var player = videojs("live-player", {
						liveui: true,
						controls: true,
						autoplay: true,
						preload: 'auto'
					});
					player.fluid(true);
					player.src({type: 'application/x-mpegURL', src: streamUrl});
					var qualityLevels = player.qualityLevels();
					
					// output detected quality levels to the console
					console.log(qualityLevels);

					player.on('ready', function() {
						this.addClass('my-example');
						player.hlsQualitySelector({ 
							displayCurrentQuality: true,
						});
					});

					console.log("live stream just started");
				}
				// when the live stream ends, hide the video player
				if(lastLiveStatus === "active" && liveStatus === "inactive") {
					var player = videojs("live-player", {liveui: true});
					player.dispose();
					playerBox.innerHTML = "<p><H1>Sendepause</H1></p><p><H3>" + newsText + "</H3></p>";
					console.log("live stream just stopped");
				}
			}
		}
	};

	// Make asynchronous uncached HTTP GET request to pull status in the background
	xhttp.open("GET", "php/status.php?q=" + Math.random(), true);
	xhttp.send();
}

function updateVideoList()
{
	var videoList = document.getElementById("video-list");
	if(videoList == null)
		return;
	
	const xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			videoList.innerHTML = this.responseText;
			console.log("Reloaded video list");
		}
	};
	
	xhttp.open("GET", "php/pullVideoList.php?q=" + Math.random().toString() + "&priv=no", true);
	xhttp.send();
}

function getValueByIdentifier(identifier, array) {
    // length of the array we are searching in
    var size = array.length;

    // Loop through the array
    for (var i = 0; i < size; i++) {
        if (array[i] == identifier && i < (size - 1)) {
            return array[i + 1];
        }
    }

    return "not found";
}

