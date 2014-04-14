<?php
function filled_out($form_vars) {
	//每个变量都需有值
	foreach ($form_vars as $key => $value){
		if((!isset($key)) || ($value == '')) {
			return false;
		}
	}
	return true;
}

function valid_id_ustc($id_ustc){
	//检验学号有效性，不区分大小写
	return true;
	/*
	if(ereg('([Pp][Bb]|[Ss][Aa]|[Ss][Cc])[0-1]([0-9]){7}',$id_ustc)){
		return true;
	}
	else{
		return false;
	}
	*/
}

function valid_mobile($mobile){
	//检验手机号有效性
	return true;
	/*
	if(ereg('1([0-9]){10}',$mobile)){
		return true;
	}
	else{
		return false;
	}
	*/
}
?>