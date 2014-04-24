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
			$this->id_ustc = strtoupper($_SESSION['valid_id_ustc']);
		}
		$this->query('id_ustc',$this->id_ustc);
	}
	private function query($table_name){
		$this->conn = db_connect();
		$this->conn->query("SET NAMES UTF8");
		$sql = 'select ';
		$sql.= $table_name;
		$sql.= ' from user where id_ustc="';
		$sql.= $this->id_ustc;
		$sql.= '";';
		//echo_event("?",$sql);
		$results = $this->conn->query($sql);
		return $results->fetch_assoc();
	}
	public function get($table_name){
		return $this->query($table_name)[$table_name];
	}
	public function display_pensonal_data(){
		$name = $this->get('name');
		$mobile = $this->get('mobile');
		$content = '<p>'.$name.'</p><p>'.$mobile.'</p><p><a href="member.php">返回</a> ';
		if($this->id_ustc==strtoupper($_SESSION['valid_id_ustc'])){
			$content.= '| <a href="login.php">退出</a>';
		}
		echo_event('基本资料',$content);
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
	public function display_game(){
		$event = $this->conn->query('select * from game where id_p1="'.$this->id_ustc.'"
		union
		select * from game where id_p2="'.$this->id_ustc.'"
		order by time desc;');
		for($i=0;$i<$event->num_rows;$i++){
			$name1 = $event->fetch_assoc()['name'];
		}
	}
	function __get($name){
		return $this->name;
	}
	function __set($name,$value){
		$this->name = $value;
	}
	public function display_id(){
		echo_event("id",$this->$id_ustc);
	}
}
$profile = new profile();
set_html_header('profile',"会员：".$profile->get('name'));

$profile->display_pensonal_data();
$profile->display_practice_time();
$profile->display_game();
set_html_footer(3);

?>