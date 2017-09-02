<?php
require 'connection.php';
session_start();
$_SESSION["username"] = $_POST["username"];
$_SESSION["passkey"] = $_POST["passkey"];
$_SESSION["collegename"] = $_POST["collegename"];
//$_SESSION["collegename"] = $_POST["collegename"];
$query = 'select * from users where username = "'.$_SESSION["username"].'"';
$qry = mysqli_query($conn, $query) or die(mysqli_error($conn));
$result = mysqli_fetch_array($qry);
if($result){
	//$query = 'insert into users (name, password, collegeName) values("'.$_SESSION["username"].'" ,"'.$_SESSION["password"].'" ,"'.$_SESSION["collegename"].'" )';
	//echo $query;
	if($result["passkey"] != $_SESSION["passkey"]){
		unset($_SESSION["username"]);
		header("location: index.php");
	}
	if($result["firstlogin"]==1){
		header("location: game.php");
	}
	else{
		$query = 'update users set firstlogin = "1" where username = "'.$_SESSION["username"].'"';
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
		$query = 'create table assets'.$_SESSION["username"].' as select * from assets';
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
		$query = 'create table data'.$_SESSION["username"].' as select * from data';
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
		$query = 'create table mainmap'.$_SESSION["username"].' as select * from mainmap';
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
		$query = 'create table questions'.$_SESSION["username"].' as select * from questions';
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
		$query = 'create table stages'.$_SESSION["username"].' as select * from stages';
		$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
		$query = 'insert into scoreboard (score, blueDiamond, redDiamond, name, college) values(0,0,0, "'.$_SESSION["username"].'", "'.$_SESSION["collegename"].'")';
		echo $query;
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		//header("location: game.php");
		if($result)
			header("location: game.php");
		else
			echo "failure";
	}
}


?>