<?php

function generate_title($name1,$name2,$set_p1,$set_p2){
	return "<a href=\"#\">".$name1."</a>&nbsp;&nbsp;".$set_p1."-".$set_p2."&nbsp;&nbsp;<a href=\"#\">".$name2."</a>";
}
function generate_content($time,$court){
	
	if($court==1){
		$court_name = "东区网球场";
	}else{
		$court_name = "西区网球场";
	}
	$time = intval($time,10);
	$delta = (int)((time()/3600/24))-(int)(($time/3600/24));
	if($delta==0){
		$hour = (int)($time%(3600*24)/3600);
		$delta = $hour."小时前";
	}else{
		$delta = $delta."天前";
	}
	return $delta."，".$court_name;
}
function display_timeline(){
	//所有会员暂时使用同一个timeline
	date_default_timezone_set('PRC');
	$conn = db_connect();
	$conn->query("SET NAMES UTF8");
	$result_p1 = $conn->query("select time,name,set_p1,court,comment from game
							join user
							where game.id_p1=user.id_ustc
							order by time desc
							limit 15;");
	$result_p2 = $conn->query("select time,name,set_p2 from game
							join user
							where game.id_p2=user.id_ustc
							order by time desc
							limit 15;");
	//获得15条Timeline数据
   	$num_results = $result_p1->num_rows;
   	for ($i=0; $i < $num_results;$i++) {
		$row_p1 = $result_p1->fetch_assoc();
		$row_p2 = $result_p2->fetch_assoc();
		$title = generate_title($row_p1['name'],$row_p2['name'],$row_p1['set_p1'],$row_p2['set_p2']);
		$content = generate_content($row_p1['time'],$row_p1['court'],$row_p1['comment']);
		echo_event($title,$content);
	}
}
function display_table($flag){
	?>
<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive"  data-column-popup-theme="a">
			 <thead>
			 <tr class="ui-bar-d">
				 <th>名次</th>
				 <th>姓名</th>
				 <th>积分</th>
			 </tr>
			 </thead>
			 <tbody>
<?php
	//generate <tr> and <td>
	if($flag==1){
		$conn = db_connect();
		$conn->query('SET NAMES UTF8');
		$result = $conn->query('select * from score_free;');
		$num_results = $result->num_rows;
		for ($i=1; $i <= $num_results;$i++){
			$row = $result->fetch_assoc();
			echo '<tr><td>'.$i.'</td><td>'.$row['name'].'</td><td>'.$row['total_score'].'</td></tr>';
		}
	}
	#else{}
	?>
			</tbody>
			</table>
			</br>
<?php
	//generate game info of current user
	$id_ustc = $_SESSION['valid_id_ustc'];
	$result = $conn->query('select total_score from score_free where id_p1= "'.$id_ustc.'";');
	//get name of current user, then query to count his rank
	$row = $result->fetch_assoc();
	$my_total_score = $row['total_score'];
	$result = $conn->query('select count(total_score) as count from score_free where total_score >="'.$my_total_score.'";');
	$row = $result->fetch_assoc();
	$count = $row['count'];
	$rank = (int)(($count/($i-1))*100);
	//总人数由i求出，将受表格行数影响，需要改进
	echo_event("我的自由赛","".$my_total_score."分，排第".$count."名，前".$rank."%");
}
?>