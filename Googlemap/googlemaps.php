<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>Google Maps Example</title>
<style>
  #map {
    height: 100%;
  }
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
</style>
</head>
<body>
<div id="map"></div><script>
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: {lat: 45.444343, lng: -75.746633}
  });
  setMarkers(map);
}

// JavaScript array to hold data from PHP
var lots = [
  <?php

    // Connect to the database
    include "../bd.php"; // Adjust path as needed

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    $con->set_charset("utf8");
    // Query to fetch data from the table
    // Prepare the SQL statement
    $stmt = $con->prepare("SELECT `Nom de l'entreprise`, numtelephone, latitude, longitude, googlemap, `adresse`, `nocivique`, `rue`, `ville`, `province`, `codepostal` FROM acces_employeurs"); $stmt->execute(); 
    $result = $stmt->get_result();

    // Format data for JavaScript
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "['" . addslashes($row["Nom de l'entreprise"]) . "', '" . addslashes($row["numtelephone"]) . "', '" . addslashes($row["adresse"]) . "', '" . $row["nocivique"] . "', '" . addslashes($row["rue"]) . "', '" . addslashes($row["ville"]) . "', '" . addslashes($row["province"]) . "', '" . $row["codepostal"] . "', " . $row["latitude"] . ", " . $row["longitude"] . ", '" . addslashes($row["googlemap"]) . "'],";
        }
    } else {
        echo "[]";
    }

    $con->close();
  ?>
];

function setMarkers(map) {
  var infowindow = new google.maps.InfoWindow();

  for (var i = 0; i < lots.length; i++) {
    var lot = lots[i];
    var marker = new google.maps.Marker({
      position: {lat: parseFloat(lot[8]), lng: parseFloat(lot[9])},
      map: map,
      title: lot[0]
    });

    google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
      return function() {
        infowindow.setContent(
          "<strong>" + lots[i][0] + "</strong><br>" +
           lots[i][1] +  "<br>" +
          "Address: " + lots[i][3] + " " + lots[i][4] + ", " + lots[i][5] + ", " + lots[i][6] + " " + lots[i][7] + "<br>" +
          "<a href='" + lots[i][10] + "' target='_blank'>View on Google Maps</a>"
        );
        infowindow.open(map, marker);
      };
    })(marker, i));
  }
}
</script>

<script async defer  src="https://maps.googleapis.com/maps/api/js?key="></script>
</body>
</html>
