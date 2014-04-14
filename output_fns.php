<?php
function display_register_form(){
	//注册表单
?>
<form action="register_verify.php"method="post">
	<ul>
		<li>学号<input type="text"name="id_ustc"></li>
		<li>姓名<input type="text"name="name"></li>
		<li>手机<input type="text"name="mobile"></li>
		<li>密码<input type="password"name="passwd"></li>
	</ul>
	<br/>
	<input type="submit"value="注册！">
</form>
<?php
}//函数结束
function display_login_form(){
	//登录表单
?>
<button><img src="img/logo.png"></img></button>
<form action="member.php"method="post">
	<ul>
		<li>学号<input type="text"name="id_ustc"></li>
		<li>密码<input type="password"name="passwd"></li>
	</ul>
	<br/>
	<input type="submit"value="登录！">
</form>
<?php
}
function display_date_button($flag){
	date_default_timezone_set('PRC');
	//flag取值  1:month 2:day 3:hour
	$year = date("Y",time());
	$month = date("m",time());
	$date = date("d",time());
	$hour = date("h",time());
	switch($flag) {
		case 1:
			for($i=1;$i<=12;$i++){
				if ($i==$month){
					echo '<option value="'.$i.'"selected="selected">'.$i.'月</option>';
				}
				else echo '<option value="'.$i.'">'.$i.'月</option>';
			}
			break;
		case 2:
			if($month==4||$month==6||$month==9||$month==11){
				$days_of_month = 30;
			}
			else if($month==2){
				if($year%4==0){
					$days_of_month = 29;
				}else $days_of_month = 28;
			}
			else $days_of_month = 31;
			for($i=1;$i<=$days_of_month;$i++){
				if ($i==$date){
					echo '<option value="'.$i.'"selected="selected">'.$i.'日</option>';
				}
				else echo '<option value="'.$i.'">'.$i.'日</option>';
			}
			break;
		case 3:
			if($hour < 7){
				echo '<option value="7"selected="selected">7点</option>';
				for($i=8;$i<=18;$i++){
					echo '<option value="'.$i.'">'.$i.'点</option>';
				}
			}
			if($hour > 18){
				for($i=7;$i<=17;$i++){
					echo '<option value="'.$i.'">'.$i.'点</option>';
				}
				echo '<option value="18"selected="selected">18点</option>';
			}
			for($i=7;$i<=18;$i++){
					if ($i==$hour){
						echo '<option value="'.$i.'"selected="selected">'.$i.'点</option>';
					}
					else echo '<option value="'.$i.'">'.$i.'点</option>';
			}
			break;
	}
	?>

<?php
}
function echo_event($title,$content){
	//输出一条由标题和内容组成的消息
?>
	 <br/>
	 <div class="ui-corner-all custom-corners">
	   <div class="ui-bar ui-bar-a">
	     <h3><?php echo $title;?></h3>
	   </div>
	   <div class="ui-body ui-body-a">
	     <p><?php echo $content;?></p>
	   </div>
	 </div>
<?php
}
function set_html_header($flag,$title){
	//0类不含header按钮，1类不含积分排名所用navbar
	//2类带自由赛navbar，3类带巡回赛navbar
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../jquery/jquery.mobile-1.4.0.css">
<script src="../jquery/jquery-2.0.3.min.js"></script>
<script src="../jquery/jquery.mobile-1.4.0.js"></script>
<style>
.ui-table-columntoggle-btn {
    display: none !important;
}	
</style>
<title><?php echo $title;?></title>
</head>


<body>
<div data-role="page">
	<div data-role="header" data-position="fixed">
<?php
	if($flag==0){
?>
		 <h1><?php echo $title;?></h1>
 	</div><!--header-->
 	<div role="main" class="ui-content">
		 
<?php
		return true;
	}	
?>		
	   	<!--单打战果按钮--> 
		<a href="#upload_free" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all  ui-btn-inline ui-icon-plus ui-btn-icon-left ui-btn-a" data-transition="pop">单打</a>
		<div data-role="popup" id="upload_free" data-theme="a" class="ui-corner-all">
		        <div style="padding:10px 20px;">
		            <h3>上传战果</h3>
					<form action="free_upload.php"method="post">
					<input type="text" name="name_p2" id="textinput-2" placeholder="对手" value="">
					
					<fieldset data-role="controlgroup" data-mini="true"data-type="horizontal">
						<legend>时间</legend>
						<select name="month">
							
							
<?php
	display_date_button(1);
?>

					    </select>
					    <select name="day">
					        
<?php
	display_date_button(2);
?>
							
					    </select>
					    <select name="hour">
<?php
	display_date_button(3);
?>
					    </select>
						
						
					</fieldset>
					<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
						<legend>比分</legend>
					    <select name="set_p1">
							<option>我</option>
							<option value="0">0</option>
					        <option value="1">1</option>
					        <option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
					    </select>
					    <select name="set_p2">
							<option>对手</option>
							<option value="0">0</option>
					        <option value="1">1</option>
					        <option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
					    </select>
						
					</fieldset>
					
					<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
					        <legend>地点</legend>
					        <input type="radio" name="court" id="court-1a" value="1">
					        <label for="court-1a">东区网球场</label>
					        <input type="radio" name="court" id="court-1b" value="2">
					        <label for="court-1b">西区网球场</label>

					</fieldset>
					<input type="text" name="comment" id="textinput-2" placeholder="一句话感慨" value="">

		            <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">提交！</button>
		        </form>
				</div>
		    
		</div>
		<!--单打战果按钮--> 
		<h1><?php echo $title;?></h1>
		<!--双打战果按钮--> 
		<a href="#invitation" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all  ui-btn-inline ui-icon-plus ui-btn-icon-left ui-btn-a" data-transition="pop">双打</a>
		<div data-role="popup" id="invitation" data-theme="a" class="ui-corner-all">
	    <form>
	        <div style="padding:10px 20px;">
	            <h3>我要约球</h3>
				<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
					<legend>时间</legend>
				    <select name="day" id="day">
				        <option value="1">明天</option>
				        <option value="2">后天</option>
				        <option value="3">26日</option>
				        <option value="4">27日</option>
				        <option value="5">28日</option>
				    </select>
				    <select name="time" id="time">
				        <option value="7">约7点</option>
				        <option value="8">约8点</option>
				        <option value="9">约9点</option>
				        <option value="10">约10点</option>
				        <option value="11">约11点</option>
						<option value="12">约12点</option>
						<option value="13">约13点</option>
						<option value="14">约14点</option>
						<option value="15">约15点</option>
						<option value="16">约16点</option>
						<option value="17">约17点</option>
						<option value="18">约18点</option>
				    </select>
				</fieldset>
				<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
			        <legend>地点</legend>
			        <input type="radio" name="court" id="court-2a" value="on">
			        <label for="court-2a">东区网球场</label>
			        <input type="radio" name="court" id="court-2b" value="off">
			        <label for="court-2b">西区网球场</label>

				</fieldset>
				<br/>
	            <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">提交！</button>
	        </div>
	    </form>
	</div>
		<!--双打战果按钮--> 
<?php
	if($flag==2){
?>
	   <div data-role="navbar">
	           <ul>
	               <li><a href="#"class="ui-btn-active">自由赛积分</a></li>
	               <li><a href="rank_tour.html">巡回赛积分</a></li>
	           </ul>
	       </div><!-- /navbar -->
<?php
	}	
	if($flag==3){
?>
	   <div data-role="navbar">
	           <ul>
	               <li><a href="rank_free.html">自由赛积分</a></li>
	               <li><a href="#"class="ui-btn-active">巡回赛积分</a></li>
	           </ul>
	       </div><!-- /navbar -->
<?php
	}
?>
	
	</div><!--header-->
	<div role="main" class="ui-content">
<?php
}
?>
<?php
function set_html_footer($flag){
	//html页尾格式
	if($flag==0){
?>
	</div><!--main-->
	<div data-role="footer"data-position="fixed">
		<h1>USTC Tennis 2014</h1>
	</div><!--footer-->
</div><!--page-->
</body>
</html>
<?php
		return true;
	}//if
?>
	</div><!--main-->
	<div data-role="footer" data-position="fixed">
	    <div data-role="navbar">
			<ul>
<?php
	if($flag==1){
?>	
                <li><a href="#" data-icon="bars" class="ui-btn-active">动态</a></li>
                <li><a href="rank_free.php" data-icon="star" >积分</a></li>
                <li><a href="profile.php" data-icon="user">我的</a></li>
<?php
	}
	else if($flag==2){
?>
                <li><a href="member.php" data-icon="bars">动态</a></li>
                <li><a href="#" data-icon="star"class="ui-btn-active">积分</a></li>
                <li><a href="profile.php" data-icon="user">我的</a></li>
<?php
	}
	else if($flag==3){
?>
                <li><a href="member.php" data-icon="bars" >动态</a></li>
                <li><a href="rank_free.php" data-icon="star" >积分</a></li>
                <li><a href="#"class="ui-btn-active" data-icon="user">我的</a></li>
<?php
	}
?>
            </ul>
	    </div><!-- /navbar -->
	</div><!-- /footer -->
</div><!--page-->
</body>
</html>
<?php
}//set_html_footer
?>