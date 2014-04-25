<?php
function register($id_ustc,$name,$mobile,$passwd) {
	//连接数据库，若注册失败则返回错误提示
	$conn = db_connect();
	$result = $conn->query("select * from user where 
		id_ustc ='".$id_ustc."'");
	if (!$result) {
		throw new Exception ('异常操作，暂无法注册，<a href="register.php">返回</a>');
	}
	if ($result->num_rows>0) {
		throw new Exception ('该学号已被注册！<a href="register.php">返回</a>');
	}
	//验证成功，则写入数据库
	$conn->query("SET NAMES UTF8");
	$result = $conn->query("insert into user values
		('".$id_ustc."','".$name."',sha1('".$passwd."'),'".$mobile."','0')");
	if (!$result) {
		throw new Exception ('异常操作，暂无法注册，<a href="register.php">返回</a>');
	}
	$namelist = Array("陈强","牟卓群","奚悦诚","梅博文","刘力源","许艳艳","程广珲","李碧薇","李旻鸶","张新成","丁冉","赵桐周","王镱霏","姚致远","余辛炜","刘伟","张杰","刘嘉欣","尹捷凯","李安迪","胡皓","胡冰","胡行亭","王天玉","徐竹菁","李天择","严红红","任佳颖","李思奇","邱洪波","李慧","张宜萍","王馨","时雪草","高文智","张科","高筱培","高洋","林智中","陈嘉翔","李伟力","周浩磊","丁毛毛","张予曦","王责越","罗浩","刘楚劼","柯招清","母立众","王译锋","王雨林","宋喆涵","余子锐","杨森","马天骥","蒋林","罗潇","钱雨辰","杨叔阳","钟亚序","金通","沈洋","姜晓岚","邓勇","左献迪","李柏","杨悦","章一超","董顺","邓恬然","薛嘉宏");
	if(in_array($name,$namelist))
		return true;
	else
		throw new Exception ('用户名不在会员名单中！');
}
function login($id_ustc,$passwd) {
	//用户登录，若出现异常则返回错误提示
	$conn = db_connect();
	$id_ustc = strtoupper($id_ustc);
	$sql = "select * from user where id_ustc=(?) and passwd_sha=sha1(?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ss",$id_ustc,$passwd);
	$stmt->execute();
	if (!$stmt) {
		throw new Exception('无法连接到数据库，<a href="login.php">返回</a>');	
	}
	if ($stmt->fetch()) {
		return true;
	}else{
		throw new Exception('用户名或密码错误，<a href="login.php">返回</a>');
	}
}
function init_member() {
	//初始化成员页面变量，并登录
	$id_ustc = $_POST['id_ustc'];
	$passwd = $_POST['passwd'];

	if ($id_ustc && $passwd) {
		try {
			login($id_ustc,$passwd);//in user_auth_fns.php
			$_SESSION['valid_id_ustc'] = $id_ustc;//在register_verify.php中有同一句
		}
		catch(Exception $e){
			set_html_header(0,"Error...");
			echo_event("错误提示",$e->getMessage());
			set_html_footer(0);
			exit();
		}
	}
}
function check_valid_id() {
	//如果当前用户未登陆而访问此页面，返回错误提示
	if(isset($_SESSION['valid_id_ustc'])) {
		return true;
	}
	else{
		echo_event("错误提示","你尚未登陆，请<a href=\"login.php\">重试</a>");
		exit();
	}
}
/*
function get_id($name){
	$conn = db_connect();
	$conn->query("SET NAMES UTF8");
	$sql = 'select id_ustc ';
	$sql.= 'from user where name="';
	$sql.= $name;
	$sql.= '";';
	$result = $conn->query($sql);
	return $result->fetch_assoc()['id_ustc'];
}
*/
?>