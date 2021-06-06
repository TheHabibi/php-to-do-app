<?php

require_once "./db.php";

extract($_GET);

$sql =  "update note set status = NOT status where id = ?";
$rs = $db->prepare($sql);
$rs->execute([$id]);




