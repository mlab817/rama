<?php
$connection = null;
try{
    $host = "rama-prod.ctcpkgeop5m3.ap-southeast-1.rds.amazonaws.com";
    $username = "admin";
    $password = "P1ULTFRB";
    $dbname = "ramaprod";
    $database = "mysql:dbname=$dbname;host=$host";
    $connection = new PDO($database, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    echo "Error ! " . $e->getMessage();
    die;
}
?>
