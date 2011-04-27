<?php session_start(); ?>
<?php
include("login.logic.php");



unset($_SESSION['sid']);
unset($_SESSION['sidtype']);
unset($_SESSION['userid']);
unset($_SESSION['user']);


$_login = new login();
//list($resultID, $sessionID, $statusDec,$hashedPassword) = $_login->validateLogin();

list($res_StatusNo, $res_StatusDesc, $res_SessionID, $res_SessionType, $res_UserID, $res_Username) = $_login->validateLogin();

if ($res_StatusNo == 0){
    $_SESSION['sid']= $res_SessionID;
    $_SESSION['sidtype']= $res_SessionType;
    $_SESSION['userid']= $res_UserID;
	$_SESSION['user'] = $res_Username; //For the Logged In User (upper right hand of the screen)
//	$_SESSION['message'] = $res_StatusDesc;
    header("Location: PlayerProfile.php");
}
else{
	$_SESSION['message'] = $res_StatusDesc;
	$_SESSION['messagecolor'] =
    header("Location: index.php");
}

?>
