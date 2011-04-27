<?php include("include/mouseovereffect.inc"); ?>

<div id="Menu">

<?php  ?>

	<table width="900" cellpadding="0" cellspacing="0" class="mainmenu">
		<tr>

		<?php

		if(!empty($sessionID)) {
			
			switch ($sid_type)
			{
				case "A": // Admin  ?>
					
					
					<td class="<?php if ($page == "Points Inquiry") { echo "selectedmenuitem"; } else { echo "menuitem";} ?> ">
						<a href="PlayerProfile.php">Points Inquiry</a>
					</td>
					<td class="<?php if ($page == "Points Transfer") { echo "selectedmenuitem"; } else { echo "menuitem";} ?> ">
						<a href="PointsTransfer.php">Points Transfer</a>
					</td>
					<td class="<?php if ($page == "Rewards Management") { echo "selectedmenuitem"; } else { echo "menuitem";} ?> ">
						<a href="RewardsManagement.php">Rewards Management</a>
					</td>
					<td class="<?php if ($page == "Reports") { echo "selectedmenuitem"; } else { echo "menuitem";} ?> ">
						<a href="StatisticalReports.php">Reports</a>
					</td>
		<?php
				break;
				case "C": // Cashier  ?>
					
					<td class="<?php if ($page == "Points Inquiry") { echo "selectedmenuitem"; } else { echo "menuitem";} ?> ">
						<a href="PlayerProfile.php">Points Inquiry</a>
					</td>
					<td class="<?php if ($page == "Rewards Redemption") { echo "selectedmenuitem"; } else { echo "menuitem";} ?> ">
						<a href="Redemption.php">Rewards Redemption</a>
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
		<?php
				break;
			}

		}
	?>

		</tr>
	</table>

<?php  ?>

</div>
