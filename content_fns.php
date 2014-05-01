<?php
class timeline
{
	private $conn;
	private $event;
	function __construct(){
		date_default_timezone_set('PRC');
		$this->conn = db_connect();
		$this->conn->query("SET NAMES UTF8");
		$this->create_single_event($this->conn->query("select * from game_free order by time desc limit 30;"));
		$this->create_double_event($this->conn->query("select * from game_double  order by time desc limit 30;"));
		//$this->create_tour_event($this->conn->query("select * from result_tour;"));
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
		if($id){
			$this->href = '<a href="profile.php?id_ustc=';
			$this->href.= $id;
			$this->href.= '"data-ajax="false">';
			$this->href.= $name;
			$this->href.= '</a>';
			return $this->href;
		}
		else{
			return $name;
		}
	}
	public function countTime(){
		$time = $this->row['time'];
		$month = ltrim(date('m',$time),"0");
		$now_day = date('d');
		$day = date('d',$time);
		$time = date('H点',$time);
		$time = ltrim($time,"0");
		
		if($now_day==$day){
			return "今天".$time;
		}
		else if($now_day==$day+1){
			return "昨天".$time;
		}
		else{
			$day = ltrim($day,"0");
			return $month."月".$day."日";
		}
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
		$this->name1 = $this->row['name_p1'];
		$this->name2 = $this->row['name_p2'];
		$this->name3 = $this->row['name_p3'];
		$this->name4 = $this->row['name_p4'];
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

class table
{
	public $str='';//table的html输出流
	public $thead;//table的首部格式
	public $tend;//table的尾部
	public function __construct($conn){
		$this->createTable($conn);//由子类实现
		$this->displayTable();
	}
	public function displayTable(){
		$table = $this->table;
		$this->str.=$this->thead;
		
		$num = $this->num;
		for($i=1;$i<=$num;$i++){
			//showTableHead所需变量在继承的table类中初始化
			$this->str.=$this->showRow($i,$table->fetch_assoc());
		}
		
		$this->str.=$this->tend;
		echo $this->str;
	}
	public function setLink($id,$name){
		return '<a href="profile.php?id_ustc='.$id.'"data-ajax="false">'.$name.'</a>';
	}
}
class freeVaryTable extends table
{
	public $thead = '
<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
    <h4>一周积分榜</h4><table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d  table-stripe ui-responsive">';
	public $tend = '</table></div>';
	public function createTable($conn){
		$this->sql = '
			select id_p1 as id,name,sum(sum_single) as score from(
				select time,sum(sum_half) as sum_single,name,id_p1 from(
					select time,sum(value_p1) as sum_half,name_p1 as name,id_p1 from game_free
					group by name_p1
					union all
					select time,sum(value_p2),name_p2,id_p2 from game_free
					group by name_p2)
				as sum_hf1
				group by name
				union
				select time,sum(sum_tmp) as sum_double,name,id_p1 from(
					select time,sum(value_p1n2) as sum_tmp,name_p1 as name,id_p1 from game_double
					where time
					group by name_p1 union all
					select time,sum(value_p1n2),name_p2 as name,id_p2 from game_double
					group by name_p2 union all
					select time,sum(value_p3n4),name_p3 as name,id_p3 from game_double
					group by name_p3 union all
					select time,sum(value_p3n4),name_p4 as name,id_p4 from game_double
					group by name_p4)
				as sum_hf2
				group by name)as sum_hf where UNIX_TIMESTAMP()-time < 604800
			group by name
			order by score desc;';
		$this->table = $conn->query($this->sql);
		$this->num = $this->table->num_rows;
	}
	public function showRow($i,$row){
		return '<tr><td>'.$i.'</td><td>'.$this->setLink($row['id'],$row['name']).'</td><td>'.$row['score'].'</td></tr>';
	}
}
class freeAllTable extends table
{
	public $thead = '<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
    <h4>All Time积分榜</h4><table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d  table-stripe ui-responsive">';
	public $tend = '</table></div>';
	public function createTable($conn){
		$this->sql = '
			select id_p1 as id,name,sum(sum_single) as score from(
				select time,sum(sum_half) as sum_single,name,id_p1 from(
					select time,sum(value_p1) as sum_half,name_p1 as name,id_p1 from game_free
					group by name_p1
					union all
					select time,sum(value_p2),name_p2,id_p2 from game_free
					group by name_p2)
				as sum_hf1
				group by name
				union
				select time,sum(sum_tmp) as sum_double,name,id_p1 from(
					select time,sum(value_p1n2) as sum_tmp,name_p1 as name,id_p1 from game_double
					where time
					group by name_p1 union all
					select time,sum(value_p1n2),name_p2 as name,id_p2 from game_double
					group by name_p2 union all
					select time,sum(value_p3n4),name_p3 as name,id_p3 from game_double
					group by name_p3 union all
					select time,sum(value_p3n4),name_p4 as name,id_p4 from game_double
					group by name_p4)
				as sum_hf2
				group by name)as sum_hf
			group by name
			order by score desc;';
		$this->table = $conn->query($this->sql);
		$this->num = $this->table->num_rows;
	}
	public function showRow($i,$row){
		return '<tr><td>'.$i.'</td><td>'.$this->setLink($row['id'],$row['name']).'</td><td>'.$row['score'].'</td></tr>';
	}
}
class freeFrequencyTable extends table
{
	public $thead = '<div data-role="collapsible" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
    <h4>一周勤奋榜</h4><table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d  table-stripe ui-responsive">';
	public $tend = '</table></div>';
	public function createTable($conn){
		$this->sql = 'select id_p1 as id,name,sum(sum_single) as score,sum(count) as count from(
	select time,sum(sum_half) as sum_single,name,id_p1,sum(count)as count from(
		select time,sum(set_p1) as sum_half,name_p1 as name,id_p1,count(*)as count from game_free
		group by name_p1
		union
		select time,sum(set_p2),name_p2,id_p2,count(*)as count from game_free
		group by name_p2)
	as sum_hf1 
	group by name
	union
	select time,sum(sum_tmp) as sum_double,name,id_p1,sum(count)as count from(
		select time,sum(set_p1n2) as sum_tmp,name_p1 as name,id_p1,count(*)as count from game_double
		group by name_p1 union all
		select time,sum(set_p1n2),name_p2 as name,id_p2,count(*)as count from game_double
		group by name_p2 union all
		select time,sum(set_p3n4),name_p3 as name,id_p3,count(*)as count from game_double
		group by name_p3 union all
		select time,sum(set_p3n4),name_p4 as name,id_p4,count(*)as count from game_double
		group by name_p4)
	as sum_hf2
	group by name
)as sum_hf where UNIX_TIMESTAMP()-time < 604800
group by name
order by score desc,count asc;';
		$this->table = $conn->query($this->sql);
		$this->num = $this->table->num_rows;
	}
	public function showRow($i,$row){
		return '<tr><td>'.$i.'</td><td>'.$this->setLink($row['id'],$row['name']).'</td><td>'.$row['count'].'场'.$row['score']."局".'</td></tr>';
	}
}

class profile_member{
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
		$content = ''.$this->name.' · '.$this->mobile.' · ';
		$content.= ' <a href="login.php"data-ajax="false">退出</a>';
		
