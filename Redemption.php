<?php $page = "Rewards Redemption"; ?>
<?php $formname = "frmRedemption"; ?>
<?php include("checksession.php"); ?>

<?php if ($sid_type == "C") { ?>

<?php

$searchby_barcodeonly = 1;


if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["searchkey"]))
{
	$searchkey = $_POST["searchkey"];
if (strpos($searchkey, "-") > -1)
        {
            $barcode = split("-", $searchkey);
            $serialnumber = $barcode[0];
            $cnumber = substr($barcode[1], strlen($barcode[1]) - 3);
            $searchkey = $serialnumber . $cnumber;
        }
}

if (isset($_POST["searchby"]))
	$searchby = $_POST["searchby"];
else
	$searchby = 1;


if (isset($_POST["playerid"]))
	$playerid = $_POST["playerid"];

if (isset($_POST["quantity"]))
	$quantity = $_POST["quantity"];

if (isset($_POST["email"]))
	$email = $_POST["email"];

if (isset($_POST["itemid"]))
	$itemid = $_POST["itemid"];

if (isset($_POST["cardno"]))
	$cardno = $_POST["cardno"];



$points_lifetime = "---";
$points_current = "---";
$points_redeemed = "---";
$last_playedtime = "---";
$last_playedlocation = "---";

switch($actiontxt)
{
	case "redeemitem":
		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

		$query = "Call sp_RedeemPoints('" . $sessionID . "', '" . $cardno . "', '" . $itemid . "', '" . $quantity . "')";

		$qryredeem = mysql_query($query,$dbConnection) or die(mysql_error());

		$rowredeem = mysql_fetch_array($qryredeem);

		$message = $rowredeem['StatusDesc'];
		$statno = $rowredeem['StatusNo'];


		if ($statno > 0)  // Error
		{
			$errorcolor = "error";

			if (($statno == "1") || ($statno == "2")) //Session Timeout and Session ID not found
			{
				if (force_logout($sessionID))
					header("Location: index.php?mess=$message");
			}
		}
		else // Successful
		{
			$errorcolor = "success";

			if (!empty($email))
			{
				$type = 1;
			}
			else
				$type = "";


				$xitem = $rowredeem["Item"];
				$xitemdescr = $rowredeem["Description"];
				$xitemamount = $rowredeem["AmountPerItem"];

				$xtotal = $xitemamount * $quantity;

//				$xitem = "Raffle Ticket";
//				$xitemdescr = "Put description here...";
//				$xitemamount = "100 Points";

				$name = "PeGS Loyalty";
				$subject = "PeGS Loyalty Card: Item Redeemed";
				

				$body = "
					<html>
						<body>
							<span style=\"font-size:1em;\">
							Hello <b>$username</b>, <br /><br />

							You have successfully redeemed, the following Reward Item, <br /><br />


							<table style=\"width:50%;font-size:1em;\">
								<tr>
									<td width=\"20%\" style=\"font-weight:Bold;\">Reward Item</td>
									<td width=\"5%\" style=\"font-weight:Bold;\">:</td>
									<td width=\"75%\"style=\"font-size:1em;font-weight:Bold;color:Blue;\">$xitem</td>
								</tr>
								<tr>
									<td style=\"font-weight:Bold;\">Description</td>
									<td style=\"font-weight:Bold;\">:</td>
									<td style=\"font-style:italic;color:Black;\">$xitemdescr</td>
								</tr>
								<tr>
									<td style=\"font-weight:Bold;\">Points</td>
									<td style=\"font-weight:Bold;\">:</td>
									<td style=\"font-size:1em;font-weight:Bold;color:Green;\">$xitemamount</td>
								</tr>
								<tr>
									<td style=\"font-weight:Bold;\">Quantity</td>
									<td>:</td>
									<td style=\"font-size:1em;font-weight:Bold;\">$quantity</td>
								</tr>
								<tr>
									<td colspan=\"3\">------------------------------------------------------------------</td>
								</tr>
								<tr>
									<td style=\"font-size:1em;font-weight:Bold;\">Total Deducted</td>
									<td>:</td>
									<td style=\"font-size:1em;font-weight:Bold;\">$xtotal</td>
								</tr>
							</table>

							 <br /><br />
							This is under Loyalty Card No. $cardno.

							Thank you.

							<br /><br />

							<b>Note: This is an auto-generated email. Please do not reply. </b>
							<br /><br />

							</span>

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
//			}

		}


		unset($query);
		unset($qryredeem);
		unset($rowredeem);
		mysql_close($dbConnection);

		$itemid = "";
		
		
	break;
}




