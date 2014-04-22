<?php

require_once('tennis_fns.php');
set_html_header(0,"Profile_Sample");

?>
<a href="profile.php?x=28&amp;y=66">get method test</a>
<?php

$demo = 'hey ';
$demo.='Jude. ';
$demo.='I am well.';
echo_event("a",$demo);
set_html_footer(0);

?>