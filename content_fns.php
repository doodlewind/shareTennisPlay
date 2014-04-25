<?php
class timeline
{
	private $conn;
	private $event;
	function __construct(){
		date_default_timezone_set('PRC');
		$this->conn = db_connect();
		$this->conn->query("SET NAMES UTF8");
		$this->create_single_event($this->conn->query("select * from game where value_free>0;"));
		$this->create_double_event($this->conn->query("select * from game_double"));
		$this->create_tour_event($this->conn->query("select * from game where value_tour>0;"));
		$this->create_practice_event($this->conn->query("select * from practice"));
		$this->sort();
	}
	private function create_single_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$tmp = new singleEvent($results->fetch_assoc());
			$this->event_insert($tmp);
		}
	}
	private function create_double_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$tmp = new doubleEvent($results->fetch_assoc());
			$this->event_insert($tmp);
		}
	}
	private function create_tour_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$tmp = new tourEvent($results->fetch_assoc());
			$this->event_insert($tmp);
		}
	}
	private function create_practice_event($results){
		$num_results = $results->num_rows;
		for($i=0;$i<$num_results;$i++){
			$tmp = new practiceEvent($results->fetch_assoc());
			$this->event_insert($tmp);
		}
	}
	private function event_insert($event){
		do{
			if($this->event[$event->time]){
				$event->time+=1;
			}
			else break;
		}while(1);
		$this->event[$event->time] = $event;
	}
	function sort(){
		//按时间降序排列事件，并输出
		if($this->event)krsort($this->event);
		else {
			echo_short('还没有动态');
			return;
		}
		foreach($this->event as $key => $value){
			echo_event($value->getTitle(),$value->getContent());
		}
	}
}

class event 
{
	public $time;
	public $row;
	public $title;
	public $content;
	public function __construct($row){
		$this->row=$row;
		$this->time=$this->row['time'];
		//echo_event($this->getTitle(),$this->getContent());
	}
	public function getName($id_num){
		//输入id_p1或id_p2，输出其对应的姓名
		$this->id = $this->row[$id_num];
		$this->conn = db_connect();
		$this->conn ->query("SET NAMES UTF8");
		
		$this->result = $this->conn->query('select name from user where id_ustc="'.$this->id.'";');
		if($this->result){
			return $this->result->fetch_assoc()['name'];
		}
		else
			return false;
	}
	public function setProfileLink($id,$name){
		$this->href = '<a href="profile.php?id_ustc=';
		$this->href.= $id;
		$this->href.= '">';
		$this->href.= $name;
		$this->href.= '</a>';
		return $this->href;
	}
	public function countTime(){
		$time = intval($this->row['time'],10);
		$delta = (int)((time()/3600/24))-(int)(($time/3600/24));
		if($delta==0){
			$hour = (int)($time%(3600*24)/3600);
			$delta = $hour."小时前";
		}else{
			$delta = $delta."天前";
		}
		return $delta;
	}
	public function getCourt(){
		if($this->row['court']=='1'){
			return "东区网球场";
		}else return "西区网球场";
	}
	public function __get($value){
		return $this->value;
	}
}
class singleEvent extends event
{
	public function getTitle(){
		$this->name1 = $this->getName('id_p1');
		$this->name2 = $this->getName('id_p2');
		$this->id_p1 = $this->row['id_p1'];
		$this->id_p2 = $this->row['id_p2'];
		$title = $this->setProfileLink($this->id_p1,$this->name1);
		$title.= '&nbsp;';
		$title.= $this->row['set_p1'];
		$title.= '-';
		$title.= $this->row['set_p2'];
		$title.= '&nbsp;';
		$title.= $this->setProfileLink($this->id_p2,$this->name2);
		return $title;
	}
	public function getContent(){
		$content = $this->countTime().'，'.$this->getCourt().'，『'.$this->row['comment'].'』';
		return $content;
	}
}
class doubleEvent extends event
{
	function getTitle(){
		$this->name1 = $this->getName('id_p1');
		$this->name2 = $this->getName('id_p2');
		$this->name3 = $this->getName('id_p3');
		$this->name4 = $this->getName('id_p4');
		$id_p1 = $this->row['id_p1'];
		$id_p2 = $this->row['id_p2'];
		$id_p3 = $this->row['id_p3'];
		$id_p4 = $this->row['id_p4'];
		$title = $this->setProfileLink($id_p1,$this->name1);
		$title.= '&nbsp;';
		$title.= $this->setProfileLink($id_p2,$this->name2);
		$title.= '&nbsp;';
		$title.= $this->row['set_p1n2'];
		$title.= '-';
		$title.= $this->row['set_p3n4'];
		$title.= '&nbsp;';
		$title.= $this->setProfileLink($id_p3,$this->name3);
		$title.= '&nbsp;';
		$title.= $this->setProfileLink($id_p4,$this->name4);
		return $title;
	}
	function getContent(){
		$content = $this->countTime().'，'.$this->getCourt().'，『'.$this->row['comment'].'』';
		return $content;
	}
}
class tourEvent extends event
{
	function getTitle(){
		$this->name1 = $this->getName('id_p1');
		$this->name2 = $this->getName('id_p2');
		$this->id_p1 = $this->row['id_p1'];
		$this->id_p2 = $this->row['id_p2'];
		$title = $this->setProfileLink($this->id_p1,$this->name1);
		$title.= '&nbsp;';
		$title.= $this->row['set_p1'];
		$title.= '-';
		$title.= $this->row['set_p2'];
		$title.= '&nbsp;';
		$title.= $this->setProfileLink($this->id_p2,$this->name2);
		return $title;
	}
	function getContent(){
		$content = $this->countTime().'，【巡回赛】'.$this->getCourt();
		return $content;
	}
}
class practiceEvent extends event
{
	function getTitle(){
		$name = $this->getName('id_p1');
		return $this->setProfileLink($this->id_p1,$name)." 练了会球";
	}
	function getContent(){
		return $this->getItem().$this->getDuration();
	}
	function getDuration(){
		if($this->row['duration']==0){
			$str="坚持了半小时";
		}else $str="坚持了".$this->row['duration']."小时";
		return $str;
	}
	function getItem(){
		$str = '练了';
		$flag = $this->row['sum_item'];
		//8421求和唯一性，判断正手、反手、发球、截击
		if($flag%2==1){
			$str.="正手、";
		}
		if($flag==3||$flag==6||$flag==7||$flag==10||$flag==11||$flag==14||$flag==15){
			$str.="反手、";
		}
		if($flag==5||$flag==6||$flag==7||$flag==12||$flag==13||$flag==14||$flag==15){
			$str.="发球、";
		}
		if($flag>=9){
			$str.="截击、";
		}
		return $str;
	}
}
/*
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
*/
function display_timeline(){
	$timeline = new timeline();
}
function display_table($flag){
	?>
<table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive"data-column-popup-theme="a">
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
	//hotfix
	if($i==1){$i=2;}
	
	$rank = (int)(($count/($i-1))*100);
	//总人数由i求出，将受表格行数影响，需要改进
	if($flag==1)echo_event("我的自由赛","".$my_total_score."分，排第".$count."名，前".$rank."%");
	else echo_event("我的巡回赛","".$my_total_score."分，排第".$count."名，前".$rank."%");
}
?>