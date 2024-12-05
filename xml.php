<?php
require("connection.php");

function parseToXML($htmlStr) {
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

// Select all the rows in the markers table
$query = "SELECT * FROM map";
$result = mysqli_query($con, $query);
if (!$result) {
    die('Invalid query: ' . mysqli_error($con));
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<map>';
$ind = 0;

// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)) {
    // Add to XML document node
    echo '<marker ';
    echo 'id="' . $row['id'] . '" ';
    echo 'name="' . parseToXML($row['name']) . '" ';
    echo 'address="' . parseToXML($row['address']) . '" ';
    echo 'lat="' . $row['lat'] . '" ';
    echo 'lng="' . $row['lng'] . '" ';
    echo 'type="' . $row['type'] . '" ';
    echo '/>';
    $ind++;
}

// End XML file
echo '</map>';

?>
