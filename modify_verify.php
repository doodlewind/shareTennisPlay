<?php
	require_once('tennis_fns.php');
	session_start();
	$conn = db_connect();
	try{
		if(!$_SESSION['valid_id_ustc']){
			throw new Exception('登录状态出问题啦，<a href="member.php#profile"data-ajax="false">返回</a>');
		}
		if($_GET['tp']=="fr_del"){
			//throw new Exception ($_GET['tp']);
			$id_game = $_GET['id_game'];
			if(!($id_game < 500)){
				throw new Exception($sql);
			}
			$sql = "delete from game_free where id_game='".$id_game."';";
			$result = $conn->query($sql);
			if(!$result){
				throw new Exception('操作出错，<a href="member.php#profile"data-ajax="false">返回</a>');
			}
		}
		if($_GET['tp']=="fd_del"){
			//throw new Exception ($_GET['tp']);
			$id_game_double = $_GET['id_game_double'];
			
			if(!($id_game < 500)){
				throw new Exception($sql);
			}
			$sql = "delete from game_double where id_game_double='".$id_game_double."';";
			$result = $conn->query($sql);
			if(!$result){
				throw new Exception('操作出错，<a href="member.php#profile"data-ajax="false">返回</a>');
			}
		}
		//布置成员页面
		set_html_header();
		set_page_header('banner','Done');
		echo_event('提示','操作完成，<a href="member.php#profile"data-ajax="false">返回</a>');
		
		set_page_footer(0);
		set_html_footer();
	}
	catch (Exception $e) {
		set_html_header();
		set_page_header(0,'Err...');
		echo_event('错误提示',$e->getMessage());
		
		set_page_footer(0);
		set_html_footer();
		exit;
	}
?>