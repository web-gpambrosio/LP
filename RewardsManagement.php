<?php $page = "Rewards Management"; ?>
<?php $formname = "frmRewardsMgmt"; ?>
<?php include("checksession.php"); ?>

<?php if ($sid_type == "A") { ?>

<?php include("include/mouseovereffect.inc"); ?>

<?php

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["mode"]))
	$mode = $_POST["mode"];
else
	$mode = "";




if (isset($_POST["itemid"]))
	$itemid = $_POST["itemid"];

if (isset($_POST["itemtitle"]))
	$itemtitle = $_POST["itemtitle"];

if (isset($_POST["itemdesc"]))
	$itemdesc = $_POST["itemdesc"];

if (isset($_POST["amount"]))
	$amount = $_POST["amount"];

if (isset($_POST["validfrom"]))
	$validfrom = $_POST["validfrom"];

if (isset($_POST["validto"]))
	$validto = $_POST["validto"];

if (isset($_POST["cardtypeid"]))
	$cardtypeid = $_POST["cardtypeid"];
//else
//	$cardtypeid = 1;

if (isset($_POST["status"]))
	$status = $_POST["status"];



$checked = "checked='checked'";
$readonly = "readonly='readonly'";

switch($actiontxt)
{
	case "additem":

		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

		if (!empty($validfrom))
			$validfromraw = date("Y-m-d",strtotime($validfrom));
		else
			$validfromraw = "";

		if (!empty($validto))
			$validtoraw = date("Y-m-d",strtotime($validto));
		else
			$validtoraw = "";

		$itemdesc = mysql_escape_string($itemdesc);


		$query = "Call sp_AdminAddRewardItem('" . $sessionID . "', '" . $cardtypeid . "', '" . $itemtitle . "', '" . $itemdesc . "', '', '" . $amount ."', '" . $validfromraw . "', '" . $validtoraw . "', '')";

		$qryreward = mysql_query($query,$dbConnection) or die(mysql_error());

		$rowreward = mysql_fetch_array($qryreward);

		$message = $rowreward['StatusDesc'];
		$statno = $rowreward['StatusNo'];

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
//			$mode = "";
			$itemid = "";
		}

		mysql_close($dbConnection);

	break;
	case "updateitem":


		$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
		$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

		if (!empty($validfrom))
			$validfromraw = date("Y-m-d",strtotime($validfrom));
		else
			$validfromraw = "";

		if (!empty($validto))
			$validtoraw = date("Y-m-d",strtotime($validto));
		else
			$validtoraw = "";

		$itemdesc = mysql_escape_string($itemdesc);

		$query = "Call sp_AdminUpdateRewardItem('" . $sessionID . "', '" . $itemid . "', '" . $cardtypeid . "', '" . $itemtitle . "', '" . $itemdesc . "', '', '" . $amount ."', '" . $validfromraw . "', '" . $validtoraw . "', '1', '" . $status . "')";

		$qryreward = mysql_query($query,$dbConnection) or die(mysql_error());

		$rowreward = mysql_fetch_array($qryreward);

		$message = $rowreward['StatusDesc'];
		$statno = $rowreward['StatusNo'];

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
			$mode = "";
			$itemid = "";
		}

		mysql_close($dbConnection);

	break;
	case "reset":

//		$cardtypeid = 1;
		$itemid = "";
		$itemtitle = "";
		$itemdesc = "";
		$amount = "";
		$validfrom = "";
		$validto = "";
		$status = "A";

	break;
}


if (!empty($cardtypeid))
{
	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

	$query = "SELECT ID,Description from tbl_CardTypes WHERE ID=$cardtypeid";

	$qrycardtype = mysql_query($query,$dbConnection) or die(mysql_error());

	if (mysql_num_rows($qrycardtype) > 0)
	{
		$rowcardtype = mysql_fetch_array($qrycardtype);
		$cardtype = $rowcardtype["Description"];
	}
}




