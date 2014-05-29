<?php 
require_once('tennis_fns.php');
$count = $_GET['count'];
$timeline = new timeline($count);
?>