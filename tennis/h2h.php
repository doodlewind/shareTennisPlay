<?php 
require_once('tennis_fns.php');
$p1 = $_GET['p1'];
$p2 = $_GET['p2'];
//echo '<p>'.$p1.$p2.'</p>';
class h2h{
	private $conn;
	private $name_p1;
	private $name_p2;
	private $str="";
	private $count_p1 = 0;
	private $count_p2 = 0;
	function __construct($p1,$p2){
		$this->name_p1 = $p1;
		$this->name_p2 = $p2;

		$this->conn = db_connect();
	}
	public function getResult(){
		$sql = '
				select * from game_free 
					where name_p1="'.$this->name_p1.'"
					and name_p2="'.$this->name_p2.'"
			union
				select * from game_free
					where name_p2="'.$this->name_p1.'"
					and name_p1="'.$this->name_p2.'"
			order by time desc
		;';
		$results = $this->conn->query($sql);
		$num = $results->num_rows;
		if($num==0||!isset($num)){
			echo "<br/><p>尚无交手记录</p>";
			return;
		}
		$this->str.='<table class="ui-table ui-responsive">';
		for($i=0;$i<$num;$i++){
			$this->str.=$this->get_single_row($results);
		}
		$this->str.="</table>";
		echo '<div class ="ui-body"><h3><center>'."Total:&nbsp;".$this->count_p1." - ".$this->count_p2."</center></h3></div>";
		echo $this->str;
	}
	public function get_single_row($results){
			$row = $results->fetch_assoc();
			date_default_timezone_set('PRC');
			$time = date('m-d',$row['time']);
			$id_game = $row['id_game'];
			$name_p1 = $row['name_p1'];
			$name_p2 = $row['name_p2'];
			$set_p1 = $row['set_p1'];
			$set_p2 = $row['set_p2'];
			if($row['court']==1){
				$court = "东厂";
			}
			else $court = "西厂";
			$str =  "<tr><td>".$time."</td><td>".$name_p1."</td><td><b>".$set_p1."-".$set_p2."</b></td><td>".$name_p2."</td><td>".$court."</td>";
			$str.= '</tr>';
			
			if($name_p1==$this->name_p1){
				if($set_p1>$set_p2){
					$this->count_p1++;
				}else $this->count_p2++;
			}else{
				if($set_p1>$set_p2){
					$this->count_p2++;
				}else $this->count_p1++;
			}
			return $str;
	}
}
$h2h = new h2h($p1,$p2);
$h2h->getResult();
	?>