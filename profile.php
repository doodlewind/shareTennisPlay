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
			$str='<table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">';
			for($i=0;$i<$num;$i++){
				$result = $event_old->fetch_assoc();
				$str.= '<tr><td>';
				$str.= date('m月',$result['time'])."</td><td>";
				$str.= $result['stat']."</td><td><b>";
				$str.= $result['value']."分";
				$str.= '</b></td></tr>';
			}	
			$str.= '</table><br/><table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">';
			$num = $event->num_rows;
			for($i=0;$i<$num;$i++){
				$str.=$this->display_row($event);
			}
			$str.="</table>";
			echo_event("巡回赛战绩",$str);
		}
		public function display_game_free(){
			//显示单打部分
			$event = $this->conn->query('select * from result_free where name_p1="'.$this->name.'" or name_p2="'.$this->name.'"
	order by time desc;');
			$str = '';
			$str.= '<table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">';
			$num1 = $event->num_rows;
			for($i=0;$i<$num1;$i++){
				$str.=$this->get_single_row($event);
			}
			$str.='</table><table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">';
		
			//显示双打部分
			$sql = 'select * from game_double where name_p1="'.$this->name.'" or name_p2="'.$this->name.'" or name_p3="'.$this->name.'"or name_p4="'.$this->name.'"order by time desc;';
			//echo_short($sql);
			$event = $this->conn->query($sql);
			$num2 = $event->num_rows;
			if($num1==0&&$num2==0){
				echo_event("自由赛战绩",'暂无');
				return;
			}
			for($i=0;$i<$num2;$i++){
				$str.=$this->get_double_row($event);
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
		$str.= '<td><a href="modify_verify.php?tp=fr_del&amp;id_game='.$id_game_double.'"data-ajax="false">删除</a></td></tr>';
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
		$str.= '<td><a href="modify_verify.php?tp=fd_del&amp;id_game_double='.$id_game_double.'"data-ajax="false">删除</a></td></tr>';
		return $str;
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