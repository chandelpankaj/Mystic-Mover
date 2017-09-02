<?php
///////////////////////////////////////////////////////////////////////////
/////101 blue diamond												///////
/////102 red diamond												///////
/////202 breakable wall 	
/////155 boulder 													///////
/////questions start from 501 										///////
/////601 horizontal gate(closed),602-horizontal gate(open) 			///////
/////603 vertical gate(closed), 604 vertical gate(open) 			///////
/////301 thorn ball, 302 thorn box,......310 thorn 					///////
/////1001 key 1002 lock that opens with that key	
/////504,, 505 lock, question both									///////
/////2001 down top gate(closed)										///////	
/////2002 down top gate(open)										///////
/////5000 hammer
/////1200 key
/////103 coin
/////99 exit
///////////////////////////////////////////////////////////////////////////

require 'connection.php';
session_start();
if(!isset($_SESSION["ttime"]))
	$_SESSION["ttime"] = time();
if(!isset($_SESSION["stop"])){
	$_SESSION["stop"]=0;
}
$keyDown = $_GET["keyDown"];
if(!isset($_SESSION["totalbdiamonds"])){
	getDiamonds();
}
if(!isset($_SESSION["area"])){
	$_SESSION["area"] = "mainMap";
}
if(!isset($_SESSION["blinkTime"])){
	$_SESSION["blinkTime"]=0;
	$_SESSION["blink"]=0;
}
if(!isset($_SESSION["globalCounter"])){
	$_SESSION["globalCounter"]=0;
}
if($_SESSION["globalCounter"] % 10 ==0){
	$t1 = time();
	$td = $t1 - $_SESSION["ttime"];
	if($td >= 36000){
		//updateDatabase();
		updateDatabase();
		getScore();
		//header("location: index.php");
		$_SESSION["stop"]=1;
	}
}
$_SESSION["globalCounter"]++;
$answer = $_GET["answer"];

if(!isset($_SESSION["facingDirection"]))
	$_SESSION["facingDirection"]="down";

function cropMap($startRow, $startCol, $endRow, $endCol){
///we get other varables first

	if(!isset($_SESSION["croppedMap"])){
		$_SESSION["croppedMap"]=array("cols"=>0,"characterX"=>0, "characterY"=>0, "dx"=>$_SESSION["dx"], "dy"=>$_SESSION["dy"], "rows"=>0,"tSize"=>0, "totalLayers"=>0, "playerLayer"=>0, "layers"=>array(), "blueCollected"=>0, "redCollected"=>0,"type" => "map", "answer"=>10, "question"=>"");
	}
	$_SESSION["croppedMap"]["tSize"] = $_SESSION["completeMap"]["tSize"];
	$_SESSION["croppedMap"]["type"] = "map";
	$_SESSION["croppedMap"]["playerLayer"] = $_SESSION["completeMap"]["playerLayer"];
	$_SESSION["croppedMap"]["dx"] = $_SESSION["dx"];
	$_SESSION["croppedMap"]["dy"] = $_SESSION["dy"];
	$_SESSION["croppedMap"]["characterX"] = $_SESSION["character"]->screenX;
	$_SESSION["croppedMap"]["characterY"] = $_SESSION["character"]->screenY;
	$_SESSION["croppedMap"]["cols"] = $endCol - $startCol + 1;
	$_SESSION["croppedMap"]["rows"] = $endRow - $startRow + 1;
	$_SESSION["croppedMap"]["totalLayers"] = $_SESSION["completeMap"]["totalLayers"];
	$_SESSION["croppedMap"]["hammer"] = $_SESSION["hammer"];
	$_SESSION["croppedMap"]["key"] = $_SESSION["key"];
	$_SESSION["croppedMap"]["blink"] = $_SESSION["blink"];
///copy data of layers
	for($layer = 0;$layer < $_SESSION["completeMap"]["totalLayers"];$layer++){
		$_SESSION["croppedMap"]["layers"][$layer]=array();
		for($i=$startRow; $i <= $endRow; $i++){
			$_SESSION["croppedMap"]["layers"][$layer][$i - $startRow]= array();
			for($j=$startCol; $j <= $endCol; $j++){
				$_SESSION["croppedMap"]["layers"][$layer][$i-$startRow][$j-$startCol] = $_SESSION["completeMap"]["layers"][$layer][$i][$j];
			}
		}
	}
}

