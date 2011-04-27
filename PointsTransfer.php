<?php $page = "Points Transfer"; ?>
<?php $formname = "frmPointsTransfer"; ?>
<?php include("checksession.php"); ?>


<?php if ($sid_type == "A") { ?>

<?php
//include ('TransferPoints.cs.php');
//include ('TransferPoints.logic.php');

$checked = "checked='checked'";
$disabled = "disabled='disabled'";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];

if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
else
	$searchby = "1";

if (isset($_POST["transfermode"]))
	$transfermode = $_POST["transfermode"];
else
	$transfermode = 1;

$tmpchk = "chkmode" . $transfermode;
$$tmpchk = $checked;



if (isset($_POST["oldcardno"]))
	$oldcardno = $_POST["oldcardno"];

if (isset($_POST["oldcardpoints"]))
	$oldcardpoints = $_POST["oldcardpoints"];

if (isset($_POST["username"]))
	$username = $_POST["username"];

if (isset($_POST["newcardno"]))
	$newcardno = $_POST["newcardno"];

if (isset($_POST["newcard_username"]))
	$newcard_username = $_POST["newcard_username"];

if (isset($_POST["newcard_points"]))
	$newcard_points = $_POST["newcard_points"];


switch ($actiontxt)
{
	case "transferpoints" :

		if ($transfermode == 1) // BGI Transfer
			$type = "BGI";
		else
			$type = "PEGS";

        $dbConnection = mysql_connect($dbHost,$dbUser,$dbPass,false,65536) or die("Cannot connect to the host");
        $dbSelect = mysql_select_db($dbName,$dbConnection) or die("Cannot connect to the database");
        $getRecord = "CALL sp_AdminTransferPoints('$sessionID','$type','$oldcardno','$newcardno')";
		$result = mysql_query($getRecord,$dbConnection) or die(mysql_error());
        $query_result = mysql_fetch_array($result);

//		$statno = $query_result['StatusNo'];
//        $statdesc = $query_result['StatusDesc'];

		$message = $query_result['StatusDesc'];
		$statno = $query_result['StatusNo'];

		mysql_close($dbConnection);

		if ($statno > 0)  // Error
		{
			$errorcolor = "error";

			if (($statno == "1") || ($statno == "2")) //Session Timeout and Session ID not found
			{
				force_logout($sessionID);
				header("Location: index.php?mess=$message");
			}
		}
		else // Successful
		{
			$errorcolor = "success";

			$username = "";

			$oldcardno = "";
			$oldcardpoints = "";

			$newcardno = "";
			$newcard_username = "";
			$newcard_points = "";
		}

	break;
	case "checknewpoints" :  //$newcard_username , $newcard_points


		if ($oldcardno != $newcardno) {
			$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
			$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

			$query = "Call sp_ViewMember('2','" . $newcardno . "');";
			$qrygetnewcardpoints = mysql_query($query,$dbConnection) or die(mysql_error());
			$rowgetnewcardpoints = mysql_fetch_array($qrygetnewcardpoints);

			if ($rowgetnewcardpoints["StatusNo"] == 0)
			{
				$newcard_username = $rowgetnewcardpoints['UserName'];
				$newcard_points = $rowgetnewcardpoints['vCurPoints'];
			}
			else
			{
				$message = $rowgetnewcardpoints["StatusDesc"];
				$errorcolor = "error";

				$newcardno = "";
				$newcard_username = "";
				$newcard_points = "";
			}

			mysql_close($dbConnection);
		}
		else
		{
			$message = "Invalid NEW Card Number. (Duplicate) ";
			$errorcolor = "error";

			$newcardno = "";
		}


	break;
	case "checkoldpoints" :

		if ($transfermode == 1)  // From BGI Card
		{ 
			$oldcardno = strtoupper($oldcardno);
			$dbConnection = mysql_connect($dbHost,$dbUser,$dbPass,false,65536) or die("Cannot connect to the host");
			$dbSelect = mysql_select_db($dbName,$dbConnection) or die("Cannot connect to the database");
			$query = "SELECT Points,TStatus FROM tbl_BGIPoints where CardNumber='$oldcardno'";
			$qrygetbgipoints = mysql_query($query) or die(mysql_error());
			$rowgetbgipoints = mysql_fetch_array($qrygetbgipoints);

			if (mysql_num_rows($qrygetbgipoints) > 0)
			{
				$xpoints = $rowgetbgipoints["Points"];
				$xstatus = $rowgetbgipoints["TStatus"];

				if (empty($xstatus))
				{
					$oldcardpoints = $xpoints;
				}
				else
				{
					$message = "Points for BGI Card No. " . $oldcardno . ", has been transferred already!";
					$errorcolor = "error";
					
					$oldcardno = "";
					$oldcardpoints = "";
				}
			}
			else
			{
				$message = "BGI Card No. \"" . $oldcardno . "\" not found.";
				$errorcolor = "error";

				$oldcardno = "";
			}

			mysql_close($dbConnection);
		}
		else  // From Lost/Damaged/Stolen Card
		{ 

			$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
			$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

			$query = "Call sp_ViewMember('3','" . $username . "');";
			$qrygetlostcardpoints = mysql_query($query,$dbConnection) or die(mysql_error());
			$rowgetlostcardpoints = mysql_fetch_array($qrygetlostcardpoints);

			if ($rowgetlostcardpoints["StatusNo"] == 0)
			{
				$oldcardno = $rowgetlostcardpoints['CardNumber'];
				$oldcardpoints = $rowgetlostcardpoints['vCurPoints'];

			}
			else
			{
				$message = $rowgetlostcardpoints["StatusDesc"];
				$errorcolor = "error";

				$username = "";
				$oldcardno = "";
				$oldcardpoints = "";
			}

			mysql_close($dbConnection);
		}


	break;
	case "reset":

		$username = "";

		$oldcardno = "";
		$oldcardpoints = "";

		$newcardno = "";
		$newcard_username = "";
		$newcard_points = "";

	break;
}

