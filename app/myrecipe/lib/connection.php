<?php
$db=new mysqli('localhost','root','','geniusdb');
if($db->errno>0)
    die('Unable to connect to database'.$db->error);
//session_start();
?>