function getScore(){
	global $conn;
	$sql = 'select name, college, score , redDiamond from scoreboard order by score desc, time asc';
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	$rows = $result->num_rows;
	$_SESSION["scoreboard"]["total"]=$rows;
	$count=0;
	if($rows > 0){
		while($row = $result->fetch_assoc()){
			$_SESSION["scoreboard"][$count] = $row;
			$count++;
		}
	}
	return;
}

function getMap($level){	
	global $conn;
	$sql = 'select enabled from assets'.$_SESSION["username"].' where asset = "hammer"';
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$_SESSION["hammer"]=$row["enabled"];
	}

	/*$sql = 'select enabled from assets'.$_SESSION["username"].' where asset = "key"';
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$_SESSION["key"]=$row["enabled"];
	}
	*/
	$sql = 'select * from stages'.$_SESSION["username"].' where level = '.$level."";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$_SESSION["completeMap"] = json_decode($row["map"],true);
			$_SESSION["currentRow"] = $row["currentRow"];
			$_SESSION["currentCol"] = $row["currentCol"];
			$_SESSION["completeMap"]["blueCollected"]= $row["blueCollected"];
			$_SESSION["completeMap"]["redCollected"] = $row["redCollected"];
			$_SESSION["completeMap"]["totalRed"]= $row["totalRed"];
			$_SESSION["completeMap"]["totalBlue"] = $row["totalBlue"];
			$_SESSION["dx"] = 0;
			$_SESSION["dy"] = 0;
			$_SESSION["currentQuestion"]=0;
			$_SESSION["key"] = $row["pkey"];
			return;
			break;
		}
	}
}

function updateDatabase(){
	global $conn;
	$sql = 'update stages'.$_SESSION["username"].' set currentRow = '.$_SESSION["currentRow"].', currentCol ='.$_SESSION["currentCol"].', pkey = '.$_SESSION["key"].', blueCollected = '.$_SESSION["completeMap"]["blueCollected"].', redCollected = '.$_SESSION["completeMap"]["redCollected"].', map = \''.json_encode($_SESSION["completeMap"]).'\' where level = '.$_SESSION["m_level"].'';
	//$sql = 'update stages set map = \''.json_encode($_SESSION["completeMap"]).'\' where level = 1';
		$qry=mysqli_query($conn, $sql) or die(mysqli_error($conn));
	$sql = 'update scoreboard set score = '.$_SESSION["score"].', time = NOW() where name = "'.$_SESSION["username"].'"';
	$qry = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	$sql = 'update data'.$_SESSION["username"].' set blueDiamonds = '.$_SESSION["totalbdiamonds"].', redDiamonds = '.$_SESSION["totalrdiamonds"].'';
	$qry = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	$sql = 'update assets'.$_SESSION["username"].' set enabled = '.$_SESSION["hammer"].' where asset = "hammer"';
	$qry = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	$sql = 'update mainmap'.$_SESSION["username"].' set map = \''.json_encode($_SESSION["mainMap"]).'\'';
	$qry = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	/*$sql = 'update assets'.$_SESSION["username"].' set enabled = '.$_SESSION["key"].' where asset = "key"';
	$qry = mysqli_query($conn, $sql) or die(mysqli_error($conn));*/


}

