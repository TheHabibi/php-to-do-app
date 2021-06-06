<?php

require_once "./db.php";

extract($_GET);

$sql =  "update note set important = NOT important where id = ?";
$rs = $db->prepare($sql);
$rs->execute([$id]);



