<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");

global $systemConfiguration;
global $logger;

$logger->LogDebug("Script is starting ...");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?= $systemConfiguration->getSiteDescription() ?>" />
	<meta name="keywords" content="<?= $systemConfiguration->getSiteKeywords() ?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?= $systemConfiguration->getSiteTitle() ?></title>
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
    <script type="text/javascript" src="./js/geometr231_hv_bt_400.font.js"></script>
    <script type="text/javascript" src="js/jquery.datePicker.js"></script>
    <script type="text/javascript" src="js/date.js"></script>        
    <script type="text/javascript" src="js/flowplayer-3.2.6.min.js"></script>
</head>
<body class="light">    
    <div class="back">
        <div class="base">
			<?php include("header.php"); ?>
			<div class="page_top">				
			</div>
			<div class="page">
                <div class="page_inside clear">
                    <h1><strong><?= APARTMENTS_TITLE ?></strong></h1>
                    <br />
                    <?php
                    $rooms = Room::fetchFromDbApartments();
                    foreach ($rooms as $room) 
                    {
                    	if (!($room instanceof Room))
                    	{
                    		continue;
                    	}
                    ?>
	                     <div class="boxes lines_x0x0x">
	                        <div class="container clear">
	                            <div class="inner">
	                                <div class="box box_3-2 white">
	                                    <div class="box_top">
	                                    </div>
	                                    <h2><a href="#"><strong><?= $room->roomName . " #" . $room->roomNumber ?></strong></a></h2>
	                                    <br />
	                                    <?= $room->getDescription($language_selected) ?>	                                    
	                                    <div class="rule">
	                                    </div>                                              
	                                    <div id="book" style="margin-left: -100px;">
	                                        <form action="<?= $systemConfiguration->getSiteAddress() . "page.php?id=2" ?>" method="post">                                           
	                                            <div class="confirm clear" style="width: 125px;">
	                                                <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
	                                                    background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
	                                                    cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
	                                                    float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
	                                                    text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
	                                                    -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
	                                                    -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
	                                                    background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
	                                                    -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
	                                                    value="<?= APARTMENTS_RESERVE_NOW_BUTTON ?>" />
	                                            </div>                                       
	                                        </form>
	                                    </div>                               
	                                    <div class="box_down">
	                                    </div>
	                                </div>
	                                <div class="box box_3-1 white">
	                                    <div class="box_top">
	                                    </div>
	                                    <div class="gallery">
									        <div class="gallery_inner clear">
									        <?php
									        $roomImages = $room->getImages();
									        
									        // Output first 9 images and make them rest invisible
									        for ($i = 0; $i < 9 && $i < sizeof($roomImages); $i++) 
									        {
									        	$roomImage = $roomImages[$i];
								        	?>
								        		<a href="./images/<?= $roomImage->galleryImage->imageFileName ?>" rel="<?= $room->id ?>" title="<?= htmlentities($roomImage->galleryImage->description->getText($language_selected)) ?>"><img alt="<?= htmlentities($roomImage->galleryImage->description->getText($language_selected)) ?>" src="./images/<?= $roomImage->galleryImage->thumbImageFileName ?>" /></a>								        	
								        	<?php									        	
									        }
									        // Output other images but make them rest invisible
									        if (sizeof($roomImages) > 9)
									        {
									        	echo '<div style="display: none;">';
                    							for ($i = 9; $i < sizeof($roomImages); $i++) 
									        	{
									        		$roomImage = $roomImages[$i];
									        ?>
								        			<a href="./images/<?= $roomImage->galleryImage->imageFileName ?>" rel="<?= $room->id ?>" title="<?= htmlentities($roomImage->galleryImage->description->getText($language_selected)) ?>"><img alt="<?= htmlentities($roomImage->galleryImage->description->getText($language_selected)) ?>" src="./images/<?= $roomImage->galleryImage->thumbImageFileName ?>" /></a>								        	
								        	<?php									        	
									        	}
									        	echo '</div>';
									        }
									        ?>									           									        
									        </div>
								        </div>                                    
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
