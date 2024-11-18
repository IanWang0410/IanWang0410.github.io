<?php
    require_once "conn.php";
    require_once "validate.php";

    $id = validate($_POST['id']);
    $result = $conn->query("SELECT * FROM sensor_history WHERE SENSOR_Id = '$id' ORDER BY SENSOR_Time DESC LIMIT 1");
    $output = array();

    //假如在iot找的到該sensor_id的gas_empty_weight的話 要減掉
    //這裡只有一個sensor_id
    

    while ($row = $result->fetch_assoc()) // 當該指令執行有回傳
    {
        $result_1 = $conn->query("SELECT * FROM iot WHERE SENSOR_Id = '$id'");

        while($row_1 = $result_1->fetch_assoc()){
            if($row_1['Gas_Empty_Weight'] !== null){
                $Gas_Empty_Weight = $row_1['Gas_Empty_Weight'];
                if(round(($row['SENSOR_Weight']/1000 - $Gas_Empty_Weight),1)>0){
                    $Gas_Original_Weight = $row_1['Gas_Original_Weight'];
                    if($Gas_Original_Weight === null){
                        $Gas_remain = (($row['SENSOR_Weight']/1000) - $Gas_Empty_Weight);
                        if($Gas_remain<0){
                            $Gas_remain = 0;
                        }
                    }
                    else{
                        
                        $Gas_remain = ($row['SENSOR_Weight']/1000 - $Gas_Empty_Weight)/(($Gas_Original_Weight/1000) - $Gas_Empty_Weight);
                        if($Gas_remain<0){
                            $Gas_remain = 0;
                        }
                    }
                }
                else{
                    $Gas_remain = 0;
                }
                if($Gas_remain<1) {
                    $row['Gas_remain'] = round($Gas_remain,2)*100;
                } else {
                    $row['Gas_remain'] = $Gas_remain;
                }
            }
         }
        $output[] = $row; // 就逐項將回傳的東西放到陣列中
    }

    // 將資料陣列轉成 Json 並顯示在網頁上，並要求不把中文編成 UNICODE
    print(json_encode($output, JSON_UNESCAPED_UNICODE));
    $result -> close(); // 關閉資料庫連線

?>