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
		$conn = db_connect();
		$conn->query("SET NAMES UTF8");
		$sql = 'select ';
		$sql.= $table_name;
		$sql.= ' from user where id_ustc="';
		$sql.= $this->id_ustc;
		$sql.= '";';
		//echo_event("?",$sql);
		$results = $conn->query($sql);
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
$ewind = new profile();
set_html_header(0,"会员：".$ewind->get('name'));

$ewind->display_pensonal_data();

set_html_footer(0);

?>