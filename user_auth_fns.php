<?php
function register($id_ustc,$name,$mobile,$passwd) {
	//连接数据库，若注册失败则返回错误提示
	$conn = db_connect();
	$result = $conn->query("select * from user where 
		id_ustc ='".$id_ustc."'");
	if (!$result) {
		throw new Exception ('Sorry，系统出错，无法注册...');
	}
	if ($result->num_rows>0) {
		throw new Exception ('该学号已被注册!');
	}
	//验证成功，则写入数据库
	$conn->query("SET NAMES UTF8");
	$result = $conn->query("insert into user values
		('".$id_ustc."','".$name."',sha1('".$passwd."'),'".$mobile."','0')");
	if (!$result) {
		throw new Exception ('Sorry，当前无法注册，请稍后重试~');
	}
	return true;
}
function login($id_ustc,$passwd) {
	//用户登录，若出现异常则返回错误提示
	$conn = db_connect();
	$id_ustc = strtoupper($id_ustc);
	$result = $conn->query("select * from user
							where id_ustc='".$id_ustc."'
							and passwd_sha=sha1('".$passwd."')");
	if (!$result) {
		throw new Exception('抱歉，无法创建到后台的登录连接...');	
	}
	if ($result->num_rows>0) {
		return true;
	}else{
		throw new Exception('用户名或密码填错了哦...<a href="login.php">返回</a>');
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
			set_html_header(0,"出错啦");
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
		echo_event("出错啦","你还没登陆~请<a href=\"login.php\">重试</a>");
		exit();
	}
}
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
?>