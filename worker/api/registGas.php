<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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
    // Retrieve and validate form data
    $gasId = validate($_POST['id']);
    $gasWeightEmpty = validate($_POST['gasWeightEmpty']);
    $gasType = validate($_POST['gasType']);
    $workerId = validate($_POST['Worker_Id']);

    // Validate that all required fields are provided
    if (!$gasId || !$gasWeightEmpty || !$gasType || !$workerId) {
        echo "invalid_data";
        exit();
    }

    // Retrieve the worker's company ID
    $selectWorkerCompanyQuery = "SELECT `WORKER_Company_Id` FROM `worker` WHERE `WORKER_Id` = ?";
    if ($stmt = $conn->prepare($selectWorkerCompanyQuery)) {
        $stmt->bind_param("s", $workerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $workerCompany_Id = $row['WORKER_Company_Id'];

            // Insert new data into the gas table with the retrieved WORKER_Company_Id
            $insertQuery = "INSERT INTO `gas` (`GAS_Id`, `GAS_Weight_Empty`, `GAS_Type`, `GAS_Company_Id`, `last_worker_id`) 
                            VALUES (?, ?, ?, ?)";
            if ($insertStmt = $conn->prepare($insertQuery)) {
                $insertStmt->bind_param("ssssi", $gasId, $gasWeightEmpty, $gasType, $workerCompany_Id, $workerId);
                if ($insertStmt->execute()) {
                    echo "success";
                } else {
                    echo "insert_failure";
                }
                $insertStmt->close();
            } else {
                echo "insert_statement_error";
            }
        } else {
            echo "worker_not_found";
        }
        $stmt->close();
    } else {
        echo "select_statement_error";
    }
} else {
    echo "invalid_request";
}

// Close the database connection
$conn->close();
?>
