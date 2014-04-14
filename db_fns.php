<?php
function db_connect() {
	$result = new mysqli('127.0.0.1','root','6050','ustc_tennis');
	if(!$result) {
		throw new Exception('Sorry，无法连接到数据库...');
	}
	else {
		return $result;
	}
}
?>