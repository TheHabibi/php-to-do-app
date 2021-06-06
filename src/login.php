<?php

session_start();

require_once "./db.php";

extract($_POST);


$rs = $db->prepare("select * from user where email = ?");
$rs-> execute([$email]);

if ($rs->rowCount() === 1){

    $user = $rs->fetch(PDO::FETCH_ASSOC);
    if(password_verify($password, $user["password"])){

        $_SESSION["user"] = $user;
    header ("Location: main.php");
    exit;
    }
    else{
        $errMsg = "Wrong credentials";
    }
    
}
 header("Location: index.php");
