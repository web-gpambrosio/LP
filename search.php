
<?php 

switch ($searchby)
{
	case "1": $searchinstructions = "Please scan barcode"; $search_inputtype = "password"; break;
	case "2": $searchinstructions = "Please enter Card No."; $search_inputtype = "text"; break;
	case "3": $searchinstructions = "Please enter Username"; $search_inputtype = "text"; break;
}



?>

		<script language="javascript" type="text/javascript">

			function goreset()
			{
				with (document.<?php echo $formname; ?>)
				{
					searchkey.value='';
					
					if ('<?php echo $formname; ?>' == 'frmRedemption')
						itemid.value='';

					submit();
				}
			}

		</script>


                <div class="searchbox">
                    <table width="100%" cellpadding="5" cellspacing="0">
                        <tr>
                            <td width="440">
                                <div class="searchinstruction"><?php echo $searchinstructions; ?></div>
                                <div class="searchboxandbutton">
                                    <input type="<?php echo $search_inputtype; ?>" id="txtSearchBox"  name="searchkey" id="searchkey" maxlength="21" class="txtSearchBox" align="absmiddle" value="<?php echo $searchkey; ?>">
                                    <input type="submit" class="btnSearch" value="Search" align="absmiddle" onclick="if (document.<?php echo $formname; ?>.searchkey.value != '') {document.<?php echo $formname; ?>.submit();} else {alert('Please specify Search Key.'); }">
                                    <input type="button" class="btnSearch" value="Clear" align="absmiddle" onclick="goreset();">
                                </div>
                                <div class="searchlinkdiv">
                                    Search by:

									<?php

									$barcodelink = "document.$formname.searchkey.value='';document.$formname.searchby.value='1';document.$formname.submit();";
									$cardnolink = "document.$formname.searchkey.value='';document.$formname.searchby.value='2';document.$formname.submit();";
									$usernamelink = "document.$formname.searchkey.value='';document.$formname.searchby.value='3';document.$formname.submit();";

									if ($searchby_barcodeonly == "1")  //Rewards Redemption
									{
										$searchby = 1;
										$cardnolink = "";
										$usernamelink = "";

										echo "<span class=\"selectedsearchlink\">Barcode</span> &nbsp; <i>(Scan Only!)</i>";
									}
									else
									{
										switch($searchby)
										{
											case "1": //Barcode
											?>
												<span class="selectedsearchlink">Barcode</span> | <a href="#" onclick="<?php echo $cardnolink; ?>" class="searchlink">Card Number</a> | <a href="#" onclick="<?php echo $usernamelink; ?>" class="searchlink">Username</a>
											<?php
											break;
											case "2": //Card Number
											?>
												<a href="#" onclick="<?php echo $barcodelink; ?>" class="searchlink">Barcode</a> | <span class="selectedsearchlink">Card Number</span> | <a href="#" onclick="<?php echo $usernamelink ?>" class="searchlink">Username</a>
											<?php
											break;
											case "3": //Username
											?>
												<a href="#" onclick="<?php echo $barcodelink; ?>" class="searchlink">Barcode</a> | <a href="#" onclick="<?php echo $cardnolink; ?>" class="searchlink">Card Number</a> | <span class="selectedsearchlink">Username</span>
											<?php
											break;
										}
									}

									?>

                                </div>
                            </td>
                            <td width="200">
                                <div class="usernametitle">Username</div>
                                <span class="username"><?php echo $username; ?></span>
								<hr />
                                <div class="cardtypediv <?php if($cardtype=="GREEN") { echo "greencardtype";} elseif($cardtype=="GOLD") { echo "goldcardtype";} ?>"><?php if (!empty($cardtype)) {echo $cardtype;} else{echo "&nbsp;";} ?></div>
                            </td>
                            <td width="140">
                                <div class="currentpointscontainer">
                                    Current Points
                                    <div class="currentpoints"><?php echo $points_current; ?></div>
                                </div>
                            </td>

                        </tr>
                    </table>
				</div>  <!-- End of searchbox -->

				<input type="hidden" name="searchby" value="<?php echo $searchby; ?>" />
				