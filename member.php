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
$conn = db_connect();
$freeVaryTable = new freeVaryTable($conn);
$freeAllTable = new freeAllTable($conn);
$freeFrequencyTable = new freeFrequencyTable($conn);
echo '<div data-role="collapsible" data-collapsed-icon="info" data-expanded-icon="info">
    <h4>计分说明</h4>
  <p><b>单打胜者：</b> (27-总局数)*倍率<br/><b>单打负者：</b>总局数*倍率<br/><b>倍率</b>=对方一周积分/我方一周积分，下限为0.9，上限为3（我方若尚无积分，则按6分计算）<br/><b>双打胜者：</b>27-总局数<br/><b>双打负者：</b>总局数*倍率</p>
  </div>';
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