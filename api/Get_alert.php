<?php
    if (isset($_POST['id'])) {
        require_once "conn.php";
        require_once "validate.php";

        $id = validate($_POST['id']);
        
        $sql = "SELECT Alert_Volume FROM alert WHERE Sensor_Id = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); 

            // Echo only the Alert_Volume value
            echo $row['Alert_Volume'];
        } else {
            echo json_encode('no result'); 
        }

        $stmt->close();
    }
?>