<?php $page = "Login"; ?>
<?php $formname = "frmLogin"; ?>
<?php include("checksession.php"); ?>

<?php

if (isset($_GET["mess"]))
	$message = $_GET["mess"];
else
	$message = "";

$errorcolor = "error";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>PEGS Loyalty Program - <?php echo $page; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="css/styles.css">
		<script language="javascript" type="text/javascript" src="js/login.js"></script>
		<script language="javascript" type="text/javascript" src="js/pegsloyalty.js"></script>
    </head>

    <body onload="document.frmLogin.txtUsername.select();">

		<form id="<?php echo $formname; ?>" method="post" name="<?php echo $formname; ?>" action="login_logout.php">

		<?php include("header.php"); ?>
		<?php include("menu.php"); ?>
		<?php include("message.php"); ?>

			<div id="maincontent">

				<br /><br /><br /><br /><br />
				<center>
				<table width="300" cellpadding="5" cellspacing="0" class="infotable">
					<tr>
						<td colspan="2" class="infotablegrouping">LOGIN</td>
					</tr>
					<tr>
						<td>Username</td>
						<td><input type="text" name="txtUsername" id="txtUsername" maxlength="20" size="10" onkeypress="return smallcapsOnly(event);" onBlur="chkUsername();" /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="txtPassword" id="txtPassword" maxlength="100" size="10" onBlur="chkPassword();"/></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" id="btnLogin" name="btnLogin" class="btnRedeem" value="Login" onClick="return chkSubmit();"/></td>
					</tr>
				</table>

				</center>
				<br /><br /><br /><br /><br />
			</div>

			</form>


<?php include("footer.php"); ?>