<?php
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");


global $systemConfiguration;
global $logger;
session_start ();
$logger->LogInfo(__FILE__);

$systemConfiguration->assertReferer();

$logger->LogInfo("Getting session object ...");
if (!isset($_SESSION['bookingDetails']))
{
	$logger->LogError("Session object is not set!");
	header ("Location: booking-failure.php?error_code=9");
}

// Get booking details from session
$bookingDetails = unserialize($_SESSION['bookingDetails']);

// Get selected room
$logger->LogInfo("Getting selected room ...");
if (!isset($_POST['roomId']) || !is_numeric($_POST['roomId']))
{
	$logger->LogError("No roomId variable in POST!");
    $_SESSION['errors'] = array (0 => BOOKING_FAILURE_INVALID_REQUEST);
    header ("Location: booking-failure.php");
}
$selectedRoom = Room::fetchFromDb(intval($_POST['roomId']));
if (is_null($selectedRoom) || is_null($bookingDetails->searchCriteria))
{
	$logger->LogError("No room for roomId: " . $_POST['roomId'] . " could be foudn in the database!");
    $_SESSION['errors'] = array (0 => BOOKING_FAILURE_INVALID_REQUEST);
    header ("Location: booking-failure.php");
}
$logger->LogInfo("Selected room is: " . $selectedRoom->roomName . " #" . $selectedRoom->roomNumber);
$bookingDetails->room = $selectedRoom;

// Save booking details to session
$bookingDetailsSerialized = serialize($bookingDetails);
$_SESSION['bookingDetails'] = $bookingDetailsSerialized;

// Get all extra services
$logger->LogInfo("Fetching all extra services ...");
$extraServices = ExtraService::fetchAllFromDb();
$logger->LogInfo("Fetched " . count($extraServices) . " extra services.");

// If there are no extra bed option or other services available, skip
if (!$bookingDetails->room->hasExtraBed == 0 && sizeof($extraServices) == 0)
{
	$logger->LogInfo("No extra bed or extra servces could be found! Skipping to details page ...");
	header ("Location booking-details.php");
}
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
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $bookingDetails->searchCriteria->checkInDate->format("m/d/Y") ?></td>
                                        </tr>
                                        <tr>                                        
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_CHECK_OUT ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $bookingDetails->searchCriteria->checkOutDate->format("m/d/Y") ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_TOTAL_NIGHTS ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $bookingDetails->searchCriteria->getNightCount() ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_ADULTS ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $bookingDetails->searchCriteria->adultsCount ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= BOOKING_SEARCH_CHILDREN ?></td>
                                            <td style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left; width: 200px;"><?= $bookingDetails->searchCriteria->childrenCount ?></td>
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
                            	<div class="box box_1-1 white">
                                	<div class="box_top">
                                    </div>
                                    <h2 style="padding-bottom: 15px;"><a href="#"><strong><?= BOOKING_SERVICES_TITLE ?></strong></a></h2>
                                    <form name="booking-services" action="booking-details.php" method="post">
	                                    <table style="background: url('images/light_boldline.png') top left repeat-x; width: 100%; margin-bottom: 20px;">
	                                    	<tr>
	                                    		<th style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;"><?= BOOKING_SERVICES_SERVICE_NAME ?></th>
	                                    		<th style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;"><?= BOOKING_SERVICES_PRICE ?></th>
	                                    		<th style="background: url('images/light_line.png') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;"><?= BOOKING_SERVICES_QUANTITY ?></th>
	                                    	</tr>
	                                    <?php
	                                    	if ($bookingDetails->room->hasExtraBed)
	                                    	{
                                                $bedPrice = $bookingDetails->room->getBedPrice($bookingDetails->searchCriteria->checkInDate, $bookingDetails->searchCriteria->checkOutDate);
	                                    		echo '<tr>' . "\n";
	                                    		echo '	<td style="background: url(\'images/light_line.png\') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;">' . BOOKING_SERVICES_EXTRA_BED . '</td>' . "\n";
	                                    		echo '	<td style="background: url(\'images/light_line.png\') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;">' .$systemConfiguration->formatCurrency($bedPrice) . ' ' . BOOKING_SERVICES_PER_STAY . '</td>' . "\n";
	                                    		echo '	<td style="background: url(\'images/light_line.png\') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;">' . "\n";
	                                    		echo '		<select name="extraBed">' . "\n";
	                                    		echo '			<option value="0" selected>0</option>' . "\n";
	                                    		echo '			<option value="1">1</option>' . "\n";
	                                    		echo '		</select>' . "\n";
	                                    		echo '	</td>' . "\n";                                    		
	                                    		echo '</tr>' . "\n";
	                                    	} 

	                                    	$extraServices = ExtraService::fetchAllFromDb();
	                                    	if ($extraServices == null)
	                                    	{
	                                    		$_SESSION['errors'] = ExtraService::$staticErrors;
	                                    		header("Location: booking-failure.php");
	                                    	}
                                            foreach($extraServices as $extraService)
                                            {                                            	                                            	
                                                if (!($extraService instanceof ExtraService))
                                                {
                                                    continue;
                                                }

	                                    		echo '<tr>' . "\n";
	                                    		echo '	<td style="background: url(\'images/light_line.png\') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;">' . $extraService->getName($language_selected) . '</td>' . "\n";
	                                    		echo '	<td style="background: url(\'images/light_line.png\') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;">' . $systemConfiguration->formatCurrency($extraService->price) . ' ';
	                                    		$maxNumberSelectable = 0;
	                                    		if ($extraService->isNightly)
	                                    		{
	                                    			echo BOOKING_SERVICES_PER_NIGHT . '</td>' . "\n";
	                                    			$maxNumberSelectable = $bookingDetails->searchCriteria->getNightCount();
	                                    		}
	                                    		else 
	                                    		{                                    			
	                                    			echo BOOKING_SERVICES_PER_STAY . '</td>' . "\n";
	                                    			$maxNumberSelectable = $extraService->maxNumberAvailable;
	                                    		}                                    		
	                                    		echo '	<td style="background: url(\'images/light_line.png\') bottom left repeat-x; padding: 4px 10px 5px 0px; /* vertical-align: top;*/ text-align: left;">' . "\n";
	                                    		echo '		<select name="extraServices[' . $extraService->id . ']">' . "\n";
	                                    		for ($i = 0; $i <= $maxNumberSelectable; $i++) 
	                                    		{
	                                    			if ($i == 0)
	                                    			{
	                                    				echo '			<option value="'. $i . '" selected>'. $i . '</option>' . "\n";
	                                    			}
	                                    			else
	                                    			{
	                                    				echo '			<option value="'. $i . '">'. $i . '</option>' . "\n";	
	                                    			}                                    			
	                                    		}                                    		
	                                    		echo '		</select>' . "\n";
	                                    		echo '	</td>' . "\n";                                    		
	                                    		echo '</tr>' . "\n";                                    		
	                                    	}
	                                    ?>
	                                    	<tr>
												<td colspan="3" align="center">
	                                    			<div style="width: 100px; padding-top: 15px;">	                                    			
			                                    			<input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
					                                            background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
					                                            cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
					                                            float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
					                                            text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
					                                            -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
					                                            -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
					                                            background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
					                                            -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
					                                            value="<?= BOOKING_SERVICES_SUBMIT_BUTTON ?>" />
			                                        </div>
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
                </div>
            </div>
            <div class="page_down">
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
</body>
</html>