		echo_short($content);
		echo '<form action="upload_verify.php"method="post">
			<div data-role="collapsible"data-collapsed-icon="gear" data-expanded-icon="gear" data-mini="true">
			    <h4>更改密码</h4>
				<div class="ui-grid-a">
				<div class="ui-block-a"><div class="ui-body ui-body-d">
					<input data-mini="true"type="password"name="password"placeholder="6-16位">
				</div></div>
				<div class="ui-block-b"><div class="ui-body ">
					<input data-mini="true"type="submit"value="确认"class="ui-btn ui-mini"></input>
				</div></div>
				</div><!--ui-grid-->
				</form>
			</div>';
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
				$str.=$this->get_tour_row($event);
			}
			$str.="</table>";
			echo_event("巡回赛战绩",$str);
		}
	public function display_game_free(){
		//显示单打部分
		$event = $this->conn->query('select * from game_free where name_p1="'.$this->name.'" or name_p2="'.$this->name.'"
order by time desc;');
		$str = '点击删除将立即执行，当心手滑...';
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
	public function display_id(){
		echo_event("id",$this->$id_ustc);
	}
	public function get_single_row($event){
		$row = $event->fetch_assoc();
		date_default_timezone_set('PRC');
		$time = date('m-d',$row['time']);
		$id_game = $row['id_game'];
		//dbug($id_game);
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
		$str.= '<td><a href="modify_verify.php?tp=fr_del&amp;id_game='.$id_game.'"data-ajax="false">删除</a></td></tr>';
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
	public function get_tour_row($event){
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
*/
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