<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
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
    <script type="text/javascript" src="./js/geometr231_hv_bt_400.font.js"></script>
    <script type="text/javascript" src="js/jquery.datePicker.js"></script>
    <script type="text/javascript" src="js/date.js"></script>        
    <script type="text/javascript" src="js/flowplayer-3.2.6.min.js"></script>


    <script type="text/javascript">      
        Date.firstDayOfWeek = 0;
        Date.format = 'mm/dd/yyyy';

        $(function () {

            $('.date-pick').datePicker();
            $('#start-date').bind(
					'dpClosed',
					function (e, selectedDates) {
					    var d = selectedDates[0];
					    if (d) {
					        d = new Date(d);
					        $('#end-date').dpSetStartDate(d.addDays(1).asString());
					    }
					}
				);
            $('#end-date').bind(
					'dpClosed',
					function (e, selectedDates) {
					    var d = selectedDates[0];
					    if (d) {
					        d = new Date(d);
					        $('#start-date').dpSetEndDate(d.addDays(-1).asString());
					    }
					}
				);
	});    

    Date.firstDayOfWeek = 0;
    Date.format = '<?=$bsiCore->config['conf_dateformat']?>';

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
	    Date.abbrDayNames = ['Dom.', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];    
	    Date.monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
	    Date.abbrMonthNames = ['Gen', 'Febb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Sett', 'Ott', 'Nov', 'Dic'];
    <?php } ?>
    </script>   
   
	
</head>
<body class="light">    
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
										$sql_gallery=mysql_query("select * from bsi_gallery where gallery_type=2");
										while($gallery_row=mysql_fetch_assoc($sql_gallery))
										{	
									?>
                                    		<a href="<?=$gallery_row['link']?>"><img src="images/<?=$gallery_row['img_path']?>" alt="<?=$gallery_row['description']?>" class="slide" title="<?=$gallery_row['description']?>" /></a>
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
                                        <!--a href="#"><img src="./images/tmp/icon2_64.png" alt="" /></a-->
                                        <h2>
                                            <?= HOME_BOX_TOP_LEFT_TITLE ?>
                                            <span><?= HOME_BOX_TOP_LEFT_SUBTITLE ?></span>
                                        </h2>
                                        <div class="divider_med">
                                        </div>
                                        <!--div id="main" class="clear"-->
                                        <div id="book">
                                            <form id="contact_form" action="#" method="post">
                                            <div class="clear">
                                                <label for="name">
                                                    <?= HOME_BOX_TOP_LEFT_CHECK_IN ?></label>
                                                <!--<input type="text" name="form[check_in_date]" id="check_in_date" class="input required" />-->
                                                    <input name="check_in" id="start-date" readonly="readonly" class="date-pick input" />
                                            </div>
                                            <!-- /.clear -->
                                            <div class="clear">
                                                <label for="subject">
                                                    <?= HOME_BOX_TOP_LEFT_CHECK_OUT ?></label>
                                                <input name="check_out" id="end-date" readonly="readonly" class="date-pick input" />
                                                <!--<input type="text" name="form[check_out_date]" id="check_out_date" class="input required" />-->
                                            </div>
                                            <!-- /.clear -->
                                            <div class="clear">
                                                <label for="select">
                                                    <?= HOME_BOX_TOP_LEFT_ADULT ?></label>
                                                <select id="adults" class="select">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                </select>
                                            </div>
                                            <div class="clear">
                                                <label for="select">
                                                    <?= HOME_BOX_TOP_LEFT_CHILD ?></label>
                                                <select id="children" class="select">
                                                    <option>0</option>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
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
                                    <div class="icon clear">                                        
                                        <h2>
                                            <?= HOME_BOX_TOP_MIDDLE_TITLE ?>
                                            <span><?= HOME_BOX_TOP_MIDDLE_SUBTITLE ?></span>
                                        </h2>
                                    </div>
                                    <?= HOME_BOX_TOP_MIDDLE_TEXT ?>                                    
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-1">
                                    <div class="box_top">
                                    </div>
                                    <!--<object width="290" height="260" id="_player" name="_player" data="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" type="application/x-shockwave-flash"><param name="autoPlay" value="false" /><param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="flashvars" value='config={"clip":{"url":"http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv"},"autoPlay":"false","autoBuffering":"true"}' /></object>-->
                                    <div style="width:295px;height:260px; margin-left: -10px;" id="player"></div>
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
                                                <form action="http://list-manage.com/subscribe/post" id="subscribe-form" method="post" style="padding-top: 10px;">
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
                                                        float: left;">Follow Us</strong> 
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
                                    <div style="padding-top: 50px; font-size: 8pt;"><?= HOME_BOX_BOTTOM_LEFT_FOOTER ?></div>
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-2 white">
                                    <div class="box_top">
                                    </div>
                                    <div class="home_post clear">
                                        <div class="post_thumb">
                                        <?php
											$sql_latest_news=mysql_query("select * from news_posts order by date_posted DESC LIMIT 1");
											$row=mysql_fetch_assoc($sql_latest_news)
										?>
                                            <img src="images/<?= $row['image_small'] ?>" alt="<?= $row['title_' . $language_selected] ?>" />
                                            <div class="post_thumb_top">
                                            </div>
                                            <div class="post_thumb_down">
                                            </div>
                                        </div>
                                        
										<h2><a href="<?= "news.php?id=" . $row['id'] ?>"><?= $row['title_' . $language_selected] ?></a></h2>
										<?= substr($row['contents_' . $language_selected], 0, 450) . " ..." ?>                                                                                                                      
                                    </div>
                                    <div class="post_links clear">
                                        <span class="more"><a href="<?= "news.php?id=" . $row['id'] ?>" class="bold"><?= HOME_BOX_NEWS_READ_MORE ?></a></span> <span class="date">
                                            <strong><?= $row['date_posted'] ?></strong></span> 
                                    </div>
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
	
	<script type="text/javascript">
	    flowplayer("player", "videos/flowplayer-3.1.5.swf", {
	        clip: {
	            url: "videos/home_page_video.flv",
	            autoPlay: false
	        }
		});
	</script>
</body>
</html>
