<?php

//if (isset($_SESSION["message"]))
//{
//	$message = $_SESSION["message"];
//	$messagecolor = $_SESSION["messagecolor"];
//}
//else
//{
//	$message = "";
//	$messagecolor = "";
//}


//unset ($_SESSION["message"]);
//unset ($_SESSION["messagecolor"]);

switch ($messagetype)
{
	case "0" : $bgcolor = "background-color:Red;"; break;
	case "1" : $bgcolor = "background-color:Green;"; break;
}


if (!empty($message)) {
?>

<div id="message" style="<?php echo $bgcolor; ?>" >

	<?php echo $message; ?>

</div>

<?php } ?>

