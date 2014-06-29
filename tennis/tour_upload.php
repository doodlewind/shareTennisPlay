<?php
require_once('tennis_fns.php');
session_start();
set_html_header();
set_page_header('banner',"巡回赛上传");
display_tour_form();
start_tour();
function start_tour(){
	try{
		upload_tour();
	}
	catch (Exception $e) {
		echo_event('提示',$e->getMessage());
		set_page_footer(0);
		set_html_footer();
		exit;
	}
	echo_event('提示','上传完成');
	set_page_footer(0);
	set_html_footer();
}

?>