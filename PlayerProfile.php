<?php $page = "Points Inquiry"; ?>
<?php $formname = "frmProfile"; ?>
<?php include("checksession.php"); ?>

<?php if ($sid_type == "C" || $sid_type == "A") { ?>

<?php

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["searchkey"]))
	$searchkey = $_POST["searchkey"];

if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
else
	$searchby = "1";

if (isset($_POST["playerid"]))
	$playerid = $_POST["playerid"];



if (isset($_POST["genderid"]))
	$genderid = $_POST["genderid"];

		$tmpchk = "genderchk" . $genderid;
		$$tmpchk = $checked;

if (isset($_POST["agerangeid"]))
	$agerangeid = $_POST["agerangeid"];

		$tmpchk = "agechk" . $agerangeid;
		$$tmpchk = $checked;

if (isset($_POST["smokerid"]))
	$smokerid = $_POST["smokerid"];

		$tmpchk = "smokerchk" . $smokerid;
		$$tmpchk = $checked;

if (isset($_POST["occupationid"]))
	$occupationid = $_POST["occupationid"];

if (isset($_POST["ethnicityid"]))
	$ethnicityid = $_POST["ethnicityid"];

if (isset($_POST["contactno"]))
	$contactno = $_POST["contactno"];

if (isset($_POST["email"]))
	$email = $_POST["email"];


$checked = "checked='checked'";
$readonly = "readonly='readonly'";

$points_lifetime = "---";
$points_current = "---";
$points_redeemed = "---";
$last_playedtime = "---";
$last_playedlocation = "---";


//$styleReadOnly = "background-color:#DCDCDC;border:1px solid Black;padding:2px;";
//$styleInput = "font-size:1.1em;font-weight:Bold;color:Blue;border:1px solid #6C80CB;padding:2px;text-align:center;background-color:White;";

//echo "XXX: " . $actiontxt;

switch($actiontxt)
{
	case "updateprofile":

		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

		$query = "Call sp_UpdateMember('" . $sessionID . "', '" . $playerid . "', '" . $email . "', '" . $genderid . "', '" . $agerangeid . "', '" . $ethnicityid . "', '" . $smokerid . "', '" . $contactno . "', '" . $occupationid . "')";

		$qryupdateprofile = mysql_query($query,$dbConnection) or die(mysql_error());
		$rowupdateprofile = mysql_fetch_array($qryupdateprofile);

		$message = $rowupdateprofile['StatusDesc'];
		$statno = $rowupdateprofile['StatusNo'];

		mysql_close($dbConnection);

		if ($statno > 0)  // Error
		{
			$errorcolor = "error";

			if (($statno == 1) || ($statno == 2)) //Session Timeout and Session ID not found
			{
				if (force_logout($sessionID))
				{
					set_errormsg($message,0);
					header("Location: index.php");
				}
			}
		}
		else // Successful
		{
			$errorcolor = "success";
		}
	break;

	case "deactivate":

		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

		$query = "Call sp_AdminDeactivateCard('" . $sessionID . "', '" . $cardno . "')";

		$qrydeactivate = mysql_query($query,$dbConnection) or die(mysql_error());
		$rowdeactivate = mysql_fetch_array($qrydeactivate);

		$message = $rowdeactivate['StatusDesc'];
		$statno = $rowdeactivate['StatusNo'];

		mysql_close($dbConnection);

		if ($statno > 0)  // Error
		{
			$errorcolor = "error";

			if (($statno == 1) || ($statno == 2)) //Session Timeout and Session ID not found
			{
				if (force_logout($sessionID))
				{
					set_errormsg($message,0);
					header("Location: index.php");
				}
			}
		}
		else // Successful
		{
			$errorcolor = "success";
		}

	break;
}


$searchkey_orig = $searchkey;

switch ($searchby)
{
	case "1" : // Get Barcode
		
		if (strpos($searchkey, "-") > -1)
			{
				$barcode = split("-", $searchkey);
				$serialnumber = $barcode[0];
				$cnumber = substr($barcode[1], strlen($barcode[1]) - 3);
				$searchkey = $serialnumber . $cnumber;
			}
	break;
//	case "2" : //Get Card No.
//		$searchkey = substr($searchkey,11,20);
//	break;
}



