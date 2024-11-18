<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 這裡放置您的處理邏輯
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0); // CORS 預檢請求處理
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    // Include the necessary files
    require_once "conn.php";
    require_once "validate.php";

    // Call validate, pass form data as parameter and store the returned value
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    // Create a prepared statement
    $sql = "SELECT * FROM customer WHERE CUSTOMER_Email = ? AND CUSTOMER_Password = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bind_param("ss", $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
        // Store the result
        $result = $stmt->get_result();

        // If number of rows returned is greater than 0 (that is, if the record is found), print "success"
        if ($result->num_rows > 0) {
    //         echo "success";
    //     } else {
    //         // If no record is found, print "failure"
    //         echo "failure";
    //     }
    // } else {
    //     // Handle the case where the statement execution fails
    //     echo "failure";
    // }
            $response = ["response" => "success"];
        } else {
    // If no record is found, return failure response
            $response = ["response" => "failure", "message" => "Invalid email or password"];
        }
    } else {
// Handle the case where the statement execution fails
        $response = ["response" => "failure", "message" => "Database query failed"];
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
} else {
    $response = ["response" => "failure", "message" => "Email or password not provided"];
}

echo json_encode($response);
?>
