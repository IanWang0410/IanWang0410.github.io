<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "conn.php";
require_once "validate.php";

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Check if the ID is set via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Retrieve the gas tank ID from the POST request and validate it
    $gasId = validate($_POST['id']);

    // Prepare the SQL query to check if the gas tank is registered
    $query = "SELECT * FROM gas WHERE GAS_id = ?";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $gasId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any row was returned
        if ($result->num_rows > 0) {
            echo "registered";
        } else {
            echo "not_registered";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "error";
    }
} else {
    echo "invalid_request";
}

// Close the database connection
$conn->close();
?>
