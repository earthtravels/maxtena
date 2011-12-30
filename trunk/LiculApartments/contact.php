<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");

global $systemConfiguration;
global $logger;

unset($_SESSION['bookingDetails']);

$logger->LogInfo(__FILE__);
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
</head>
<body class="light">
<?php include_once("analyticstracking.php") ?>    
    <div class="back">
        <div class="base">
			<?php include("header.php"); ?>
			<div class="page_top">
				<div class="slider">
                    <div class="boxes">
                        <div class="container clear">
                            <div class="inner">
                                <div class="box box_1-1 white">
                                    <div class="box_top">
                                    </div>
                                    <div class="box_customfull">
                                        <div class="box_customfull_inner">
                                            <a href="http://maps.google.com/?q=Lošinjska%2026,%20Labin,%20Croatia" target="_blank">                                                
                                                <img src="images/map.png" class="full" alt="Location" /></a>
                                            <div class="rule">
                                            </div>
                                        </div>
                                        <div class="box_custombck2_inner">
                                            <div class="box_custom_descr">
                                                <?= CONTACT_FIND_US ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box_down">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
			</div>
			 <div class="page">
                <div class="page_inside clear">
                    <div class="subpage clear">
                        <!-- ****** SIDEBAR ****** -->
                        <div id="sidebar">
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <div class="sideinner sidecontact">
                                    <a href="">
                                        <img src="./images/envelope.png" alt="" class="mailicon" /></a>
                                    <h2><?= CONTACT_INFO ?></h2>
                                    <ul>
                                        <li><?= $systemConfiguration->getHotelDetails()->getHotelAddress() ?></li>
                                        <li><?= $systemConfiguration->getHotelDetails()->getHotelCity() . ", " . $systemConfiguration->getHotelDetails()->getHotelCountry() ?></li>
                                        <li>&nbsp;</li>
                                        <li><strong><?= CONTACT_EMAIL ?>: </strong><a href="#"><?= $systemConfiguration->getHotelDetails()->getHotelEmail() ?> </a></li>
                                    </ul>
                                </div>
                                <div class="box_down">
                                </div>
                            </div>
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <div class="sideinner sideservice">
                                    <h2><?= CONTACT_SERVICE_PLACES ?></h2>
                                    <ul class="list">
                                        <li class="clear">
                                            <div class="fl">
                                                <strong>US:</strong> Washington, DC, 1-800-XXX-XXXX</div>
                                            <!-- /.fl -->
                                            <div class="fr">
                                                <a title="" href="#">
                                                    <img alt="" src="./images/mail.png" class="icon" /></a></div>
                                            <!-- /.fr -->
                                        </li>
                                        <li class="clear">
                                            <div class="fl">
                                                <strong>HR:</strong> <?= $systemConfiguration->getHotelDetails()->getHotelCity() . ", " . $systemConfiguration->getHotelDetails()->getHotelCountry() . ", " . $systemConfiguration->getHotelDetails()->getHotelPhone() ?>
                                            </div>
                                            <!-- /.fl -->
                                            <div class="fr">
                                                <a title="" href="#">
                                                    <img alt="" src="./images/mail.png" class="icon" />
                                                </a>
                                            </div>
                                            <!-- /.fr -->
                                        </li>
                                    </ul>
                                </div>
                                <div class="box_down">
                                </div>
                            </div>
                        </div>
                        <!-- end of sidebar -->
                        <!-- MAIN -->
                        <div id="main" class="clear">
                            <h1>
                                <strong><?= CONTACT_TITLE ?></strong></h1>
                            <p><?= CONTACT_TOP_PARAGRAPH ?></p>
                            <div id="contact">
                                <div class="rule">
                                </div>
                                <form id="contact_form" action="contact-process.php" method="post" onsubmit="return validateContactForm();" > 
                                <div class="clear">                              
                                    <label for="name" class="required">
                                        <?= CONTACT_NAME ?>
                                    </label>
                                    <input type="text" name="form[name]" id="name" class="input required" />
                                </div>
                                <!-- /.clear -->
                                <div class="clear">
                                    <label for="subject" class="required">
                                        <?= CONTACT_SUBJECT ?>
                                    </label>
                                    <input type="text" name="form[subject]" id="subject" class="input required" />
                                </div>
                                <!-- /.clear -->
                                <div class="clear">
                                    <label for="mail" class="required">
                                        <?= CONTACT_EMAIL ?>
                                    </label>
                                    <input type="text" name="form[mail]" id="mail" class="input required email" />
                                </div>
                                <!-- /.clear -->
                                <div class="clear">
                                    <label for="message" class="required">
                                        <?= CONTACT_MESSAGE ?>
                                    </label>
                                    <textarea name="form[message]" id="message" cols="40" rows="6"></textarea>
                                </div>                                
                                <div class="rule">
                                </div>
                                <div class="confirm clear">
                                    <div style="padding-right: 15px; width: 100px; padding-top: 0px;">
                                        <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
                                            background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
                                            cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
                                            float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
                                            text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
                                            -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
                                            -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
                                            background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
                                            -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
                                            value="<?= CONTACT_SUBMIT_BUTTON ?>" />
                                    </div>                                    
                                </div>
                                <!-- /.confirm -->
                                </form>
                                <div class="rule">
                                </div>
                            </div>                            
                        </div>
                        <!-- end of main -->
                    </div>
                </div>
            </div>
            <div class="page_down">
            </div>
            <?php include("footer.php"); ?>
		</div>
	</div>
	
	<script type="text/javascript">
		String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };
		
		function isEmail(string) 
		{
			if (string.search(/^[A-Za-z0-9\._\%\+\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,4}$/ ) != -1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function validateContactForm()
		{
			var bookingForm = document.getElementById("contact_form");
			if (bookingForm == null)
			{
				alert("Error looking up newsletter form!");
				return false;
			}
			
			if(bookingForm.mail.value.mytrim().length == 0)
			{
				alert('<?= HOME_BOX_BOTTOM_LEFT_EMAIL_REQ ?>');
				return false;
			}
			else if (!isEmail(bookingForm.mail.value))
			{
				alert('<?= HOME_BOX_BOTTOM_LEFT_EMAIL_INVALID ?>');
				return false;
			}

			if(bookingForm.name.value.mytrim().length == 0)
			{
				alert('<?= CONTACT_NAME_REQ ?>');
				return false;
			}	

			if(bookingForm.subject.value.mytrim().length == 0)
			{
				alert('<?= CONTACT_SUBJECT_REQ ?>');
				return false;
			}

			if(bookingForm.message.value.mytrim().length == 0)
			{
				alert('<?= CONTACT_MESSAGE_REQ ?>');
				return false;
			}
		}
	</script>
</body>
</html>
