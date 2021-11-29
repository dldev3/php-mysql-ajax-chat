<?php

#server name
$sName = "localhost";

#username
$uName = "root";

#password
$pass = "";


#db name
$db_name = "chat_php";


#creating dn connection
try {

    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed : " . $e->getMessage();
}
