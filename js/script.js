const FPS = 30;
var gCanvas = document.getElementById('gCanvas').getContext('2d');
var tileSet = document.getElementById('tileSet');
var bird = document.getElementById('bird');
var star = document.getElementById('star');
var boy = document.getElementById('boy');
var path0 = document.getElementById('path0');
var path1 = document.getElementById('path1');
var blueDiamond = document.getElementById('blueDiamond');
var redDiamond = document.getElementById('redDiamond');
var crossDark = document.getElementById('crossDark');
var crossLight = document.getElementById('crossLight');
var gateHClose = document.getElementById('gateHClose');
var gateHOpen = document.getElementById('gateHOpen');
var gateVClose = document.getElementById('gateVClose');
var gateVOpen = document.getElementById('gateVOpen');
var ball = document.getElementById('ball');
var ouch = document.getElementById('ouch');
var bdiamond = document.getElementById('bdiamond');
var rdiamond = document.getElementById('rdiamond');
var answer = document.getElementById('ans1');
var wall = document.getElementById('wall');
var key = document.getElementById('key');
var lock1 = document.getElementById('lock1');
var lockNum = document.getElementById('lockNum');
var boulder = document.getElementById('boulder');
var imgExit = document.getElementById('imgExit');
var blueBox = document.getElementById('blueBox');
var blackBox = document.getElementById('blackBox');
var woodWin = document.getElementById('woodWin');
var breakable = document.getElementById('breakable');
var goldcoin = document.getElementById('goldcoin');
var fireman = document.getElementById('fireman');
var gatekeeperleft = document.getElementById('gatekeeperleft');
var gatekeeperdown = document.getElementById('gatekeeperdown');
var pbluediamond = document.getElementById('bluediamond');
var preddiamond = document.getElementById('reddiamond');
var fire = document.getElementById('fire');
var a724 = document.getElementById('a724');
var a725 = document.getElementById('a725');
var a731 = document.getElementById('a731');
var a741 = document.getElementById('a741');
var a751 = document.getElementById('a751');
var a761 = document.getElementById('a761');
var a762 = document.getElementById('a762');
var hammershowing = false;
var questionVisible = false;
var tileWidth = 64;
var tileHeight = 64;
var george = document.getElementById('george');
var canvasWidth = gCanvas.canvas.width;
var canvasHeight = gCanvas.canvas.height;
var dataRequested = false;
var movingTick = 0;
var ans = 'none';
var question='';
var answerSubmit = false;
var counter = 0;
var facingDirection = 0;
var mapData;

var keyboard = {
	left:[{'pressed':false},{'pressedTime':0}],
	right:[{'pressed':false},{'pressedTime':0}],
	up:[{'pressed':false},{'pressedTime':0}],
	down:[{'pressed':false},{'pressedTime':0}],
	space:[{'pressed':false},{'pressedTime':0}],
	enter:[{'enter':false},{'pressedTime':0}],
	esc:[{'esc':false},{'pressedTime':0}]
};
var keyDown = 'none';

function getKeyPressed(){
	var mostRecentKeyDown = 'none';
	var maxKeyCounter = 0;
	for(var k in keyboard){
		if(keyboard[k].pressed){
			if(maxKeyCounter < keyboard[k].pressedTime){
				maxKeyCounter = keyboard[k].pressedTime;
				mostRecentKeyDown = k;
			}
		}
	}
	return mostRecentKeyDown;
}
function getMovingTick(){
	if(keyDown == 'left' || keyDown == 'right' || keyDown == 'up'|| keyDown == 'down')
		movingTick += 0.2;
	else
		movingTick = 0;
}
function getDirection(){
	switch(keyDown){
		case 'down': facingDirection = 0; break;
		case 'left': facingDirection = 1; break;
		case 'up': 	 facingDirection = 2; break;
		case 'right':facingDirection = 3; break;
	}
}
function getMapData(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			mapData = JSON.parse(this.responseText);
			dataRequested = false;
			if(answerSubmit==true){
				answerSubmit = false;
				checkAnswer();
			}
		}
	}
	xmlhttp.open("GET","main.php?keyDown="+keyDown+"&answer="+ans);
	xmlhttp.send();
}
function checkAnswer(){
	var answered = mapData["answer"];
	if(answered == 1){
		hidequestion();
		$("#wronganswer").fadeOut(1000);

	}
	else if(answered == 0){
		$("#wronganswer").fadeIn(1000);
	}
}
function submitAnswer(){
	ans = answer.value;
	if(ans.length > 0){
		dataRequested = true;
		getMapData();
		answerSubmit = true;
	}
}
$(".qbutton").click(function(){
	submitAnswer();
});

