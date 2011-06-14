<?php
global $systemConfiguration;
global $logger;
global $language_selected;
$logger->LogInfo(__FILE__);
?>
			<div id="footer" class="clear">
                <div class="copy">
                    <table width="100%">
                        <tr>
                            <td style="width: 430px;">
                                <?= $systemConfiguration->getHotelDetails()->getHotelAddress() . ", " . $systemConfiguration->getHotelDetails()->getHotelCity() ." &#45; " . $systemConfiguration->getHotelDetails()->getHotelCountry() ?> 
                                (<a href="http://maps.google.com/?q=Lošinjska%2026,%20Labin,%20Croatia" target="_blank"><?=FOOTER_DIRECTIONS?></a>)
                            </td>
                            <td>
                                <?= FOOTER_PHONE ?>: <?= $systemConfiguration->getHotelDetails()->getHotelPhone() ?>
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
	                            $pages = PageContents::fetchFromDbActiveForParent(0);
								if ($pages == null)
								{
									die (PageContents::$staticErrors);
								}    
								$separator="";
								foreach ($pages as $page) 
								{
									if (!($page instanceof PageContents))
									{
										continue;	
									}
									echo $separator;
									echo '<a href="' . $page->getUrl() . '">' . $page->title->getText($language_selected) . '</a>' . "\n";
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
                                <a href="http://www.twitter.com" target="_blank"><img alt="Twitter Icon" style="margin: 0 0 0 10px;" src="images/icon_twitter.png" /></a>
                                <a href="http://www.facebook.com/pages/Villas-Rabac" target="_blank"><img alt="Facebook Icon" style="margin: 0 0 0 10px;" src="images/icon_facebook.png"/></a>
                                <a href="http://therabac.tumblr.com" target="_blank"><img alt="Tumblir Icon" style="margin: 0 0 0 10px;" src="images/icon_tumblir.png"/></a>
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