function getMainMap(){
	global $conn;
	$sql = 'select * from mainmap'.$_SESSION["username"].'';
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$_SESSION["mainMap"] = json_decode($row["map"], true);
			$_SESSION["mainMap"]["xpos"] = $row["xpos"];
			$_SESSION["mainMap"]["ypos"] = $row["ypos"];
			$_SESSION["mainMap"]["onLevel"] = $row["onLevel"];
		}
	}

	$_SESSION["mainMap"]["destLevel"]  = $_SESSION["mainMap"]["currentLevel"];
	$_SESSION["mainMap"]["isMoving"] = 0;
	$_SESSION["mainMap"]["blink"]=0;

	$sql = 'select score from scoreboard where name = "'.$_SESSION["username"].'"';
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$_SESSION["score"] = $row["score"];
	}
}

function getDiamonds(){
	global $conn;
	$sql = 'select * from data'.$_SESSION["username"].'';
	$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$_SESSION["totalbdiamonds"] = $row["blueDiamonds"];
		$_SESSION["totalrdiamonds"] = $row["redDiamonds"];
	}
}
function getQuestion($questionNo){
	global $conn;
	$sql = 'select question from questions'.$_SESSION["username"].' where featureNo = '.$questionNo.'';
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$_SESSION["croppedMap"]["question"]=$row["question"];
	}
	else
		echo "failed to get question";
}
/*function getScores(){
	global $conn;
	$sql = 'select * from scoreboard'
}*/
function checkAnswer($question){
	global $conn, $answer;
	$sql = 'select * from questions'.$_SESSION["username"].' where featureNo = '.$question.'';
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			if($answer == $row["answer"]){
				removeQuestion($_SESSION["questionRow"],$_SESSION["questionCol"]);
				return 1;
			}
			elseif($answer=='none'){
				return 10;
			}
			else{
				return 0;
			}
			
		}
	}
}
function removeQuestion($row, $col){
	removeObstacle($row, $col, $_SESSION["currentQuestion"]);
	$_SESSION["currentQuestion"]=0;
}
class Camera{
	var $x;
	var $y;
	var $width;
	var $height;
	var $maxX;
	var $maxY;
	var $startRow;
	var $endRow;
	var $startCol;
	var $endCol;

	function __construct(){
		$this->x = 0;
		$this->y = 0;
		$this->width = 512;
		$this->height = 512;
		$this->maxX = $_SESSION["completeMap"]["cols"]*($_SESSION["completeMap"]["tSize"]-1 )- 512;
		$this->maxY = $_SESSION["completeMap"]["rows"]*($_SESSION["completeMap"]["tSize"] - 1) - 512;
		$this->startRow = 0;
		$this->endRow = 0;
		$this->startCol = 0;
		$this->endCol = 0;
	}

	function update(){
		$_SESSION["character"]->screenX = $this->width/2;
		$_SESSION["character"]->screenY = $this->height/2;
		$this->x = $_SESSION["character"]->x - $this->width/2;
		$this->y = $_SESSION["character"]->y - $this->height/2;

		$this->x = max(0, min($this->x, $this->maxX));
		$this->y = max(0, min($this->y, $this->maxY));
		$this->startRow = floor($this->y/64);
		$this->endRow = $this->startRow + ($this->height/64);
		$this->startCol = floor($this->x/64);
		$this->endCol = $this->startCol + ($this->width/64);
		$_SESSION["dx"] = -$this->x + $this->startCol * 64;
		$_SESSION["dy"] = -$this->y + $this->startRow*64;
		$_SESSION["character"]->screenX = $_SESSION["character"]->x - $this->x;
		$_SESSION["character"]->screenY = $_SESSION["character"]->y - $this->y;
		//echo $this->startCol." is start col and $this->endCol is end col <br>";
	}
}
function getRow($y){
	return floor($y/$_SESSION["completeMap"]["tSize"]);
}
function getCol($x){
	return floor($x/$_SESSION["completeMap"]["tSize"]);
}

