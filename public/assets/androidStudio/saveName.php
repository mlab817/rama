<?php
define('DB_HOST','rama-prod.ctcpkgeop5m3.ap-southeast-1.rds.amazonaws.com');
define('DB_USERNAME','admin');
define('DB_PASSWORD','P1ULTFRB');
define('DB_NAME', 'ramaprod');

//Connecting to the database
$connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$success1 = false;
$success2 = false;

//checking the successful connection
if($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

//if there is a post request move ahead
if($_POST['detail']){
    $long_array_info = $_POST['detail'];
    if(!empty($long_array_info)){
        $result = explode('/',$long_array_info);
        $save_data = $connection->prepare("INSERT INTO puv_details (date_scanned, time_scanned, station_id, bound, plate_no, user_id) VALUES ( '$result[5]', '$result[6]', '$result[0]', '$result[1]', '$result[4]', '$result[2]')");
        $save_data->execute();
        $success1 = true;
    }
}

if($_POST['name']){
    $long_array_info2 = $_POST['name'];
    if(!empty($long_array_info2)){
        $stmt = $conn->prepare("INSERT INTO puv_attendance (location) VALUES (?)");
        $stmt->bind_param("s", $long_array_info2);
        $stmt->execute();
        $success2 = true;
    }
}

//if data inserts successfully
if($success1 || $success2){
    //making success response
    $response['error']   = false;
    $response['message'] = 'Successfully Added';
}else{
    //if not making failure response
    $response['error']   = true;
    $response['message'] = 'Please try later';
}

//displaying the data in json format
echo json_encode($response);
?>
