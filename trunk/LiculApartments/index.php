<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");

global $systemConfiguration;
global $logger;

unset($_SESSION['bookingDetails']);

$logger->LogInfo(__FILE__);
$logger->LogDebug("Script is starting ...");

$errorMessage = "";
$successMessage = "";
if (sizeof($_POST) > 0)
{
	$logger->LogInfo("Processing newsletter subscription ...");
	$systemConfiguration->assertReferer();
	if (isset($_POST['email']))
	{
		$emailAddress = trim($_POST['email']);
		$logger->LogDebug("Email address for newsletter was specified.");
		$logger->LogDebug($emailAddress);
		$subscription = NewsletterSubscription::fetchFromDbForEmail($emailAddress);
		if ($subscription == null)
		{
			$subscription = new NewsletterSubscription();			
		}		
		$subscription->email = $emailAddress;		
		$subscription->isActive = true;
		$logger->LogDebug("Saving subscription ...");
		if ($subscription->save())
		{
			$logger->LogDebug("Saving is successful.");
			$successMessage = HOME_BOX_BOTTOM_LEFT_SUCCESS;			
		}
		else
		{
			$logger->LogError("Saving FAILED!");
			$logger->LogError($subscription->errors);
			$errorMessage = BOOKING_DETAILS_EMAIL_INVALID;
		}
	} 
}
$logger->LogDebug("Starting HTML ...");
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
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="css/screen.css" type="text/css" />
    <link rel="stylesheet" href="css/date.css" type="text/css" />
    <link rel="stylesheet" href="css/datePicker.css" type="text/css" />
    <!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="http://www.ait.sk/simplicius/html/css/ie7.css" />
	<![endif]-->	
</head>
<body class="light">
<?php include_once("analyticstracking.php") ?>    
    <div class="back">
        <div class="base">
			<?php include("header.php"); ?>
			<div class="page_top">
				<div id="slider" class="slider">
                    <div class="picture box_full white">                        
                        <div class="top">
                        </div>
                        <div class="middle">
                            <div class="wrap">
                                <div class="slides">
                                	<?php
                                		$logger->LogInfo("Fetching slider images to display ...");
                                		$sliderImages = SliderImage::fetchAllDb();
                                		foreach ($sliderImages as $sliderImage) 
                                		{
									?>
											<a href="<?= $sliderImage->galleryImage->link ?>"><img src="images/<?= $sliderImage->galleryImage->imageFileName ?>" alt="<?= $sliderImage->galleryImage->description->getText($language_selected) ?>" class="slide" title="<?= $sliderImage->galleryImage->description->getText($language_selected) ?>" /></a>
									<?php                                 			
                                		}
                                	?>                                                                         
                                </div>                                
                            </div>
                        </div>
                        <div class="down">
                        </div>
                    </div>
                    <div class="nav box_full white">                        
                        <div class="top">
                        </div>
                        <div class="middle">
                        </div>
                        <div class="down">
                        </div>
                    </div>
                </div>                
			</div>
			 <div class="page">
                <div class="page_inside clear">
                    <div class="boxes lines_x1x1x">
                        <div class="container clear">
                            <div class="inner">
                                <div class="box box_3-1  gray">
                                    <div class="box_top">
                                    </div>
                                    <div class="icon clear">                                        
                                        <h2>
                                            <?= HOME_BOX_TOP_LEFT_TITLE ?>
                                            <span><?= HOME_BOX_TOP_LEFT_SUBTITLE ?></span>
                                        </h2>
                                        <div class="divider_med">
                                        </div>
                                        <!--div id="main" class="clear"-->
                                        <div id="book">
                                            <form id="booking_form" action="booking-search.php" method="post" onsubmit="return validateBookingForm();">
                                            <div class="clear">
                                                <label for="name">
                                                    <?= HOME_BOX_TOP_LEFT_CHECK_IN ?></label>                                                
                                                    <input name="check_in" id="check_in" readonly="readonly" class="date-pick input" />
                                            </div>
                                            <!-- /.clear -->
                                            <div class="clear">
                                                <label for="subject">
                                                    <?= HOME_BOX_TOP_LEFT_CHECK_OUT ?></label>
                                                <input name="check_out" id="check_out" readonly="readonly" class="date-pick input" />                                                
                                            </div>                                            
                                            <div class="clear">
                                                <label for="select">
                                                    <?= HOME_BOX_TOP_LEFT_ADULT ?></label>
                                                <select name="adults" id="adults" class="select">
                                          		<?php
                                          			$logger->LogInfo("Fetching max capacity of rooms ...");
                                          			$max_capacity = 0;
                                          			$sql = "select MAX(capacity) as capacity from bsi_rooms";
													$sqlMaxCapacityQuery=mysql_query($sql);
													if (!$sqlMaxCapacityQuery)
													{
														$logger->LogFatal("Error running SQL: " . $sql);
														$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_errno() );
														die("There was an error connecting to the database. Please try your request again or contact the system administrator.");
													}
													if($row = mysql_fetch_assoc($sqlMaxCapacityQuery))
													{
														$max_capacity = intval($row['capacity']);
														for ($i = 1; $i <= $max_capacity; $i++) 
														{
															echo "<option>". $i . "</option>\n";															
														}
													}
												?>                                                    
                                                </select>
                                            </div>
                                            <div class="clear">
                                                <label for="select">
                                                    <?= HOME_BOX_TOP_LEFT_CHILD ?></label>
                                                <select name="children" id="children" class="select">
                                                	<?php 
                                                    	for ($i = 0; $i <= $max_capacity; $i++) 
														{
															echo "<option>". $i . "</option>\n";															
														}
													?>
                                                </select>
                                            </div>
                                            <div class="rule">
                                            </div>
                                            <div style="padding-left: 100px; width: 100px; padding-top: 0px;">
                                                <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
                                                    background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
                                                    cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
                                                    float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
                                                    text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
                                                    -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
                                                    -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
                                                    background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
                                                    -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
                                                    value="<?= HOME_BOX_TOP_LEFT_BUTTON_GO ?>" />
                                            </div>                                            
                                            <!-- /.confirm -->
                                            </form>
                                        </div>
                                        <!--/div-->
                                    </div>                                    
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-1">
                                    <div class="box_top">
                                    </div>                                    
                                    <?php
                                    	$logger->LogInfo("Fetching content to display ...");
                                    	$indexPage = PageContents::fetchFromDbForUrl("index.php");
                                    	if ($indexPage != null)
                                    	{                                    		
                                    		echo $indexPage->contents->getText($language_selected);
                                    	}
                                    	else 
                                    	{
                                    		$logger->LogWarn("No content exists for page: index.php!");
                                    	} 
                                    ?>                                                                        
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-1">
                                    <div class="box_top">
                                    </div>
                                    <!--<object width="290" height="260" id="_player" name="_player" data="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" type="application/x-shockwave-flash"><param name="autoPlay" value="false" /><param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="flashvars" value='config={"clip":{"url":"http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv"},"autoPlay":"false","autoBuffering":"true"}' /></object>-->
