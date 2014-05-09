<?php
function count_double_value($set_p1,$set_p2){
	
	if($set_p1>$set_p2){
		return 27-($set_p1+$set_p2);
	}else
		return $set_p1+$set_p2;
}
function count_single_value($id_p1,$id_p2,$set_p1,$set_p2,$conn){
	$p = getRatio($conn,$id_p1,$id_p2);
	if($set_p1>$set_p2){
		return $p*(27-($set_p1+$set_p2));
	}else
		return $p*($set_p1+$set_p2);
}
//积分为7天内->604800
function getRatio($conn,$id_p1,$id_p2){
	$sql = '
		select * from
		(select id_p1 as id,name,sum(sum_single) as score from(
			select time,sum(sum_half) as sum_single,name,id_p1 from(
				select time,sum(value_p1) as sum_half,name_p1 as name,id_p1 from game_free
				group by name_p1
				union all
				select time,sum(value_p2),name_p2,id_p2 from game_free
				group by name_p2)
			as sum_hf1
			group by name
			union
			select time,sum(sum_tmp) as sum_double,name,id_p1 from(
				select time,sum(value_p1n2) as sum_tmp,name_p1 as name,id_p1 from game_double
				where time
				group by name_p1 union all
				select time,sum(value_p1n2),name_p2 as name,id_p2 from game_double
				group by name_p2 union all
				select time,sum(value_p3n4),name_p3 as name,id_p3 from game_double
				group by name_p3 union all
				select time,sum(value_p3n4),name_p4 as name,id_p4 from game_double
				group by name_p4)
			as sum_hf2
			group by name)as sum_hf where UNIX_TIMESTAMP()-time < 604800
		group by name)as rank
		where id="';
		
	$res = $conn->query($sql.$id_p1.'";');
	$score1 = $res->fetch_assoc()['score'];

	$res = $conn->query($sql.$id_p2.'";');
	$score2 = $res->fetch_assoc()['score'];
	
	//即便sql查找没有找到id_p1，判断仍可用
	if($score1==0){
		$score1 = 6;
	}
	
	$p = (float)$score2/(float)$score1;
	if($p < 1){
		$p = 1;
	}
	else if($p > 2.5){
		$p = 2.5;
	}
	return $p;
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
	if($flag==1){
		$id_p1 = $_SESSION['valid_id_ustc'];
		$name_p2 = $_POST['name_p2'];
		$month = $_POST['month'];
		$day = $_POST['day'];
		$hour = $_POST['hour'];
		$court = $_POST['court'];
		$comment = $_POST['comment'];
		$set_p1 = $_POST['set_p1'];
		$set_p2 = $_POST['set_p2'];
		$time = strtotime( date("Y",time())."-".$month."-".$day." ".$hour.":00:".date("s"));
		$result = $conn->query('select id_ustc from user where name="'.$name_p2.'";');
		$id_p2 = $result->fetch_assoc()['id_ustc'];
		$value_p1 = count_single_value($id_p1,$id_p2,$set_p1,$set_p2,$conn);
		$value_p2 = count_single_value($id_p2,$id_p1,$set_p2,$set_p1,$conn);
		
		$result = $conn->query('select name from user where id_ustc="'.$id_p1.'";');
		$name_p1 = $result->fetch_assoc()['name'];
		
		$sql = 'insert into game_free values(
			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$set_p1.'","'.$set_p2.'","'.$value_p1.'","'.$value_p2.'","'.$court.'","'.$comment.'","'.$name_p1.'","'.$name_p2.'");';
		//throw new Exception ($sql);
		$result = $conn->query($sql);
	}else if($flag==2){
		$id_p1 = $_SESSION['valid_id_ustc'];
		$name_p2 = $_POST['name_p2'];
		$month = $_POST['month'];
		$day = $_POST['day'];
		$hour = $_POST['hour'];
		$court = $_POST['court'];
		$comment = $_POST['comment'];
		$name_p3 = $_POST['name_p3'];
		$name_p4 = $_POST['name_p4'];
		$set_p1n2 = $_POST['set_p1n2'];
		$set_p3n4 = $_POST['set_p3n4'];
		$time = strtotime( date("Y",time())."-".$month."-".$day." ".$hour.":00:00");
		$value_p1n2 = count_double_value($set_p1n2,$set_p3n4);
		$value_p3n4 = count_double_value($set_p3n4,$set_p1n2);
		$result = $conn->query('select name from user where id_ustc="'.$_SESSION['valid_id_ustc'].'";');
		$name_p1 = $result->fetch_assoc()['name'];
		
		//依次根据输入的姓名（name_p2 - name_p4）查找协会会员id
		$result = $conn->query('select id_ustc from user where name="'.$name_p2.'";');
			$id_p2 = $result->fetch_assoc()['id_ustc'];
		$result = $conn->query('select id_ustc from user where name="'.$name_p3.'";');
			$id_p3 = $result->fetch_assoc()['id_ustc'];
		$result = $conn->query('select id_ustc from user where name="'.$name_p4.'";');
			$id_p4 = $result->fetch_assoc()['id_ustc'];
		
		$sql = 'insert into game_double values(
			NULL,"'.$time.'","'.$id_p1.'","'.$id_p2.'","'.$id_p3.'","'.$id_p4.'","'.$set_p1n2.'","'.$set_p3n4.'","'.$value_p1n2.'","'.$value_p3n4.'","'.$court.'","'.$comment.'","'.$name_p1.'","'.$name_p2.'","'.$name_p3.'","'.$name_p4.'");';
		//throw new Exception ($sql);
		$result = $conn->query($sql);
	}else throw new Exception ('请勿重复刷新本页，<a href="upload.php#single"data-ajax="false">返回</a>');
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