if (!empty($itemid))
{

	$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
	$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

//	$query = "SELECT Item,ItemDescription,Amount,date_format(PromoDateFrom,'%m/%d/%Y'),PromoDateTo,ItemGroupID_FK,Status FROM tbl_RewardItems WHERE CardTypeID_FK=$cardtypeid AND ID=$itemid";
	$query = "SELECT Item,ItemDescription,Amount,CASE WHEN PromoDateFrom='0000-00-00 00:00:00' THEN '' ELSE date_format(PromoDateFrom,'%m/%d/%Y') END AS PromoDateFrom,CASE WHEN PromoDateTo='0000-00-00 00:00:00' THEN '' ELSE date_format(PromoDateTo,'%m/%d/%Y') END AS PromoDateTo, ItemGroupID_FK,Status FROM tbl_RewardItems WHERE CardTypeID_FK=$cardtypeid AND ID=$itemid";

//	$query = "SELECT r.Item,r.ItemDescription,r.Amount,CASE WHEN PromoDateFrom='0000-00-00 00:00:00' THEN '' ELSE date_format(PromoDateFrom,'%m/%d/%Y') END AS PromoDateFrom,CASE WHEN PromoDateTo='0000-00-00 00:00:00' THEN '' ELSE date_format(PromoDateTo,'%m/%d/%Y') END AS PromoDateTo, ItemGroupID_FK,Status,c.Description AS CardType
//				FROM tbl_RewardItems r
//				LEFT JOIN tbl_CardTypes c ON c.ID=r.CardTypeID_FK
//				WHERE CardTypeID_FK=$cardtypeid AND r.ID=$itemid
//			";

	$qryrewarditems = mysql_query($query,$dbConnection) or die(mysql_error());

	if (mysql_num_rows($qryrewarditems) > 0)
	{
		$rowrewarditems = mysql_fetch_array($qryrewarditems);

		$itemtitle = $rowrewarditems["Item"];
		$itemdesc = $rowrewarditems["ItemDescription"];
		$amount = $rowrewarditems["Amount"];

		if (!empty($rowrewarditems["PromoDateFrom"]))
			$validfrom = date("Y/m/d",strtotime($rowrewarditems["PromoDateFrom"]));
		else
			$validfrom = "";

		if (!empty($rowrewarditems["PromoDateTo"]))
			$validto = date("Y/m/d",strtotime($rowrewarditems["PromoDateTo"]));
		else
			$validto = "";
		
		$itemgroupid = $rowrewarditems["ItemGroupID_FK"];
		$status = $rowrewarditems["Status"];

	}
	else
	{
//		echo "<script>alert('" . $rowviewprofile["StatusDesc"]  . "');</script>";
	}

	mysql_close($dbConnection);
}



