var map;

function initMap() {
	map = new google.maps.Map(document.getElementById('patchee-map'), {
          center: {lat: 39.091111, lng: -105.767260},
          zoom: 8
        });


	infoWindow = new google.maps.InfoWindow;
	var patcheeContainer = document.createElement('div');

    // Create the DIV to hold the control and call the CenterControl()
    // constructor passing in this DIV.
    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControl(centerControlDiv, map);

    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_CENTER].push(centerControlDiv);
}

function CenterControl(controlDiv, map) {

    // Set CSS for the control border.
    var patcheeButton = document.createElement('div');
    patcheeButton.style.backgroundColor = '#fff';
    patcheeButton.style.border = '2px solid #fff';
    patcheeButton.style.borderRadius = '100%';
    patcheeButton.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    patcheeButton.style.cursor = 'pointer';
    patcheeButton.style.margin = '22px';
    patcheeButton.style.textAlign = 'center';
    patcheeButton.style.height = '70px';
    patcheeButton.style.width = '70px';
    controlDiv.appendChild(patcheeButton);

    // Set CSS for the control interior.
    var PatcheeImage = document.createElement('div');
    PatcheeImage.style.margin = "0 auto";
    PatcheeImage.style.marginTop = "13%";
    PatcheeImage.style.width = "50px";
    PatcheeImage.style.height = "50px";
    PatcheeImage.style.backgroundSize = "cover";
    PatcheeImage.style.backgroundSize = 'cover';
    PatcheeImage.style.backgroundImage = "url('https://cdn2.iconfinder.com/data/icons/complete-medical-healthcare-icons-for-apps-and-web/128/bandage-patch-outline-512.png')";

    patcheeButton.appendChild(PatcheeImage);

    // Setup the click event listeners: simply set the map to Chicago.
    patcheeButton.addEventListener('click', function() {
      	if (navigator.geolocation) {
		  	navigator.geolocation.getCurrentPosition(function(position) {
			    var potholePost = {
			      lat: position.coords.latitude,
			      lng: position.coords.longitude
			    };

			    console.log( typeof JSON.stringify(potholePost));
			    console.log( JSON.stringify(potholePost));

			    var url = './endpoint.php';
			    var params = potholePost;
			    var xhr = new XMLHttpRequest();
			    xhr.open("POST", url, true);
			    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			    xhr.send("data=" + JSON.stringify(potholePost));
		    });
		} else {
		  handleLocationError(false, infoWindow, map.getCenter());
		}
    });

}



function handleLocationError(browserHasGeolocation, infoWindow, pos) {
infoWindow.setPosition(pos);
infoWindow.setContent(browserHasGeolocation ?
                      'Error: The Geolocation service failed.' :
                      'Error: Your browser doesn\'t support geolocation.');
infoWindow.open(map);
}