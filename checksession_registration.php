<?php
session_start();

include ("connectionstring.php");

function force_logout($sessid)
{
	include("login.logic.php");

	$_logout = new login();

	list ($resultID, $statusDec) = $_logout->logout($sessid);
	if($resultID == 0){
		unset($_SESSION['sid3']);
		unset($_SESSION['user3']);
		$sessionID = "";
		$userloggedin = "";
		return true;
	}
	else { return false; }
}


if((!isset($_SESSION['sid3'])) || ($_SESSION['sid3']=='') || ($_SESSION['sid3']==NULL))
{
	$txtUsername = "galps";
	$txtPassword = "test";

	$hashedPassword = sha1($txtPassword);
	$transDetails = "Player Registration from POS";
	$ipAddress = $_SERVER['REMOTE_ADDR'];

	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

	$query = "Call sp_Login('".$txtUsername."', '".$hashedPassword."', '".$transDetails."', '".$ipAddress."')";
	$sql_result = mysql_query($query,$dbConnection) or die(mysql_error($dbConnection));
	$row = mysql_fetch_array($sql_result);

	$sessionStatusNo = $row["StatusNo"];
	$sessionStatusDesc = $row["StatusDesc"];

mysql_close($dbConnection);
	if ($sessionStatusNo == 0) {
		$_SESSION['sid3']= $row['SessionID'];
		$_SESSION['user3'] = $txtUsername;
		
		$sessionID = $_SESSION['sid3'];
		$userloggedin = $_SESSION['user3'];
	}
	else
	{
		if (force_logout($sessionID))
			header("Location: LPRegistration_SessionError.php?mess=$sessionStatusDesc");
	}

//	mysql_close($dbConnection);
}
else
{
	$sessionID = $_SESSION['sid3'];
	$userloggedin = $_SESSION['user3'];

	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

	$query = "Call sp_CheckSessionID('" . $sessionID . "')";
//	echo $query;
	
	$qrychksession = mysql_query($query,$dbConnection) or die(mysql_error());
	$rowchksession = mysql_fetch_array($qrychksession);
	mysql_close($dbConnection);

	$sessionStatusNo = $rowchksession["StatusNo"];
	$sessionStatusDesc = $rowchksession["StatusDesc"];

	if (($sessionStatusNo == "1") || ($sessionStatusNo == "2"))  //Session Timeout OR Session ID does not exists.
	{
		if (force_logout($sessionID))
			header("Location: LPRegistration_SessionError.php?mess=$sessionStatusDesc");
	}
	else //Session is ACTIVE -- or possible error in updating of session logs. (In which case, CONTINUE)
	{
		$session_dbuser = $rowchksession["Username"];

		if ($session_dbuser != $userloggedin)
		{
//			echo "<script>alert('User logged in Database is NOT the same with the User logged in the Loyalty Program.');</script>";

			if (force_logout($sessionID))
				header("Location: LPRegistration_SessionError.php?mess=User logged in Database is NOT the same with the User logged in the Loyalty Program.");
		}
	}

//	mysql_close($dbConnection);
}
?>