function hidequestion(){
	$(".bgquestion").fadeOut(1000);
	$("#question").slideUp(1000);
	questionVisible = false;
	answerSubmit = false;
	ans = "none";
		$("#wronganswer").fadeOut(1000);
}
$("#skipButton").click(function(){
	/*$(".bgquestion").fadeOut(1000);
	$("#question").slideUp(1000);
	questionVisible = false;
	*/
	ans = "hide";
	dataRequested = true;
	getMapData();
	ans = "none";
	
});
function showQuestion(){
	questionVisible = true;
	$("#qst").html(question);
	$("#question").slideDown(1000);
	$(".bgquestion").fadeIn(1000);

}
function displayKey(){
	$("#imgkey").show();
}
function hideKey(){
	$("#imgkey").hide();
}
function displayHammer(){
	$("#imghammer").show();
	hammershowing = true;
}
function updateScoreboard(){
	/*$("#11").html(mapData["scoreboard"][0]["name"]);
	$("#12").html(mapData["scoreboard"][0]["college"]);
	$("#13").html(mapData["scoreboard"][0]["score"]);
	*/
	for(var i = 0;i<mapData["scoreboard"]["total"];i++){
		j=i+1;
		$("#"+j+"1").html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+mapData["scoreboard"][i]["name"]);
		$("#"+j+"2").html(mapData["scoreboard"][i]["college"]);
		$("#"+j+"3").html(mapData["scoreboard"][i]["score"]);
		$("#"+j+"4").html(mapData["scoreboard"][i]["redDiamond"]);
	}

}

