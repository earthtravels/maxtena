<?php
include_once ("includes/SystemConfiguration.class.php");
include ("includes/language.php");


global $systemConfiguration;
global $logger;
$logger->LogInfo(__FILE__);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?= $systemConfiguration->getSiteDescription() ?>" />
	<meta name="keywords" content="<?=$systemConfiguration->getSiteKeywords()?>" />
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
    <script type="text/javascript" src="js/geometr231_hv_bt_400.font.js"></script>
</head>
<body class="light">
<?php include_once("analyticstracking.php") ?>
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
        	<?php include ("header.php"); ?>            
            <div class="page_top">                
            </div>
            <div class="page">
                <div class="page_inside clear">                    
                   					<?php
                                    	$logger->LogInfo("Fetching content for id: 4 ...");
                                    	$content = Content::fetchFromDbForId(4);
                                    	if ($content == null)
                                    	{
                                    		$logger->LogError("Error fetching content with id: 4 from the database.");
                                    		foreach (Content::$staticErrors as $error) 
                                    		{
                                    			$logger->LogError($error);
                                    			die;
                                    		}                                    		
                                    	} 
                                    	$logger->LogInfo("Content fetched successfully.");
                                    	
                                    	echo $content->contents->getText($language_selected);
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