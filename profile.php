<?function set_profile_header(){
?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="../jquery/jquery.mobile-1.4.0.css">
	<script src="../jquery/jquery-2.0.3.min.js"></script>
	<script src="../jquery/jquery.mobile-1.4.0.js"></script>
	<script src="../jquery/zxml.js"></script>
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
	<style>
	#event_id,#event_type{
		display:none !important;
	}
	.ui-table-columntoggle-btn {
	    display: none !important;
	}
	</style>
	<script type="text/javascript">
	var countFreeTable = 0;
	var oXmlHttpFreeTable = zXmlHttp.createRequest();
	oXmlHttpFreeTable.onreadystatechange = function(){
		if (oXmlHttpFreeTable.readyState == 4) {
			var freeTable = document.getElementById("freeProfileTable");
			freeTable.innerHTML += oXmlHttpFreeTable.responseText;
		}
	}
	function showMoreFreeTable(){
		var linkToGet = "free_table.php?count="+countFreeTable+"&id="+<?php echo '"'.$_GET['id_ustc'].'"';?>;
		oXmlHttpFreeTable.open("get",linkToGet,true);
		
		oXmlHttpFreeTable.send(null);
		countFreeTable += 5;
	}
	showMoreFreeTable();
	</script>
	<title>USTC-TENNIS</title>
	</head>
	<body>	
		<iframe width='0' height='0' frameborder='0' src="cache.html"></iframe>
<?php
}

require_once('tennis_fns.php');
session_start();
class profile{
	public $id_ustc;
	public $name;
	public $mobile;
	public $score_free;
	public $score_tour;
	public $game_attend;
	public $game_win;
	public function __construct(){
		date_default_timezone_set('PRC');
		$this->id_ustc = $_GET['id_ustc'];
		if(!$this->id_ustc){
			//点击footer按钮访问profile.php时，默认显示当前用户的profile
			$this->id_ustc = $_SESSION['valid_id_ustc'];
		}
		$this->conn = db_connect();
		$this->conn->query("SET NAMES UTF8");
		$this->row = $this->conn->query('select * from user where id_ustc="'.$this->id_ustc.'";')->fetch_assoc();
		$this->name = $this->row['name'];
	}
	public function display_pensonal_data(){
		$this->mobile = $this->row['mobile'];
		$content = ''.$this->name.' · '.$this->mobile.' · <a href="member.php#rank_tour"data-ajax="false">返回</a> ';
		echo_short($content);
	}
	/*public function display_practice_time(){
		$durations = $this->conn->query('select duration from practice where id_p1="'.$this->id_ustc.'";');
		$num = $durations->num_rows;
		$sum=0;
		for($i=0;$i<$num;$i++){
			$delta = $durations->fetch_assoc()['duration'];
			if ($delta==0){
				$sum+=0.5;
				
			}
			else $sum+=$delta;
		}
		if($i==0)$delta=0;
		echo_short("累计练球时间:".$sum."小时");
	}*/
	public function display_game_tour(){
			$event = $this->conn->query('select * from result_tour where name_p1="'.$this->name.'" or name_p2="'.$this->name.'"
	order by time desc;');
			
			$event_old = $this->conn->query('select * from record_tour where name = "'.$this->name.'";');
			$num = $event_old->num_rows;
		$str='<table data-role="table"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">';
		for($i=0;$i<$num;$i++){
			$result = $event_old->fetch_assoc();
			$str.= '<tr><td>';
			$str.= date('m月',$result['time'])."</td><td>";
			$str.= $result['stat']."</td><td><b>";
			$str.= $result['value']."分";
			$str.= '</b></td></tr>';
		}	
		$str.= '</table><br/><table data-role="table"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">';
		$num = $event->num_rows;
		for($i=0;$i<$num;$i++){
			$str.=$this->display_row($event);
		}
		$str.="</table>";
		echo_event("巡回赛战绩",$str);
}
	public function display_game_free(){
		$str= '<div id="freeProfileTable"class="ui-body"></div><br/>
			<button onclick="showMoreFreeTable()"data-mini="true">Show More</button>';
		
		echo_event("自由赛战绩",$str);
		}
	public function display_practice_time(){
		$durations = $this->conn->query('select duration from practice where id_p1="'.$this->id_ustc.'";');
		$num = $durations->num_rows;
		$sum=0;
		for($i=0;$i<$num;$i++){
			$delta = $durations->fetch_assoc()['duration'];
			if ($delta==0){
				$sum+=0.5;
				
			}
			else $sum+=$delta;
		}
		if($i==0)$delta=0;
		echo_short("累计练球时间:".$sum."小时");
	}
	public function display_id(){
		echo_event("id",$this->$id_ustc);
	}
	public function display_row($event){
		date_default_timezone_set('PRC');
		$row = $event->fetch_assoc();
		$time = date('m-d',$row['time']);
		$name_p1 = $row['name_p1'];
		$name_p2 = $row['name_p2'];
		$set_p1 = $row['set_p1'];
		$set_p2 = $row['set_p2'];
		$value_p1 = $row['value_p1'];
		$value_p2 = $row['value_p2'];
		if($name_p1==$this->name){
			$value = $value_p1;
		}else $value = $value_p2;
		return "<tr><td>".$time."</td><td>".$name_p1."</td><td><b>".$set_p1."-".$set_p2."</b></td><td>".$name_p2."</td><td><b>".$value."分</b></td></tr>";
	}
	public function get_single_row($event){
		$row = $event->fetch_assoc();
		date_default_timezone_set('PRC');
		$time = date('m-d',$row['time']);
		$id_game_double = $row['id_game_double'];
		$name_p1 = $row['name_p1'];
		$name_p2 = $row['name_p2'];
		$set_p1 = $row['set_p1'];
		$set_p2 = $row['set_p2'];
		$value_p1 = $row['value_p1'];
		$value_p2 = $row['value_p2'];
		if($name_p1==$this->name){
			$value = $value_p1;
		}else $value = $value_p2;
		$str =  "<tr><td>".$time."</td><td>".$name_p1."</td><td><b>".$set_p1."-".$set_p2."</b></td><td>".$name_p2."</td><td><b>".$value."分</b></td>";
		return $str;
	}
	public function get_double_row($event){
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
		if($name_p1==$this->name||$name_p2==$this->name){
			$value = $value_p1n2;
		}else $value = $value_p3n4;
		$str =  "<tr><td>".$time."</td><td>".$name_p1."<br/>".$name_p2."</td><td><b>".$set_p1n2."-".$set_p3n4."</b></td><td>".$name_p3."<br/>".$name_p4."</td><td><b>".$value."分</b></td>";
		return $str;
	}
}

set_profile_header();

$profile = new profile();
set_page_header('banner',"会员: ".$profile->name);
check_valid_id();
$profile->display_pensonal_data();
$profile->display_practice_time();
$profile->display_game_tour();
$profile->display_game_free();

set_page_footer(0);
set_html_footer();

?>