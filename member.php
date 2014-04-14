<?php

require_once('tennis_fns.php');
session_start();	
init_member();//this function locates in user_auth_fun.php
set_html_header(1,"主页");
check_valid_id();
display_timeline();
set_html_footer(1);

?>