<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:<?=HTML_PARAMS?> >
<head>
<title>
<?=$bsiCore->config['conf_hotel_sitetitle']?>
</title>
<meta name="description" content="<?=$bsiCore->config['conf_hotel_sitedesc']?>" />
<meta name="keywords" content="<?=$bsiCore->config['conf_hotel_sitekeywords']?>" />
<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />
<meta name="robots" content="all" />
<link rel="stylesheet" type="text/css" href="css/main.css" />

<!-- Pull in the JQUERY library -->

<!-- Pull in the JQUERY library -->
<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>
<!-- Pull in and set up the JFLOW functionality -->
<script type="text/javascript" src="scripts/jquery.flow.1.2.auto.js"></script>
<script type="text/javascript">
	$(document).ready(function(){	
		$("#myController").jFlow({
			slides: "#mySlides",
			controller: ".jFlowControl", // must be class, use . sign
			slideWrapper : "#jFlowSlide", // must be id, use # sign
			selectedWrapper: "jFlowSelected",  // just pure text, no sign
			width: "980px",
			height: "377px",
			duration: 600,
			prev: ".jFlowPrev", // must be class, use . sign
			next: ".jFlowNext", // must be class, use . sign
			auto: true
		});
	
	});
	</script>
<!-- Pull in and set up the DROPDOWN functionality -->
<script type="text/javascript" src="scripts/hoverIntent.js"></script>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$("ul.sf-menu").superfish(); 
}); 
</script>
<!-- required plugins -->
<script type="text/javascript" src="scripts/date.js"></script>
<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.min.js"></script><![endif]-->
<!-- jquery.datePicker.js -->
<script type="text/javascript" src="scripts/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css">
<!-- page specific styles -->
<link rel="stylesheet" type="text/css" media="screen" href="css/date.css">
<!-- page specific scripts -->
<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = '<?=$bsiCore->config['conf_dateformat']?>';

<?php if($langauge_selcted=='es'){ ?>

Date.dayNames = ['domingo', 'lunes', 'martes', 'Miércoles', 'jueves', 'viernes', 'Sábado'];
Date.abbrDayNames = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
Date.monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'Diciembre'];
Date.abbrMonthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
<?php } elseif($langauge_selcted=='de') { ?>
Date.dayNames = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
Date.abbrDayNames = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
Date.monthNames = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
Date.abbrMonthNames = ['Jan', 'Feb', 'Mrz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];
<?php } elseif($langauge_selcted=='fr') { ?>
Date.dayNames = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
Date.abbrDayNames = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
Date.monthNames = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'Décembre'];
Date.abbrMonthNames = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
<?php } ?>

$(function()
{	
	$('.date-pick').datePicker()
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];			
			if (d) {
				d = new Date(d);
				$('#end-date').dpSetStartDate(d.addDays(<?=$bsiCore->config['conf_min_night_booking']?>).asString());
			}
		}
	);
	$('#end-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#start-date').dpSetEndDate(d.addDays(-<?=$bsiCore->config['conf_min_night_booking']?>).asString());
			}
		}
	);
});
			
$(document).ready(function(){
	$('#btn_booking_status').click(function() { 	
	   if($('#booking_number').val()==""){
	  alert('<?=INDEX_JAVASCRIPT_BOOKING_NUMBER?>');
	  return false;
	  }	
	    $('#booking_status_wait').html("<img src='graphics/ajax-loader_2.gif' border='0'>")
		 var querystr = 'actioncode=1&booking_id='+$('#booking_number').val(); 	
			//alert(querystr);
		
		$.post("ajaxreq-processor.php", querystr, function(data){						
			//alert("1");						 
			if(data.errorcode == 0){
			     $('#booking_status_info').html(decode64(data.strhtml))
				 $('#booking_status_wait').html("")
			}
			else { 
				alert(data.strmsg);
				$('#booking_number').val("")
				$('#booking_status_wait').html("")
			}	
		}, "json");
		
	});

	$('#btn_room_search').click(function() { 		
	  	if($('#start-date').val()==""){
	  		alert('<?=INDEX_JAVASCRIPT_CHECK_IN?>');
	  		return false;
	 	}else if($('#end-date').val()==""){
	  		alert('<?=INDEX_JAVASCRIPT_CHECK_OUT?>');
	  		return false;
	  	} else {
	  		return true;
	 	}	  
	});	
	
	$("#booking_number").keypress(function (e)  
	{ 
	  //if the letter is not digit then display error and don't type anything
	  if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	  {
		//display error message
		$("#booking_status_digit").html("Digits Only").show().fadeOut("slow"); 
	    return false;
      }	
	});	
});
</script>
<script type="text/javascript" src="scripts/base64_decode.js"></script>
</head>