if (!empty($searchkey))
{

	// 1 - Barcode/Serial
	// 2 - Card No.
	// 3 - Username


	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

	$query = "Call sp_ViewMember('" . $searchby . "', '" . $searchkey . "')";
	$qryviewprofile = mysql_query($query,$dbConnection) or die(mysql_error());
	$rowviewprofile = mysql_fetch_array($qryviewprofile);

	$message1 = $rowdeactivate['StatusDesc'];
	$statno = $rowdeactivate['StatusNo'];

	mysql_close($dbConnection);

	//if (!empty($rowviewprofile["StatusNo"]))
	if($statno > 0)
	{
//		seterrormsg($message,0);

		$cardtype = "";
		$genderid = "";
		$agerangeid = "";
		$smokerid = "";
		$occupationid = "";
		$ethnicityid = "";
		$contactno = "";
		$email = "";

		$points_lifetime = "---";
		$points_current = "---";
		$points_redeemed = "---";
		$last_playedtime = "---";
		$last_playedlocation = "---";

		$username = "---";
		$barcodeserial = "---";
		$cardno = "---";
	}
	else
	{
		$playerid = $rowviewprofile["playerID"];
		$cardno = $rowviewprofile["CardNumber"];
		$cardtype = $rowviewprofile["CardType"];
		$username = $rowviewprofile["UserName"];
		$barcodeserial = $rowviewprofile["SerialNumber"];
		$email = $rowviewprofile["EmailAdd"];
		$contactno = $rowviewprofile["ContactNumber"];
		$smokerid = $rowviewprofile["isSmoker"];

		$tmpchk = "smokerchk" . $smokerid;
		$$tmpchk = $checked;

		$genderid = $rowviewprofile["GenderID"];

		$tmpchk = "genderchk" . $genderid;
		$$tmpchk = $checked;

		$agerangeid = $rowviewprofile["AgeID"];
		$ethnicityid = $rowviewprofile["EthnicityID"];
		$occupationid = $rowviewprofile["OccupationID"];

		if (!empty($rowviewprofile["vLTPoints"]))
			$points_lifetime = $rowviewprofile["vLTPoints"];

		if (!empty($rowviewprofile["vRedeemPoints"]))
			$points_redeemed = $rowviewprofile["vRedeemPoints"];

		if (!empty($rowviewprofile["vCurPoints"]))
			$points_current = $rowviewprofile["vCurPoints"];

		if(!empty($rowviewprofile["DateLastPlay"]))
			$last_playedtime = date("F d, Y h:m A",strtotime($rowviewprofile["DateLastPlay"]));

		if (!empty($rowviewprofile["LastLocationPlay"]))
		{
			$last_playedlocation = substr ($rowviewprofile["LastLocationPlay"], 0, 25);
			$last_playedlocation_title = $rowviewprofile["LastLocationPlay"];
		}

		$status = $rowviewprofile["Status"];
	}

//	mysql_close($dbConnection);

	$searchkey = $searchkey_orig;

}
else  // If NO SearchKey, CLEAR all fields
{
	$cardtype = "";
	$genderid = "";
	$agerangeid = "";
	$smokerid = "";
	$occupationid = "";
	$ethnicityid = "";
	$contactno = "";
	$email = "";

	$points_lifetime = "---";
	$points_current = "---";
	$points_redeemed = "---";
	$last_playedtime = "---";
	$last_playedlocation = "---";

	$username = "---";
	$barcodeserial = "---";
	$cardno = "---";
}


if ($cardno != "---" && $sid_type == "C")
	$disabled = "";