function updateAssets(){
	$("#pscore").html("&nbsp;&nbsp;"+mapData["score"]);
	$("#pblue").html("&nbsp;&nbsp;"+mapData["totalbdiamonds"]);
	$("#pred").html("&nbsp;&nbsp;"+mapData["totalrdiamonds"]);
	$("#plevel").html("&nbsp;&nbsp;"+mapData["onLevel"]);
}
function allKeyUp(){
	keyboard.left.pressed = false;
	keyboard.right.pressed = false;
	keyboard.up.pressed = false;
	keyboard.down.pressed = false;
	keyboard.space.pressed = false;
	keyboard.enter.pressed = false;
	keyboard.esc.pressed = false;
}
document.addEventListener('keydown',function(event){
	//65 A, 37-left, 83-s 40-down, 68-d, 96-right, 87-w, 38-up
	console.log(mapData);
	if(!questionVisible){
		event.preventDefault();
	}
	else{
		if(event.keyCode==13){

			if(questionVisible == true){
				submitAnswer();
			}
		}
		return;
	}
	switch(event.keyCode){
		case 65:
		case 37:
			keyboard.left.pressed = true;
			keyboard.left.pressedTime = counter;
		break;
		case 83:
		case 40:
			keyboard.down.pressed = true;
			keyboard.down.pressedTime = counter;
		break;
		case 68:
		case 39:
			keyboard.right.pressed = true;
			keyboard.right.pressedTime = counter;
		break;
		case 87:
		case 38:
			keyboard.up.pressed = true;
			keyboard.up.pressedTime = counter;
		break;
		case 32:
			keyboard.space.pressed = true;
			keyboard.space.pressedTime = counter;
		break;
		case 13:
			keyboard.enter.pressed = true;
			keyboard.enter.pressedTime = counter;
			break;
		case 27:
			keyboard.esc.pressed = true;
			keyboard.esc.pressedTime = counter;
			break;
}
});
document.addEventListener('keyup',function(event){
	switch(event.keyCode){
		case 65:
		case 37:
			keyboard.left.pressed = false;
			keyboard.left.releaseTime = counter;
			break;
		case 83:
		case 40:
			keyboard.down.pressed = false;
			break;
		case 68:
		case 39:
			keyboard.right.pressed = false;
			keyboard.right.releaseTime=counter;
			break;
		case 87:
		case 38:
			keyboard.up.pressed = false;
			break;
		case 32:
			keyboard.space.pressed = false;
			break;
		case 13:
			keyboard.enter.pressed = false;
			break;
		case 27:
			keyboard.esc.pressed = false;
			allKeyUp();
			break;
	}
});
function drawAnimation(item, dx, dy, tSize,i ,j){
	var tile;
	var maxImage;
	var size;
	switch(item){
		case 101: tile = blueDiamond; maxImage = 1;size = 339; break;
		case 102: tile = redDiamond; maxImage = 1; size = 339; break;
	}

	gCanvas.drawImage(tile,(Math.round(counter/2)%maxImage)*size, 0, size, size, j * tSize + dx, i * tSize + dy, tSize, tSize );
}
function rendercoin(dx,dy,tSize,i,j){
	var frame = (Math.round(counter/2)%24);
	gCanvas.drawImage(goldcoin,(frame%8)*128, Math.floor(frame/8) * 128, 128,128, j * tSize + dx, i * tSize + dy, tSize, tSize ); 

}
function renderfireman(dx,dy,tSize,i,j){
	var frame = (Math.round(counter/2)%4);
	var dir = (Math.round(counter/100))%4;
	gCanvas.drawImage(fireman, frame*72, dir*72,72,72,j * tSize + dx, i * tSize + dy, tSize, tSize );
}
function animateBall(dx,dy,tSize,i,j){
	var maxImage = 6;
	gCanvas.drawImage(ball,(Math.round(counter/30)%maxImage)*146, 0, 146, 144, j * tSize + dx, i * tSize + dy, tSize+10, tSize+10 );
}
function renderfire(dx,dy,tSize,i,j){
	var frame = (Math.round(counter/2)%32);
	gCanvas.drawImage(fire,(frame%8)*64, Math.floor(frame/8)*128, 64,128, j * tSize + dx , i * tSize + dy-40, tSize, tSize+50);
}
function renderblue(dx,dy,tSize,i,j){
	var frame = (Math.round(counter/5)%6);
	gCanvas.drawImage(pbluediamond,2+ frame*80,0,80,98,j * tSize + dx+7, i * tSize + dy, tSize-14, tSize );
}
function renderred(dx,dy,tSize,i,j){
	var frame = (Math.round(counter/5)%6);
	gCanvas.drawImage(preddiamond,frame*82,0,82,95,j * tSize + dx+7, i * tSize + dy, tSize-14, tSize );

}
function renderLayer(layer){
	var dx = mapData['dx'];
	var dy = mapData['dy'];
	var tSize = mapData['tSize'];
	for(var i = 0; i < mapData['rows']; i++){
		for(var j = 0; j < mapData['cols']; j++){
			var drawItem = mapData['layers'][layer][i][j];
			if(drawItem < -1)
				continue;
			if(drawItem <5)
				gCanvas.drawImage(tileSet, (drawItem-1) * tileWidth, 0, tileWidth, tileHeight, j * tSize + dx, i * tSize + dy, tSize, tSize);
			else if(drawItem == 101){
				//drawAnimation(drawItem, dx, dy, tSize, i, j);
				//rendercoin(dx,dy,tSize,i,j);
				//renderfireman(dx,dy,tSize,i,j);
				//rendergatekeeper(dx,dy,tSize,i,j);
				//renderfire(dx,dy,tSize,i,j);
				//renderred(dx,dy,tSize,i,j);
				renderblue(dx,dy,tSize,i,j);
			}
			else if(drawItem == 102){
				renderred(dx,dy,tSize,i,j);
			}
			else if(drawItem == 103){
				rendercoin(dx,dy,tSize,i,j);
			}
			else if(drawItem == 301)
				animateBall(dx, dy, tSize, i, j);
			else if(drawItem == 302){
				renderfire(dx,dy,tSize,i,j);
			}
			else if(drawItem == 303){
				renderfireman(dx,dy,tSize,i,j);
			}
			else if(drawItem == 1001){
				gCanvas.drawImage(key, 0,0,256, 256,j * tSize + dx, i * tSize + dy, tSize, tSize);
			}
			else if(drawItem == 1002){
				gCanvas.drawImage(lock1,0,0,512,512,j * tSize + dx, i * tSize + dy, tSize, tSize)
			}
			else if(drawItem == 151){
				gCanvas.drawImage(gateVClose, 0, 0, 251, 966,j * tSize + dx, i * tSize + dy - tSize, tSize, tSize*3);
			}
			else if(drawItem == 152){
				gCanvas.drawImage(gateVOpen, 0,0,251,966, j*tSize + dx, i*tSize + dy - tSize, tSize, tSize*3);
			}
			else if(drawItem == 155){
				gCanvas.drawImage(boulder, 0, 0, 410, 416,j*tSize + dx, i*tSize + dy, tSize, tSize );
			}
			else if(drawItem == 160){
				gCanvas.drawImage(gatekeeperleft,0,0,53,130,j*tSize + dx+10, i*tSize + dy, tSize-10, tSize );
			}
			else if(drawItem == 161){
				gCanvas.drawImage(gatekeeperdown,0,0,66,127,j*tSize + dx+10, i*tSize + dy, tSize-10, tSize );
			}
			else if(drawItem == 99){
				gCanvas.drawImage(imgExit,0,0,86,70,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 721){
				gCanvas.drawImage(blueBox,0,0,32,32,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 722){
				gCanvas.drawImage(blackBox,0,0,32,32,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem >= 504 && drawItem <= 530){
				gCanvas.drawImage(lockNum,0,0,512,512,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 723){
				gCanvas.drawImage(woodWin,0,0,32,32,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 724){
				gCanvas.drawImage(a724,0,0,66,60,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 725){
				gCanvas.drawImage(a725,0,0,130,130,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 731){
				gCanvas.drawImage(a731,0,0,105,106,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 741){
				gCanvas.drawImage(a741, 0,0,717,716,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 751){
				gCanvas.drawImage(a751, 0,0,128,128,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 761){
				gCanvas.drawImage(a761, 0,0,282,179,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 762){
				gCanvas.drawImage(a762, 0,0,64,64,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
			else if(drawItem == 202){
				gCanvas.drawImage(breakable, 0,0,48,51,j*tSize + dx, i*tSize + dy, tSize, tSize);
			}
		}
	}
}

function renderPlayer(width, height, x, y){
	getDirection();
	if(mapData["blink"]==0 || (counter%10==0)){
		gCanvas.drawImage(george, facingDirection * 48, (Math.round(movingTick)%4)*48, 48,48, x,y ,width,height);
		//gCanvas.drawImage(boy, facingDirection * 65, (Math.round(movingTick)%4)*65, 65,65, x,y ,width,height)
	}
}

function update(){
	counter += 1;
	if(!dataRequested){
		dataRequested = true;
		if(mapData){
			if(mapData["stop"]==1){
				clearInterval(animationInterval);
			}
			if(mapData["hammer"]==1 && hammershowing == false){
				displayHammer();
			}
			if(mapData["type"]=="map" && mapData["blink"]==1){
				allKeyUp();
			}
		}
		keyDown = getKeyPressed();
		getMapData();
	}
	gCanvas.clearRect(0,0,canvasWidth, canvasHeight);
	if(mapData){
		updateScoreboard();
		updateAssets();
		if(questionVisible == false && mapData["showQuestion"]==1){
			question = mapData["question"];
			showQuestion();
		}
		else if(questionVisible == true && mapData["showQuestion"]==0){
			hidequestion();
		}

		if(mapData["type"]=="map"){
			if(mapData["key"]==1){
				displayKey();
			}
			else{
				hideKey();
			}
			for(var layer = 0; layer < mapData['playerLayer']; layer++){
				renderLayer(layer);
			}

			getMovingTick();
			renderPlayer(48,48, mapData['characterX'] -24, mapData['characterY'] - 24);

			for(var layer = mapData['playerLayer']; layer < mapData['totalLayers']; layer++){
				renderLayer(layer);
			}
			if(mapData["blink"]==1){
				gCanvas.drawImage(ouch, 0,0,960,480,mapData['characterX'] -24, mapData['characterY'] - 24-50, 80,50);
			}
		}
		else if(mapData["type"]=="mainMap"){
			var p_count=0;
			for(var dot = 0; dot < mapData["totalDots"]; dot++){
				p_count++;
				if(p_count==10)
					break;
				var x = mapData["dots"][dot]["x"];
				var y = mapData["dots"][dot]["y"];
				var type = mapData["dots"][dot]["type"];
				gCanvas.drawImage(crossDark, 0, 0, 1600,1600, x-12, y-1, 23,23);
			}

			p_count=0;
			for(var line = 0; line < mapData["totalLines"]; line++){
				p_count++;
				if(p_count==5||p_count==9||p_count==10)
					continue;
				var x = mapData["lines"][line]["x"];
				var y = mapData["lines"][line]["y"];
				var dir = mapData["lines"][line]["direction"];
				var type = mapData["lines"][line]["type"];
				gCanvas.save();
				if(dir=='right')
					gCanvas.drawImage(path0, 0,0, 82,33, x+10, y-5, 60,28);
				else
					gCanvas.drawImage(path1, 0,0,33,82,x-13,y+20,28,60);
				gCanvas.restore();

			}
			if(mapData["isMoving"]==1)
				movingTick += 0.2;
			///just to make the character face correct direction
			keyDown = mapData["movingDirection"];
			renderPlayer(30,30, mapData["xpos"]-15, mapData["ypos"]-15);
			gCanvas.font = "16px Arial Bold";
			gCanvas.drawImage(rdiamond, 0,0, 665,529, 150, 450, 40, 30);
			gCanvas.fillText(mapData["dots"][mapData["onLevel"]-1]["collectedRed"] + '/' + mapData["dots"][mapData["onLevel"]-1]["totalRed"], 200,470);
			gCanvas.drawImage(bdiamond, 0,0, 665, 529, 350,450, 40, 30);
			gCanvas.fillText(mapData["dots"][mapData["onLevel"]-1]["collectedBlue"] + '/' + mapData["dots"][mapData["onLevel"]-1]["totalBlue"], 400, 470);


		}
	}
}

var animationInterval = setInterval(update, 1000/FPS);