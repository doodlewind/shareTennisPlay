<?php
	require_once('tennis_fns.php');
	$id_ustc = $_POST['id_ustc'];
	$name = $_POST['name'];
	$mobile = $_POST['mobile'];
	$passwd = $_POST['passwd'];

	session_start();
	try{
		//表格完整性
		if(!filled_out($_POST)) {
			throw new Exception('Sorry, 你还没填完整表格呢~');
		}
		if(!valid_id_ustc($id_ustc)) {
			throw new Exception('你输入的学号貌似不对哦~');
		}
		if(!valid_mobile($mobile)) {
			throw new Exception('这个手机号恐怕打不通哦~');
		}
		//密码长度在6-16位之间
		/*
		if(strlen($passwd) < 6||(strlen($passwd) > 16)) {
			throw new Exception('密码长度在6-16位之间，重试一下吧~');
		}
		*/
		//发起注册请求
		register($id_ustc,$name,$mobile,$passwd);
		//注册会话变量
		$_SESSION['valid_id_ustc'] = $id_ustc;
		
		//布置成员页面
		set_html_header(0,'注册成功~');
		echo_event('恭喜~','注册成功，点<a href="login.php">这里</a>登录');
		set_html_footer(0);
	}
	catch (Exception $e) {
		set_html_header(0,'出了点问题...');
		echo_event('错误提示',$e->getMessage());
		set_html_footer(0);
		exit;
	}

?>