else
	$disabled = "disabled='disabled'";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>PeGS Loyalty Program - <?php echo $page; ?></title>
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="js/pegsloyalty.js"></script>

		<script language="javascript" type="text/javascript">

		</script>
    </head>
    <body onload="<?php if (empty($playerid)) { echo "document.$formname.searchkey.select();"; } ?>">

		<form name="<?php echo $formname; ?>" id="<?php echo $formname; ?>" method="POST">

			<?php include("header.php"); ?>
			<?php include("menu.php"); ?>
			<?php include("search.php"); ?>
			<?php include("message.php"); ?>


			<div class="maincontent">
				<table width="900" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td colspan="4" class="sectiontitle">REWARDS CARD INFORMATION</td>
					</tr>
					<tr>
						<td class="infotabledetailtitle">Card Number</td>
						<td><?php echo $cardno; ?></td>
						<td class="infotabledetailtitle">Lifetime Points</td>
						<td><?php echo $points_lifetime; ?></td>
					</tr>
					<tr>
						<td class="infotabledetailtitlealternate">&nbsp;</td>
						<td>&nbsp;</td>
						<td class="infotabledetailtitlealternate">Redeemed Points</td>
						<td><?php echo $points_redeemed; ?></td>
					</tr>
					<tr>
						<td class="infotabledetailtitle">Contact Number</td>
						<td><input type="text" name="contactno" maxlength="20" <?php echo $disabled; ?> value="<?php echo $contactno; ?>" onkeypress="return numericOnly(event);"/></td>
						<td class="infotabledetailtitle">Last Play Date</td>
						<td><?php echo $last_playedtime; ?></td>
					</tr>
					<tr>
						<td class="infotabledetailtitlealternate">Email</td>
						<td><input type="text" name="email" maxlength="30" <?php echo $disabled; ?> value="<?php echo $email; ?>" onkeypress="return validEmailChar(event);"/></td>
						<td class="infotabledetailtitlealternate">Last Site Played</td>
						<td><?php echo $last_playedlocation; ?></td>
					</tr>
				</table>
				<BR>
				<table width="900" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td colspan="2" class="sectiontitle">ACCOUNT INFORMATION</td>
					</tr>
					<tr>
						<td>
							<table width="430" cellpadding="5" cellspacing="0" class="infotable">
								<tr>
									<td class="infotabledetailtitle">Gender</td>
									<td>
										<?php
											$tmp = "genderchk" . $genderid;
											$$tmp = $checked;
										?>
										<input type="radio" <?php echo $genderchk1 . " " . $disabled; ?> name="genderid" id="genderid1" value="1" /><label for="genderid1">Male</label>
										<input type="radio" <?php echo $genderchk2 . " " . $disabled; ?> name="genderid" id="genderid2" value="2" /><label for="genderid2">Female</label>
									</td>
								</tr>
								<tr>
									<td class="infotabledetailtitlealternate">Age</td>
									<td>
										<?php

										$tmp = "agerangechk" . $agerangeid;
										$$tmp = $checked;

										?>

										<input type="radio" <?php echo $agerangechk1 . " " . $disabled;; ?>  name="agerangeid" value="1" id="agerangeid1" /><label for="agerangeid1">21 - 30</label><br />
										<input type="radio" <?php echo $agerangechk2 . " " . $disabled;; ?>  name="agerangeid" value="2" id="agerangeid2" /><label for="agerangeid2">31 - 40</label><br />
										<input type="radio" <?php echo $agerangechk3 . " " . $disabled;; ?>  name="agerangeid" value="3" id="agerangeid3" /><label for="agerangeid3">41 - 50</label><br />
										<input type="radio" <?php echo $agerangechk4 . " " . $disabled;; ?>  name="agerangeid" value="4" id="agerangeid4" /><label for="agerangeid4">51 - 60</label><br />
										<input type="radio" <?php echo $agerangechk5 . " " . $disabled;; ?>  name="agerangeid" value="5" id="agerangeid5" /><label for="agerangeid5">61 and above</label><br />

									</td>
								</tr>
								<tr>
									<td class="infotabledetailtitle">Card Status</td>
									<td>
										<?php if($status == 'A') {echo "Active";} elseif ($status == 'D') {echo "<span style=\"color:Red;font-style:italic;\">Deactivated</span>";} else { echo "&nbsp;";} ?>
									</td>
								</tr>

							</table>

						</td>

						<td>
							<table width="430" cellpadding="5" cellspacing="0" class="infotable">
								<tr>
									<td class="infotabledetailtitle">Ethnicity</td>
									<td>
										<?php

										$tmp = "ethnicitychk" . $ethnicityid;
										$$tmp = $checked;

										?>

										<input type="radio" <?php echo $ethnicitychk1 . " " . $disabled;; ?>  name="ethnicityid" id="ethnicity1" value="1" /><label for="ethnicity1">Filipino</label><br />
										<input type="radio" <?php echo $ethnicitychk2 . " " . $disabled;; ?>  name="ethnicityid" id="ethnicity2" value="2" /><label for="ethnicity2">Fil-Am</label><br />
										<input type="radio" <?php echo $ethnicitychk3 . " " . $disabled;; ?>  name="ethnicityid" id="ethnicity3" value="3" /><label for="ethnicity3">Fil-Chinese/Chinese</label><br />
										<input type="radio" <?php echo $ethnicitychk5 . " " . $disabled;; ?>  name="ethnicityid" id="ethnicity4" value="5" /><label for="ethnicity4">Caucasian</label><br />
										<input type="radio" <?php echo $ethnicitychk6 . " " . $disabled;; ?>  name="ethnicityid" id="ethnicity5" value="6" /><label for="ethnicity5">Others</label><br />

									</td>
								</tr>
								<tr>
									<td class="infotabledetailtitlealternate" valign="top">Smoker</td>
									<td>
										<?php
											$tmp = "smokerchk" . $smokerid;
											$$tmp = $checked;
										?>
										<input type="radio" <?php echo $smokerchk1 . " " . $disabled; ?> name="smokerid" id="smoker1" value="1" /><label for="smoker1">Yes</label>
										<input type="radio" <?php echo $smokerchk0 . " " . $disabled; ?> name="smokerid" id="smoker2" value="0" /><label for="smoker2">No</label>
									</td>
								</tr>
								<tr>
									<td class="infotabledetailtitle">Occupation</td>
									<td><select name="occupationid" style="width:90%;" <?php echo $disabled; ?> >

											<?php
											$tmp = "occupationsel" . $occupationid;
											$$tmp = "SELECTED";
											?>

											<option value="">-- Select --</option>
											<option <?php echo $occupationsel1; ?> value="1">Employee</option>
											<option <?php echo $occupationsel2; ?> value="2">Self-employed</option>
											<option <?php echo $occupationsel3; ?> value="3">Unemployed</option>
										</select>
									</td>
								</tr>


							</table>
						</td>
					</tr>
					<tr>
						<td align="right" colspan="2">

							<?php if($sid_type == "C") {  ?>

							<input type="button" name="btnupdate" class="btnDisable" value="Update Profile" align="absmiddle" <?php if(empty($playerid)) {echo $disabled;} ?> onclick="
									if(confirm('Are you sure you want to update Profile?')) {document.frmProfile.actiontxt.value='updateprofile';submit();}
								" />

							<?php } else { ?>

							<input type="button" name="btndeactivate" class="btnDisable" value="Deactivate Card" <?php if(empty($playerid)) {echo $disabled;} else {if($status != 'A') {echo $disabled;}} ?> onclick="if(confirm('Are you sure you want to Deactivate Card No. <?php echo $cardno; ?>?')) {document.frmProfile.actiontxt.value='deactivate';submit();}" />

							<?php } ?>
							&nbsp;
						</td>
					</tr>
				</table>

			</div>
	<!--	End of maincontent-->



			<input type="hidden" name="actiontxt" />
			<input type="hidden" name="playerid" value="<?php echo $playerid; ?>" />
		</form>


<?php  include("footer.php"); ?>

<?php } else {

	force_logout($sessionID);
	header("Location: index.php?mess=Access Denied! Please login again.");

}?>
