<?php
class timeline
{
	private $conn;
	private $event;
	function __construct(){
		date_default_timezone_set('PRC');
		$this->conn = db_connect();
		$this->conn->query("SET NAMES UTF8");
		$this->create_single_event($this->conn->query("select * from result_free;"));
		$this->create_double_event($this->conn->query("select * from game_double;"));
		$this->create_tour_event($this->conn->query("select * from result_tour;"));
		$this->create_practice_event($this->conn->query("select * from practice;"));
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
	public function setProfileLink($id,$name){
		$this->href = '<a href="profile.php?id_ustc=';
		$this->href.= $id;
		$this->href.= '"data-ajax="false">';
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
			if($delta > 3){
				if($delta > 365){
					$delta = "1年前";
				}
				else $delta = date('m-d',$time);	
			}else
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
}
class singleEvent extends event
{
	public function getTitle(){
		$this->name1 = $this->row['name_p1'];
		$this->name2 = $this->row['name_p2'];
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
		$comment = $this->row['comment'];
		if($comment=="NULL"){
			$content = $this->countTime().'，'.$this->getCourt();
		}
		else 
			$content = $this->countTime().'，'.$this->getCourt().'，『'.$comment.'』';
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
		$this->name1 = $this->row['name_p1'];
		$this->name2 = $this->row['name_p2'];
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
		return $this->setProfileLink($this->id,$name)." 练了球";
	}
	function getContent(){
		return $this->countTime()."，".$this->getItem().$this->getDuration();
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
		if($flag==2||$flag==3||$flag==6||$flag==7||$flag==10||$flag==11||$flag==14||$flag==15){
			$str.="反手、";
		}
		if($flag==4||$flag==5||$flag==6||$flag==7||$flag==12||$flag==13||$flag==14||$flag==15){
			$str.="发球、";
		}
		if($flag>=8){
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
/*
function setProfileLink($id,$name){
		$href = '<a href="profile.php?id_ustc=';
		$href.= $id;
		$href.= '"data-ajax="false">';
		$href.= $name;
		$href.= '</a>';
		return $href;
}
*/
function display_free_table(){
	//flag=1，自由赛 flag=2，巡回赛
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
		$results = $conn->query("select * from sum_free;");

		$num_results = $results->num_rows;
		for ($i=1; $i <= $num_results;$i++){
			$row = $results->fetch_assoc();
			echo '<tr><td>'.$i.'</td><td>'.$row['name'].'</td><td>'.$row['sum'].'</td></tr>';
		}
	?>
			</tbody>
			</table>
			</fieldset>
			</br>
<?php
	//generate game info of current user
		//由id_ustc获得姓名
		$sql = "select name from user where id_ustc=(?);";
		$stmt = $conn->prepare($sql);
		$id_ustc = $_SESSION['valid_id_ustc'];
		$stmt->bind_param("s",$id_ustc);
		$stmt->execute();
		$results = $stmt->get_result();
		$name = $results->fetch_assoc()['name'];
	
		//获得总积分
		$sql = "select sum from sum_free where name=(?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s",$name);
		$stmt->execute();
		$results = $stmt->get_result();
		$my_total_score =  $results->fetch_assoc()['sum'];
	
		//获得总排名
		$results = $conn->query('select count(sum)+1 as rank from sum_free where sum > (select sum from sum_free where name="'.$name.'");');
		$count = $results->fetch_assoc()['rank'];
		echo_event("我的自由赛","".$my_total_score."分，排第".$count."名");
}
function display_tour_table(){
	//flag=1，自由赛 flag=2，巡回赛
	//generate game info of current user
	//由id_ustc获得姓名
	$conn = db_connect();
	$sql = "select name from user where id_ustc=(?);";
	$stmt = $conn->prepare($sql);
	$id_ustc = $_SESSION['valid_id_ustc'];
	$stmt->bind_param("s",$id_ustc);
	$stmt->execute();
	$results = $stmt->get_result();
	$name = $results->fetch_assoc()['name'];

	//获得总积分
	$sql = "select sum from out_sum_tour where name=(?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s",$name);
	$stmt->execute();
	$results = $stmt->get_result();
	$my_total_score =  $results->fetch_assoc()['sum'];

	//获得总排名
	$results = $conn->query('select count(sum)+1 as rank from out_sum_tour where sum > (select sum from out_sum_tour where name="'.$name.'");');
	$count = $results->fetch_assoc()['rank'];
	echo_event("我的巡回赛","".$my_total_score."分，排第".$count."名");
	?>
			<div data-role="collapsible" data-mini="true">
		    <h4>男子积分榜</h4>
<table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d table-stripe ui-responsive"data-column-popup-theme="a">
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
	$sql = "select id_ustc,user.name,sum from old_sum_tour 
join user where old_sum_tour.name=user.name and grade_tennis='0' order by sum desc;";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$results = $stmt -> get_result();
	$num_results = $results->num_rows;
	for ($i=1; $i <= $num_results;$i++){
		$row = $results->fetch_assoc();
		echo '<tr><td>'.$i.'</td><td><a href=profile.php?id_ustc='.$row['id_ustc'].'>'
			.$row['name'].'</a></td><td>'.$row['sum'].'</td></tr>';
	}
	?>
			</tbody>
			</table>
			</div><!--clooapsible-->
			<div data-role="collapsible" data-mini="true">
		    <h4>女子积分榜</h4>
			<table data-role="table"id="table-custom-2"data-mode="columntoggle" class="ui-body-d  table-stripe ui-responsive">
						 <thead>
						 <tr class="ui-bar-d">
							 <th>姓名</th>
							 <th>积分</th>
						 </tr>
						 </thead>
						 <tbody>
			<?php
				//generate <tr> and <td>	
				$sql = "select id_ustc,user.name,sum from old_sum_tour 
			join user where old_sum_tour.name=user.name and grade_tennis='1' order by sum desc;";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				$results = $stmt -> get_result();
				$num_results = $results->num_rows;
				for ($i=1; $i <= $num_results;$i++){
					$row = $results->fetch_assoc();
					echo '<tr><td><a href=profile.php?id_ustc='.$row['id_ustc'].'>'
						.$row['name'].'</a></td><td>'.$row['sum'].'</td></tr>';
				}
				?>
						</tbody>
						</table>
					</div><!--collapsible-->

<?php
	

}
?>