function removeObstacle($row, $col, $obst){
	$replaceWith=0;
	for($layer = 0; $layer < $_SESSION["completeMap"]["totalLayers"]; $layer++){
		$tile = $_SESSION["completeMap"]["layers"][$layer][$row][$col];
		if($tile == $obst || $tile == 151 || $tile == 155){
			if($tile == 151)
				$replaceWith = 152;
			elseif($tile == 153)
				$replaceWith = 154;
			elseif($tile == 155)
				$replaceWith = 0;
			$_SESSION["completeMap"]["layers"][$layer][$row][$col] = $replaceWith;
			$_SESSION["completeMap"]["tiles"][$row][$col] = 0;
		}
	}
}

function getFacingRow(){
	$y = $_SESSION["character"]->y - $_SESSION["character"]->height/2;
	$row = -1;
	if($y%64 < 35){
		$row = floor($y/64);
		switch ($_SESSION["facingDirection"]) {
			case 'up':
				$row--;
				break;
			case 'down':
				$row++;
				break;
		}
	}
	return $row;
}
function getFacingCol(){
	$x = $_SESSION["character"]->x - $_SESSION["character"]->width/2;
	$col = -1;
	if($x%64 < 35){
		$col = floor($x/64);
		switch ($_SESSION["facingDirection"]) {
			case 'right':
				$col++;
				break;
			case 'left':
				$col--;
				break;
		}
	}
	return $col;
}
function openGate(){
	$row = getFacingRow();
	$col = getFacingCol();
	if($row == -1 || $col == -1)
		return;
	for($layer = 0; $layer < $_SESSION["completeMap"]["totalLayers"]; $layer++){
		$tile = $_SESSION["completeMap"]["layers"][$layer][$row][$col];
		if($tile == 2001){
			$_SESSION["completeMap"]["layers"][$layer][$row][$col]=2002;
			$_SESSION["key"]=0;
			updateDatabase();
		}
		elseif($tile == 1200){
			$_SESSION["completeMap"]["layers"][$layer][$row][$col]=0;
			$_SESSION["key"]=0;
			updateDatabase();
		}
		elseif($tile == 1002){
			$_SESSION["completeMap"]["layers"][$layer][$row][$col]=0;
			$_SESSION["completeMap"]["tiles"][$row][$col]=0;
			$_SESSION["key"]=0;
			updateDatabase();
		}
	}
}
function breakwall(){
	$x = $_SESSION["character"]->x - $_SESSION["character"]->width/2;
	$y = $_SESSION["character"]->y - $_SESSION["character"]->height/2;
	if($x%64 < 35 && $y%64 < 35){
		$col = floor($x/64);
		$row = floor($y/64);
		switch ($_SESSION["facingDirection"]) {
			case 'up':
				removeObstacle($row - 1, $col, 202);
				break;
			case 'right':
				removeObstacle($row, $col + 1,202);
				break;
			case 'down':
				removeObstacle($row + 1, $col,202);
				break;
			case 'left':
				removeObstacle($row, $col - 1,202);
				break;
		}
	}
}

class character{
	var $x;
	var $width;
	var $height;
	var $screenX;
	var $y;
	var $screenY;
	var $speed;
	function __construct(){
		$this->x = $_SESSION["currentCol"] * $_SESSION["completeMap"]["tSize"] + 34/2; //plus width as x,y points to center of character
		$this->width=34;
		$this->height = 34;
		$this->screenX = 0;
		$this->y = $_SESSION["currentRow"]*$_SESSION["completeMap"]["tSize"] + 34/2; //plus height
		$this->screenY = 0;
		$this->speed = 1;
	}
	function move($delta, $dirx, $diry){
		$nextX = $this->x + $dirx * $this->speed * $delta;
		$nextY = $this->y + $diry * $this->speed * $delta;
			$this->x = $nextX;
			$this->y = $nextY;
		$this->checkCollision($dirx, $diry);
		$_SESSION["currentRow"] = getRow($this->y);
		$_SESSION["currentCol"] = getCol($this->x);
	}

