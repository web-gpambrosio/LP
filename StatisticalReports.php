<?php $page = "Reports"; ?>
<?php $formname = "frmReports"; ?>
<?php include("checksession.php"); ?>

<?php if ($sid_type == "A") { ?>

<?php // include("StatisticalReports.logic.php");

$checked = "checked='checked'";
$disabled = "disabled='disabled'";

if (isset($_POST["actiontxt"]))
	$actiontxt = $_POST["actiontxt"];

if (isset($_POST["reportsmode"]))
	$reportsmode = $_POST["reportsmode"];
else
	$reportsmode = 1;

$tmpchk = "chkmode" . $reportsmode;
$$tmpchk = $checked;

if (isset($_POST["category"]))
	$category = $_POST["category"];


switch($category)
{
	case "1" : $report_title = "Card Type"; break;
	case "2" : $report_title = "Gender"; break;
	case "3" : $report_title = "Age Ratio"; break;
	case "4" : $report_title = "Ethnicity"; break;
	case "5" : $report_title = "Occupation"; break;
	case "6" : $report_title = "Smokers/Non-smokers"; break;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>PeGS Loyalty Program - <?php echo $page; ?></title>
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" language="javascript" src="js/statReport.js"></script>
        <script language="javascript" type="text/javascript" src="js/pegsloyalty.js"></script>
    </head>
    <body onload="">

		<form name="<?php echo $formname; ?>" id="<?php echo $formname; ?>" method="POST">

			<?php include("header.php"); ?>
			<?php include("menu.php"); ?>
			<?php include("message.php"); ?>


			<div class="maincontent">
				<br />
				<center>
					<table width="700" cellpadding="5" cellspacing="0" class="infotable">
						<tr>
							<td colspan="3" align="center" class="sectiontitle">
								<input type="radio" name="reportsmode" id="reportsmode1" <?php echo $chkmode1; ?> value="1" onclick="category.value='';submit();" /><label for="reportsmode1">Points Summary</label>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="reportsmode" id="reportsmode2" <?php echo $chkmode2; ?> value="2" onclick="category.value='';submit();" /><label for="reportsmode2">Head Count per Category</label>
							</td>
						</tr>
						<tr><td colspan="3">&nbsp;</td></tr>
						<tr>
							<td colspan="2" class="infotabledetailtitle">Select Category: &nbsp;&nbsp;
						<?php
							$tmpselect = "catselected" . $category;
							$$tmpselect = "SELECTED";
						?>
								<select name="category" onchange="if(this.value != '') {document.getElementById('btnSubmit').disabled=false;} else {document.getElementById('btnSubmit').disabled=true;}">
									<option value="">-- Select Report Category --</option>
									<option <?php echo $catselected1; ?> value="1">Card Type</option>
									<option <?php echo $catselected2; ?> value="2">Gender</option>
									<option <?php echo $catselected3; ?> value="3">Age Ratio</option>
									<option <?php echo $catselected4; ?> value="4">Ethnicity</option>
									<option <?php echo $catselected5; ?> value="5">Occupation</option>
									<option <?php echo $catselected6; ?> value="6">Smokers/Non-smokers</option>
								</select>
							</td>
							<td align="center">
								<input type="submit" name="btnSubmit" id="btnSubmit" class="btnRedeem" disabled="disabled" value="View Report" onclick="submit();" />
							</td>
						</tr>
						<tr><td colspan="3">&nbsp;</td></tr>

		<?php if (!empty($category)) {

					if ($reportsmode == 1) { // Points Summary  ?>

						<tr>
							<td colspan="3">
								<table width="700" cellpadding="5" cellspacing="0" class="infotable">
									<tr>
										<td colspan="4" class="infotablegrouping"><?php echo $report_title;?></td>
									</tr>
									<tr>
										<th>CATEGORY</th>
										<th>LIFETIME POINTS</th>
										<th>REDEEMED POINTS</th>
										<th>CURRENT POINTS</th>
									</tr>
									<?php

									include ("connectionstring.php");

									$dbConnection = mysql_connect($dbHost,$dbUser,$dbPass,false,65536) or die("Cannot connect to the host");
									$dbSelect = mysql_select_db($dbName,$dbConnection) or die("Cannot connect to the database 9");
									$query_points = "CALL sp_AdminStatReport('$sessionID','$category','$reportsmode')";
									$result_points = mysql_query($query_points) or die(mysql_error());

									if (mysql_num_rows($result_points) == 1) 
									{
										$query_result = mysql_fetch_array($result_points);
										
										$message = $query_result['StatusDesc'];
										$statno = $query_result['StatusNo'];

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
//											$errorcolor = "success";
										}
									}
									else
									{
										$tot_lifetime = 0;
										$tot_redeem = 0;
										$tot_current = 0;
										while($query_result = mysql_fetch_array($result_points))
										{

											$message = $query_result['StatusDesc'];
											$statno = $query_result['StatusNo'];

											if ($statno > 0)
											{
												$errorcolor = "error";
												if (($statno == "1") || ($statno == "2")) //Session Timeout and Session ID not found
												{
													force_logout($sessionID);
													header("Location: index.php?mess=$message");
												}
											}
											else
											{
												$rep_desc = $query_result['Description'];
												$rep_lifetimepts = $query_result['LifeTimePoints'];
												$rep_redeempts = $query_result['PointsRedeemed'];
												$rep_currentpts = $query_result['CurrentPoints'];

												$tot_lifetime += $rep_lifetimepts;
												$tot_redeem += $rep_redeempts;
												$tot_current += $rep_currentpts;


												echo"<tr $mouseovereffect>";
													echo"<td>". $rep_desc ."</td>";
													echo"<td align=\"center\">". $rep_lifetimepts ."</td>";
													echo"<td align=\"center\">". $rep_redeempts ."</td>";
													echo"<td align=\"center\">". $rep_currentpts ."</td>";
												echo"</tr>";
											}
										}
									}

									mysql_close($dbConnection);

									?>

									<tr>
										<th>Total Points</th>
										<th align="center"><?php echo $tot_lifetime; ?></th>
										<th align="center"><?php echo $tot_redeem; ?></th>
										<th align="center"><?php echo $tot_current; ?></th>
									</tr>
								</table>

							</td>
						</tr>

					<?php }
					else
					{  //Head count Reports ?>

						<tr>
							<td colspan="3">
								<table width="700" cellpadding="5" cellspacing="0" class="infotable">
									<tr>
										<td colspan="2" class="infotablegrouping"><?php echo $report_title;?></td>
									</tr>
									<tr>
										<th>CATEGORY</th>
										<th>HEAD COUNT</th>
									</tr>
									<?php

									include ("connectionstring.php");
									
									$dbConnection = mysql_connect($dbHost,$dbUser,$dbPass,false,65536) or die("Cannot connect to the host");
									$dbSelect = mysql_select_db($dbName,$dbConnection) or die("Cannot connect to the database 5");
									$query_points = "CALL sp_AdminStatReport('$sessionID','$category','$reportsmode')";
									$result_points = mysql_query($query_points) or die(mysql_error());

//									if (mysql_num_rows($result_points) == 1)
//									{
//										$query_result = mysql_fetch_array($result_points);
//
//										$message = $query_result['StatusDesc'];
//										$statno = $query_result['StatusNo'];
//
//										if ($statno > 0)  // Error
//										{
//											$errorcolor = "error";
//
//											if (($statno == "1") || ($statno == "2")) //Session Timeout and Session ID not found
//											{
//												if (force_logout($sessionID))
//													header("Location: index.php?mess=$message");
//											}
//										}
//										else
//										{
////											$errorcolor = "success";
//										}
//									}
//									else
//									{
										$total_cnt = 0;
										while($query_result = mysql_fetch_array($result_points))
										{

											$message = $query_result['StatusDesc'];
											$statno = $query_result['StatusNo'];

											if ($statno > 0)
											{
												$errorcolor = "error";
												if (($statno == "1") || ($statno == "2")) //Session Timeout and Session ID not found
												{
													force_logout($sessionID);
													header("Location: index.php?mess=$message");
												}
											}
											else
											{
												$rep_desc = $query_result['Description'];
												$rep_count = $query_result['HC'];

												$total_cnt += $rep_count;

//												if (empty($rep_desc))
//													$rep_desc = "< Not Specified >";

												echo"<tr $mouseovereffect>";
												echo"<td>". $rep_desc ."</td>";
												echo"<td align=\"center\">". $rep_count ."</td>";
												echo"</tr>";
											}
										}
//									}

									mysql_close($dbConnection);

									?>

									<tr>
										<th>Total Count</th>
										<th align="center"><?php echo $total_cnt; ?></th>
									</tr>

								</table>

							</td>
						</tr>

					<?php } ?>
			<?php } ?>

					</table>

				</center>

			</div>  <!-- End DIV of #maincontent  -->

		</form>

<?php include("footer.php"); ?>

<?php } else {
	force_logout($sessionID);
	header("Location: index.php?mess=Access Denied! Please login as Administrator.");

}?>