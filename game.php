<?php
session_start();
if(!isset($_SESSION['username']))
	{

		header('Location:index.php');
	}
?>
<head>
	<link rel="stylesheet" type="text/css" href="css/style2.css">
	
	<link rel="stylesheet" href="css/bootstrap.css">

	<link rel="stylesheet" href="css/font-awesome.css"> <!-- Font-Awesome-Icons-CSS -->
<!-- //css files -->
<!-- iceland font -->
<link rel="stylesheet" href="css/font.css" type="text/css" /> <!-- Font-CSS -->
<!-- iceland font -->


<script src="js/jquery.min.js"></script>
<script type="text/javascript">
	
	$(document).ready(function(){
	
		$("#close1Button").click(function(){
			
			$("#scorediv").slideUp(1000);
			$(".bgleaderboard").fadeOut(1000);
		});
		
		$("#close2Button").click(function(){
			$("#instruction").slideUp(1000);
			$(".bginstruction").fadeOut(1000);
		});
		
		
		var scoreDisplay = false;
		
		$("#btnLeaderboard").click(function(e){
			e.preventDefault();
			if(!scoreDisplay){
				$("#instruction").slideUp(1000);
				$("#scorediv").slideDown(1000);
				scoreDisplay = true;
				instructDisplay = false;
				$(".bgleaderboard").fadeIn(1000);
			}
			else
			{
				$("#instruction").slideUp(1000);
				$("#scorediv").slideUp(1000);
				scoreDisplay = false;
				instructDisplayw = false;
				$(".bgleaderboard").fadeOut(1000);
			}
		});
		
		$(".bgleaderboard").click(function(e){
			e.preventDefault();
			if(!scoreDisplay){
				$("#instruction").slideUp(1000);
				$("#scorediv").slideDown(1000);
				scoreDisplay = true;
				instructDisplay = false;
				$(".bgleaderboard").fadeIn(1000);
			}
			else
			{
				$("#instruction").slideUp(1000);
				$("#scorediv").slideUp(1000);
				scoreDisplay = false;
				instructDisplay = false;
				$(".bgleaderboard").fadeOut(1000);
			}
		});
		
		var instructDisplay = false;
		
		$(".bginstruction").click(function(){
			if(!instructDisplay){
				$("#instruction").slideDown(1000);
				$("#scorediv").slideUp(1000);
				instructDisplay = true;
				scoreDisplay = false;
				$(".bginstruction").fadeIn(1000);
			}
			else
			{
				$("#instruction").slideUp(1000);
				$("#scorediv").slideUp(1000);
				instructDisplay = false;
				scoreDisplay = false;
				$(".bginstruction").fadeOut(1000);
			}
		});
		
		$("#btnInstructions").click(function(f){
			f.preventDefault();
			if(!instructDisplay){
				$("#instruction").slideDown(1000);
				$("#scorediv").slideUp(1000);
				instructDisplay = true;
				scoreDisplay = false;
				$(".bginstruction").fadeIn(1000);
			}
			else
			{
				$("#instruction").slideUp(1000);
				$("#scorediv").slideUp(1000);
				instructDisplay = false;
				scoreDisplay = false;
				$(".bginstruction").fadeOut(1000);
			}
		});
		
	});
</script>
</head>
<body>
		<div class="container-fluid text-center">
			 <h2 style="margin-bottom: -15px; color: #ffd090; margin-bottom: -15px; text-shadow: -1px 5px 0px #464646;">Mystic Mover</h2>
		</div>


	<div class="container">
			
			
				<div id="myscore" class="col-md-3 col-lg-3 "  style="margin-top: 90px; ">
					<table style="font-family: Iceland; font-weight: normal; font-size:23; color:#ffd090">
						
						<tr>

						<td style="">&nbsp;&nbsp;&nbsp;&nbsp;Level&nbsp;&nbsp;&nbsp;&nbsp;
						<span style="color: #26707b;">
						<span>
						</td>
						<td><div style="position: relative; background: rgba(0, 0, 0, 0.2); top: 2px; height: 30px; width: 5px;"></div>	</td>
						<td id = "plevel">&nbsp;	</td>
						</tr>
						<tr>
						<td><td>
						
							
						<td><span style="font-size:27;"></span></td>
						</tr>
						<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;Score</td>
						<td><div style="position: relative; background: rgba(0, 0, 0, 0.2); top: 2px; height: 30px; width: 5px;"></div>	</td>
						<td id = "pscore">&nbsp;</td>	
						</tr>
						<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="assets/rdiamond.png" style="width:20; height: 20;" alt="">&nbsp;</td>
						<td><div style="position: relative; background: rgba(0, 0, 0, 0.2); top: 2px; height: 30px; width: 5px;"></div>	</td>
						<td id = "pred">&nbsp;</td>
						</tr>
						<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="assets/bdiamond.png" style="width:20; height: 20;" alt="">&nbsp;</td>
						<td>
						<div style="position: relative; background: rgba(0, 0, 0, 0.2); top: 2px; height: 30px; width: 5px;"></div>	</td>
						<td id = "pblue"></td>
						</tr>
						<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;Assets&nbsp;&nbsp;</td>
						<td><div style="position: relative; background: rgba(0, 0, 0, 0.2); top: 2px; height: 30px; width: 5px;"></div>	</td>
						<td>&nbsp;<img src="assets/hammer.png" id = "imghammer" style = " display: none; width: 50; height: 50;" alt=""></td>
						<td>&nbsp;<img src="assets/key.png" id = "imgkey" style = "display: none; width: 35; height: 35;" alt=""></td>

						</tr>
						</table>

				</div>
				<div class="col-md-6 col-lg-6">
					
					<div style = "width: 600px; height: 600px; margin: auto;">
						<canvas id = "gCanvas" width="512" height="512"></canvas>
					</div>					
								
				</div>
				<div id="" class="col-md-3 col-lg-3" style="margin-top: 90px;">
						<input class="submitbutton myfont" id="btnInstructions" style=" margin-left:0px;width:250px;" type="submit" name="instructions" value="Instructions">
			</br></br>
			<input class="submitbutton myfont" id="btnLeaderboard" style="margin-left:0px;width:250px;" type="submit" name="leaderboard" value="Leaderboard">
				</div>
				
						
		
	</div> 