	function checkCollision($dirx, $diry){
		$left = $this->x - $this->width/2;
		$right = $this->x + $this->width/2 -1;
		$top = $this->y - $this->height/2;
		$bottom = $this->y + $this->height/2 -1;
		$tSize = $_SESSION["completeMap"]["tSize"];
		//first check for any collectible
		checkFeature($left, $top, $dirx, $diry);
		checkFeature($right, $top, $dirx, $diry);
		checkFeature($left, $bottom, $dirx, $diry);
		checkFeature($right, $bottom, $dirx, $diry);
		$left = $this->x - $this->width/2;
		$right = $this->x + $this->width/2 -1;
		$top = $this->y - $this->height/2;
		$bottom = $this->y + $this->height/2 -1;
		if(!(isSolid($left, $top) || isSolid($right, $top) || isSolid($left, $bottom) || isSolid($right, $bottom)))
			return;
		if($diry > 0) {
	        $row = floor($bottom/$tSize);
	        $this->y = -$this->height/2 + $row * $tSize-2;
	    }
	    elseif ($diry < 0) {
	        $row = floor($top/$tSize);
	        $this->y = $this->height/2 + ($row + 1)* $tSize+2;
	    }
	    elseif ($dirx > 0) {
	        $col = floor($right/$tSize);
	        $this->x = -$this->width/2 + $col * $tSize-2;
	    }
	    elseif ($dirx < 0) {
	        $col = floor($left/$tSize);
	        $this->x = $this->width/2 + ($col + 1) * $tSize+2;
	    }
	    return;
	}
}


function isSolid($x, $y){
	$row = getRow($y);
	$col = getCol($x);
	if($_SESSION["completeMap"]["tiles"][$row][$col]==5){
		return false;
	}
	if($_SESSION["completeMap"]["tiles"][$row][$col]!=0){
		return true;
	}
	return false;
}
function pushBack($dirx, $diry){

	if($diry > 0){
		$_SESSION["character"]->y -= 10;
	}
	elseif($diry < 0){
		$_SESSION["character"]->y += 10;
	}
	elseif($dirx > 0){
		$_SESSION["character"]->x -= 10;
	}
	elseif($dirx < 0){
		$_SESSION["character"]->x += 10;
	}
}
function checkFeature($x, $y, $dirx, $diry){
	$row = floor($y/$_SESSION["completeMap"]["tSize"]);
	$col = floor($x/$_SESSION["completeMap"]["tSize"]);
	for($layer = 0; $layer < $_SESSION["completeMap"]["totalLayers"]; $layer++){
		$feature =$_SESSION["completeMap"]["layers"][$layer][$row][$col];
		if($feature>100 && $feature <=200){
			switch ($feature) {
				case 101:
					$_SESSION["completeMap"]["layers"][$layer][$row][$col]=0;
					$_SESSION["completeMap"]["blueCollected"] += 1;
					$_SESSION["totalbdiamonds"] += 1;
					$_SESSION["score"] += 50;
					$_SESSION["mainMap"]["dots"][$_SESSION["m_level"]-1]["collectedBlue"]+=1;
					updateDatabase();
					break;
				case 102:
					$_SESSION["completeMap"]["layers"][$layer][$row][$col]=0;
					$_SESSION["completeMap"]["redCollected"] += 1;
					$_SESSION["totalrdiamonds"] += 1;
					$_SESSION["score"] += 1000;
					$_SESSION["mainMap"]["dots"][$_SESSION["m_level"]-1]["collectedRed"]+=1;
					updateDatabase();
					break;
				case 103:
					$_SESSION["completeMap"]["layers"][$layer][$row][$col]=0;
					$_SESSION["score"] += 10;
					updateDatabase();
				default:
					break;
			}
		}
		elseif($feature > 500 && $feature <=600){
			$_SESSION["currentQuestion"]=$feature;
			getQuestion($feature);
			$_SESSION["questionRow"] = $row;
			$_SESSION["questionCol"] = $col;
		}
		elseif($feature == 1001){//key
			if($_SESSION["key"]==0){
				$_SESSION["key"] = 1;
				removeObstacle($row, $col, 1001);
				updateDatabase();
			}
			//updateDatabase();
		}
		elseif($feature >= 301 && $feature <= 310){
			$_SESSION["score"] -= 200;
			if($_SESSION["score"] < 0)
				$_SESSION["score"] = 0;
			pushBack($dirx, $diry);
			$_SESSION["blink"]=1;
			updateDatabase();
		}
		elseif($feature == 5000){
			$_SESSION["hammer"] = 1;
			updateDatabase();
		}
		elseif($feature == 99){
			$_SESSION["area"]="mainMap";
			updateDatabase();
		}
	}
}

