<?php
// Check if newpassword are set
if(isset($_POST['id'])){
    // Include the necessary files
    require_once "conn.php";
    require_once "validate.php";
    // Call validate, pass form data as parameter and store the returned value
    $id = validate($_POST['id']);
    // Create the SQL query string
    $sql = "select * from worker where `WORKER_Id`='$id'";
    // Execute the query
    $result = $conn->query($sql);
    // If number of rows returned is greater than 0 (that is, if the record is found), we'll print "success", otherwise "failure"
    if($result->num_rows > 0){
        $row = mysqli_fetch_assoc($result);
        $Worker_Name = $row['WORKER_Name'];
        $Worker_Tel = $row['WORKER_HouseTelpNo'];
        $Worker_Phone = $row['WORKER_PhoneNum'];
        $Worker_Address = $row['WORKER_Address'];
        $Worker_Email = $row['WORKER_Email'];
        $worker_data = array();
        $worker_data['Worker_Name'] = $Worker_Name;
        $worker_data['Worker_Tel'] = $Worker_Tel;
        $worker_data['Worker_Phone'] = $Worker_Phone;
        $worker_data['Worker_Address'] = $Worker_Address;
        $worker_data['Worker_Email'] = $Worker_Email;
        $worker_data['response'] = 'success';
        echo json_encode($worker_data);

        //echo "success";
    } else{
        // If no record is found, print "failure"
        echo json_encode(["response" => "failure"]);
        //echo "failure";
    }
}
?>