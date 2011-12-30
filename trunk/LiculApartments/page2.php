<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");

global $systemConfiguration;
global $logger;
$logger->LogInfo(__FILE__);

unset($_SESSION['bookingDetails']);

$page = null;
if (sizeof($_REQUEST) > 0 && isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$id = intval($_REQUEST['id']);
	$page = PageContents::fetchFromDb($id);
	if ($page == null)
	{
		$_SESSION['errors'] = BOOKING_FAILURE_INVALID_REQUEST;
		header("Location: booking-failure.php");
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
    <title><?= htmlentities($page->title->getText($language_selected)) ?></title>
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

    <script type="text/javascript">      
        Date.firstDayOfWeek = 0;
        Date.format = 'mm/dd/yyyy';

        $(function () {
            $('.date-pick').datePicker();
            $('#check_in').bind(
					'dpClosed',
					function (e, selectedDates) {
					    var d = selectedDates[0];
					    if (d) {
					        d = new Date(d);
					        $('#check_out').dpSetStartDate(d.addDays(<?= $systemConfiguration->getMinimumNightCount() ?>).asString());
					    }
					}
				);
            $('#check_out').bind(
					'dpClosed',
					function (e, selectedDates) {
					    var d = selectedDates[0];
					    if (d) {
					        d = new Date(d);
					        $('#check_in').dpSetEndDate(d.addDays(-1).asString());
					    }
					}
				);
	});    
   
    <?php if($language_selected=='hr'){ ?>	
	    Date.dayNames = ['Nedelja', 'Ponedjeljak', 'Utorak', 'Srijeda', '&#268;etvtak', 'Petak', 'Subota'];
	    Date.abbrDayNames = ['Ned', 'Pon', 'Uto', 'Sri', '&#268;et', 'Pet', 'Sub'];
	    Date.monthNames = ['Sije&#269;anj', 'Velja&#269;a', 'O&#382;ujak', 'Travanj', 'Svibanj', 'Lipanj', 'Srpanj', 'Kolovoz', 'Rujan', 'Listopad', 'Studeni', 'Prosinac'];
	    Date.abbrMonthNames = ['Sij', 'Vel', 'O&#382;u', 'Tra', 'Svi', 'Lip', 'Srp', 'Kol', 'Ruj', 'Lis', 'Stu', 'Pro'];
    <?php } elseif($language_selected=='de') { ?>
	    Date.dayNames = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
	    Date.abbrDayNames = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
	    Date.monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
	    Date.abbrMonthNames = ['Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];
    <?php } elseif($language_selected=='it') { ?>  
	    Date.dayNames = ['Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato'];
	    Date.abbrDayNames = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];    
	    Date.monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
	    Date.abbrMonthNames = ['Gen', 'Febb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Sett', 'Ott', 'Nov', 'Dic'];
    <?php }
    ?>
    </script>
</head>
<body class="light">
<?php include_once("analyticstracking.php") ?>
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
            <?php include("header.php"); ?>
			<div class="page_top">
            </div>
            <div class="page">
                <div class="page_inside clear">
                    <div class="subpage clear">
                        <!-- ****** SIDEBAR ****** -->
                        <div id="sidebar">                          
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <h1><?= TEMPLATE_2_NEWS ?>.</h1>
                                <div class="rule">
                                </div>
                                <?php
                                $lastNewsPosts = NewsPost::fetchFromDbNewestX(2);
                                foreach ($lastNewsPosts as $newsPost) 
                                {
                                ?>
                                	<div class="sideinner sidepost">
	                                    <h2>
	                                        <a href="news.php?id=<?= $newsPost->id ?>"><?= $newsPost->title->getText($language_selected) ?></a></h2>
	                                    <div class="post_thumb">
	                                    	<a href="<?= "news.php?id=" . $newsPost->id ?>"> 
	                                        	<img alt="<?= $newsPost->title->getText($language_selected) ?>" src="./images/<?= $newsPost->imageMedium ?>" />
                                        	</a>
	                                    </div>
	                                    <?= $newsPost->contents->getFirstXCharacters($language_selected, 150) ?> ...
	                                    <div class="rule">
	                                    </div>
	                                    <div class="post_links clear">
	                                        <span class="more"><a href="news.php?id=<?= $newsPost->id ?>" class="bold"><?= HOME_BOX_NEWS_READ_MORE ?></a></span> <span class="date">
	                                            <strong><?= $newsPost->postedDate->format("m/d/Y") ?></strong></span> &nbsp;
	                                    </div>
	                                    <div class="rule">
	                                    </div>
                                	</div>
                                
                                <?php 
                                } 
                                ?>                              
                                <div class="box_down">
                                </div>
                            </div>                         
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <h1><?= TEMPLATE_2_FOLLOW_US ?>.</h1>
                                <div class="rule">
                                </div>
                                <div class="divider_small">
                                </div>
                                <a href="http://www.twitter.com" target="_blank"><img src="images/icon_twitter.png" alt="Twitter Icon" /></a>
                                <a href="http://www.facebook.com/pages/Villas-Rabac" target="_blank"><img src="images/icon_facebook.png" alt="Facebook Icon" /></a>
                                <a href="http://therabac.tumblr.com" target="_blank"><img src="images/icon_tumblir.png" alt="Tumblir Icon" /></a>
                                <div class="box_down">
                                </div>
                            </div>
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <h1><?= TEMPLATE_2_CONTACT_US ?>.</h1>
                                <div class="rule">
                                </div>
                                <div class="divider_small">
                                </div>
                                <p><?= TEMPLATE_2_PHONE . ": " . $systemConfiguration->getHotelDetails()->getHotelPhone() ?></p>
                                <p><?= TEMPLATE_2_EMAIL ?>: <a href="contact.php"><?= $systemConfiguration->getHotelDetails()->getHotelEmail() ?></a></p>
                                <div class="box_down">
                                </div>
                            </div>                            
                        </div>
                        <!-- end of sidebar -->
                        <!-- MAIN -->
                        <div id="main" class="clear">
                        	<?= $page->contents->getText($language_selected) ?>                                           
                        </div>
                        <!-- end of main -->
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