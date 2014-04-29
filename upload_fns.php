<?php
function count_value($set_p1,$set_p2){
	if($set_p1>$set_p2){
		return 27-$set_p1;
	}else
		return $set_p1+$set_p2;
}
function update_password($conn){
	$password = $_POST['password'];
	$id_ustc = $_SESSION['valid_id_ustc'];
	if(!$password||strlen($password)<6 ||strlen($password)>16){
		throw new Exception ('密码格式有误，<a href="member.php#profile"data-ajax="false">返回</a>');
	}
	if(!$id_ustc){
		throw new Exception ('登录状态异常，<a href="member.php#profile"data-ajax="false">返回</a>');
	}
	$sql = 'UPDATE user SET passwd_sha = sha1("'.$password.'")
WHERE id_ustc = "'.$id_ustc.'";';
	$result = $conn->query($sql);
	if(!$result){
		throw new Exception ('无法连接数据库，<a href="member.php#profile"data-ajax="false">返回</a>');
	}
	return;
}
function upload_free($flag,$conn){
	//$flag=1为上传单打   $flag=2为上传双打
	date_default_timezone_set('PRC');
	$id_p1 = $_SESSION['valid_id_ustc'];
	$name_p2 = $_POST['name_p2'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$hour = $_POST['hour'];
	$court = $_POST['court'];
	$comment = $_POST['comment'];
	
	$time = strtotime( date("Y",time())."-".$month."-".$day." ".$hour.":00:00");
	if($flag==1){
		$set_p1 = $_POST['set_p1'];
		$set_p2 = $_POST['set_p2'];
		$value_p1 = count_value($set_p1,$set_p2);
		$value_p2 = count_value($set_p2,$set_p1);
		$result = $conn->query('select id_ustc from user where name="'.$name_p2.'";');
		$id_p2 = $result->fetch_assoc()['id_ustc'];
		//set_html_header(1);
		//echo($id_p1." ".$name_p2." ".$month." ".$day." ".$hour." ".$set_p1." ".$set_p2." ".$court." ".$comment);
		//$time为当前年份与表单中month、day、hour一起生成的上传时间，分秒均取零
		$result = $conn->query('insert into game_free values(
			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$set_p1.'","'.$set_p2.'","'.$value_p1.'","'.$value_p2.'","'.$court.'","'.$comment.'");');
	}else{
		$name_p3 = $_POST['name_p3'];
		$name_p4 = $_POST['name_p4'];
		$set_p1n2 = $_POST['set_p1n2'];
		$set_p3n4 = $_POST['set_p3n4'];
		$value_p1n2 = count_value($set_p1n2,$set_p3n4);
		$value_p3n4 = count_value($set_p3n4,$set_p1n2);
		$result = $conn->query('select id_ustc from user where name="'.$name_p2.'"
								union
								select id_ustc from user where name="'.$name_p3.'"
								union
								select id_ustc from user where name="'.$name_p4.'";');
		$id_p2 = $result->fetch_assoc()['id_ustc'];
		$id_p3 = $result->fetch_assoc()['id_ustc'];
		$id_p4 = $result->fetch_assoc()['id_ustc'];
		$result = $conn->query('insert into game_double values(
			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$id_p3.'","'.$id_p4.'","'.$set_p1n2.'","'.$set_p3n4.'","'.$value_p1n2.'","'.$value_p3n4.'","'.$court.'","'.$comment.'");');
	}
	
	if (!$result) {
		//throw new Exception ($id_p4.'insert into game_double values(
		//			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$id_p3.'","'.$id_p4.'","'.$set_p1n2.'","'.$set_p3n4.'","'.$value_free.'","'.$court.'","'.$comment.'");'.'请勿重复刷新本页，<a href="member.php">返回</a>');
		throw new Exception ('请勿重复刷新本页，<a href="upload.php#single"data-ajax="false">返回</a>');
	}
	return true;
}
function upload_practice($conn){
	date_default_timezone_set('PRC');
	$id_p1 = $_SESSION['valid_id_ustc'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$hour = $_POST['hour'];
	$court = $_POST['court'];
	$duration = $_POST['duration'];
	$item = $_POST['item'];
	$sum_item = 0;
	if($item){
		foreach($item as $it){
			$sum_item += $it;
		}
	}
	$time = strtotime( date("Y",time())."-".$month."-".$day." ".$hour.":00:00");
	
	
	$sql = 'insert into practice values(';
	$sql.= 'NULL,"';
	$sql.= $time;
	$sql.= '","';
	$sql.= $id_p1;
	$sql.= '","';
	$sql.= $duration;
	$sql.= '","';
	$sql.= $sum_item;
	$sql.= '","';
	$sql.= $court;
	$sql.= '");';
	$result = $conn->query($sql);
	if(!$result){
		throw new Exception ('上传失败...再试试吧~<a href="member.php"> 返回</a>');
	}
	return true;
}
function get_id($conn,$name){
		//输入name，输出相应的id_ustc
		$result = $conn->query('select id_ustc from user where name="'.$name.'";');
		if(!$result){
			return false;
		}
		else
			return $result->fetch_assoc()['id_ustc'];
}
function upload_tour($conn){
	date_default_timezone_set('PRC');
	$name1 = $_POST['name1'];
	$name2 = $_POST['name2'];
	if(!get_id($conn,$name1)||!get_id($conn,$name2)){
		throw new Exception ('未收到有效输入');
	}
	$id_p1 = get_id($conn,$name1);
	$id_p2 = get_id($conn,$name2);
	$set_p1 = $_POST['set_p1'];
	$set_p2 = $_POST['set_p2'];
	$value_p1 = $_POST['value_p1'];
	$value_p2 = $_POST['value_p2'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$hour = $_POST['hour'];
	$court = $_POST['court'];
	$conn->query("SET NAMES UTF8");
	$time = strtotime($year."-".$month."-".$day." ".$hour.":00:00");	
	$result = $conn->query('insert into game_tour values(
			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$set_p1.'","'.$set_p2.'","'.$value_p1.'","'.$value_p2.'","'.$court.'",NULL);');
	if (!$result) {
		throw new Exception (/*'insert into game_tour values(
			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$set_p1.'","'.$set_p2.'","'.$value_p1.'","'.$value_p2.'","'.$court.'",NULL);'.*/'上传失败，未连接到数据库');
	}
	return true;	
}


?>