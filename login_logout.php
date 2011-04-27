<?php session_start(); ?>
<?php
include("login_logout.logic.php");


if (empty($_SESSION['sid']))
{
	$_login = new login_logout();

	list($res_StatusNo, $res_StatusDesc, $res_SessionID, $res_SessionType, $res_UserID, $res_Username) = $_login->validateLogin();

	if ($res_StatusNo == 0){
		$_SESSION['sid']= $res_SessionID;
		$_SESSION['sidtype']= $res_SessionType;
		$_SESSION['userid']= $res_UserID;
		$_SESSION['user'] = $res_Username; //For the Logged In User (upper right hand of the screen)

		header("Location: PlayerProfile.php");
	}
	else{
		header("Location: index.php?mess=$res_StatusDesc");
	}
}
else
{
	$_logout = new login_logout();

	list ($res_StatusNo, $res_StatusDesc) = $_logout->logout($_SESSION['sid']);
	if($res_StatusNo == 0){

		unset($_SESSION['sid']);
		unset($_SESSION['sidtype']);
		unset($_SESSION['userid']);
		unset($_SESSION['user']);

		header("Location: index.php?mess=$res_StatusDesc");
	}
	else{
		mysql_error();
	}
}

?>
