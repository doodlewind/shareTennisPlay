<?php
class timeline
{
	private $conn;
	private $event_count = 0;
	private $event;
	private function create_single_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$this->event[$event_count] = new singleEvent($results->fetch_assoc());
		}
	}
	private function create_double_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$this->event[$event_count] = new doubleEvent($results->fetch_assoc());
		}
	}
	private function create_tour_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$this->event[$event_count] = new tourEvent($results->fetch_assoc());
		}
	}
	private function create_practice_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$this->event[$event_count] = new practiceEvent($results->fetch_assoc());
		}
	}
	function __construct(){
		date_default_timezone_set('PRC');
		$this->conn = db_connect();
		$this->conn->query("SET NAMES UTF8");
		$this->free_single = $this->create_single_event($this->conn->query("select * from game
							where value_free>0;"));
		$this->free_double = $this->create_double_event($this->conn->query("select * from game_double	where value_free>0;"));
		$this->tour = $this->create_tour_event($this->conn->query("select * from game
							where value_tour>0;"));
		$this->practice = $this->create_practice_event($this->conn->query("select * from practice"));
	}
	//?
}
class event
{
	public $id_p1;
	public $time;
	public $court;
	function __construct($row){
		getTitle($row);
		getContent($row);
	}
	public function getName($id){
		
	}
	public function displayEvent(){
		
	}
}
class singleEvent extends event
{
	function getTitle($row){
		
	}
	function getContent($row){
		
	}
}
class doubleEvent extends event
{
	function getTitle($row){
		
	}
	function getContent($row){
		
	}
}
class tourEvent extends event
{
	function getTitle($row){
		
	}
	function getContent($row){
		
	}
}
class practiceEvent extends event
{
	function getTitle($row){
		
	}
	function getContent($row){
		
	}
}
function generate_title($id_p1,$id_p2,$name1,$name2,$set_p1,$set_p2){
	$output = '';
	$output.='<a href="profile.php?id_ustc=';
	$output.=$id_p1;
	$output.='">';
	$output.=$name1;
	$output.='</a>&nbsp;&nbsp;';
	$output.=$set_p1."-".$set_p2;
	$output.='&nbsp;&nbsp;<a href="profile.php?id_ustc=';
	$output.=$id_p2;
	$output.='">';
	$output.=$name2;
	$output.='</a>&nbsp;&nbsp;';
	return $output;
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
function display_timeline_old(){
	//所有会员暂时使用同一个timeline
	date_default_timezone_set('PRC');
	$conn = db_connect();
	$conn->query("SET NAMES UTF8");
	$result_p1 = $conn->query("select id_p1,time,name,set_p1,court,comment from game
							join user
							where game.id_p1=user.id_ustc
							order by time desc
							limit 15;");
	$result_p2 = $conn->query("select id_p2,time,name,set_p2 from game
							join user
							where game.id_p2=user.id_ustc
							order by time desc
							limit 15;");
	//获得15条Timeline数据
   	$num_results = $result_p1->num_rows;
   	for ($i=0; $i < $num_results;$i++) {
		$row_p1 = $result_p1->fetch_assoc();
		$row_p2 = $result_p2->fetch_assoc();
		$title = generate_title($row_p1['id_p1'],$row_p2['id_p2'],$row_p1['name'],$row_p2['name'],$row_p1['set_p1'],$row_p2['set_p2']);
		$content = generate_content($row_p1['time'],$row_p1['court'],$row_p1['comment']);
		echo_event($title,$content);
	}
}
function display_timeline(){
	$timeline = new timeline();
	
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
		$conn = db_connect();
		$conn->query('SET NAMES UTF8');
		
		if($flag==1) $result = $conn->query('select * from score_free;');
		else $result = $conn->query('select * from score_tour order by total_score desc;');
		
		$num_results = $result->num_rows;
		for ($i=1; $i <= $num_results;$i++){
			$row = $result->fetch_assoc();
			echo '<tr><td>'.$i.'</td><td>'.$row['name'].'</td><td>'.$row['total_score'].'</td></tr>';
		}
	?>
			</tbody>
			</table>
			</br>
<?php
	//generate game info of current user
	$id_ustc = $_SESSION['valid_id_ustc'];
	
	if($flag==1)$result = $conn->query('select total_score from score_free where id_p1= "'.$id_ustc.'";');
	else $result = $conn->query('select total_score from score_tour where id_p1= "'.$id_ustc.'";');
	//get name of current user, then query to count his rank
	
	$row = $result->fetch_assoc();
	$my_total_score = $row['total_score'];
	
	if($flag==1)$result = $conn->query('select count(total_score) as count from score_free where total_score >="'.$my_total_score.'";');
	else $result = $conn->query('select count(total_score) as count from score_tour where total_score >="'.$my_total_score.'";');
	
	$row = $result->fetch_assoc();
	$count = $row['count'];
	$rank = (int)(($count/($i-1))*100);
	//总人数由i求出，将受表格行数影响，需要改进
	if($flag==1)echo_event("我的自由赛","".$my_total_score."分，排第".$count."名，前".$rank."%");
	else echo_event("我的巡回赛","".$my_total_score."分，排第".$count."名，前".$rank."%");
}
?>