<body>
<div id="content">
  <?php include("header.php"); ?>
  
  <!-- Slider Area -->
  <div id="slider-content">
    <div id="myController"> 
      
      <!-- This area is hidden, but you need to add more of these if you add more slider DIVs
            The next one would be: ... <span class="jFlowControl">4</span> ... and so on -->
      <?php
			$count123=1;
			$sql_gallery1=mysql_query("select * from bsi_gallery where gallery_type=2");
			while($gallery_row1=mysql_fetch_assoc($sql_gallery1)){	
			?>
      <span class="jFlowControl">
      <?=count123?>
      </span>
      <?php } ?>
    </div>
    <div id="mySlides"> 
      
      <!-- Each DIV is a separate slider, just add more DIVs if you want more, but be sure to add
            more "jFlowControl" SPANs as described above, or it will not work. -->
      <?php
			$sql_gallery=mysql_query("select * from bsi_gallery where gallery_type=2");
			while($gallery_row=mysql_fetch_assoc($sql_gallery)){	
			?>
      <div><!-- Slider 1 --> 
        <img src="gallery/<?=$gallery_row['img_path']?>" width="980" height="313" />
        <div class="blue-block">
          <?=$gallery_row['description']?>
        </div>
      </div>
      <?php } ?>
    </div>
    <!-- END mySlides --> 
    
    <!-- Previous and Next Arrow Buttons (The text is hidden using CSS) --> 
    <span class="jFlowPrev">Previous</span> <span class="jFlowNext">Next</span> </div>
  <!-- END slider-content -->
  
  <div id="main-content">
    <div class="right">
      <h2 style=" font-size:18px;"><em><strong><?=LEFT_ONLINE_BOOKING?></strong></em></h2>
      <form name="search" action="booking-search.php" method="post">
      
        <table cellpadding="3" cellspacing="0" border="0" style="font-size:13px">
          <tr>
            <td style="font-size:12px;"><?=LEFT_CHECK_IN_DT?></td>
            <td><input name="check_in" id="start-date" readonly="readonly" class="date-pick"   style="width:70px;"/></td>
          </tr>
          <tr>
            <td style="font-size:12px;"><?=LEFT_CHECK_OUT_DT?></td>
            <td><input name="check_out" id="end-date" readonly="readonly" class="date-pick" style="width:70px;" /></td>
          </tr>
          <tr>
            <td style="font-size:12px;"><?=LEFT_CAPACITY?></td>
            <td valign="top"><select name="capacity"  style="width:60px;">
                <?php 
				$capacity_sql = mysql_query("SELECT DISTINCT (capacity) FROM bsi_capacity WHERE `id` IN (SELECT DISTINCT (capacity_id) FROM bsi_room) ORDER BY capacity");
				while($capacityrow = mysql_fetch_assoc($capacity_sql)){ 
					echo '<option value="'.$capacityrow["capacity"].'">'.$capacityrow["capacity"].'</option>';
				}
				?>
              </select></td>
          </tr>
          <?php
		  $child_sql = mysql_query("SELECT DISTINCT (no_of_child) FROM bsi_room WHERE no_of_child > 0 ORDER BY no_of_child");
		  if(mysql_num_rows($child_sql)){         
		  ?>
           <tr>
            <td style="font-size:12px;"><?=LEFT_CHILD_PER_ROOM?></td>
            <td valign="top"><select name="childcount"  style="width:60px;">
            <option value="0" selected="selected">0</option>
                <?php 
				while($childyrow = mysql_fetch_assoc($child_sql)){ 
					echo '<option value="'.$childyrow["no_of_child"].'">'.$childyrow["no_of_child"].'</option>';
				}
				?>
              </select></td>
          </tr>
          <?php } //End of child 
		  $extrabed_sql = mysql_query("SELECT DISTINCT (extra_bed) FROM bsi_room WHERE extra_bed > 0 ");
		  if(mysql_num_rows($extrabed_sql)){         
		  ?>
           <tr>
            <td style="font-size:12px;"><?=LEFT_EXTRA_BED_NEED?></td>
            <td><input type="checkbox" name="extrabed"  value="YES"></td>
          </tr>
          <?php } //End of extra bed ?>
          <tr>
            <td></td>
            <td><input type="submit" value="<?=INDEX_SEARCH_BTN?>" class="button2" id="btn_room_search"/></td>
          </tr>
        </table>
        <input type="hidden" name="allowlang" id="allowlang" value="no" />
      </form>
      <br />
      <br />
      <h2 style=" font-size:18px;"><em><strong><?=INDEX_BOOKING_STATUS?></strong></em></h2>
      <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
        <tr>
          <td align="center" ><?=INDEX_BOOKING_NUMBER_ENTER?></td>
        </tr>
        <tr>
          <td align="center"><input type="text" name="booking_number" id="booking_number" class="textbox4" style="text-align:center; font-size:20px; color:#000000; font-family:Arial, Helvetica, sans-serif; font-weight:bold; width:120px;" maxlength="10"  /></td>
        </tr>
        <tr>
          <td align="center"><input type="submit" value="<?=INDEX_STATUS_BTN?>" class="button2" id="btn_booking_status" /></td>
        </tr>
        <tr>
          <td align="center" id="booking_status_digit"></td>
        </tr>
        <tr>
          <td align="center" id="booking_status_wait"></td>
        </tr>
      </table>
    </div>
    <div class="left" id="booking_status_info">
      <h2><?=INDEX_WELCOME_TITLE?> <?=$bsiCore->config['conf_hotel_name']?>
        </h2>
      <p>
        <?=$index_content?><br />
       
      </p>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- END content -->
<?php include("footer.php"); ?>
</body>
</html>