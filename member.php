<?php

require_once('tennis_fns.php');
session_start();
init_member();//this function locates in user_auth_fun.php

set_html_header();

set_page_header("member","主页");
check_valid_id();
display_timeline();
set_page_footer(1);

set_page_header('rank_free',"积分排名");
display_free_table();
set_page_footer(2);

set_page_header('rank_tour',"积分排名");
display_tour_table();
set_page_footer(2);

//class profile_member locates in content_fns.php
$profile = new profile_member();
set_page_header('profile',"个人中心");
$profile->display_pensonal_data();
$profile->display_practice_time();
$profile->display_game_tour();
$profile->display_game_free();

set_page_footer(3);

set_html_footer();


?>