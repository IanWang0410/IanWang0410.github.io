<?php
if (isset($_POST['id'])) {
    require_once "conn.php";
    require_once "validate.php";

    $Origin_id = validate($_POST['id']);
    $Family_Phone = validate($_POST['Family_Phone']);
    $Customer_id = null;
    $response = array(); // Ensure $response is initialized

    $sql0 = "SELECT CUSTOMER_Id FROM customer WHERE CUSTOMER_PhoneNo=?";
    $stmt0 = $conn->prepare($sql0);
    $stmt0->bind_param("s", $Family_Phone);
    $stmt0->execute();
    $stmt0->store_result();

    if ($stmt0->num_rows > 0) {
        $stmt0->bind_result($Customer_id);
        $stmt0->fetch();
    } else {
        $response['condition'] = -1;
        echo json_encode($response);
        exit;
    }

    $Family_Id = 0;
    $condition = 0;

    $sql1 = "SELECT Family_Id FROM family_member WHERE Customer_Id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("s", $Origin_id);
    $stmt1->execute();
    $stmt1->store_result();

    if ($stmt1->num_rows > 0) {
        $stmt1->bind_result($Family_Id);
        $stmt1->fetch();
    } else {
        $condition = 1;

        // Get the latest Family_Id and increment it
        $sql2 = "SELECT Family_Id FROM family_member ORDER BY Family_Id DESC LIMIT 1";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->store_result();

        if ($stmt2->num_rows > 0) {
            $stmt2->bind_result($Family_Id);
            $stmt2->fetch();
            $Family_Id += 1;

            $stmt2->close();
        } else {
            $Family_Id += 1;
        }
    }

    // Prepare and execute the insert statement for the new family member
    $sql4 = "INSERT INTO family_member (Family_Id, Customer_Id) VALUES (?, ?)";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("ss", $Family_Id, $Customer_id);

    if ($stmt4->execute()) {
        if ($condition == 1) {
            // Prepare and execute the insert statement for the original family member
            $stmt3 = $conn->prepare($sql4);
            $stmt3->bind_param("ss", $Family_Id, $Origin_id);
            
            if ($stmt3->execute()) {
                $response['condition'] = 1;
            } else {
                $response['condition'] = 0; // Failure in the second insert
            }

            $stmt3->close(); // Close this statement inside this block
        } else {
            $response['condition'] = 1;
        }
    } else {
        $response['condition'] = 0; // Failure in the first insert
    }

    // Output the JSON response
    echo json_encode($response);

    // Close the statements and the database connection
    $stmt0->close();
    $stmt1->close();
    $stmt4->close();
    $conn->close();
}
?>