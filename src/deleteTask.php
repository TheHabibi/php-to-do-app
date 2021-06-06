<?php
require_once "./db.php";

var_dump($_GET);


if (isset($_GET)) {
    
    $id = $_GET["id"] ?? "";
  
    try {
        $rs = $db->prepare("delete from note where id = ?");
        $rs->execute([$id]);
       
        if ($rs->rowCount() == 0) $errMsg = "Already deleted";
    } catch (PDOException $ex) {
        $errMsg = "Delete Fail";
    }
}