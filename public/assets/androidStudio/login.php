<?php
include 'connection.php';

if($_POST){
    //Data
    $username = $_POST['username'];
    $password = $_POST['password'];

    $response = []; //Data Response

    //Check username if correct
    $userQuery = $connection->prepare("SELECT * FROM users WHERE (username = '$username') AND is_active IS TRUE");
    $userQuery->execute(array($username));
    $query = $userQuery->fetch();

    if($userQuery->rowCount() == 0){
        $response['status'] = false;
        $response['message'] = "Login Invalid";
    } else {
        if(password_verify($password, $query['password']))
        {
            $response['status'] = true;
            $response['message'] = "Login Successful";
            $response['data'] = [
                'userid' => $query['id'],
                'username' => $query['username'],
                'name' => $query['name'],
            ];
        } else {
            $response['status'] = false;
            $response['message'] = "Password does not match";
        }
    }
    $json = json_encode($response, JSON_PRETTY_PRINT);
    echo $json;
}
