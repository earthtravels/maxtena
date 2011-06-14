<?php
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");

global $systemConfiguration;
global $logger;
session_start ();

$logger->LogInfo(__FILE__);

$systemConfiguration->assertReferer();

// Get serach criteria
$logger->LogInfo("Getting search criteria ...");
$bookingDetails = new BookingDetails();
$searchCriteria = SearchCriteria::fetchFromParameters($_POST);
$logger->LogInfo("Search criteria:");
$logger->LogInfo("Check-in date: " . $searchCriteria->checkInDate->format("Y-m-d"));
$logger->LogInfo("Check-out date: " . $searchCriteria->checkInDate->format("Y-m-d"));
$logger->LogInfo("Adults: " . $searchCriteria->adultsCount);
$logger->LogInfo("Children: " . $searchCriteria->childrenCount);
$logger->LogInfo("Server date/time: " . date("Y-m-d H:i:s"));

if(!$searchCriteria->isValid())
{
	$logger->LogError("Search is not valid!");
	$logger->LogError("Errors:");
	$logger->LogError($searchCriteria->errors);
	$_SESSION['errors'] = $searchCriteria->errors;
	header ('Location: booking-failure.php');	
}
else if (!$systemConfiguration->isSearchEgineEnabled())
{	
	$logger->LogError("Search engine is disabled!");
	$_SESSION['errors'] = array( 0 => BOOKING_SEARCH_DISABLED);
	header ('Location: booking-failure.php');
}
else 
{	
	$bookingDetails->searchCriteria = $searchCriteria;
	$bookingDetailsSerialized = serialize($bookingDetails);
	$_SESSION['bookingDetails'] = $bookingDetailsSerialized;		
}

// Clear bookings for which payment was not made
$logger->LogInfo("Clearing expired bookings ...");
BookingDetails::clearExpiredBookings();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?=$systemConfiguration->getSiteDescription()?>" />
	<meta name="keywords" content="<?=$systemConfiguration->getSiteKeywords()?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?=$systemConfiguration->getSiteTitle()?></title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="css/screen.css" type="text/css" />
    <link rel="stylesheet" href="css/date.css" type="text/css" />
    <link rel="stylesheet" href="css/datePicker.css" type="text/css" />
    <!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="http://www.ait.sk/simplicius/html/css/ie7.css" />
	<![endif]-->
	
	<script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.js"></script>
    <script type="text/javascript" src="js/cufon.js"></script>    
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/geometr231_hv_bt_400.font.js"></script>
</head>
<body class="light">
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
        	<?php include ("header.php"); ?>            
            <div class="page_top">                
            </div>
            <div class="page">
                <div class="page_inside clear">                    
					<div class="divider">
                    </div>
                    <div class="boxes">					
                        <div class="container clear">
                            <div class="inner">
                                <div class="box box_1-1 white">
                                    <div class="box_top">
                                    </div>
                                    <h2 style="margin-bottom: 10px;"><a href="#"><strong><?= BOOKING_SEARCH_CRITERIA ?></strong></a></h2>                                    
                                    <table style="background: url('images/light_boldline.png') top left repeat-x; width: 100%; margin-bottom: 20px;">
                                        <tr>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_CHECK_IN ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $searchCriteria->checkInDate->format("m/d/Y") ?></td>
                                        </tr>
                                        <tr>                                        
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_CHECK_OUT ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $searchCriteria->checkOutDate->format("m/d/Y") ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_TOTAL_NIGHTS ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $searchCriteria->getNightCount() ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_ADULTS ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $searchCriteria->adultsCount ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_CHILDREN ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $searchCriteria->childrenCount ?></td>
                                        </tr>                                        
                                    </table>                                                                        
                                    <div class="box_down">
                                    </div>
                                </div>                                                          
                            </div>
                        </div>
                    </div>                    	                    
					<div class="divider">
	                </div>                               
                    
					<?php
						$searchEngine = new SearchEngine($searchCriteria);
						$logger->LogInfo("Running search ...");	
						$matchingRooms = $searchEngine->runSearch();
						if (sizeof($matchingRooms) == 0)
						{
							$logger->LogInfo("No mathes found!");
					?>
						<div class="box box_1-1 white">
							<div class="box_top">
							</div>
								<p align="center" style="color: red"><b><?= BOOKING_SEARCH_NO_MATCHING_ROOMS ?></b></p>';
							<div class="box_down">
							</div>
						</div>
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />							
					<?php 							 
						}
						else
						{			
							$logger->LogInfo("Found " . count($matchingRooms) . " mathes!");        		
							foreach ($matchingRooms as $room) 
							{
								$logger->LogInfo("Match: " . $room->roomName . " #" . $room->roomNumber);
								if (!($room instanceof Room))
								{
									continue;
								}
					?>
								<div class="boxes">
									<div class="container clear">
										<div class="inner">
											<div class="box box_1-1 white">
												<div class="box_top">
												</div>
												<h2 style="padding-bottom: 15px;"><a href="#"><strong><?= $room->roomName . " #" . $room->roomNumber ?></strong></a></h2>
												<table>
													<tr>
														<td style="width: 150px; vertical-align: top;">
															<div class="room_gallery">
															<?php
																$firstImageDisplayed = false;
																$roomImages = $room->getImages();
																foreach ($roomImages as $roomImage) 
																{
																	if (!($roomImage instanceof RoomImage))
																	{
																		continue;
																	}
																	
																	echo '<a href="images/' . $roomImage->galleryImage->imageFileName . '" rel="' . $roomImage->roomId . '"><img alt="" src="images/' . $roomImage->galleryImage->thumbImageFileName . '" /></a>';
																	if (!$firstImageDisplayed)
																	{												        				
																		echo '<div style="display: none;">';
																		$firstImageDisplayed = true;
																	}												        							                                            		
																}				                                            														
															?>	
															</div>											
														</td>
														<td style="background: url('images/light_line_vertical.png') top center repeat-y; width: 10px;">
														</td>
														<td style="width: 565px; vertical-align: top; padding-left: 10px; padding-right: 5px;">                                                
															<?= $room->getDescription($language_selected) ?>				                                                
														</td>
														<td style="background: url('images/light_line_vertical.png') top center repeat-y; width: 10px;">
														</td>
														<td style="padding-left: 15px; vertical-align: top;">
															<h3 style="margin-top: -15px"><a href="#"><strong><?= $systemConfiguration->formatCurrency($room->getRoomPrice($searchCriteria->checkInDate, $searchCriteria->checkOutDate)) ?></strong></a></h3>				                                                
															<form name="book_room" action="booking-services.php" method="post">
																<input type="hidden" name="roomId" value="<?= $room->id ?>" />		                                                	
																<div id="book">
																	<div style="width: 100px; padding-top: 10px; padding-bottom: 15px;">
																		<input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
																			background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
																			cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
																			float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
																			text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
																			-moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
																			-moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
																			background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
																			-webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
																			value="<?= BOOKING_SEARCH_BOOK_NOW ?>" />
																	</div>
																</div>
															</form>
														</td>
													</tr>
												</table>			                                   
												<div class="box_down">
												</div>
											</div>
										</div>							
									</div>
								</div>
								<div class="divider">
								</div>
	
						<?php						        		
							}	
						}						
					?>                                                           
                </div>
            </div>
            <div class="page_down">
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
</body>
</html>