if($_SESSION["area"] == "mainMap"){
	//for the first page
	if(!isset($_SESSION["mainMap"]))
		getMainMap();
	if(isset($_SESSION["completeMap"]))
		unset($_SESSION["completeMap"]);
	if(isset($_SESSION["character"]))
		unset($_SESSION["character"]);
	if(isset($_SESSION["camera"]))
		unset($_SESSION["camera"]);
	$dirx = $diry = 0;
	switch($keyDown){
	case 'left':
		$dirx = -1; break;
	case 'right':
		$dirx = 1; break;
	case 'up':
		$diry = -1; break;
	case 'down':
		$diry = 1; break;
	default:
		$dirx = $diry = 0; break;
	}
	$cl = $_SESSION["mainMap"]["onLevel"];

	if($dirx > 0){
		if(($cl == 1||$cl == 2||$cl == 3||$cl == 4||$cl == 5||$cl == 6||$cl == 11)&&($_SESSION["mainMap"]["isMoving"] == 0)){
			$dest=$_SESSION["mainMap"]["onLevel"];
			switch ($cl) {
				case 1: if($_SESSION["totalrdiamonds"] > 1) $dest = 2; break;
				case 2: if($_SESSION["totalrdiamonds"] > 1) $dest = 9; break;
				case 3: if($_SESSION["totalrdiamonds"] > 100) $dest = 10; break;
				case 4: if($_SESSION["totalrdiamonds"] > 1) $dest = 3; break;
				case 5: if($_SESSION["totalrdiamonds"] > 1) $dest = 6; break;
				case 6: if($_SESSION["totalrdiamonds"] > 100) $dest = 12; break;
				case 11: if($_SESSION["totalrdiamonds"]> 1) $dest = 5; break;
			}
			if($dest != $_SESSION["mainMap"]["onLevel"]){
				$_SESSION["mainMap"]["movingDirection"] = "right";
				$_SESSION["mainMap"]["isMoving"] = 1;
				$_SESSION["mainMap"]["destLevel"]=$dest;
			}
		}
	}
	elseif($dirx < 0){
		if(($cl == 2||$cl == 3||$cl == 5||$cl == 6||$cl == 9||$cl == 10||$cl == 12)&&($_SESSION["mainMap"]["isMoving"] == 0)){
			$dest=$_SESSION["mainMap"]["onLevel"];
			switch ($cl) {
				case 2: if($_SESSION["totalrdiamonds"] > 0) $dest = 1; break;
				case 3: if($_SESSION["totalrdiamonds"] > 1) $dest = 4; break;
				case 5: if($_SESSION["totalrdiamonds"] > 100) $dest = 11; break;
				case 6: if($_SESSION["totalrdiamonds"] > 1) $dest = 5; break;
				case 9: if($_SESSION["totalrdiamonds"] > 1) $dest = 2; break;
				case 10: if($_SESSION["totalrdiamonds"] > 1) $dest = 3; break;
				case 12: if($_SESSION["totalrdiamonds"] > 1) $dest = 6; break;
			}
			if($dest != $_SESSION["mainMap"]["onLevel"]){

				$_SESSION["mainMap"]["movingDirection"] = "left";
				$_SESSION["mainMap"]["isMoving"] = 1;
				$_SESSION["mainMap"]["destLevel"]=$dest;
			}
		}
	}
	elseif($diry > 0){
		if(($cl == 2||$cl == 3||$cl == 5||$cl == 7)&&($_SESSION["mainMap"]["isMoving"] == 0)){
			$dest=$_SESSION["mainMap"]["onLevel"];
			switch ($cl) {
				case 2: if($_SESSION["totalrdiamonds"] > 1) $dest = 8; break;
				case 3: if($_SESSION["totalrdiamonds"] > 1) $dest = 2; break;
				case 5: if($_SESSION["totalrdiamonds"] > 1) $dest = 4; break;
				case 7: if($_SESSION["totalrdiamonds"] > 1) $dest = 6; break;
			}
			if($dest != $_SESSION["mainMap"]["onLevel"]){
				$_SESSION["mainMap"]["movingDirection"] = "down";
				$_SESSION["mainMap"]["isMoving"] = 1;
				$_SESSION["mainMap"]["destLevel"]=$dest;
			}
		}
	}
	elseif($diry < 0){
		if(($cl == 2||$cl == 4||$cl == 6||$cl == 8)&&($_SESSION["mainMap"]["isMoving"] == 0)){
			$dest=$_SESSION["mainMap"]["onLevel"];
			switch ($cl) {
				case 2: if($_SESSION["totalrdiamonds"] > 1) $dest = 3; break;
				case 4: if($_SESSION["totalrdiamonds"] > 1) $dest = 5; break;
				case 6: if($_SESSION["totalrdiamonds"] > 1) $dest = 7; break;
				case 8: if($_SESSION["totalrdiamonds"] > 1) $dest = 2; break;
			}
			if($dest != $_SESSION["mainMap"]["onLevel"]){
				$_SESSION["mainMap"]["movingDirection"] = "up";
				$_SESSION["mainMap"]["isMoving"] = 1;
				
				$_SESSION["mainMap"]["destLevel"]=$dest;
			}

		}
	}

	if($_SESSION["mainMap"]["isMoving"]==1){
		switch ($_SESSION["mainMap"]["movingDirection"]) {
			case "left":
				$_SESSION["mainMap"]["xpos"]-=2;
				break;
			case "right":
				$_SESSION["mainMap"]["xpos"]+=2;
				break;
			case "up":
				$_SESSION["mainMap"]["ypos"]-=2;
				break;
			case "down":
				$_SESSION["mainMap"]["ypos"]+=2;
				break;
		}
	///there can be another method
		//store destination x,y in json and then compare
		if($_SESSION["mainMap"]["movingDirection"] == "left" || $_SESSION["mainMap"]["movingDirection"] == "right"){
			if(($_SESSION["mainMap"]["xpos"]-120)%80==0){
				$_SESSION["mainMap"]["isMoving"] = 0;
				$_SESSION["mainMap"]["movingDirection"] = "none";
				$_SESSION["mainMap"]["onLevel"] = $_SESSION["mainMap"]["destLevel"];
			}
		}
		else if($_SESSION["mainMap"]["movingDirection"] == "up" || $_SESSION["mainMap"]["movingDirection"] == "down"){
			if($_SESSION["mainMap"]["ypos"]%80==0){

				$_SESSION["mainMap"]["isMoving"] = 0;
				$_SESSION["mainMap"]["movingDirection"] = "none";
				$_SESSION["mainMap"]["onLevel"] = $_SESSION["mainMap"]["destLevel"];
			}
		}
	}
	if($keyDown == "enter"){
		if($_SESSION["mainMap"]["onLevel"] == $_SESSION["mainMap"]["destLevel"]){
			$_SESSION["m_level"] = $_SESSION["mainMap"]["onLevel"];
			$_SESSION["area"] = "map";
		}
	}
	if(!isset($_SESSION["scoreboard"])){
		getScore();
	}
	else if($_SESSION["globalCounter"]%150==0){
		getScore();
	}
	$_SESSION["mainMap"]["scoreboard"]= $_SESSION["scoreboard"];
	$_SESSION["mainMap"]["score"] = $_SESSION["score"];
	$_SESSION["mainMap"]["totalbdiamonds"] = $_SESSION["totalbdiamonds"];
	$_SESSION["mainMap"]["totalrdiamonds"] = $_SESSION["totalrdiamonds"];
	$_SESSION["mainMap"]["stop"] = $_SESSION["stop"];
	echo json_encode($_SESSION["mainMap"]);

}
else if($_SESSION["area"] == "map"){
	if(!isset($_SESSION["completeMap"]))
		getMap($_SESSION["m_level"]);
	
	if(!isset($_SESSION["camera"])){
		$_SESSION["camera"] = new Camera();
	}
	if(!isset($_SESSION["character"])){
		$_SESSION["character"] = new character();
	}
	if(!isset($_SESSION["facingDirection"]))
		$_SESSION["facingDirection"] = "down";

	$dirx = $diry = 0;
	switch($keyDown){
	case 'left':
		$dirx = -1; $_SESSION["facingDirection"] = "left"; break;
	case 'right':
		$dirx = 1; $_SESSION["facingDirection"] = "right"; break;
	case 'up':
		$diry = -1; $_SESSION["facingDirection"] = "up"; break;
	case 'down':
		$diry = 1; $_SESSION["facingDirection"] = "down"; break;
	case 'esc':
		$_SESSION["area"] = "mainMap"; break;
	default:
		$dirx = $diry = 0; break;

	}
	$_SESSION["character"]->move(5, $dirx, $diry);

	if($keyDown == "space"){
		if($_SESSION["hammer"] == 1){
			breakWall();
		}
		if($_SESSION["key"] == 1 ){
			openGate();
		}
	}
	if($_SESSION["blink"]==1){
		$_SESSION["blinkTime"]++;
		if($_SESSION["blinkTime"] > 30){
			$_SESSION["blink"]=0;
			$_SESSION["blinkTime"]=0;
		}
	}
	if($answer == "hide"){
		$_SESSION["currentQuestion"] = 0;
	}
	if($_SESSION["currentQuestion"]!=0){
		$_SESSION["croppedMap"]["showQuestion"]=1;
		$_SESSION["croppedMap"]["answer"] = checkAnswer($_SESSION["currentQuestion"]);
	}
	else{
		$_SESSION["croppedMap"]["showQuestion"]=0;
	}
	$_SESSION["camera"]->update();
	cropMap($_SESSION["camera"]->startRow, $_SESSION["camera"]->startCol, $_SESSION["camera"]->endRow, $_SESSION["camera"]->endCol);


	if(!isset($_SESSION["scoreboard"])){
		getScore();
	}
	else if($_SESSION["globalCounter"]%150==0){
		getScore();
	}
	$_SESSION["croppedMap"]["scoreboard"]= $_SESSION["scoreboard"];
	$_SESSION["croppedMap"]["score"] = $_SESSION["score"];
	$_SESSION["croppedMap"]["totalbdiamonds"] = $_SESSION["totalbdiamonds"];
	$_SESSION["croppedMap"]["totalrdiamonds"] = $_SESSION["totalrdiamonds"];
	$_SESSION["croppedMap"]["onLevel"] = $_SESSION["m_level"];
	$_SESSION["croppedMap"]["stop"] = $_SESSION["stop"];
	echo json_encode($_SESSION["croppedMap"]);

}



?>