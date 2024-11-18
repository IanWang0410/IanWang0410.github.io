<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once "conn.php";
require_once "validate.php";

$response = ['country' => []];  // Initialize the response.

// Prepared statement not necessary here as no user input is involved
$sql = "SELECT country_id, country_name FROM country";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['country'][] = array(
                'country_id' => $row['country_id'],
                'country_name' => $row['country_name']
            );
        }
    } else {
        $response['error'] = "No countries found.";
    }
} else {
    $response['error'] = "Error executing the query.";
}

echo json_encode($response);
$conn->close();
?>