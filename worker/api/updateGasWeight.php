<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require_once "conn.php";
    require_once "validate.php";

    if(isset($_POST['id'])) {
        $gasId = validate($_POST['id']);
        $gasWeightEmpty = validate($_POST['gasWeightEmpty']);
        $workerId = validate($_POST['Worker_Id']);

        $stmt = $conn->prepare("UPDATE gas SET GAS_Weight_Empty = ?, last_worker_id = ? WHERE GAS_Id = ?;");
        $stmt->bind_param("iis", $gasWeightEmpty, $workerId, $gasId);

        if($stmt->execute()) {
            echo "success";
        } else {
            echo "failure";
        }
    }
    $conn->close();
?>