if (!empty($searchkey))
{

	// 1 - Barcode/Serial
	// 2 - Card No.
	// 3 - Username


//	include ("connectionstring.php");

	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");



	$query = "Call sp_ViewMember('1', '" . $searchkey . "')";
	$qryviewprofile = mysql_query($query,$dbConnection) or die(mysql_error());
	$rowviewprofile = mysql_fetch_array($qryviewprofile);
	
	$statno = $rowviewprofile['StatusNo'];

	if ($statno > 0)  // Error
	{
		$errorcolor = "error";
		$message = $rowviewprofile['StatusDesc'];


//			$barcode = "";  //reset scanned barcode

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

			$itemid = "";
			$quantity = "";
//		}
	}
	else // Successful
	{
		$errorcolor = "success";

		$playerid = $rowviewprofile["playerID"];
		$cardno = $rowviewprofile["CardNumber"];
		$cardtype = $rowviewprofile["CardType"];
		$cardtypeid = $rowviewprofile["CardTypeID"];
		$username = $rowviewprofile["UserName"];
		$barcodeserial = $rowviewprofile["SerialNumber"];
		$email = $rowviewprofile["EmailAdd"];
		$contactno = $rowviewprofile["ContactNumber"];
//		$smokerid = $rowviewprofile["isSmoker"];

//		$tmpchk = "smokerchk" . $smokerid;
//		$$tmpchk = $checked;

//		$genderid = $rowviewprofile["GenderID"];

//		$tmpchk = "genderchk" . $genderid;
//		$$tmpchk = $checked;

//		$agerangeid = $rowviewprofile["AgeID"];
//		$ethnicityid = $rowviewprofile["EthnicityID"];
//		$occupationid = $rowviewprofile["OccupationID"];

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

	
	unset($query);
	unset($qryviewprofile);
	unset($rowviewprofile);
	mysql_close($dbConnection);
	
}
else  // If NO SearchKey, CLEAR all fields
{
	$cardtype = "";
	$cardtypeid = "";
//	$genderid = "";
//	$agerangeid = "";
//	$smokerid = "";
//	$occupationid = "";
//	$ethnicityid = "";
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


//if ($cardno != "---")
if (!empty($itemid))
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

    </head>
    <body onload="<?php if (empty($playerid)) { echo "document.$formname.searchkey.focus();"; } ?>">

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

				<br />

			<?php if (empty($itemid)) { ?>

				<table width="900" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td colspan="4" class="sectiontitle">AVAILABLE REWARD ITEMS</td>
					</tr>
					<?php

						$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
						$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

						if (!empty($cardtypeid))
							$query = "SELECT r.ID,r.Item,r.ItemDescription,r.Amount,r.CardTypeID_FK,c.Description AS CardType
										FROM tbl_RewardItems r
										LEFT JOIN tbl_CardTypes c ON c.ID=r.CardTypeID_FK
										WHERE Status='A' AND r.CardTypeID_FK = $cardtypeid";
						else
							$query = "SELECT r.ID,r.Item,r.ItemDescription,r.Amount,r.CardTypeID_FK,c.Description AS CardType
										FROM tbl_RewardItems r
										LEFT JOIN tbl_CardTypes c ON c.ID=r.CardTypeID_FK
										WHERE Status='A' ORDER BY r.CardTypeID_FK";

						$qryitems = mysql_query($query,$dbConnection) or die(mysql_error());

						$classtype = "odd";
						$tmpgroupid = "";
						while ($rowitems = mysql_fetch_array($qryitems))
						{
							$iid = $rowitems["ID"];
							$itm = $rowitems["Item"];
							$desc = $rowitems["ItemDescription"];
							$iamt = $rowitems["Amount"];
							$igroupid = $rowitems["CardTypeID_FK"];
							$igroup = $rowitems["CardType"];

							if ($tmpgroupid != $igroupid)
							{ ?>
								<tr>
									<td colspan="4" class="infotablegrouping"><?php echo $igroup; ?> CARD ITEMS</td>
								</tr>
								<tr>
									<th width="25%">ITEM</th>
									<th width="40%">DESCRIPTION</th>
									<th width="10%">POINTS</th>
									<th width="25%">REDEEM</th>
								</tr>
							<?php
							}

							if ($iamt > $points_current)
								$disabled_redeem = "disabled='disabled'";
							else
								$disabled_redeem = "";
					?>
							<tr class="<?php echo $classtype; ?>" <?php echo $mouseovereffect; ?> >
								<td valign="middle" align="center"><?php echo $itm; ?></td>
								<td valign="middle" align="center"><?php echo $desc; ?></td>
								<td valign="middle" align="center"><?php echo $iamt; ?></td>
								<td valign="middle" align="center">
									<input type="button" value="Redeem Now!" class="btnRedeem" <?php echo $disabled_redeem; ?> onclick="document.<?php echo $formname; ?>.itemid.value='<?php echo $iid; ?>';document.<?php echo $formname; ?>.submit();" />
								</td>
							</tr>

					<?php
							$tmpgroupid = $igroupid;
							if($classtype=="even") { $classtype="odd";} else { $classtype="even"; }
						}
					?>

				</table>

			<?php } else {

				$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
				$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

				$query = "SELECT ID,Item,ItemDescription,Amount FROM tbl_RewardItems WHERE ID=$itemid";

				$qryitemdtls = mysql_query($query,$dbConnection) or die(mysql_error());

				$rowitemdtls = mysql_fetch_array($qryitemdtls);

				$disp_item = $rowitemdtls["Item"];
				$disp_itemdesc = $rowitemdtls["ItemDescription"];
				$disp_itempoints = $rowitemdtls["Amount"];

			?>
				<br />
				<center>
					<table width="800" cellpadding="5" cellspacing="0" class="infotable">
						<tr>
							<td colspan="2" class="infotablegrouping">You have chosen the following Reward Item:</td>
						</tr>
						<tr>
							<td>
								<table width="100%" cellpadding="5" cellspacing="0" class="infotable">
									<tr>
										<td class="infotabledetailtitle">Item</td>
										<td class="redeem_item"><?php echo $disp_item; ?></td>
									</tr>
									<tr>
										<td class="infotabledetailtitle">Description</td>
										<td class="redeem_itemdesc"><?php echo $disp_itemdesc; ?></td>
									</tr>
									<tr>
										<td class="infotabledetailtitle">Points</td>
										<td class="redeem_itempoints"><?php echo $disp_itempoints; ?> Points</td>
									</tr>
								</table>
							</td>
							<td align="center">

								<b>Quantity</b> <br />
								<input type="text" name="quantity" maxlength="5" size="5" class="inputQuantity" value="<?php echo $quantity; ?>" onkeyup="if(this.value != '') {document.getElementById('btnRedeemItem').disabled=false;} else {document.getElementById('btnRedeemItem').disabled=true;}" onkeypress="return numericOnly(event);" />

							</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td colspan="2" align="right">

								<input type="button" name="btnRedeemItem" id="btnRedeemItem" class="btnDisable" value="Redeem Item" align="absmiddle" disabled="disabled" onclick="
										if(confirm('Are you sure you want to redeem Item: <?php echo $disp_item; ?>?')) {document.<?php echo $formname; ?>.actiontxt.value='redeemitem';document.<?php echo $formname; ?>.submit();}
									" />
								<input type="button" class="btnDisable" value="Cancel" align="absmiddle" onclick="document.<?php echo $formname; ?>.itemid.value='';document.<?php echo $formname; ?>.submit();" />
							</td>
						</tr>
					</table>
				</center>

			<?php } ?>

			</div>  <!-- End DIV of #maincontent  -->

		<input type="hidden" name="actiontxt" />
		<input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
		<input type="hidden" name="playerid" value="<?php echo $playerid; ?>" />
		<input type="hidden" name="email" value="<?php echo $email; ?>" />
		<input type="hidden" name="cardno" value="<?php echo $cardno; ?>" />

		</form>

<?php include("footer.php"); ?>

<?php } else {
	
	force_logout($sessionID);
	header("Location: index.php?mess=Access Denied! Please login as Cashier.");

}?>
