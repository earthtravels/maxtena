<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");


global $systemConfiguration;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>     
    <meta name="description" content="<?= $systemConfiguration->getSiteDescription() ?>" />
	<meta name="keywords" content="<?= $systemConfiguration->getSiteKeywords() ?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?= TERMS_AND_CONDITIONS ?></title>
    <link rel="stylesheet" href="./css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./css/jquery.fancybox.css" type="text/css" />
    <link rel="stylesheet" href="./css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="./css/screen.css" type="text/css" />
    <link rel="stylesheet" href="./css/submenu.css" type="text/css" />
</head>
<body class="light">
<?php include_once("analyticstracking.php") ?>
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
            <?php include("header.php") ?>
            <div class="page_top">
            </div>
            <div class="page">
                <div class="page_inside clear">
                    <div class="subpage clear">                        
                        <!-- MAIN -->
                        <div id="main" class="clear fullwidth_pad">
                            <?php
                                    		$content = Content::fetchFromDbForName("Terms and Conditions");
                                    		if ($content != null)
                                    		{                                    		
                                    			echo $content->contents->getText($language_selected);
                                    		}                                    		 
                                    	?>
                        <!-- end of main -->                       
	                    </div>
	                </div>
	            </div>
            </div>
            <div class="page_down">
            </div>
           	<?php include("footer.php") ?>
        </div>
    </div>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.validate.js"></script>
    <script type="text/javascript" src="./js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="./js/jquery.nivo.js"></script>
    <script type="text/javascript" src="./js/cufon.js"></script>
    <script type="text/javascript" src="./js/geometr231_hv_bt_400.font.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>    
</body>
</html>
