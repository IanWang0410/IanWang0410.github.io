<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Check if newpassword are set
if(isset($_POST['id'])){
    // Include the necessary files
    require_once "conn.php";
    require_once "validate.php";
    // Call validate, pass form data as parameter and store the returned value
    $id = validate($_POST['id']);
    // Create the SQL query string
    $sql = "select * from gas_order where `ORDER_Id`='$id'";
    // Execute the query
    $result = $conn->query($sql);
    // If number of rows returned is greater than 0 (that is, if the record is found), we'll print "success", otherwise "failure"
    if($result->num_rows > 0){
        $row = mysqli_fetch_assoc($result);
        $Customer_Id = $row['CUSTOMER_Id'];
        $ordertime = $row['Order_Time'];
        $expectTime = $row['Expect_time'];
        $sql1 = "SELECT CUSTOMER_Name FROM customer WHERE `CUSTOMER_Id` = '$Customer_Id'";
        $result1 = $conn->query($sql1);
        $row1 = mysqli_fetch_assoc($result1);
        $Order_Name = $row1['CUSTOMER_Name'];
        $Order_Phone = $row['DELIVERY_Phone'];
        $Order_Address = $row['DELIVERY_Address'];
        $Gas_Quantity = $row['Gas_Quantity'];
        $order_Info = array();
        $order_Info['Customer_Id'] = $Customer_Id;
        $order_Info['Order_Name'] = $Order_Name;
        $order_Info['Order_Phone'] = $Order_Phone;
        $order_Info['Order_Address'] = $Order_Address;
        $order_Info['Order_Time'] = $ordertime;
        $order_Info['Expect_time'] = $expectTime;
        $order_Info['Gas_Quantity'] = $Gas_Quantity;
        $order_Info['response'] = 'success';
        echo json_encode($order_Info);

        //echo "success";
    } else{
        // If no record is found, print "failure"
        echo json_encode(["response" => "failure"]);
        //echo "failure";
    }
}
?>