			<div id="footer" class="clear">
                <div class="copy">
                    <table width="100%">
                        <tr>
                            <td style="width: 430px;">
                                <?= $bsiCore->config['conf_hotel_streetaddr'] . ", " . $bsiCore->config['conf_hotel_city'] ." &#45; " . $bsiCore->config['conf_hotel_country']?> 
                                (<a href="http://maps.google.com/?q=Lošinjska%2026,%20Labin,%20Croatia" target="_blank"><?=FOOTER_DIRECTIONS?></a>)
                            </td>
                            <td>
                                <?= FOOTER_PHONE ?>: <?= $bsiCore->config['conf_hotel_phone'] ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="contact.php"><?= FOOTER_CONTACT_US ?></a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="links" style="padding-top: 10px;">
                    <table width="100%">
                        <tr>
                            <td style="text-align: center;">
                            <?php
                            	$sql_parent=mysql_query("select * from bsi_site_contents where parent_id=0 and status='Y' order by ord");
                            	$separator="";
								while($row=mysql_fetch_assoc($sql_parent))
								{
										
									echo $separator;						
							?>									
									<a href="<?= $row['url'] ?>"><?= $row['title_'.$language_selected] ?></a>								
								 
							<?php
									$separator = '<span class="sep">|</span>';
								}								 
                            ?>                               
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="copy" style="padding-top: 10px;">
                    <table width="100%">
                        <tr>
                            <td style="width: 46%; vertical-align: middle; text-align: right;">
                                <?= FOOTER_FOLLOW_US ?>
                            </td>
                             <td align="left">
                                <a href="http://www.twitter.com"><img alt="Twitter Icon" style="margin: 0 0 0 10px;" src="images/icon_twitter.png" /></a>
                                <a href="http://www.facebook.com/pages/Villas-Rabac"><img alt="Facebook Icon" style="margin: 0 0 0 10px;" src="images/icon_facebook.png"/></a>
                                <a href="http://therabac.tumblr.com"><img alt="Tumblir Icon" style="margin: 0 0 0 10px;" src="images/icon_tumblir.png"/></a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="copy" style="padding-top: 10px;">
                    <table width="100%">
                        <tr>
                            <td style="width: 50%; text-align: center;">
                                <a href="#">&copy; <?= date("Y") ?> <?= FOOTER_ALL_RIGHTS_RESERVED ?>. RabacCroatia.com</a>
                            </td>                            
                        </tr>
                    </table>
                </div>
            </div>          