        <div align="center">
            <div class="mainbody">
                <div class="topheader">
                    <div class="welcomesignout">
						
						<?php if(!empty($sessionID) && $page != "Loyalty Card Registration") {  ?>
							<br />
							Welcome &nbsp;&nbsp; <span style="color:Blue;font-size:1.2em;" title="<?php echo $sessionID; ?>"> <?php echo $sid_user; ?> </span>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

							<a href="login_logout.php" style="color:Red;" >Sign Out</a>

						<?php } ?>

                    </div>
                </div>


