<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    require_once "conn.php";
    require_once "validate.php";

    if(isset($_POST['iot']) && isset($_POST['remain']) && isset($_POST['gas_id']) && isset($_POST['customer_id']) && isset($_POST['order_id'])) {
        $iot = validate($_POST['iot']);
        $remain = validate($_POST['remain']);
        $gas_id = validate($_POST['gas_id']);
        $customer_id = validate($_POST['customer_id']);
        $order_id = validate($_POST['order_id']);

        //update iot of customer
        $getEmpty = "SELECT `GAS_Weight_Empty` FROM gas WHERE `GAS_Id` = ?;";
        $stmt = $conn->prepare($getEmpty);
        $stmt->bind_param("s", $gas_id);
        $stmt->execute();
        $stmt->bind_result($empty);
        $stmt->fetch();
        $stmt->close();

        $updateIot = "UPDATE `iot` SET `Gas_Id`= ?, `Gas_Empty_Weight` = ? WHERE `SENSOR_Id` = ?;";
        $stmt = $conn->prepare($updateIot);
        $stmt->bind_param("sii", $gas_id, $empty, $iot);
        $stmt->execute();
        $stmt->close();


        //update accumulation of customer
        $hasAccu = "SELECT `GAS_Volume` FROM customer_accumulation WHERE `Customer_Id`= ?;";
        $stmt = $conn->prepare($hasAccu);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $stmt->bind_result($volume);
        if ($stmt->fetch()) {
            $stmt->close();
            
            $accu = $volume + $remain;
            $updateAccu = "UPDATE `customer_accumulation` SET `GAS_Volume` = ? WHERE `Customer_Id`= ?;";
            $stmt = $conn->prepare($updateAccu);
            $stmt->bind_param("di", $accu, $customer_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt->close();
            
            $getCompanyOfCustomer = "SELECT `COMPANY_Id` FROM customer WHERE `CUSTOMER_Id` = ?;";
            $stmt = $conn->prepare($getCompanyOfCustomer);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $stmt->bind_result($company_id);
            $stmt->fetch();
            $stmt->close();
            
            $updateAccu = "INSERT INTO `customer_accumulation`(`Gas_Volume`, `Customer_Id`, `Company_Id`) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($updateAccu);
            $stmt->bind_param("dii", $remain, $customer_id, $company_id);
            $stmt->execute();
            $stmt->close();
        }


        //update the order to finished
        $finish = "UPDATE `gas_order` SET `DELIVERY_Time` = ?, `DELIVERY_Condition` = ? WHERE `ORDER_Id` = ?;";
        date_default_timezone_set('Asia/Taipei');
        $current_time = date("Y-m-d H:i:s");
        $blob = 1;
        $stmt = $conn->prepare($finish);
        $stmt->bind_param("sii", $current_time, $blob, $order_id);
        $stmt->execute();
        $stmt->close(); 
    }

?>