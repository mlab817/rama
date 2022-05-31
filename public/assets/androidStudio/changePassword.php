<?php
include 'connection.php';
date_default_timezone_set('Asia/Manila');

if($_POST){
    //Data
    $userid = $_POST['userid'];
    $password = $_POST['password'];	
    $new_password = $_POST['new_password'];
    
    $response = []; //Data Response

    //Check user if correct
    $userQuery = $connection->prepare("SELECT * FROM users WHERE (id = '$userid') AND is_active IS TRUE");
    $userQuery->execute(array());
    $query = $userQuery->fetch();

    if ($userQuery->rowCount() == 0) {
        $response['error'] = true;
        $response['message'] = "Update failed";
    } else {
        if (password_verify($password, $query['password']))
        {
			$new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $date = date('Y-m-d H:i:s');

			$data = array($new_password, $date, $userid); 
			$updateQuery = $connection->prepare("UPDATE users SET password=?, updated_at=? WHERE id=?");
			$updateQuery->execute($data);

			$response['error'] = false;
			$response['message'] = "Update Successful";
        } else {
            $response['error'] = true;
            $response['message'] = "Password does not match";
        }
    }
    $json = json_encode($response, JSON_PRETTY_PRINT);
    echo $json;

}