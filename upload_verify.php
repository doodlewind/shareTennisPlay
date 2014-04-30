<?php
	require_once('tennis_fns.php');
	session_start();
	$conn = db_connect();
	try{
		//表格完整性
		if(!filled_out($_POST)) {
			throw new Exception('没填完整呢，<a href="upload.php"data-ajax="false">返回</a>');
		}
		if(isset($_POST['name_p2'])&&!isset($_POST['name_p3'])){
			//throw new Exception ("uploadsingle");
			upload_free(1,$conn);		
		}
		if(isset($_POST['name_p3'])){
			//throw new Exception ("uploaddouble");
			upload_free(2,$conn);	
		}
		if(isset($_POST['duration'])){
			//throw new Exception ("practice");
			upload_practice($conn);
		}
		if(isset($_POST['password'])){
			//throw new Exception ("chgpasswd");
			update_password($conn);	
		}
		//throw new Exception ("failed");
		//布置成员页面
		set_html_header();
		set_page_header('banner','上传成功！');
		echo_event('提示','记录已上传，<a href="member.php#member"data-ajax="false">返回</a>');
		
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