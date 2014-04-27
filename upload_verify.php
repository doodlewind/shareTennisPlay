<?php
	require_once('tennis_fns.php');
	session_start();
	try{
		//表格完整性
		if(!filled_out($_POST)) {
			throw new Exception($_POST['name_p3'].'记录没填完整呢，<a href="member.php"data-ajax="false">返回</a>');
		}
		if(!$_POST['name_p3']&&!$_POST['duration']){
			upload_free(1);
		}else if(!$_POST['duration']){
			upload_free(2);
		}
		else{
			upload_practice();
		}
		//布置成员页面
		set_html_header();
		set_page_header('banner','上传成功！');
		echo_event('提示','记录已上传，<a href="member.php"data-ajax="false">返回</a>');
		
		set_page_footer(0);
		set_html_footer();
	}
	catch (Exception $e) {
		set_html_header();
		set_page_header(0,'呃，出了点问题...');
		echo_event('错误提示',$e->getMessage());
		
		set_page_footer(0);
		set_html_footer();
		exit;
	}
?>