<?php

// Configuration settings for My Site

// Email Settings
$site['from_name'] = "PeGS Loyalty Program"; // from email name
$site['from_email'] = "webitstest@gmail.com"; // from email address

$site['pegsadmin_name'] = "PeGS Operations"; // PeGS Operations email name (for Notifications in Card Registration and Rewards Redemption)
//$site['pegsadmin_email'] = "ammarcos@philweb.com.ph"; // PeGS Operations email address (for Notifications in Card Registration and Rewards Redemption)
$site['pegsadmin_email'] = "gino.ambrosio@yahoo.com.ph"; // PeGS Operations email address (for Notifications in Card Registration and Rewards Redemption)

$site['dbadmin_name'] = "ITS"; // Database Admin Email
$site['dbadmin_email'] = "gino.ambrosio@yahoo.com.ph"; // Database Admin Email

// Just in case we need to relay to a different server, 
// provide an option to use external mail server.
$site['smtp_mode'] = "enabled"; // enabled or disabled
$site['smtp_host'] = "smtp.gmail.com";
$site['smtp_port'] = 25;
$site['smtp_username'] = "webitstest@gmail.com";
$site['smtp_password'] = "p@ssw0rd2010!!";
?> 