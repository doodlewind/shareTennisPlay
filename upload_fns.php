<?php
function count_free_value($set_p1,$set_p2){
	//计算自由赛事积分，与局数相关
	return ($set_p1+$set_p2)*5;
}
function upload_free(){
	date_default_timezone_set('PRC');
	$id_p1 = $_SESSION['valid_id_ustc'];
	$name_p2 = $_POST['name_p2'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$hour = $_POST['hour'];
	$set_p1 = $_POST['set_p1'];
	$set_p2 = $_POST['set_p2'];
	$court = $_POST['court'];
	$comment = $_POST['comment'];
	$conn = db_connect();
	$conn->query("SET NAMES UTF8");
	$result = $conn->query('select id_ustc from user where name="'.$name_p2.'";');
	$id_p2 = $result->fetch_assoc()['id_ustc'];
	
	
	$time = strtotime( date("Y",time())."-".$month."-".$day." ".$hour.":00:00");
	$value_free = count_free_value($set_p1,$set_p2);
	//$time为当前年份与表单中month、day、hour一起生成的上传时间，分秒均取零

	$result = $conn->query('insert into game values(
		NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$set_p1.'","'.$set_p2.'","'.$value_free.'",NULL,"'.$court.'","'.$comment.'");');
	if (!$result) {
		throw new Exception ('请勿重复刷新本页，<a href="member.php">返回</a>');
	}
	return true;
	
}
?>