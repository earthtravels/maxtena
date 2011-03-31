<?php
session_start ();
include ("includes/db.conn.php");
include ("includes/language.php");
include ("includes/conf.class.php");
// TODO: Uncomment
//if (isset ($_SERVER['HTTP_REFERER']))
//{
//	if (!($_SERVER['HTTP_REFERER'] == $bsiCore->getweburl () . "index.php" || $_SERVER['HTTP_REFERER'] == $bsiCore->getweburl ()))	
//	{
//		header ('Location: booking-failure.php?error_code=9');
//	}
//}
//else
//{
//	header ('Location: booking-failure.php?error_code=9');
//}
$_SESSION['auth'] = $bsiCore->config['conf_license_key'];
date_default_timezone_set ($bsiCore->config['conf_hotel_timezone']);
include ("includes/search.class.php");
$bsisearch = new bsiSearch ();
$bsiCore->clearExpiredBookings ();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?=$bsiCore->config['conf_hotel_sitedesc']?>" />
	<meta name="keywords" content="<?=$bsiCore->config['conf_hotel_sitekeywords']?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?=$bsiCore->config['conf_hotel_sitetitle']?></title>
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
                    <div class="boxes">
                        <div class="container clear">
                            <div class="inner">
                                <div class="box box_1-1 white">
                                    <div class="box_top">
                                    </div>
                                    <h2 style="margin-bottom: 10px;"><a href="#"><strong><?= BOOKING_SEARCH_CRITERIA ?></strong></a></h2>                                    
                                    <table style="background: url('images/light_boldline.png') top left repeat-x; width: 100%; margin-bottom: 20px;">
                                        <tr>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= HOME_BOX_TOP_LEFT_CHECK_IN ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?=$bsisearch->checkInDate?></td>
                                        </tr>
                                        <tr>                                        
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= HOME_BOX_TOP_LEFT_CHECK_OUT ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?=$bsisearch->checkOutDate?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= HOME_BOX_TOP_LEFT_ADULT ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?=$bsisearch->adultsPerRoom?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= HOME_BOX_TOP_LEFT_CHILD ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?=$bsisearch->childPerRoom?></td>
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
                    <div class="boxes">
                        <div class="container clear">
                            <div class="inner">
			                    <form id="searchresult" method="post" action="booking-details.php">
							      	<input type="hidden" name="allowlang" id="allowlang" value="no" />     
							        <?php						
									foreach($bsisearch->roomType as $room_type)
									{
										foreach($bsisearch->multiCapacity as $capid=>$capvalues)
										{			
											$room_result = $bsisearch->getAvailableRooms($room_type['rtid'], $room_type['rtname'], $capid, $language_selected);
											if(intval($room_result['roomcnt']) > 0) 
											{
									?>
										<div class="box box_1-1 white">
                                    		<div class="box_top">
                                    		</div>
                                    		<h2 style="padding-bottom: 15px;"><a href="#"><strong><?= $room_type['rtname'] ?></strong></a></h2>
                                    		<table>
		                                        <tr>
		                                            <td style="width: 150px; vertical-align: top;">
		                                                <div class="room_gallery">
		                                                <a href="./images/room1.jpg" rel="room1"><img alt="" src="./images/room1_150.gif" /></a>                                                
		                                                <div style="display: none;">
		                                                    <a href="./images/room1.jpg" rel="room1"><img alt="" src="./images/room1_150.gif" /></a>                                                
		                                                    <a href="./images/room1.jpg" rel="room1"><img alt="" src="./images/room1_150.gif" /></a>                                                
		                                                    <a href="./images/room1.jpg" rel="room1"><img alt="" src="./images/room1_150.gif" /></a>                                                
		                                                </div>
		                                                </div>
		                                            </td>
		                                            <td style="background: url('images/light_line_vertical.png') top center repeat-y; width: 10px;">
		                                            </td>
		                                            <td style="width: 585px; vertical-align: top; padding-left: 10px; padding-right: 5px;">                                                
		                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus aliquet blandit rutrum. Vivamus felis velit, porta vitae dapibus quis, lacinia eget urna. 
		                                                Nam eget massa vel velit viverra pretium vel elementum purus.   
		                                                Suspendisse sagittis blandit mauris ac dapibus. Pellentesque mollis, tellus non bibendum gravida, enim elit congue.
		                                                <h3><a href="#">Ammenities</a></h3>                                    
		                                                <table width="100%">                                                                                                                            
		                                                    <tr>
		                                                        <td>
		                                                            <ul style="padding-left: 16px; padding-bottom: 20px;">
		                                                                <li style="line-height: 18px; list-style-type: square; padding-bottom: 5px;">Ammenity 1</li>
		                                                                <li style="line-height: 18px; list-style-type: square; padding-bottom: 5px;">Ammenity 2</li>
		                                                                <li style="line-height: 18px; list-style-type: square; padding-bottom: 5px;">Ammenity 3</li>
		                                                            </ul>
		                                                        </td>
		                                                        <td style="padding-top: 20px">
		                                                            <ul style="padding-left: 16px; padding-bottom: 20px;">
		                                                                <li style="line-height: 18px; list-style-type: square; padding-bottom: 5px;">Ammenity 4</li>
		                                                                <li style="line-height: 18px; list-style-type: square; padding-bottom: 5px;">Ammenity 5</li>
		                                                                <li style="line-height: 18px; list-style-type: square; padding-bottom: 5px;">Ammenity 6</li>
		                                                            </ul>
		                                                        </td>
		                                                    </tr>
		                                                </table>
		                                            </td>
		                                            <td style="background: url('images/light_line_vertical.png') top center repeat-y; width: 10px;">
		                                            </td>
		                                            <td style="padding-left: 15px; vertical-align: top;">
		                                                <h3 style="margin-top: -15px"><a href="#"><strong><?= $bsiCore->config['conf_currency_symbol'].number_format($room_result['totalprice'], 2 , '.', ',') ?></strong></a></h3>                                  
		                                                <p><?= $room_result['pricedetails'] ?></p>
		                                                <form id="Form2" action="#" method="post">
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
		                                                                value="Book Now" />
		                                                        </div>
		                                                    </div>
		                                                </form>
		                                            </td>
		                                        </tr>
		                                    </table>
	                                    </div>
                                    <?php 
																		
												
											}//END room result 
										}//END multi capacity 
									}//END roomType foreach
									?>
								</form>	
							</div>
						</div>
					</div>                                                                          
                    <div class="divider">
                    </div>                                       
                </div>
            </div>
            <div class="page_down">
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
</body>
</html>