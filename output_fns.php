<?php
function display_register_form(){
	//注册表单
?>
<form action="register_verify.php"method="post">
	<div class="ui-grid-a">
		<div class="ui-block-a">
			<img src="banner.jpg"width="80%">
		</div>
		<div class="ui-block-b">
			<fieldset data-role="controlgroup" data-mini="true">
				<div>
					<legend>学号</legend><input type="text"name="id_ustc">
					<legend>姓名</legend><input type="text"name="name">
					<legend>手机</legend><input type="text"name="mobile">
					<legend>密码</legend><input type="password"name="passwd">
					<input type="submit"value="注册！">
				</div>
			</fieldset>

		
		</div>
</form>
<?php
}//函数结束
function display_login_form(){
	//登录表单
?>
<form action="member.php"method="post">
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<img src="banner.jpg"width="80%">
					</div>
					<div class="ui-block-b">
						<fieldset data-role="controlgroup" data-mini="true">
							<div>
							<legend>学号</legend>
							<input data-mini="true" type="text"name="id_ustc">
							<legend>密码</legend>
							<input data-mini="true"type="password"name="passwd">
							<br/>
							<input type="submit"value="登录！">
							</div>
						</fieldset>
					</div>
				</div>
</form>

<?php
}
function display_date_button($flag){
	//flag取值  1:month 2:day 3:hour
	date_default_timezone_set('PRC');
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
			else if($hour > 18){
				for($i=7;$i<=17;$i++){
					echo '<option value="'.$i.'">'.$i.'点</option>';
				}
				echo '<option value="18"selected="selected">18点</option>';
			}
			else for($i=7;$i<=18;$i++){
					if ($i==$hour){
						echo '<option value="'.$i.'"selected="selected">'.$i.'点</option>';
					}
					else echo '<option value="'.$i.'">'.$i.'点</option>';
			}

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
	   <div data-mini="true" class="ui-body ui-body-a">
	     <p><?php echo $content;?></p>
	   </div>
	 </div>
<?php
}
function echo_br(){
	echo '<br/>';
}
function echo_short($content){
	?>
   <div class="ui-bar ui-bar-a">
     <h3><?php echo $content;?></h3>
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
		<a href="upload_single.php" class="ui-btn ui-corner-all  ui-btn-inline ui-icon-plus ui-btn-icon-left ui-btn-a">战果</a>
		<h1><?php echo $title;?></h1>
		<!--练球战果按钮--> 
		<a href="#practice" data-rel="popup" data-position-to="window" class="ui-btn ui-corner-all  ui-btn-inline ui-icon-plus ui-btn-icon-left ui-btn-a" data-transition="pop">练球</a>
		<div data-role="popup" id="practice" data-theme="a" class="ui-corner-all">
	    <form action="practice_upload.php"method="post">
	        <div style="padding:10px 20px;">
				<fieldset data-role="controlgroup" data-mini="true"data-type="horizontal">
						<legend>练习结束时间</legend>
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
				<fieldset data-role="controlgroup"data-mini="true"data-type="horizontal">
				        <legend>练习内容</legend>
				        <input type="checkbox" name="item[]" id="checkbox-h-2a"value="1"checked="checked">
				        <label for="checkbox-h-2a">正手</label>
				        <input type="checkbox" name="item[]" id="checkbox-h-2b"value="2">
				        <label for="checkbox-h-2b">反手</label>
				        <input type="checkbox" name="item[]" id="checkbox-h-2c"value="4">
				        <label for="checkbox-h-2c">发球</label>
				        <input type="checkbox" name="item[]" id="checkbox-h-2d"value="8">
				        <label for="checkbox-h-2d">截击</label>
				    </fieldset>
					
				<div class="ui-grid-a">
					<div class="ui-block-a">
						<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
							<legend>练习时长</legend>					
						    <select name="duration" id="duration">
						        <option value="0">约半小时</option>
						        <option value="1">约1小时</option>
						        <option value="2">约2小时</option>
						        <option value="3">约3小时</option>
						        <option value="4">约4小时</option>
								<option value="5">约5小时</option>
						    </select>
						</fieldset>
					</div>
					<div class="ui-block-b">
						<fieldset data-role="controlgroup" data-mini="true">
					        <legend>地点</legend>
					        <input type="radio" name="court" id="court-2a" value="1">
					        <label for="court-2a">东场</label>
					        <input type="radio" name="court" id="court-2b" value="2">
					        <label for="court-2b">西场</label>
						</fieldset>
					</div>
				</div>	
					
					
				
				<br/>
	            <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">提交！</button>
	        </div>
	    </form>
	</div>
		<!--练球按钮--> 
<?php
	if($flag==2){
?>
	   <div data-role="navbar">
	           <ul>
	               <li><a href="#"class="ui-btn-active">自由赛积分</a></li>
	               <li><a href="rank_tour.php">巡回赛积分</a></li>
	           </ul>
	       </div><!-- /navbar -->
<?php
	}	
	if($flag==3){
?>
	   <div data-role="navbar">
	           <ul>
	               <li><a href="rank_free.php">自由赛积分</a></li>
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
                <li><a href="profile.php" data-icon="user">会员</a></li>
<?php
	}
	else if($flag==2){
?>
                <li><a href="member.php" data-icon="bars">动态</a></li>
                <li><a href="#" data-icon="star"class="ui-btn-active">积分</a></li>
                <li><a href="profile.php" data-icon="user">会员</a></li>
<?php
	}
	else if($flag==3){
?>
                <li><a href="member.php" data-icon="bars" >动态</a></li>
                <li><a href="rank_free.php" data-icon="star" >积分</a></li>
                <li><a href="#"class="ui-btn-active" data-icon="user">会员</a></li>
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
function display_single_form(){
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
<title>单打上传</title>
</head>
<body>
	<div data-role="page">
		<div data-role="header">
	 	   <div data-role="navbar"><!-- navbar -->
	 	           <ul>
	 	               <li><a href="#"class="ui-btn-active">上传单打</a></li>
	 	               <li><a href="upload_double.php">上传双打</a></li>
	 	           </ul>
	 	       </div><!-- navbar -->
	  </div>

		<div data-role="main">
			<div style="padding:10px 20px;">
		    <form action="free_upload.php"method="post">
					        <input data-mini="true" type="text" name="name_p2" value="" placeholder="对手名，必填哦">
							<fieldset data-role="controlgroup" data-mini="true"data-type="horizontal">
								<legend>时间</legend>
							    <select name="month" id="month">
<?php
	display_date_button(1);
?>
							    </select>
							    <select name="day" id="day">
<?php
	display_date_button(2);
?>
							    </select>
							    <select name="hour" id="hour">
<?php
	display_date_button(3);
?>
							    </select>	
							</fieldset>				    
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
									<legend>比分</legend>
								    <select name="set_p1" id="set_p1">
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
								    <select name="set_p2" id="set_p2">
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
								
							</div>
							<div class="ui-block-b">
								<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
								        <legend>地点</legend>
								        <input type="radio" name="court" id="radio-choice-v-6a" value="1">
								        <label for="radio-choice-v-6a">东场</label>
								        <input type="radio" name="court" id="radio-choice-v-6b" value="2">
								        <label for="radio-choice-v-6b">西场</label>
								</fieldset>
								
							</div>
						</div>
						<fieldset data-role="controlgroup">
						<input data-mini="true"type="text" name="comment" id="textinput-2" placeholder="说句感想吧，必填哦" value="">
						</fieldset>
					<div class="ui-grid-a">
						<div class="ui-block-a">
						<a href="member.php" class="ui-btn ui-corner-all ui-shadow">返回</a>
						</div>
						<div class="ui-block-b">
					    <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">提交！</button>
						</div>
					</div>
		        </div>
		    </form>
		</div><!--main-->
	</div><!--page-->
</body>
</html>



<?php
}
function display_double_form(){
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
	<title>Home</title>
	</head>
	<body>
		<div data-role="page">
			<div data-role="header">
		 	   <div data-role="navbar">
		 	           <ul>
		 	               <li><a href="upload_single.php">上传单打</a></li>
		 	               <li><a href="#"class="ui-btn-active">上传双打</a></li>
		 	           </ul>
		 	       </div><!-- /navbar -->
		  </div>

			<div data-role="main">
				<div style="padding:10px 20px;">
			    <form action="free_upload_double.php"method="post">
						<legend>*若有非网协会员，相应位置请留空</legend>
						<input data-mini="true" type="text" name="name_p2"value="" placeholder="队友名">
					<div class="ui-grid-a">
						<div class="ui-block-a">

							<input data-mini="true" type="text" name="name_p3"value="" placeholder="对手名">
						</div>
						<div class="ui-block-b">

							<input data-mini="true" type="text" name="name_p4" value="" placeholder="对手名">
						</div>
					</div>
								<fieldset data-role="controlgroup" data-mini="true"data-type="horizontal">
									<legend>时间</legend>
								    <select name="month" id="month">
<?php
	display_date_button(1);
?>
								    </select>
								    <select name="day" id="day">
<?php
	display_date_button(2);
?>
								    </select>
								    <select name="hour" id="hour">
<?php
	display_date_button(3);
?>
								    </select>	
								</fieldset>						    
							<div class="ui-grid-a">
								<div class="ui-block-a">
									<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
									    <select name="set_p1n2" id="set_p1n2">
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
									    <select name="set_p3n4" id="set_p3n4">
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
								</div>
								<div class="ui-block-b">
									<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">

									        <input type="radio" name="court" id="radio-choice-v-6a" value="1">
									        <label for="radio-choice-v-6a">东场</label>
									        <input type="radio" name="court" id="radio-choice-v-6b" value="2">
									        <label for="radio-choice-v-6b">西场</label>
									</fieldset>						
								</div>
							</div>
							<fieldset data-role="controlgroup">

							<input data-mini="true"type="text" name="comment" id="textinput-2" placeholder="说句感想吧，必填哦" value="">
							</fieldset>
						<div class="ui-grid-a">
							<div class="ui-block-a">
							<a href="member.php" class="ui-btn ui-corner-all ui-shadow">返回</a>
							</div>
							<div class="ui-block-b">
						    <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">提交！</button>
							</div>
						</div>
			        </div>
			    </form>
			</div><!--main-->
		</div><!--page-->
	</body>
	</html>
<?php
}
function display_tour_form(){
?>
		    <form action="tour_upload.php"method="post">
				
				<fieldset data-type="horizontal">
					<div class="ui-grid-a">
						<div class="ui-block-a">
							<input data-mini="true" type="text" name="name1" value="" placeholder="Player1姓名">
						</div>
						<div class="ui-block-b">
							<input data-mini="true" type="text" name="name2" value="" placeholder="Player2姓名">
						</div>
					</div>
							<input data-mini="true" type="text" name="value_tour" value="" placeholder="积分">
				</fieldset>
							<fieldset data-role="controlgroup" data-mini="true"data-type="horizontal">
								<legend>时间</legend>
								<select name="year" id="year">
									<option value="2014">2014年</option>
									<option value="2013">2013年</option>
								</select>
							    <select name="month" id="month">
<?php
	display_date_button(1);
?>
							    </select>
							    <select name="day" id="day">
<?php
	display_date_button(2);
?>
							    </select>
							    <select name="hour" id="hour">
<?php
	display_date_button(3);
?>
							    </select>	
							</fieldset>				    
						<div class="ui-grid-a">
							<div class="ui-block-a">
								<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
									<legend>比分</legend>
								    <select name="set_p1" id="set_p1">
										<option>P1</option>
										<option value="0">0</option>
								        <option value="1">1</option>
								        <option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
								    </select>
								    <select name="set_p2" id="set_p2">
										<option>P2</option>
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
								
							</div>
							<div class="ui-block-b">
								<fieldset data-role="controlgroup" data-type="horizontal"data-mini="true">
								        <legend>地点</legend>
								        <input type="radio" name="court" id="radio-choice-v-6a" value="1">
								        <label for="radio-choice-v-6a">东场</label>
								        <input type="radio" name="court" id="radio-choice-v-6b" value="2">
								        <label for="radio-choice-v-6b">西场</label>
								</fieldset>
							      
							</div>
							 <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">提交！ 
						</div>

								
					
					    

		        </div>
		    </form>
<?
}
?>