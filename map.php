<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <script>
      // Custom labels based on the type of location
      var customLabel = {
        restaurant: {
          label: "R",
        },
        bar: {
          label: "B",
        },
      };

      function initMap() {
        var map = new google.maps.Map(document.getElementById("map"), {
          center: new google.maps.LatLng(-33.863276, 151.207977),
          zoom: 12,
        });

        var infoWindow = new google.maps.InfoWindow();

        // Download XML data from the server (replace the URL with your local or server URL)
        downloadUrl("http://localhost/demo/xml.php", function (data) {
          var xml = data.responseXML;
          var markers = xml.documentElement.getElementsByTagName("marker");
          Array.prototype.forEach.call(markers, function (markerElem) {
            var id = markerElem.getAttribute("id");
            var name = markerElem.getAttribute("name");
            var address = markerElem.getAttribute("address");
            var type = markerElem.getAttribute("type");
            var point = new google.maps.LatLng(
              parseFloat(markerElem.getAttribute("lat")),
              parseFloat(markerElem.getAttribute("lng"))
            );

            // Info window content setup
            var infowincontent = document.createElement("div");
            var strong = document.createElement("strong");
            strong.textContent = name;
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement("br"));

            var text = document.createElement("text");
            text.textContent = address;
            infowincontent.appendChild(text);

            var icon = customLabel[type] || {};
            var marker = new google.maps.Marker({
              map: map,
              position: point,
              label: icon.label,
            });

            marker.addListener("click", function () {
              infoWindow.setContent(infowincontent);
              infoWindow.open(map, marker);
            });
          });
        });
      }

      // Helper function to download the XML data
      function downloadUrl(url, callback) {
        var request = window.ActiveXObject
          ? new ActiveXObject("Microsoft.XMLHTTP")
          : new XMLHttpRequest();

        request.onreadystatechange = function () {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open("GET", url, true);
        request.send(null);
      }

      function doNothing() {}

      // The Google Maps script with your actual API key
    </script>

    <!-- Replace 'YOUR_GOOGLE_MAPS_API_KEY' with your actual API key -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-8eNis0GILwTp8KvEdbDQxYu1v6bDr7I&callback=initMap"
      defer
    ></script>
  </body>
</html>