//$mode = 1;

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>PeGS Loyalty Program - <?php echo $page; ?></title>
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="js/pegsloyalty.js"></script>

		<script language="javascript" type="text/javascript">

		function checkitems()
		{
			
			var rem = '';
			with (document.frmRewardsMgmt)
			{
				if(itemtitle.value=='' || itemtitle.value==null)
				{
					if(rem=='')
						{rem = 'Item Title';}
					else
						{rem = rem + ',Item Title';}
				}
				if(amount.value=='' || amount.value==null)
				{
					if(rem=='')
						{rem = 'Required Points';}
					else
						{rem = rem + ',Required Points';}
				}
				if(status.selectedIndex <= 0)
				{
					if(rem=='')
						{rem = 'Reward Status';}
					else
						{rem = rem + ',Reward Status';}
				}

				if (rem == '')
				{
					if (btnaction.value == 'Add Reward Item')
						{
							actiontxt.value = 'additem';
						}
					else
						{
							actiontxt.value = 'updateitem';
						}

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
    <body onload="<?php if (empty($mode)) { echo "document.frmRewardsMgmt.cardtypeid.select();"; } else { echo "document.frmRewardsMgmt.itemtitle.select();"; } ?>">

		<form name="<?php echo $formname; ?>" id="<?php echo $formname; ?>" method="POST">

			<?php include("header.php"); ?>
			<?php include("menu.php"); ?>
			<?php include("message.php"); ?>


			<div class="maincontent">

				<br /><br />

	<?php if (empty($mode)) { // Display ALL Reward Items ?>

				<table width="350" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td class="infotabledetailtitle">Select Card Type</td>
						<td>
							<select name="cardtypeid" onchange="submit();">
								<option value="">-- Select One --</option>
							<?php
								$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
								$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

								$query = "SELECT ID, Description FROM tbl_CardTypes";
								$qrygetcardtypes = mysql_query($query,$dbConnection);

								while ($rowgetcardtypes = mysql_fetch_array($qrygetcardtypes))
								{
									$cid = $rowgetcardtypes["ID"];
									$cdesc = $rowgetcardtypes["Description"];
							?>
								<option <?php if ($cid == $cardtypeid) {echo "SELECTED";} ?> value="<?php echo $cid; ?>"><?php echo $cdesc; ?></option>

							<?php
								}

								mysql_close($dbConnection);
							?>
							</select>
						</td>
					</tr>
				</table>

				<br />

				<table width="800" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td colspan="4" class="sectiontitle">PREVIEW OF ALL REWARD ITEMS AVAILABLE</td>
					</tr>
					<?php

						$dbConnection = mysql_connect($dbHost, $dbUser, $dbPass, false, 65536) or die("Cannot connect to the host");
						$dbSelect = mysql_select_db($dbName, $dbConnection) or die("Cannot connect to the database");

						if (!empty($cardtypeid))
						{
							$query = "SELECT r.ID,r.Item,r.ItemDescription,r.Amount,r.CardTypeID_FK,c.Description AS CardType,Status
										FROM tbl_RewardItems r
										LEFT JOIN tbl_CardTypes c ON c.ID=r.CardTypeID_FK
										WHERE r.CardTypeID_FK = $cardtypeid";

							$disabled_edit = 1;
						}
						else
						{
							$query = "SELECT r.ID,r.Item,r.ItemDescription,r.Amount,r.CardTypeID_FK,c.Description AS CardType,Status
										FROM tbl_RewardItems r
										LEFT JOIN tbl_CardTypes c ON c.ID=r.CardTypeID_FK
										ORDER BY r.CardTypeID_FK";

							$disabled_edit = 0;
						}

						$qryitems = mysql_query($query,$dbConnection) or die(mysql_error());

						$tmpgroupid = "";
						while ($rowitems = mysql_fetch_array($qryitems))
						{
							$iid = $rowitems["ID"];
							$itm = $rowitems["Item"];
							$desc = $rowitems["ItemDescription"];
							$iamt = $rowitems["Amount"];
							$istat = $rowitems["Status"];
							$igroupid = $rowitems["CardTypeID_FK"];
							$igroup = $rowitems["CardType"];

							if ($istat == "I") //Inactive
								$showinactive = "<br /> <span class=\"BoldRed\">(INACTIVE)</span>";
							else
								$showinactive = "";

							if ($tmpgroupid != $igroupid)
							{ ?>
								<tr><td colspan="4">&nbsp;</td></tr>
								<tr>
									<td colspan="4" class="infotablegrouping"><?php echo $igroup; ?> CARD ITEMS</td>
								</tr>
								<tr>
									<th width="30%">ITEM</th>
									<th width="50%">DESCRIPTION</th>
									<th width="10%">POINTS</th>
									<th width="10%">---</th>
								</tr>
					<?php   } ?>

							<tr <?php echo $mouseovereffect; ?> >
								<td valign="middle" align="center"><?php echo $itm; ?></td>
								<td valign="middle" align="center"><?php echo $desc; ?></td>
								<td valign="middle" align="center"><?php echo $iamt . $showinactive; ?></td>
								<td valign="middle" align="center"> &nbsp;
								<?php if ($disabled_edit == 1) { ?>
									<input type="button" value="Edit" class="btnRedeem" onclick="mode.value='EDIT';itemid.value='<?php echo $iid; ?>';submit();" />
								<?php } ?>
								</td>
							</tr>

					<?php  $tmpgroupid = $igroupid;
						}

						mysql_close($dbConnection);
					?>

						<tr><td>&nbsp;</td></tr>

					<?php if ($disabled_edit == 1) {	?>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td colspan="2" align="center"><input type="button" class="btnDisable" value="Add Reward Item" onclick="mode.value='ADD';submit();" /></td>
						</tr>
				<?php } ?>

				</table>

		<?php }  //End Display Mode...
		else {
					// Start of Add/Edit Mode
		?>

				<center>
					<table width="600" cellpadding="5" cellspacing="0" class="infotable">
						<tr>
							<th class="sectiontitle" colspan="2"><?php echo $mode ?> REWARDS ITEM</th>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td class="infotabledetailtitlealternate">Card Type</td>
							<td><input type="text" name="x" value="<?php echo $cardtype; ?>" style="width:83%;border:none;font-size:1em;font-weight:Bold;text-align:center;color:Black;background-color:<?php echo $cardtype; ?>"</td>
						</tr>
						<tr>
							<td class="infotabledetailtitle">Item Title *</td>
							<td><input type="text" name="itemtitle" size="30" maxlength="50" onkeypress="return alphanumericonly(this,event);" value="<?php echo $itemtitle; ?>" /></td>
						</tr>
						<tr>
							<td class="infotabledetailtitlealternate">Description</td>
							<td><textarea cols="39" rows="4" name="itemdesc" ><?php echo $itemdesc; ?></textarea></td>
						</tr>
						<tr>
							<td class="infotabledetailtitle">Required Points *</td>
							<td><input type="text" name="amount" size="10" maxlength="10" onkeypress="return numericOnly(event);" value="<?php echo $amount; ?>" /></td>
						</tr>
<!--
						<tr>
							<td>Valid From Date</td>
							<td><input type="text" name="validfrom" size="10" maxlength="10" value="<?php echo $validfrom; ?>" onKeyPress="return dateonly(this, event);"  />(YYYY/MM/DD)</td>

						</tr>
						<tr>
							<td>Valid To Date</td>
							<td><input type="text" name="validto" size="10" maxlength="10" value="<?php echo $validto; ?>" onKeyPress="return dateonly(this, event);" />(YYYY/MM/DD)</td>
						</tr>
						-->
						<tr>
							<td class="infotabledetailtitlealternate">Reward Status *</td>
							<td><select name="status">
									<option value="">-- Select --</option>
									<option <?php if($status == 'A') {echo "SELECTED";} ?> value="A">Active</option>
									<option <?php if($status == 'I') {echo "SELECTED";} ?> value="I">Inactive</option>
								</select>
							</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td colspan="2" align="center">
								<?php if(empty($itemid)) { ?>
								<input type="button" name="btnaction" class="btnDisable" value="Add Reward Item" 
										onclick="if (amount.value != '') { if(confirm('Add Reward item?')) {checkitems();} } else { alert('Please indicate Required Points.'); }" />
								<?php } else { ?>
								<input type="button" name="btnaction" class="btnDisable" value="Update Reward Item" onclick="if (amount.value != '') {
										if(confirm('Update Reward item?')) {checkitems();}
									} else { alert('Please indicate Required Points.'); }" />
								<?php } ?>

								<input type="button" value="Cancel" class="btnDisable" onclick="itemid.value='';mode.value='';submit();" />
							</td>
						</tr>

					</table>
				</center>

				<input type="hidden" name="cardtypeid" value="<?php echo $cardtypeid; ?>" />

	<?php }	?>
				
			</div>   <!-- End DIV of #maincontent  -->

		<input type="hidden" name="actiontxt" />
		<input type="hidden" name="mode" />
		<input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
		
		</form>

<?php include("footer.php"); ?>

<?php } else {

	force_logout($sessionID);
	header("Location: index.php?mess=Access Denied! Please login as Administrator.");

}?>