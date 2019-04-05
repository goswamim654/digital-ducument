<?php
include 'dbconnect.php';
$id = (int)$_POST['id'];
$sql = "SELECT * from test_digital_document_templates WHERE id='$id'";
$res = $con->query($sql);
$row = $res->fetch_object();
$content =  $row->content;
//echo $content;
echo json_encode($content);
?>