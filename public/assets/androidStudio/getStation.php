<?php
$server="rama-prod.ctcpkgeop5m3.ap-southeast-1.rds.amazonaws.com";
$username="admin";
$password="P1ULTFRB";
$database="ramaprod";

$conn = mysqli_connect($server,$username,$password,$database);

if(!$conn){ die("connection failed: ".mysqli_connect_errno); }

$sql = "SELECT id, name FROM stations WHERE region_id=(SELECT region_id from users WHERE id='".$_GET['id']."')";

if(!$conn->query($sql)){ echo "Error in connecting to Database."; }

else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['stations'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['stations'], array(
				'station_id'=>$row['id'],
                'name'=>$row['name']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>
