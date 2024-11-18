<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once "conn.php";
require_once "validate.php";

$response = ['city' => []];  // Predefine response structure.

if(isset($_GET['country_name'])) {
    // Validation
    $country_name = validate($_GET['country_name']);

    // Using prepared statement to make SQL Injection harder.
    $sql = "SELECT city_id, city_name FROM city WHERE country_id = (SELECT country_id FROM country WHERE country_name = ?)";

    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $country_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $response['city'][] = array(
                    'city_id' => $row['city_id'],
                    'city_name' => $row['city_name']
                );
            }
        } else {
            $response['error'] = "No cities found for the given country name.";
        }

        $stmt->close();
    } else {
        $response['error'] = "Error preparing the query.";
    }
} else {
    $response['error'] = "Country name not provided.";
}

echo json_encode($response);
$conn->close();
?>