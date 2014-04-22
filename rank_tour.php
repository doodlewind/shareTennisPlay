<?php

require_once('tennis_fns.php');
session_start();	
init_member();//this function locates in user_auth_fun.php
set_html_header(3,"积分排名");
check_valid_id();
display_table(2);//在content_fns.php中
set_html_footer(2); 

?>