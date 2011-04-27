<?php $page = "Loyalty Card Registration"; ?>
<?php $formname = "frmRegistration"; ?>

<?php include ("connectionstring.php"); ?>

<?php

$datenow = date("F d, Y H:i:s");
$logout = 0;

$checked = "checked='checked'";
$readonly = "readonly='readonly'";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];


if (isset($_POST["actno"]))
	$actno = $_POST["actno"];


if (isset($_POST["username"]))
	$username = $_POST["username"];

if (isset($_POST["barcodeserial"]))
{
	$barcodeserial = $_POST["barcodeserial"];

if (strpos($barcodeserial, "-") > -1)
        {
            $barcode = split("-", $barcodeserial);
            $serialnumber = $barcode[0];
            $cnumber = substr($barcode[1], strlen($barcode[1]) - 3);
            $barcodeserial = $serialnumber . $cnumber;
        }
}

if (isset($_POST["cardno"]))
{
	$cardno = $_POST["cardno"];

}

if (isset($_POST["playerid"]))
	$playerid = $_POST["playerid"];



if (isset($_POST["genderid"]))
	$genderid = $_POST["genderid"];

if (isset($_POST["agerangeid"]))
	$agerangeid = $_POST["agerangeid"];

if (isset($_POST["smokerid"]))
	$smokerid = $_POST["smokerid"];

if (isset($_POST["occupationid"]))
	$occupationid = $_POST["occupationid"];

if (isset($_POST["ethnicityid"]))
	$ethnicityid = $_POST["ethnicityid"];

if (isset($_POST["contactno"]))
	$contactno = $_POST["contactno"];

if (isset($_POST["email"]))
	$email = $_POST["email"];

$points_lifetime = "---";
$points_current = "---";
$points_redeemed = "---";
$last_playedtime = "---";
$last_playedlocation = "---";


switch($actiontxt)
{
	case "goregister":

		if (empty($actno))
			$actno = "0000000013";

		include ("connectionstring.php");

		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database 1");

		$query = "Call sp_AddNewMember('" . $cardno . "', '" .  $username . "', '" . $email . "', '" . $genderid . "', '" . $agerangeid . "', '" . $ethnicityid . "', '" . $smokerid . "', '" . $contactno . "', '" . $occupationid . "', '" . $actno . "', '" . $pegsessid . "')";

		$qryaddplayer = mysql_query($query,$dbConnection) or die(mysql_error());
		$rowaddplayer = mysql_fetch_array($qryaddplayer);

		$statno = $rowaddplayer['StatusNo'];
//		$statdesc = $rowaddplayer['StatusDesc'];
		$message = $rowaddplayer['StatusDesc'];

		mysql_close($dbConnection);

		if ($statno == 0)
		{
			$errorcolor = "success";

			//Email PeGS Ops (notification)

			$name = "PeGS Loyalty Program";
			$subject = "New Loyalty Card Registered";
			$type = ""; // 1 - TO; 2 - CC; 3 - BCC; if NULL - Email to PeGS Operations Admin

//			$email = "gino.ambrosio@yahoo.com.ph";  //PeGS Operations email address...

			$body = "
				<html>
					<body>
						PeGS Operations Team,

						<br /><br />

						<table style=\"width:70%;font-size:1em;\">
							<tr>
								<td>PeGS Account No.</td>
								<td>:</td>
								<td style=\"font-weight:Bold;text-align:right;\">$pegaccountno</td>
							</tr>
							<tr>
								<td>Date Registered</td>
								<td>:</td>
								<td style=\"font-weight:Bold;text-align:right;\">$datenow</td>
							</tr>
							<tr>
								<td colspan=\"3\">------------------------------------------------------------------</td>
							</tr>
							<tr>
								<td width=\"30%\" style=\"font-weight:Bold;\">Barcode/Serial No.</td>
								<td width=\"5%\" style=\"font-weight:Bold;\">:</td>
								<td width=\"65%\"style=\"font-weight:Bold;text-align:right;\">$barcodeserial</td>
							</tr>
							<tr>
								<td style=\"font-weight:Bold;\">Card No.</td>
								<td style=\"font-weight:Bold;\">:</td>
								<td style=\"font-weight:Bold;text-align:right;\">$cardno</td>
							</tr>
							<tr>
								<td style=\"font-weight:Bold;\">Username</td>
								<td style=\"font-weight:Bold;\">:</td>
								<td style=\"font-weight:Bold;text-align:right;\">$username</td>
							</tr>
						</table>
						<br /><br />
						Thank you.

						<br /><br />

						<b>Note: This is an auto-generated email. Please do not reply. </b>
					</body>
				</html>
			";

			include("include/email.inc");

			if(!$mailer->Send())
			{
			  echo "<script>alert('ERROR: " . $mailer->ErrorInfo . "');</script>";
			}
			else
			{
//					echo "<script>alert('Notification Email already been sent to $email');</script>";
			}

			$mailer->ClearAddresses();
			$mailer->ClearAttachments();


			$barcodeserial = "";
			$cardno = "";
			$username = "";

			$cardtype = "";
			$genderid = "";
			$agerangeid = "";
			$smokerid = "";
			$occupationid = "";
			$ethnicityid = "";
			$contactno = "";
			$email = "";

		}
		else
		{
			$errorcolor = "error";
		}

	break;

	case "validate":

		include ("connectionstring.php");
		
		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database 2");

		// Get Serial No and Card No. combination for display
		$query = "Call sp_ValidateCard('" . $barcodeserial . "')";
		$qryvalidate = mysql_query($query,$dbConnection) or die(mysql_error());

		$rowvalidate = mysql_fetch_array($qryvalidate);

		$statno = $rowvalidate['StatusNo'];
		$message = $rowvalidate['StatusDesc'];
		mysql_close($dbConnection);

		if ($statno > 0) {

			$errorcolor = "error";
			$message = $rowvalidate['StatusDesc'];

			switch ($statno)
			{
				case 1 : $message = "Loyalty Card already been used. Please input another card."; break;
				case 2 : $message = "Loyalty Card has been Deactivated. Please input another card."; break;
				case 3 : $message = "Unknown Loyalty Card Number. Please input another card."; break;
			}

			$cardno = "";
			$barcodeserial = "";

		} else {
			$errorcolor = "success";
			$cardno = $rowvalidate['CardNumber'];
			$cardstatus = "Card Available!";
			$message = ""; // Card is Inactive, therefore AVAILABLE -- this is to preempt the "message" to show.
		}

		

	break;

	case "logout":

		force_logout($sessionID);
		$logout = 1;

	break;
}


