<?php

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
		$content = ''.$this->name.' · '.$this->mobile.' · <a href="member.php"data-ajax="false">返回</a> ';
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
		$str = "<table>";
		$num = $event->num_rows;
		if($num == 0){
			echo_event("巡回赛战绩",'暂无');
			return;
		}
		for($i=0;$i<$num;$i++){
			$str.=$this->display_row($event);
		}
		$str.="</table>";
		echo_event("巡回赛战绩",$str);
	}
	public function display_game_free(){
		$event = $this->conn->query('select * from result_free where name_p1="'.$this->name.'" or name_p2="'.$this->name.'"
order by time desc;');
		$str = "<table>";
		$num = $event->num_rows;
		if($num == 0){
			echo_event("自由赛战绩",'暂无');
			return;
		}
		for($i=0;$i<$num;$i++){
			$str.=$this->display_row($event);
		}
		$str.="</table>";
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
		return "<tr><td>".$time."</td><td>&nbsp;".$name_p1."</td><td>&nbsp;&nbsp<b>".$set_p1."-".$set_p2."</b></td><td>&nbsp;&nbsp".$name_p2."</td><td><b>&nbsp;(".$value."分)</b></td></tr>";
	}
}

set_html_header();



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