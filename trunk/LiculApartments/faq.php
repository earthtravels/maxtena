<?php
include_once ("includes/SystemConfiguration.class.php");
include("includes/language.php");

global $systemConfiguration;
global $logger;
session_start();
unset($_SESSION['bookingDetails']);

$errors = array();

$logger->LogInfo("Fetching all faqs ...");
$faqs = Faq::fetchAllFromDb();
if ($faqs == null && sizeof(Faq::$staticErrors) > 0)
{
	$logger->LogError("Error fetching all faqs!");
	foreach (Faq::$staticErrors as $error) 
	{
		$errors[] = $error;
		$logger->LogError($error);
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>     
    <meta name="description" content="<?= $systemConfiguration->getSiteDescription() ?>" />
	<meta name="keywords" content="<?= $systemConfiguration->getSiteKeywords() ?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?= $systemConfiguration->getSiteTitle() ?></title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" />    
    <link rel="stylesheet" href="css/screen.css" type="text/css" />
    <link rel="stylesheet" href="css/date.css" type="text/css" />
    <link rel="stylesheet" href="css/datePicker.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.fancybox.video.css" type="text/css" />
    <link rel="stylesheet" href="css/submenu.css" type="text/css" />
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
    <script type="text/javascript" src="js/jquery.datePicker.js"></script>
    <script type="text/javascript" src="js/date.js"></script>        
    <script type="text/javascript" src="js/flowplayer-3.2.6.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			// hides the slickbox as soon as the DOM is ready
		<?php
			foreach ($faqs as $faq) 
            {
            	echo "\$('#box" . $faq->id . "').hide();\n";
            } 
		?>
					
			// toggles the slickbox on clicking the noted link
			<?php
			foreach ($faqs as $faq) 
            {
            	echo "\$('#toggle" . $faq->id . "').click(function() {\n";            	
				echo "	\$('#box" . $faq->id . "').toggle(400);\n";
				echo "	return false;\n";
				echo "});\n";            	
            } 
			?>
		});			
	</script>
</head>
<body class="light">
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
            <?php include("header.php"); ?>
			<div class="page_top">
            </div>
            <div class="page">
            	<div class="subpage clear">
            		<div class="clear fullwidth_pad" id="main">
            			<h1><strong><?= FAQ_TITLE ?></strong></h1>
            			<?php
            			foreach ($faqs as $faq) 
            			{
            			?>
            				<div class="boxes lines_x0x0x">
		                        <div class="container clear">
		                            <div class="inner">
		                                <div class="box box_1-1 white">
		                                    <div class="box_top">
		                                    </div>
		                                    <h3 style="margin-top: -15px; margin-bottom: -15px"><a id="toggle<?= $faq->id ?>" href="#"><?= $faq->question->getText($language_selected) ?></a></h3>
		                                    <div id="box<?= $faq->id ?>">
												<?= $faq->answer->getText($language_selected) ?>                                            
		                                    </div>                                    
		                                     <div class="box_down">
		                                    </div>
		                                </div>                                                          
		                            </div>
		                        </div>
		                    </div>
		                    <div class="divider_small">
                    		</div>            			
            			<?php 
            			} 
            			?>  
            			<h2><strong><?= FAQ_STILL_HAVE_QUESTIONS ?> <a href="contact.php"><?= FAQ_CONTACT_US ?></a></strong></h2>          	
            		</div>
            	</div>                
            </div>
            <div class="page_down">
            </div>
           	<?php include("footer.php") ?>
        </div>
    </div>    
</body> 	
</html>
