<?php
// Check if newpassword are set
if(isset($_POST['id'])){
    
    //require_once "conn.php";
    //require_once "validate.php";
    $conn = mysqli_connect("database-1.cxzg4akd6dhy.ap-northeast-1.rds.amazonaws.com", "admin", "Bk+w%H86", "gasstation");
     $conn -> set_charset("UTF8"); 
     function validate($data) {
         // Strip unnecessary characters for example extra space, tab, newline from the user input
         $data = trim($data);
         // Remove backslashes    
         $data = stripslashes($data);
         // Convert special characters to HTML entities, thus preventing attackers from exploiting the code
         $data = htmlspecialchars($data);
         // Return validated data
         return $data;
     }
    
    $id = validate($_POST['id']);
    // Create the SQL query string
    $sql = "select * from gas where `GAS_Id`='$id'";
    
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        $row = mysqli_fetch_assoc($result);
        $GAS_Id = $row['GAS_Id'];
        $GAS_Volume = $row['GAS_Volume'];
        $GAS_Type = $row['GAS_Type'];
        $Gas_Weight_Empty = $row['GAS_Weight_Empty'];
        $gas_data = array();
        $gas_data['GAS_Id'] = $GAS_Id;
        $gas_data['GAS_Volume'] = $GAS_Volume;
        $gas_data['GAS_Type'] = $GAS_Type;
        $gas_data['GAS_Weight_Empty'] = $Gas_Weight_Empty;
        $gas_data['response'] = 'success';
        echo json_encode($gas_data);

        //echo "success";
    } else{
        // If no record is found, print "failure"
        echo json_encode(["response" => "failure"]);
        //echo "failure";
    }
}
?>