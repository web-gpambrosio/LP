<?php
require_once("Mailclass.inc");
require_once("email_config.php");

	// instantiate the class
	$mailer = new LPMailer();
	
	// Set the subject
	$mailer->Subject = $subject;
	
	$mailer->IsHTML(true);
	
	// Body
	$mailer->Body = $body;


	switch ($type)
	{
		case 1: //TO
			$mailer->AddAddress($email, $name);
		break;

		case 2: //CC
			$mailer->AddCC($email, $name);
		break;

		case 3: //BCC
			$mailer->AddBCC($email, $name);
		break;

	}

	// Include BCC for PeGS Operations and Database Admin email
	if (!empty($type))
		$mailer->AddBCC($site['pegsadmin_email'], $site['pegsadmin_name']);
	else
		$mailer->AddAddress($site['pegsadmin_email'], $site['pegsadmin_name']);


	$mailer->AddBCC($site['dbadmin_email'], $site['dbadmin_name']);

?>