<!--                                    	<div style="width:295px;height:260px; margin-left: -10px;" id="player"></div>-->
									        <!-- this A tag is where your Flowplayer will be placed. it can be anywhere -->
											<a  
												 href="videos/Croatia.flv"
												 style="display:block;width:295px;height:260px; margin-left:-10px"  
												 id="flowFlashPlayer"><img src="images/play_video.jpg" alt="Search engine friendly content" /> 
											</a>											
                                    <div class="box_down">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider">
                    </div>
                    <div class="boxes lines_x0x0x">
                        <div class="container clear">
                            <div class="inner">
                                <div class="box box_3-1 gray">
                                    <div class="box_top">
                                    </div>
                                    <h2>
                                    	<?= HOME_BOX_BOTTOM_LEFT_TITLE ?>
                                    	<span><?= HOME_BOX_BOTTOM_LEFT_SUBTITLE ?></span>
                                    </h2>
                                    <div class="divider_med">
                                    </div>
                                    <div style="font-size: 100%; vertical-align: baseline; line-height: 1; color: #3f3f38;">
                                        <div>
                                            <div>                                                
                                                <form action="<?= $_SERVER['PHP_SELF'] ?>" id="subscribe-form" method="post" style="padding-top: 10px;" onsubmit="return validateNewsletterForm();">
                                                <div>
                                                    <input style="margin-top: -2px; width: 240px; background: none repeat scroll 0% 0% rgb(250, 250, 250);
                                                        color: rgb(101, 100, 90); padding: 5px 10px; float: left; vertical-align: middle;
                                                        font: 99% arial,helvetica,clean,sans-serif; border: 1px solid rgb(221, 221, 221);
                                                        -moz-border-radius: 5px 5px 5px 5px;" id="email" name="email" tabindex="2" type="text" />
                                                </div>                                                
                                                <div style="padding-right: 15px; width: 100px; padding-top: 40px;">
                                                    <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
                                                        background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
                                                        cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
                                                        float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
                                                        text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
                                                        -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
                                                        -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
                                                        background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
                                                        -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
                                                        value=<?= HOME_BOX_BOTTOM_LEFT_BUTTON_SUBSCRIBE ?> />
                                                </div>
                                                <p style="padding: 0; color: #787975; line-height: 1.4em; float: right;">
                                                    <strong style="padding-left: 15px; margin: 0; padding: 0; border: 0; outline: 0;
                                                        font-size: 100%; vertical-align: baseline; background: transparent; margin-top: 5px !important;
                                                        float: left;"><?= FOOTER_FOLLOW_US?></strong> 
                                                    <a href="http://www.twitter.com" title="Twitter" style="margin: 0; 
                                                            padding: 0; border: 0; font-size: 100%; vertical-align: baseline; background: transparent; color: inherit;
                                                            text-decoration: none;">
                                                            <img alt="" style="margin: 0 0 0 10px;" src="images/icon_twitter.png" /></a>
                                                    <a href="http://www.facebook.com/pages/Villas-Rabac" title="Facebook">
                                                        <img alt="Facebook Icon" src="images/icon_facebook.png" style="margin: 0 0 0 3px;
                                                            padding: 0; border: 0; outline: 0; font-size: 100%; vertical-align: baseline;
                                                            background: transparent;" /></a>
                                                    <a href="http://therabac.tumblr.com" title="Tumblir">
                                                        <img alt="Tumblir Icon" src="images/icon_tumblir.png" style="margin: 0 0 0 3px;
                                                            padding: 0; border: 0; outline: 0; font-size: 100%; vertical-align: baseline;
                                                            background: transparent;" /></a>

                                                </p>                                                                                       
                                                </form>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                                	if ($errorMessage != "")
                                                	{
                                                		echo '<div style="padding-top: 35px; padding-left: 5px;"><p style="color: red"><b>' .$errorMessage. '</b></p></div>' . "\n"; 
                                                	} 
                                                	else if ($successMessage != "")
                                                	{
                                                		echo '<div style="padding-top: 35px; padding-left: 5px;"><p style="color: green"><b>' .$successMessage. '</b></p></div>' . "\n";
                                                	}
                                                ?>
                                    <div style="padding-top: <?= ($errorMessage != "" || $successMessage != "") ? "20px" : "50px"?>; font-size: 8pt;"><?= HOME_BOX_BOTTOM_LEFT_FOOTER ?></div>
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-2 white">
                                    <div class="box_top">
                                    </div>
                                    <?php
                                    	$logger->LogInfo("Fetching newest news post to display ...");
										$newestNewsPost = NewsPost::fetchFromDbNewest();
                                        if ($newestNewsPost != null)
                                        {									
                                   	?>                                        
		                                    <div class="home_post clear">
		                                        <div class="post_thumb">  
		                                        	<a href="<?= "news.php?id=" . $newestNewsPost->id ?>">                                      
		                                            	<img src="images/<?= $newestNewsPost->imageSmall ?>" alt="<?= $newestNewsPost->title->getText($language_selected) ?>" />
		                                            </a>
		                                            <div class="post_thumb_top">
		                                            </div>
		                                            <div class="post_thumb_down">
		                                            </div>
		                                        </div>
		                                        
												<h2><a href="<?= "news.php?id=" . $newestNewsPost->id ?>"><?= $newestNewsPost->title->getText($language_selected) ?></a></h2>
												<?= $newestNewsPost->contents->getFirstXCharacters($language_selected, 450) . " ..." ?>                                                                                                                      
		                                    </div>
		                                    <div class="post_links clear">
		                                        <span class="more"><a href="<?= "news.php?id=" . $newestNewsPost->id ?>" class="bold"><?= HOME_BOX_NEWS_READ_MORE ?></a></span> <span class="date">
		                                            <strong><?= $newestNewsPost->postedDate->format("m/d/Y") ?></strong></span> 
		                                    </div>
                                    <?php
                                        } 
                                        else 
                                    	{
                                    		$logger->LogWarn("No news posts exist!");
                                    	}
                                    ?>
                                    <div class="box_down">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page_down">
            </div>
            <?php include("footer.php"); ?>
		</div>
	</div>
