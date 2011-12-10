<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");

global $systemConfiguration;

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
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
            <?php include("header.php"); ?>
			<div class="page_top">
            </div>
            <div class="page">
            	<?= $page->contents->getText($language_selected) ?>                
            </div>
            <div class="page_down">
            </div>
           	<?php include("footer.php") ?>
        </div>
    </div>
   
<!--   <script type="text/javascript">-->
<!--        var videopath = "./";-->
<!--        var swfplayer = videopath + "videos/flowplayer-3.1.5.swf";-->
<!--        var swfcontent = videopath + "videos/flowplayer.content-3.1.0.swf";-->
<!--        var swfcaptions = videopath + "videos/flowplayer.captions-3.1.4.swf";-->
<!---->
<!--    </script>   -->
</body> 	
</html>
