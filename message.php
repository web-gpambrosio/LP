<?php

switch ($errorcolor)
{
	case "error" : $bgcolor = "background-color:Red;"; break;
	case "success" : $bgcolor = "background-color:Green;"; break;
}


if (!empty($message)) {
?>

<div id="message" style="<?php echo $bgcolor; ?>" >

	<?php echo $message; ?>

</div>

<?php } ?>

