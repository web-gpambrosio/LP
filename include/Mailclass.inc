<?php
//require_once($_SERVER['DOCUMENT_ROOT']."/phpmailer/class.phpmailer.php");
require_once("phpmailer/class.phpmailer.php");

class LPMailer extends PHPMailer
{
    var $priority = 3;
    var $to_name;
    var $to_email;
    var $From;
    var $FromName;
    var $Sender;
  
    function LPMailer()
    {
      global $site; 
      
      // Comes from email_config.php $site array
      
      if($site['smtp_mode'] == "enabled")
      {
//		$this->PluginDir = $site['smtp_plugindir'];
        $this->Host = $site['smtp_host'];
        $this->Port = $site['smtp_port'];
        if($site['smtp_username'] != '')
        {
//         $this->SMTPAuth  = true;
//		 $this->SMTPSecure = "tls";
         $this->Username  = $site['smtp_username'];
         $this->Password  =  $site['smtp_password'];
        }
//        $this->Mailer = "smtp";
      }
      if(!$this->From)
      {
        $this->From = $site['from_email'];
      }
      if(!$this->FromName)
      {
        $this->FromName = $site['from_name'];
      }
      if(!$this->Sender)
      {
        $this->Sender = $site['from_email'];
      }
      $this->Priority = $this->priority;
    }
}
?> 