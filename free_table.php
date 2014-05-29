<?php 
require_once('tennis_fns.php');
session_start();
function get_single_row($event,$name){
		$row = $event->fetch_assoc();
		date_default_timezone_set('PRC');
		$time = date('m-d',$row['time']);
		$id_game = $row['id_game'];
		$name_p1 = $row['name_p1'];
		$name_p2 = $row['name_p2'];
		$set_p1 = $row['set_p1'];
		$set_p2 = $row['set_p2'];
		$value_p1 = $row['value_p1'];
		$value_p2 = $row['value_p2'];
		if($name_p1==$name){
			$value = $value_p1;
		}else $value = $value_p2;
		$str =  "<tr><td>".$time."</td><td>".$name_p1."</td><td><b>".$set_p1."-".$set_p2."</b></td><td>".$name_p2."</td><td><b>".$value."分</b></td>";
		if(!isset($_GET['id'])){
			$str.= '<td><a href="modify_verify.php?tp=fr_del&amp;id_game='.$id_game.'"data-ajax="false">删除</a></td></tr>';
		}
		else $str.= '</tr>';
		return $str;
}
function get_double_row($event,$name){
		$row = $event->fetch_assoc();
		date_default_timezone_set('PRC');
		$time = date('m-d',$row['time']);
		$id_game_double = $row['id_game_double'];
		$name_p1 = $row['name_p1'];
		$name_p2 = $row['name_p2'];
		$name_p3 = $row['name_p3'];
		$name_p4 = $row['name_p4'];
		$set_p1n2 = $row['set_p1n2'];
		$set_p3n4 = $row['set_p3n4'];
		$value_p1n2 = $row['value_p1n2'];
		$value_p3n4 = $row['value_p3n4'];
		if($name_p1==$name||$name_p2==$name){
			$value = $value_p1n2;
		}else $value = $value_p3n4;
		$str =  "<tr><td>".$time."</td><td>".$name_p1."<br/>".$name_p2."</td><td><b>".$set_p1n2."-".$set_p3n4."</b></td><td>".$name_p3."<br/>".$name_p4."</td><td><b>".$value."分</b></td>";
		if(!isset($_GET['id'])){
			$str.= '<td><a href="modify_verify.php?tp=fr_del&amp;id_game='.$id_game.'"data-ajax="false">删除</a></td></tr>';
		}
		else $str.= '</tr>';
		return $str;
}
$conn = db_connect();
$count = $_GET['count'];
//为个人profile页面(member.php)生成表格时，member.php发来的GET无id类
//在此情况下通过session获得id需要添加删除比赛的链接
if(!isset($_GET['id']) ){
	$id_ustc = $_SESSION['valid_id_ustc'];
}
else $id_ustc = $_GET['id'];

$event = $conn->query('select * from user where id_ustc="'.$id_ustc.'";');
$name = $event->fetch_assoc()['name'];

$event = $conn->query('select * from user where id_ustc="'.$id_ustc.'";');
$sql = 'select * from game_free where name_p1="'.$name.'" or name_p2="'.$name.'"
order by time desc
limit '.$count.',5;';
$event = $conn->query($sql);
$num1 = $event->num_rows;

$str.= '<br/><table data-role="table" data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive ui-table ui-table-columntoggle" data-column-popup-theme="a"><tbody>';

for($i=0;$i<$num1;$i++){
	$str.=get_single_row($event,$name);
}
$str.='</tbody></table><br/><table data-role="table" data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive ui-table ui-table-columntoggle" data-column-popup-theme="a"><tbody>';

 


//显示双打部分
$sql = 'select * from game_double where 
	name_p1="'.$name.'" 
	or name_p2="'.$name.'" 
	or name_p3="'.$name.'"
	or name_p4="'.$name.'"
	order by time desc
	limit '.$count.',5;';
//echo_short($sql);
$event = $conn->query($sql);
$num2 = $event->num_rows;
$event = $conn->query($sql);

for($i=0;$i<$num2;$i++){
	$str.=get_double_row($event,$name);
}
$str.="</tbody></table>";
echo $str;
?>