</body>
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

    Date.firstDayOfWeek = 0;

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
    
    <script type="text/javascript">
//	    flowplayer("player", "videos/flowplayer-3.1.5.swf", {
//	        clip: {
//	            url: "videos/home_page_video.flv",
//	            autoPlay: false
//	        }
//		});

    	flowplayer("flowFlashPlayer", "videos/flowplayer-3.2.7.swf");

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

	    String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };
	    function validateBookingForm()
		{
			var bookingForm = document.getElementById("booking_form");
			if (bookingForm == null)
			{
				alert("Error looking up booking form!");
				return false;
			}
			
			if(bookingForm.check_in.value.mytrim().length == 0)
			{
				alert('<?= HOME_BOX_TOP_LEFT_CHECK_IN_REQ ?>');
				return false;
			}

			if(bookingForm.check_out.value.mytrim().length == 0)
			{
				alert('<?= HOME_BOX_TOP_LEFT_CHECK_OUT_REQ ?>');
				return false;
			}			
		}

	    function validateNewsletterForm()
		{
			var bookingForm = document.getElementById("subscribe-form");
			if (bookingForm == null)
			{
				alert("Error looking up newsletter form!");
				return false;
			}
			
			if(bookingForm.email.value.mytrim().length == 0)
			{
				alert('<?= HOME_BOX_BOTTOM_LEFT_EMAIL_REQ ?>');
				return false;
			}
			else if (!isEmail(bookingForm.email.value))
			{
				alert('<?= HOME_BOX_BOTTOM_LEFT_EMAIL_INVALID ?>');
				return false;
			}			
		}
	</script>
</html>