if ($cardno != "")
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


		<script>

		function goregister()
		{
			var rem = '';
			with (document.frmRegistration)
			{
				if(barcodeserial.value=='' || barcodeserial.value==null)
				{
					if(rem=='')
						{rem = 'Serial No.';}
					else
						{rem = rem + ',Serial No.';}
				}
				if(username.value=='' || username.value==null)
				{
					if(rem=='')
						{rem = 'Username';}
					else
						{rem = rem + ',Username';}
				}


				if (rem == '')
				{
					actiontxt.value='goregister';
					submit();
				}
				else
				{
					alert('Required Field(s): ' + rem);
				}
			}
		}

		function gologout()
		{
//			document.frmRegistration.actiontxt.value='logout';
//			document.frmRegistration.submit();
		}

		</script>

    </head>
    <body onload="if('<?php echo $logout ?>' != '1') { if('<?php echo $cardstatus; ?>' == '') {document.frmRegistration.barcodeserial.select();} else {document.frmRegistration.username.select();} } else {window.close();}" >

		<form name="<?php echo $formname; ?>" id="<?php echo $formname; ?>" method="POST">

			<?php include("header.php"); ?>

			<table width="900" cellpadding="0" cellspacing="0" class="mainmenu">
				<tr>
					<td width="25%" class="selectedmenuitem"><a href="LoyaltyCardRegistration.php">Card Registration</a></td>
					<td width="25%">&nbsp;</td>
					<td width="25%">&nbsp;</td>
					<td width="25%">&nbsp;</td>
				</tr>
			</table>

			<?php include("message.php"); ?>


			<br />

			<div class="maincontent">

			<center>
				<table width="900" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td class="infotabledetailtitle">Serial Number</td>
						<td>
							<input type="password" name="barcodeserial" style="text-align:center;font-weight:Bold;<?php echo $styleInput; ?>" size="15" maxlength="30" value="<?php echo $barcodeserial; ?>" onkeypress="javascript://return numericOnly(event);"/>
							<input type="button" name="btnValidate" value="Validate Card" onclick="if (barcodeserial.value != '') {document.frmRegistration.actiontxt.value='validate';submit();} else {alert('Invalid Input. Please try again.');}"
						</td>
						<td rowspan="4" width="170px"><b>Ethnicity</b></td>
						<td rowspan="4">
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
						<td class="infotabledetailtitlealternate">Card Number</td>
						<td>
							<input type="text" name="cardno" <?php echo $readonly; ?> style="text-align:center;font-weight:Bold;<?php echo $styleReadOnly; ?>" size="15" value="<?php echo $cardno; ?>" />
							<br /> <span class="RegistrationCardStatus"> <?php echo $cardstatus; ?> </span>
						</td>
					</tr>
					<tr>
						<td class="infotabledetailtitle">Username *</td>
						<td><input type="text" name="username" maxlength="30" size="15" <?php echo $disabled; ?> style="text-align:center;font-weight:Bold;<?php echo $styleInput; ?>" value="<?php echo $username; ?>" /></td>
					</tr>
					<tr>
						<td class="infotabledetailtitlealternate">Contact Number</td>
						<td><input type="text" name="contactno" maxlength="20" <?php echo $disabled; ?> value="<?php echo $contactno; ?>" onkeypress="return numericOnly(event);"/></td>
					</tr>
					<tr>
						<td class="infotabledetailtitle">Email</td>
						<td><input type="text" name="email" maxlength="30" <?php echo $disabled; ?> value="<?php echo $email; ?>" onkeypress="return validEmailChar(event);"/></td>
						<td><b>Smoker</b></td>
						<td>
							<?php
							$tmp = "smokerchk" . $smokerid;
							$$tmp = $checked;
							?>
							<input type="radio" <?php echo $smokerchk1 . " " . $disabled; ?> name="smokerid" id="smoker0" value="1" /><label for="smoker0">Yes</label>
							<input type="radio" <?php echo $smokerchk0 . " " . $disabled; ?> name="smokerid" id="smoker1" value="0" /><label for="smoker1">No</label>
						</td>
					</tr>
					<tr>
						<td class="infotabledetailtitlealternate">Gender</td>
						<td>
							<?php
							$tmp = "genderchk" . $genderid;
							$$tmp = $checked;
							?>
							<input type="radio" <?php echo $genderchk1 . " " . $disabled; ?> name="genderid" id="gender1" value="1" /><label for="gender1">Male</label>
							<input type="radio" <?php echo $genderchk2 . " " . $disabled;; ?> name="genderid" id="gender2" value="2" /><label for="gender2">Female</label>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="infotabledetailtitle">Age</td>
						<td>
							<?php
							$tmp = "agerangechk" . $agerangeid;
							$$tmp = $checked;
							?>

							<input type="radio" <?php echo $agerangechk1 . " " . $disabled;; ?>  name="agerangeid" id="agerange1" value="1" /><label for="agerange1">21 - 30</label><br />
							<input type="radio" <?php echo $agerangechk2 . " " . $disabled;; ?>  name="agerangeid" id="agerange2" value="2" /><label for="agerange2">31 - 40</label><br />
							<input type="radio" <?php echo $agerangechk3 . " " . $disabled;; ?>  name="agerangeid" id="agerange3" value="3" /><label for="agerange3">41 - 50</label><br />
							<input type="radio" <?php echo $agerangechk4 . " " . $disabled;; ?>  name="agerangeid" id="agerange4" value="4" /><label for="agerange4">51 - 60</label><br />
							<input type="radio" <?php echo $agerangechk5 . " " . $disabled;; ?>  name="agerangeid" id="agerange5" value="5" /><label for="agerange5">61 and above</label><br />
						</td>
						<td><b>Occupation</b></td>
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
					<tr>
						<td colspan="4" align="right">
							<input type="submit" value="Register Loyalty Card" <?php echo $disabled; ?> onclick="goregister();" />
							<input type="button" value="Close Window" onclick="if(confirm('Are you sure you want to close Loyalty Card Registration?')) { window.close(); }" />
						</td>
					</tr>
				</table>

			</center>
			</div>  <!-- End DIV of #maincontent  -->


		<input type="hidden" name="actiontxt" />
		<input type="hidden" name="playerid" value="<?php echo $playerid; ?>" />

		<input type="hidden" name="actno" value="<?php echo $actno; ?>" />
		<input type="hidden" name="pegsessid" value="<?php echo $pegsessid; ?>" />
		<input type="hidden" name="pegsessamount" value="<?php echo $pegsessamount; ?>" />

		</form>
	</body>
</html>