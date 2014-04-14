<?php
	require_once('tennis_fns.php');
	session_start();
	try{
		//表格完整性
		if(!filled_out($_POST)) {
			throw new Exception('Sorry, 貌似没填完整呢~');
		}
		upload_free();
		//布置成员页面
		set_html_header(0,'上传比赛成功！');
		echo_event('上次完成','比赛记录已上传，<a href="member.php">返回</a>');
		set_html_footer(0);
	}
	catch (Exception $e) {
		set_html_header(0,'呃，出了点问题...');
		echo_event('错误提示',$e->getMessage());
		set_html_footer(0);
		exit;
	}
?>