<!--Questions-->
					<div class="blackscreen bgquestion"></div>
					<div id="question" class="ques">
					<div class="note">
					Note: Click on Submit to submit your answer.
					</div>
					<br>
					<input class="submitbutton" style="position:absolute; top:10px; right:10px;" id="skipButton" type="button" name="skip" value="X">
					<h3 id = "qst"></h3>
					<br>
					
					<br>
					<center>
					<div class="answer" style="position:absolute; bottom:10px;">
						<input id="ans1" placeholder="Your Answer" type="text" name="answer" autofocus value="">
						<input class="qbutton" style="" type="submit" name="submit" value="Submit">
					
						<div id="wronganswer">
							Wrong Answer
						</div>
					</div>
					</center>
					

					</div>



					<!--Leaderboard-->
<div class="blackscreen bgleaderboard"></div>

<div  id = "scorediv" style="display:none; font-family: Iceland; color: black; position: absolute;">
<input style="position: absolute;top:5px; right:5px;" class="submitbutton" id="close1Button" type="button" name="close1" value="X">

<table style="position: relative; height:90%;width:100%;top:40px; color:white;">


<tr style="font-weight: bold;">

<td>&nbsp;&nbsp; &nbsp; Name&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>College&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>Score&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>Red Diamond</td>
</tr>

<tr>
<td id = "11">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "12">&nbsp;&nbsp;</td>
<td id = "13">&nbsp;&nbsp;</td>
<td id = "14"></td>
</tr>

<tr>
<td id = "21">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "22">&nbsp;&nbsp;</td>
<td id = "23">&nbsp;&nbsp;</td>
<td id = "24"></td>
</tr>

<tr>
<td id = "31">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "32">&nbsp;&nbsp;</td>
<td id = "33">&nbsp;&nbsp;</td>
<td id = "34"></td>
</tr>

<tr>
<td id = "41">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "42">&nbsp;&nbsp;</td>
<td id = "43">&nbsp;&nbsp;</td>
<td id = "44"></td>
</tr>

<tr>
<td id = "51">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "52">&nbsp;&nbsp;</td>
<td id = "53">&nbsp;&nbsp;</td>
<td id = "54"></td>
</tr>

<tr>
<td id = "61">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "62">&nbsp;&nbsp;</td>
<td id = "63">&nbsp;&nbsp;</td>
<td id = "64"></td>
</tr>

<tr>
<td id = "71">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "72">&nbsp;&nbsp;</td>
<td id = "73">&nbsp;&nbsp;</td>
<td id = "74"></td>
</tr>

<tr>
<td id = "81">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "82">&nbsp;&nbsp;</td>
<td id = "83">&nbsp;&nbsp;</td>
<td id = "84"></td>
</tr>

<tr>
<td id = "91">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
<td id = "92">&nbsp;&nbsp;</td>
<td id = "93">&nbsp;&nbsp;</td>
<td id = "94"></td>
</tr>

</table>	

</div>

<!--Instructions-->
<div class="blackscreen bginstruction"></div>

