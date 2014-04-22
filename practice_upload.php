<?php
	require_once('tennis_fns.php');
	session_start();
	try{
		//表格完整性
		if(!filled_out($_POST)) {
			throw new Exception('Sorry, 貌似没填完整呢~');
		}
		upload_practice();
		set_html_header(0,'上传练习记录成功！');
		//布置成员页面
		
		echo_event('上传完成','练习记录已上传，<a href="member.php">返回</a>');
		set_html_footer(0);
	}
	catch (Exception $e) {
		set_html_header(0,'呃，出了点问题...');
		echo_event('错误提示',$e->getMessage());
		set_html_footer(0);
		exit;
	}
?>