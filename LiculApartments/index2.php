<?php 
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="robots" content="ALL,FOLLOW" />
    <meta name="Author" content="AIT" />
    <meta http-equiv="imagetoolbar" content="no" />
    <title>rabaccroatia.com</title>
    <link rel="stylesheet" href="./css/reset.css" type="text/css" />
    <link rel="stylesheet" href="./css/jquery.fancybox.css" type="text/css" />
    <link rel="stylesheet" href="./css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="./css/screen.css" type="text/css" />
    <link rel="stylesheet" href="./css/date.css" type="text/css" />
    <link rel="stylesheet" href="./css/datePicker.css" type="text/css" />

    <script type="text/javascript">
        Date.firstDayOfWeek = 0;
        Date.format = '<?=$bsiCore->config['conf_dateformat']?>';

        <?php if($langauge_selcted=='es'){ ?>
        Date.dayNames = ['domingo', 'lunes', 'martes', 'Mi�rcoles', 'jueves', 'viernes', 'S�bado'];
        Date.abbrDayNames = ['dom', 'lun', 'mar', 'mi�', 'jue', 'vie', 's�b'];
        Date.monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'Diciembre'];
        Date.abbrMonthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        <?php } elseif($langauge_selcted=='de') { ?>
        Date.dayNames = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
        Date.abbrDayNames = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
        Date.monthNames = ['Januar', 'Februar', 'M�rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
        Date.abbrMonthNames = ['Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];
        <?php } elseif($langauge_selcted=='fr') { ?>
        Date.dayNames = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        Date.abbrDayNames = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
        Date.monthNames = ['janvier', 'f�vrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'ao�t', 'septembre', 'octobre', 'novembre', 'D�cembre'];
        Date.abbrMonthNames = ['janv.', 'f�vr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'ao�t', 'sept.', 'oct.', 'nov.', 'd�c.'];
        <?php } ?>
    </script>

</head>
<body class="light">
    <!-- setting of light/dark main page box -->
    <div class="back">
        <div class="base">
            <?php include("header.php"); ?>
            <div class="page_top">
                <div id="slider" class="slider">
                    <div class="picture box_full white">
                        <!-- setting of white/black box -->
                        <div class="top">
                        </div>
                        <div class="middle">
                            <div class="wrap">
                                <div class="slides">
                                    <a href="#">
                                        <img src="./images/tmp/slide1.jpg" alt="" class="slide" title="" /></a>
                                    <a href="#">
                                        <img src="./images/tmp/slide2.jpg" alt="" class="slide" title="" /></a>
                                    <a href="#">
                                        <img src="./images/tmp/slide3.jpg" alt="" class="slide" title="" /></a>
                                    <a href="#">
                                        <img src="./images/tmp/slide4.jpg" alt="" class="slide" title="" /></a>
                                    <a href="#">
                                        <img src="./images/tmp/slide5.jpg" alt="" class="slide" title="" /></a>
                                </div>
                                <!-- /.slides -->
                                <!-- /.descriptions -->
                            </div>
                        </div>
                        <div class="down">
                        </div>
                    </div>
                    <div class="nav box_full white">
                        <!-- setting of white/black box -->
                        <div class="top">
                        </div>
                        <div class="middle">
                        </div>
                        <div class="down">
                        </div>
                    </div>
                </div>
                <div class="breadcrumb">
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
                                            Reserve Now <span>Reserve your dream vacation now!</span>
                                        </h2>
                                        <!--div id="main" class="clear"-->
                                        <div id="book">
                                            <form id="contact_form" action="#" method="post">
                                            <div class="clear">
                                                <label for="name">
                                                    Check In Date</label>
                                                <!--<input type="text" name="form[check_in_date]" id="check_in_date" class="input required" />-->
                                                    <input name="check_in" id="start-date" readonly="readonly" class="date-pick input" />
                                            </div>
                                            <!-- /.clear -->
                                            <div class="clear">
                                                <label for="subject">
                                                    Check Out Date</label>
                                                <input name="check_out" id="end-date" readonly="readonly" class="date-pick input" />
                                                <!--<input type="text" name="form[check_out_date]" id="check_out_date" class="input required" />-->
                                            </div>
                                            <!-- /.clear -->
                                            <div class="clear">
                                                <label for="select">
                                                    Adults</label>
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
                                                    Children</label>
                                                <select id="children" class="select">
                                                    <option>None</option>
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
                                            <div class="confirm clear">
                                                <input type="submit" value="Submit" class="submit" />
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
                                            Affordable Prices <span>Subtitle about our affordable prices goes here.</span>
                                        </h2>
                                    </div>
                                    <p>
                                        Text about our affordable prices goes here. Text about our affordable prices goes
                                        here. Text about our affordable prices goes here. Text about our affordable prices
                                        goes here. Text about our affordable prices goes here. Text about our affordable
                                        prices goes here.</p>
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-1">
                                    <div class="box_top">
                                    </div>
                                    <!--<object width="290" height="260" id="_player" name="_player" data="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" type="application/x-shockwave-flash"><param name="autoPlay" value="false" /><param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="flashvars" value='config={"clip":{"url":"http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv"},"autoPlay":"false","autoBuffering":"true"}' /></object>-->
                                    <div style="width:290px;height:260px;" id="player"></div>
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
                                <div class="box box_3-1 black">
                                    <div class="box_top">
                                    </div>
                                    <div style="font-size: 100%; vertical-align: baseline; line-height: 1; color: #3f3f38;">
                                        <div>
                                            <div>
                                                <h2>
                                                    Subscribe to Our Newsletter</h2>
                                                <form action="http://list-manage.com/subscribe/post" id="subscribe-form" method="post"
                                                name="contact_form" style="padding-top: 10px;">
                                                <div>
                                                    <input style="margin-top: -2px; width: 240px; background: none repeat scroll 0% 0% rgb(250, 250, 250);
                                                        color: rgb(101, 100, 90); padding: 5px 10px; float: left; vertical-align: middle;
                                                        font: 99% arial,helvetica,clean,sans-serif; border: 1px solid rgb(221, 221, 221);
                                                        -moz-border-radius: 5px 5px 5px 5px;" id="email" name="email" tabindex="2" type="text" />
                                                </div>
                                                <div style="padding-right: 15px; width: 110px; padding-top: 35px;">
                                                    <input style="font-weight: 400; text-align: center; text-indent: 0; -moz-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;
                                                        background-color: #3f3f38; border-bottom: 1px solid #4d4d44; border-top: 1px solid #4d4d44;
                                                        cursor: pointer; vertical-align: middle; font: 99% arial,helvetica,clean,sans-serif;
                                                        float: left; margin-top: 0px !important; font-size: 13px; display: block; color: white !important;
                                                        text-decoration: none !important; position: relative; -webkit-transition: opacity .2s;
                                                        -moz-transition: opacity .2s; -o-transition: opacity .2s; -webkit-border-radius: 5px;
                                                        -moz-border-radius: 5px; padding: 4px 0 4px; letter-spacing: 0.9px; background-image: url(images/btn_overlay.png);
                                                        background-position: 0 50%; background-repeat: repeat-x; width: 100%; border: 0;
                                                        -webkit-box-shadow: 0px 1px 0 #292925, 0px -1px 0 #292925;" name="submit" type="submit"
                                                        value="Subscribe" />
                                                </div>
                                                <p style="padding: 0; color: #787975; line-height: 1.4em; float: right;">
                                                    <strong style="padding-left: 15px; margin: 0; padding: 0; border: 0; outline: 0;
                                                        font-size: 100%; vertical-align: baseline; background: transparent; margin-top: 5px !important;
                                                        float: left;">Follow Us</strong> <a href="http://www.twitter.com/mailchimp"
                                                            title="MailChimp on Twitter" style="margin: 0; padding: 0; border: 0; font-size: 100%;
                                                            vertical-align: baseline; background: transparent; color: inherit; text-decoration: none;">
                                                            <img alt="" style="margin: 0 0 0 10px;" src="images/icon_twitter.png" /></a>
                                                    <a href="http://www.facebook.com/mailchimp" title="rabaccratia on Facebook">
                                                        <img alt="Facebook Icon" src="images/icon_facebook.png" style="margin: 0 0 0 10px;
                                                            padding: 0; border: 0; outline: 0; font-size: 100%; vertical-align: baseline;
                                                            background: transparent; float: left;" /></a>
                                                </p>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box_down">
                                    </div>
                                </div>
                                <div class="box box_3-2 white">
                                    <div class="box_top">
                                    </div>
                                    <div class="home_post clear">
                                        <div class="post_thumb">
                                            <img src="./images/tmp/thumbnail1.jpg" alt="" />
                                            <div class="post_thumb_top">
                                            </div>
                                            <div class="post_thumb_down">
                                            </div>
                                        </div>
                                        <h2>
                                            <a href="#">The Most Important Events</a></h2>
                                        <p>
                                            Your latest news article text here. Your latest news article text here.
                                            Your latest news article text here. Your latest news article text here.
                                            Your latest news article text here. Your latest news article text here.
                                        </p>
                                    </div>
                                    <div class="post_links clear">
                                        <span class="more"><a href="#" class="bold">read more</a></span> <span class="date">
                                            <strong>4.5.2010</strong></span>
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
            <div id="footer" class="clear">
                <div class="copy">
                    <p>
                        <a href="../../../www.ait.sk">&copy; 2011 rabaccroatia.com</a>
                        <br />
                        Lo&#353;injska 26, Rabac – Croatia (<a href="http://maps.google.com/?q=Lošinjska%2026,%20Labin,%20Croatia" target="_blank">Google map</a>)<br />
                        Phone:  ++385 52 872 153<br />
                        Reservations: 1-800-XXX-XXXX<br />
                        Email: <a href="mailto:OurEmail@OurEmail">OurEmail@OurEmail</a>
                    </p>
                </div>
                <div class="links">
                    <li><a href="./index.html">Home</a><span class="sep">|</span></li>
                    <li><a href="./reserve_now.html">Reservations</a><span class="sep">|</span></li>
                    <li><a href="apartments.html">Accomodations</a><span class="sep">|</span></li>
                    <li><a href="./location.html">Location</a><span class="sep">|</span></li>
                    <li><a href="./about.html">About</a><span class="sep">|</span></li>
                    <li><a href="./photos.html">Photos</a><span class="sep">|</span></li>
                    <li><a href="./contact.html">Contact</a></li>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/jquery.validate.js"></script>
    <script type="text/javascript" src="./js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="./js/jquery.nivo.js"></script>
    <script type="text/javascript" src="./js/cufon.js"></script>
    <script type="text/javascript" src="./js/geometr231_hv_bt_400.font.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
    <script type="text/javascript" src="./js/jquery.datePicker.js"></script>
    <script type="text/javascript" src="./js/date.js"></script>
    <script type="text/javascript" src="./js/flowplayer-3.2.6.min.js"></script>


    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-12958851-6']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        Date.firstDayOfWeek = 0;
        Date.format = 'mm/dd/yyyy';

        $(function () {

            $('.date-pick').datePicker()
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


    </script>

    <script type="text/javascript">

        flowplayer("player", "videos/flowplayer-3.1.5.swf", {
            clip: {
                url: "videos/gorilla.flv",
                autoPlay: false
            }
        });



    </script>

</body>
</html>