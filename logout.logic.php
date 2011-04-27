<?php session_start();?>
<?php include("login.logic.php");

//if($page == "Loyalty Card Registration") {
//	$sessionID = $_SESSION['sid3'];
//} else {
//	$sessionID = $_SESSION['sid1'];
//}

$sessionID = $_SESSION['sid'];

$_logout = new login();

list ($res_StatusNo, $res_StatusDesc) = $_logout->logout($sessionID);
if($res_StatusNo == 0){

	unset($_SESSION['sid']);
	unset($_SESSION['sidtype']);
	unset($_SESSION['userid']);
	unset($_SESSION['user']);

	set_errormsg($res_StatusDesc,1);
	header("Location: index.php");

}

else{
    echo mysql_error();
}
?>