<div id = "instruction" style="display:none; height:102%;">
<b><font size="4"  face=Iceland>
<input style="position: absolute;top:5px; right:5px;" class="submitbutton" id="close2Button" type="button" name="close2" value="X">
	<p style="text-align:center; font-size:27px; position: relative; width:100%; margin-top:40px; margin-bottom: 50px;"><b>Rules and Instructions</b></p>

	<p style="margin-left:20px;">1. Use arrow keys or w,a,s,d to move the Player</p>
	<p style="margin-left:20px;">2. Press Enter key to start any level.</p>
	<p style="margin-left:20px;">3. Press space to use the key.</p>
	<p style="margin-left:20px;">3. Player can carry only one key at a time.</p>
	<p style="margin-left:20px;">3. Press space to use the hammer.</p>
	<p style="margin-left:20px;">3. Hammer can break special type of walls - <img src="assets/202.png"  style="height:50px;"></p>
	<p style="margin-left:20px;">3. Red diamonds - 1000 Points, Blue diamonds - 50 Points, Gold Coin - 10 Points.</p>
	<p style="margin-left:20px;">3. Touching Fire or spiky ball will reduce 200 Points.</p>
	<p style="margin-left:20px;">4. The answer must be written in small letters in the space provided.</p>
	<p style="margin-left:20px;">5. The answer must not contain any space and special character.</p>
	<p style="margin-left:20px;">6. There are Red diamond requirements for moving to different stages:</p>
	<p style="margin-left:20px;"> Level 2 : 1 Diamond.</p>
	<p style="margin-left:20px;"> Level 3 : 3 Diamonds.</p>
	<p style="margin-left:20px;"> Level 4 : 5 Diamonds.</p>
	<p style="margin-left:20px;"> Level 5 : 9 Diamonds.</p>
	<p style="margin-left:20px;"> Level 6 : 11 Diamonds.</p>
	<p style="margin-left:20px;"> Level 7 : 14 Diamonds.</p>
	<p style="margin-left:20px;"> Level 8 : 18 Diamonds.</p>
	<p style="margin-left:20px;"> Level 9 : 30 Diamonds.</p>
	</font>
	</b>
	
</div>





<div style="display: none">	
	<img id = "tileSet" src="assets/tiles.png">
	<img src="assets/character.png" alt="" id="character">
	<img src="assets/george.png" alt="" id="george">
	<img src="assets/tree.png" alt="" id="tree">
	<img src="assets/martha.png" alt="" id="martha">
	<img src="assets/bird1.png" alt="" id="bird">
	<img src="assets/starGold.png" alt="" id="star">
	<img src="assets/boy.png" alt="" id="boy">
	<img src="assets/path0.png" alt="" id="path0">
	<img src="assets/path1.png" alt="" id="path1">
	<img src="assets/blue.png" id = "blueDiamond">
	<img src="assets/red.png" id = "redDiamond">
	<img src="assets/crossLight.png" alt="" id="crossLight">
	<img src="assets/crossDark.png" alt="" id="crossDark">
	<img src="assets/gateVClose.png" id = "gateVClose">
	<img src="assets/gateVOpen.png" id = "gateVOpen">
	<img src="assets/gateHClose.png" id = "gateHClose">
	<img src="assets/gateHOpen.png" id = "gateHOpen">
	<img src="assets/ball.png" id = "ball">
	<img src="assets/ouch.png" alt="" id="ouch">
	<img src="assets/bdiamond.png" alt="" id="bdiamond">
	<img src="assets/rdiamond.png" alt="" id="rdiamond">
	<img src="assets/wall.png" alt="" id="wall">
	<img src="assets/key.png" alt="" id="key">
	<img src="assets/lock1.png" alt="" id = "lock1">
	<img src="assets/lockNum.png" alt="" id="lockNum">
	<img src="assets/boulder.png" alt="" id="boulder">
	<img src="assets/exit.png" alt="" id="imgExit">
	<img src="assets/721.jpg" alt="" id="blueBox">
	<img src="assets/722.png" alt="" id="blackBox">
	<img src="assets/723.png" alt="" id="woodWin">
	<img src="assets/202.png" alt="" id="breakable">
	<img src="assets/724.jpg" alt="" id="a724">
	<img src="assets/725.png" alt="" id="a725">
	<img src="assets/731.png" alt="" id="a731">
	<img src="assets/741.png" alt="" id="a741">
	<img src="assets/goldcoin.png" alt="" id="goldcoin">
	<img src="assets/fireman.png" alt="" id="fireman">
	<img src="assets/gatekeeperleft.png" alt="" id="gatekeeperleft">
	<img src="assets/gatekeeperdown.png" alt="" id="gatekeeperdown">
	<img src="assets/fire.png" alt="" id="fire">
	<img src="assets/bluediamond.png" alt="" id="bluediamond">
	<img src="assets/reddiamond" alt="" id="reddiamond">
	<img src="assets/751" alt="" id="a751">
	<img src="assets/761.jpg" alt="" id="a761">
	<img src="assets/762.png" alt="" id="a762">

 </div>
	<script src = "js/jquery.js"></script>
	<script src = "js/script.js"></script>
		<script src="js/bootstrap.js"></script>

	
</body>