//echo "New Card Points = " . $newcard_points;
if ((!empty($oldcardpoints)) && (!empty($newcardno)))
{
	$totalpoints = $oldcardpoints + $newcard_points;
	$disabled_transferbtn = "";
}
else
{
	$totalpoints = "";
	$disabled_transferbtn = $disabled;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>PeGS Loyalty Program - <?php echo $page; ?></title>
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
		<link href="css/transferpoints.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="js/pegsloyalty.js"></script>
		<script type="text/javascript" language="javascript">

			function checktransfer()
			{
				var rem = '';

				with (document.frmPointsTransfer)
				{
					if(oldcardno.value=='' || oldcardno.value==null)
					{
						if(rem=='')
							{rem = 'Card Number (BGI Card or Lost Card)';}
						else
							{rem = rem + ',Card Number (BGI Card or Lost Card)';}
					}
					if(newcardno.value=='' || newcardno.value==null)
					{
						if(rem=='')
							{rem = 'New Card Number (Loyalty Card)';}
						else
							{rem = rem + ',New Card Number (Loyalty Card)';}
					}

					if (rem == '')
					{
						actiontxt.value='transferpoints';
						submit();
					}
					else
					{
						alert('Required Field(s): ' + rem);
					}
				}
			}

		</script>
    </head>
    <body onload="<?php if (empty($playerid)) { echo "document.$formname.searchkey.select();"; } ?>">

		<form name="<?php echo $formname; ?>" id="<?php echo $formname; ?>" method="POST">

			<?php include("header.php"); ?>
			<?php include("menu.php"); ?>
			<br />
			<?php include("message.php"); ?>


			<div class="maincontent">
				<br />
				<center>
					<table width="700" cellpadding="5" cellspacing="0" class="infotable">
						<tr>
							<td colspan="2" align="center" class="sectiontitle">
								<input type="radio" name="transfermode" id="transfermode1" <?php echo $chkmode1; ?> value="1" onclick="actiontxt.value='reset';submit();" /><label for="transfermode1">From BGI Card</label>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="transfermode" id="transfermode2" <?php echo $chkmode2; ?> value="2" onclick="actiontxt.value='reset';submit();" /><label for="transfermode2">From Lost/Damaged/Stolen Card</label>
							</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>

						<!-- Source (From BGI Card OR Lost/Damaged/Stolen Card)  -->

						<?php if ($transfermode == 1) { // BGI Card Transfer  ?>

						<tr>
							<td width="30%" class="infotabledetailtitlealternate">BGI Card No.</td>
							<td width="70%">
								<input type="text" name="oldcardno" size="20"  maxlength="20" onblur="" value="<?php echo $oldcardno; ?>" />
								<input type="button" name="btncheck2" value="Check Points" onclick="if(oldcardno.value != ''){actiontxt.value='checkoldpoints';submit();} else {alert('Invalid Card No.');}" />
							</td>
						</tr>
						<tr>
							<td class="infotabledetailtitle">Current BGI Points</td>
							<td class="infotabledetailtitle"><?php echo $oldcardpoints; ?></td>
						</tr>

						<?php } else { // Lost/Damaged/Stolen Card Transfer ?>

						<tr>
							<td width="30%" class="infotabledetailtitlealternate">Username</td>
							<td width="70%">
								<input type="text" name="username" size="20"  maxlength="20" value="<?php echo $username; ?>" />
								<input type="button" name="btncheck1" value="Find Card" onclick="if(username.value != '') {actiontxt.value='checkoldpoints';submit();} else {alert('Invalid Username.');}" />
							</td>
						</tr>
						<tr>
							<td class="infotabledetailtitle">Card No.</td>
							<td class="infotabledetailtitle"><?php echo $oldcardno; ?></td>
						</tr>
						<tr>
							<td class="infotabledetailtitlealternate">Current Points</td>
							<td class="infotabledetailtitle"><?php echo $oldcardpoints; ?></td>
						</tr>

						<?php } ?>

						
						<!-- Destination Loyalty Card (NEW)  -->

						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td class="infotabledetailtitlealternate">New Loyalty Card No.</td>
							<td>
								<input type="text" name="newcardno" size="20"  maxlength="20" value="<?php echo $newcardno; ?>" />
								<input type="button" name="btncheck3" value="Check Points" onclick="if(newcardno.value != '') {actiontxt.value='checknewpoints';submit();} else { alert('Invalid Card No.'); }" />
							</td>
						</tr>
						<tr>
							<td class="infotabledetailtitle">Username</td>
							<td class="infotabledetailtitle"><?php echo $newcard_username; ?></td>
						</tr>
						<tr>
							<td class="infotabledetailtitlealternate">Current Points</td>
							<td class="infotabledetailtitle"><?php echo $newcard_points; ?></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>


						<!-- Total Points after Transfer  -->

						<tr>
							<td class="infotabledetailtitlealternate">Total Points after transfer:</td>
							<td><?php echo $totalpoints; ?></td>
						</tr>
<!--						<tr><td colspan="2">&nbsp;</td></tr>-->
						<tr>
							<td align="right" colspan="2">
								<input type="button" name="btntransfer" class="btnDisable" value="Transfer Points" <?php echo $disabled_transferbtn; ?> onclick="if(confirm('Continue?')) {checktransfer();}" />
							</td>
						</tr>
					</table>
				</center>


			</div>  <!-- End DIV of #maincontent  -->


			<input type="hidden" name="actiontxt" />

			<?php if ($transfermode == 2) { // If Lost/Damaged/Stolen Card Transfer... ?>

				<input type="hidden" name="oldcardno" value="<?php echo $oldcardno; ?>" />

			<?php } ?>

			
			<input type="hidden" name="oldcardpoints" value="<?php echo $oldcardpoints; ?>" />
			<input type="hidden" name="newcard_username" value="<?php echo $newcard_username; ?>" />
			<input type="hidden" name="newcard_points" value="<?php echo $newcard_points; ?>" />
		</form>

<?php include("footer.php"); ?>

<?php } else {

	force_logout($sessionID);
	header("Location: index.php?mess=Access Denied! Please login as Administrator.");

}?>
