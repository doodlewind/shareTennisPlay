<?php
require_once('tennis_fns.php');
session_start();
set_html_header(0,"巡回赛上传");
display_tour_form();

start_tour();
function start_tour(){
	try{
		upload_tour();
		echo_event('提示','上传完成');
	}
	catch (Exception $e) {
		echo_event('提示',$e->getMessage());
		exit;
	}
}
?>