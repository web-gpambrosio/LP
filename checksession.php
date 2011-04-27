<?php
session_start();

include ("connectionstring.php");


function force_logout($sessid)
{

	include("login_logout.logic.php");

	if (!empty($sessid))
	{
		$_logout = new login_logout();

		list ($res_StatusNo, $res_StatusDesc) = $_logout->logout($sessid);

		if ($res_StatusNo == 0){
			unset($_SESSION['sid']);
			unset($_SESSION['sidtype']);
			unset($_SESSION['userid']);
			unset($_SESSION['user']);
			$sessionID = "";
			$sid_type = "";
			$sid_userid = "";
			$sid_user = "";
			return true; }
		else { return false; }
	}
}


if (isset($_SESSION['sid']))
{
	$sessionID = $_SESSION['sid'];
	$sid_type = $_SESSION['sidtype'];
	$sid_userid = $_SESSION['userid'];
	$sid_user = $_SESSION['user'];

	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

	$query = "Call sp_CheckSessionID('" . $sessionID . "', '" . $sid_type . "')";

	$qrychksession = mysql_query($query,$dbConnection) or die(mysql_error());
	$rowchksession = mysql_fetch_array($qrychksession);

	$sessionStatusNo = $rowchksession["StatusNo"];
	$sessionStatusDesc = $rowchksession["StatusDesc"];
//	$sessionType = $rowchksession["SessionType"];
	$session_dbuser = $rowchksession["Username"];

	mysql_close($dbConnection);


		if ($sessionStatusNo == "0")
		{
			
			if ($session_dbuser != $sid_user)
			{
				force_logout($sessionID);
				header("Location: index.php?mess=User logged in the Database is NOT the same with the User logged in the Loyalty Program");
			}
			else
			{
				//If direct access to index.php and User Session is still Active, then Redirect to Points Inquiry page
				if ($page == "Login") {
					header("Location: PlayerProfile.php");
				}
			}
		}
		else
//		if (($sessionStatusNo == "1") || ($sessionStatusNo == "2"))  //Session Timeout OR Session ID does not exists.
		{
			force_logout($sessionID);
			header("Location: index.php?mess=$sessionStatusDesc");
		}

}
else
{
	if ($page != "Login")
		header("Location: index.php?mess=No active session. Please login.");
}

//if ($page != "Login") {
//	if (empty($_SESSION["sid"]))
//		header("Location: index.php");
//}

?>


