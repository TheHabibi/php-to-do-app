<?php
session_start();
 header("Content-Type: application/json") ;
require_once "./db.php";

$user = $_SESSION["user"];

$lists = $db->prepare("select * from list where userID = ?");
$lists->execute([$user["id"]]);

$sql = "select note.listID,count(note.id),list.name from note join list ON list.id = note.listID where status = 0 group by listID  ";
$rs = $db->prepare($sql);
$rs->execute();

$data;


$data = array();


foreach($lists as $l)
{
    $data[$l["id"]] = "" ;
}


foreach ($rs as $list) {


//var_dump($list["listID"]);


//var_dump($list["count(id)"]);

//var_dump($list);

$count = +($list["count(note.id)"]);

$data[$list["listID"]] = $count ;

//$data = ["dolar" => rand(370, 400)/100];

 
}

//var_dump($data);


echo json_encode($data);


