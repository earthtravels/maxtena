<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
if(isset($_SERVER['HTTP_REFERER'])){
	if($_SERVER['HTTP_REFERER'] != $bsiCore->getweburl()."booking-search.php"){ 
		header('Location: booking-failure.php?error_code=9'); 
		die;
	} 
}else{ 
	header('Location: booking-failure.php?error_code=9'); 
	die;
}

include("includes/details.class.php");
$bsiCore->clearExpiredBookings();
$bsibooking = new bsiBookingDetails();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=HTML_PARAMS?>" lang="<?=HTML_PARAMS?>">
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
<!-- Pull in and set up the JFLOW functionality -->
<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>
<!-- Pull in and set up the DROPDOWN functionality -->
<script type="text/javascript" src="scripts/hoverIntent.js"></script>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">      
$(document).ready(function(){ 
	$("ul.sf-menu").superfish(); 
}); 
</script>
<link rel="stylesheet" type="text/css" media="screen" href="css/milk.css" />
<script src="scripts/jquery.validate.js" type="text/javascript"></script>
<script src="scripts/hotelvalidation.js" type="text/javascript"></script>
<script id="demo" type="text/javascript">
$(document).ready(function() {
	// validate signup form on keyup and submit
	var validator = $("#signupform").validate({
		rules: {
			fname: "required",
			lname: "required",
			str_addr: "required",
			city: "required",
			state: "required",
			zipcode: "required",
			country: "required",
			phone: "required",
			email: {
				required: true,
				email: true
			},
			payment_type: "required",
			tos: "required"
		},
		messages: {
			fname: "<?=DETAILS_JAVASCRIPT_FNAME?>",
			lname: "<?=DETAILS_JAVASCRIPT_LNAME?>",
			str_addr: "<?=DETAILS_JAVASCRIPT_STR_ADDR?>",
			city: "<?=DETAILS_JAVASCRIPT_CITY?>",
			state: "<?=DETAILS_JAVASCRIPT_STATE?>",
			zipcode: "<?=DETAILS_JAVASCRIPT_ZIP?>",
			country: "<?=DETAILS_JAVASCRIPT_COUNTRY?>",
			phone: "<?=DETAILS_JAVASCRIPT_PHONE?>",
			
			email: {
				required: "<?=DETAILS_JAVASCRIPT_EMAIL?>",
				minlength: "<?=DETAILS_JAVASCRIPT_EMAIL?>",
				remote: jQuery.format("{0} is already in use")
			},
			payment_type: "<?=DETAILS_JAVASCRIPT_PAYMENT?>",
			tos: "<i><?=DETAILS_JAVASCRIPT_TOS?></i>"
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			if ( element.is(":radio") )
				error.appendTo( element.parent().next().next() );
			else if ( element.is(":checkbox") )
				error.appendTo ( element.next() );
			else
				error.appendTo( element.parent().next() );
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
});

$(document).ready(function(){
    $('#discount_coupon_tr').hide();
	$('#btn_exisitng_cust').click(function() { 		
	    $('#exist_wait').html("<img src='graphics/ajax-loader_2.gif' border='0'>")
		 var querystr = 'actioncode=2&existing_email='+$('#email_addr_existing').val(); 	
			//alert(querystr);
		
		$.post("ajaxreq-processor.php", querystr, function(data){						
			//alert("1");						 
			if(data.errorcode == 0){
				$('#title').html(data.title)
				$('#fname').val(data.first_name)
				$('#lname').val(data.surname)
				$('#str_addr').val(data.street_addr)
				$('#city').val(data.city)
				$('#state').val(data.province)
				$('#zipcode').val(data.zip)
				$('#country').val(data.country)
				$('#phone').val(data.phone)
				$('#fax').val(data.fax)
				$('#email').val(data.email)
				 $('#exist_wait').html("")
			}
			else { 
				alert(decode64(data.strmsg));
				$('#fname').val('')
				$('#lname').val('')
				$('#str_addr').val('')
				$('#city').val('')
				$('#state').val('')
				$('#zipcode').val('')
				$('#country').val('')
				$('#phone').val('')
				$('#fax').val('')
				$('#email').val('')
				$('#exist_wait').html("")
			}	
		}, "json");
		
	});
	
	$('#btn_coupon_apply').click(function() {		
	    $('#apply_wait').html("<img src='graphics/ajax-loader_2.gif' border='0'>")
		 var querystr = 'actioncode=4&discountcoupon='+$('#discount_coupon').val();
		 querystr += '&clientemail='+$('#email').val(); 	
		
		$.post("ajaxreq-processor.php", querystr, function(data){
			if(data.errorcode == 0){
				$('#discount_coupon_tr').show()
				$('#email_addr_existing').attr("disabled","disabled")
				$('#email').attr("readonly","true")
				$('#coupondisplay').html("<?=BOOKING_DETAILS_COUPON_CODE?>: "+data.couponcode)
				$('#dicountdisplay').html(data.fmdiscount)
				$('#taxamountdisplay').html(data.fmtaxamount)
				$('#grandtotaldisplay').html(data.fmgrandtotal)				
				if(data.advamtmodified){$('#advancepaymentamount').html(data.fmadvamount)}
				$('#apply_wait').html("")
				$('#discount_coupon_input').html("<img src=\"graphics/checked.gif\" align=\"absmiddle\"/> <?=BOOKING_DETAILS_COUPON_PART1?> ("+data.couponcode+") <?=BOOKING_DETAILS_COUPON_PART2?>")		
			}
			else { 
				alert(decode64(data.strmsg))
				$('#coupondisplay').html("<?=DETAILS_JAVASCRIPT_COUPON_DISPLAY?>")
				$('#dicountdisplay').html(data.fmdiscount)
				$('#taxamountdisplay').html(data.fmtaxamount)
				$('#grandtotaldisplay').html(data.fmgrandtotal)		
				if(data.advamtmodified){$('#advancepaymentamount').html(data.fmadvamount)}					
				$('#apply_wait').html("")
				$('#discount_coupon_tr').hide()				
			}				
		}, "json");
		
	});
});
</script>
<script type="text/javascript" src="scripts/base64_decode.js"></script>
<style type="text/css">
.block {
	display: block;
}
form.signupform label.error {
	display: none;
}
</style>
</head>

<body>

<!-- Centers the page -->
<div id="content">
  <?php include("header.php"); ?>
  <div id="main-content">
    <div class="right">
      <h2><em><strong>
        <?=LEFT_ONLINE_BOOKING?>
        </strong></em></h2>
      <table cellpadding="3" style="font-size:13px">
        <tr>
          <td><?=LEFT_CHECK_IN_DT?>
            : </td>
          <td><?=$bsibooking->checkInDate?></td>
        </tr>
        <tr>
          <td><?=LEFT_CHECK_OUT_DT?>
            : </td>
          <td><?=$bsibooking->checkOutDate?></td>
        </tr>
        <tr>
          <td><?=LEFT_CAPACITY?>
            : </td>
          <td style="font-size:18px"><?=$bsibooking->guestsPerRoom?></td>
        </tr>
        <tr>
          <td><?=LEFT_TOTAL_NIGHT?>
            :</td>
          <td style="font-size:18px"><?=$bsibooking->nightCount?></td>
        </tr>
      </table>
      <br />
      <br />
      <h2 style="font-size:22px"><em><strong>
        <?=LEFT_TITLE_EXISTING_CUSTOMER?>
        </strong></em></h2>
      <table cellpadding="3" style="font-size:13px">
        <tr>
          <td><?=LEFT_TITLE_ENTER_EMAIl?></td>
        </tr>
        <tr>
          <td><input type="text" name="email_addr_existing" id="email_addr_existing" class="textbox4" style="font-size:12px; color:#000000; font-family:Arial, Helvetica, sans-serif; font-weight:bold; width:170px;" value=""  /></td>
        </tr>
        <tr>
          <td><input type="submit" value="<?=LEFT_FETCH_DETAILS_BTN?>" class="button2" id="btn_exisitng_cust" /></td>
        </tr>
        <tr>
          <td id="exist_wait" ></td>
        </tr>
      </table>
      <br />
      <br />
      <h2 style="font-size:22px"><em><strong><?=BOOKING_DETAILS_DISCOUNT_COUPON?></strong></em></h2>
      <div id="discount_coupon_input">
      <table cellpadding="3" style="font-size:13px">
        <tr>
          <td><?=BOOKING_DETAILS_COUPON_DESC?></td>
        </tr>
        <tr>
          <td><input type="text" name="discount_coupon" id="discount_coupon" class="textbox4" style="font-size:12px; color:#000000; font-family:Arial, Helvetica, sans-serif; font-weight:bold; width:170px;" value=""  /></td>
        </tr>
        <tr>
          <td><input type="submit" value="<?=BOOKING_DETAILS_BTN_APPY?>" class="button2" id="btn_coupon_apply" /></td>
        </tr>
        <tr>
          <td id="apply_wait" ></td>
        </tr>
      </table>
      </div>
    </div>
    <div class="left">
      <h2>
        <?=BOOKING_DETAILS_TITLE?>
      </h2>
      <p>
      <?php $bookingDetails = $bsibooking->generateBookingDetails(); ?>
      <table cellpadding="4" cellspacing="1" border="0" width="98%" bgcolor="#FFFFFF" style="font-size:12px">
      	<tr>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_CHECK_IN_DT?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_CHECK_OUT_DT?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_TOTAL_NIGHT?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=BOOKING_DETAILS_TOTAL_ROOMS?></strong></td>
        </tr> 
        <tr>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->checkInDate?></td>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->checkOutDate?></td>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->nightCount?></td>
          <td align="center" bgcolor="#ece7db"><?=$bsibooking->totalRoomCount?></td>          
        </tr>
        <tr>
          <td bgcolor="#c7b998" align="center"><strong><?=BOOKING_DETAILS_ROOM_NUMBER?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=BOOKING_DETAILS_ROOM_TYPE?></strong></td>
          <td bgcolor="#c7b998" align="center"><strong><?=LEFT_CAPACITY?></strong></td>
          <td bgcolor="#c7b998" align="right"><strong><?=BOOKING_DETAILS_GROSS_TOTAL?></strong></td>
        </tr>
        <?php		
		foreach($bookingDetails as $bookings){		
			echo '<tr>';
			echo '<td align="center" bgcolor="#ece7db">'.$bookings['roomno'].'</td>';
			echo '<td align="center" bgcolor="#ece7db">'.$bookings['roomtype'].' ('.$bookings['capacitytitle'].')</td>';
			if($bookings['maxchild'] > 0){				
				if($bookings['extrabed'] == "yes"){
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult + '.$bookings['maxchild'].'  Child <br>Including Extra Bed</td>';
				}else{
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult + '.$bookings['maxchild'].' Child</td>';
				}
			}else{				
				if($bookings['extrabed'] == "yes"){
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult <br>Including Extra Bed</td>';
				}else{
					echo '<td align="center" bgcolor="#ece7db">'.$bookings['capacity'].' Adult</td>';
				}
			}		
			echo '<td align="right" bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].number_format($bookings['grosstotal'], 2 , '.', ',').'</td>';
			echo '</tr>';		
		}
		
 		if($bsibooking->listHotelExtraService){					
			echo '<tr><td colspan="4" bgcolor="#c7b998"><strong>Hotel Extras</strong></td></tr>';
			foreach($bsibooking->listHotelExtraService as $hextra){
                echo '<tr><td colspan="3"  bgcolor="#ece7db"><img src="graphics/checked.gif" align="absmiddle"/> '.$hextra['description'].'</td><td align="right" bgcolor="#ece7db">&nbsp;'.$bsiCore->config['conf_currency_symbol'].$hextra['price'].'</td></tr>';
            }              			
		}
		?>       
        
        <tr>
          <td colspan="3" align="right" bgcolor="#c7b998"><strong><?=BOOKING_DETAILS_COMULATIVE_TOTAL?></strong></td>
          <td bgcolor="#c7b998" align="right"><strong><?=$bsiCore->config['conf_currency_symbol']?><?=number_format($bsibooking->roomPrices['subtotal'], 2 , '.', ',')?></strong></td>
        </tr>
        
        <?php
        if($bsiCore->config['conf_enabled_discount'] && $bsibooking->discountPlans['discount_percent'] > 0){
		?>
        <tr>
          <td colspan="3" align="right" bgcolor="#ece7db"><?=BOOKING_DETAILS_DISCOUNT_SCHEME?> (<span id="mothlydicountdisplaytext" style="font-size:11px;"><?=$bsibooking->discountPlans['discount_percent']?>%</span>)</td>
          <td align="right" bgcolor="#ece7db">(-) <?=$bsiCore->config['conf_currency_symbol'].number_format($bsibooking->roomPrices['mothlydiscount'], 2 , '.', ',')?></span></td>
        </tr>
        <?php
		}
		?>
        
        <tr id="discount_coupon_tr">
          <td colspan="3" align="right" bgcolor="#ece7db"><img src="graphics/checked.gif" align="absmiddle"/> <?=BOOKING_DETAILS_DISCOUNT_COUPON?> (<span id="coupondisplay" style="font-size:11px;">Apply Discount Coupon if you have</span>) </td>
          <td align="right" bgcolor="#ece7db">(-) <?=$bsiCore->config['conf_currency_symbol']?><span id="dicountdisplay">0.00</span></td>
        </tr>
        
        <tr>
          <td colspan="3" align="right" bgcolor="#ece7db"><?=BOOKING_DETAILS_TAX?>
            (<?=$bsiCore->config['conf_tax_amount']?>%)</td>
          <td align="right" bgcolor="#ece7db">(+) <?=$bsiCore->config['conf_currency_symbol']?><span id="taxamountdisplay"><?=number_format($bsibooking->roomPrices['totaltax'], 2 , '.', ',')?></span></td>
        </tr>
        <tr>
          <td colspan="3" align="right" bgcolor="#c7b998"><strong><?=BOOKING_DETAILS_GRAND_TOTAL?></strong></td>
          <td align="right" bgcolor="#c7b998"><strong><?=$bsiCore->config['conf_currency_symbol']?><span id="grandtotaldisplay"><?=number_format($bsibooking->roomPrices['grandtotal'], 2 , '.', ',')?></span></strong></td>
        </tr>
        <?php 
		if($bsiCore->config['conf_enabled_deposit'] && ($bsibooking->discountPlans['deposit_percent'] > 0 && $bsibooking->discountPlans['deposit_percent'] < 100)){
		?>
        <tr id="advancepaymentdisplay"> 
          <td colspan="3" align="right" bgcolor="#c7b998"><strong><?=BOOKING_DETAILS_DEPOSIT_SCHEME?></strong> (<span style="font-size:11px;"><?=$bsibooking->discountPlans['deposit_percent']?>% of <?=BOOKING_DETAILS_GRAND_TOTAL?></span>)</td>
          <td align="right" bgcolor="#c7b998"><strong><?=$bsiCore->config['conf_currency_symbol']?><span id="advancepaymentamount"><?=number_format($bsibooking->roomPrices['advanceamount'], 2 , '.', ',')?></span></strong></td>
        </tr>
        <?php
        }?> 
      </table>      
      </p>
      <p>
      <form method="post" action="booking-process.php" id="signupform" class="signupform">
        <input type="hidden" name="allowlang" id="allowlang" value="no" />    
        <table cellpadding="4" cellspacing="0" border="0" >
          <tr>
            <td><?=BOOKING_DETAILS_CLIENT_TITLE?>
              :</td>
            <td id="title"><select name="title" class="textbox3">
                <option value="Mr.">Mr.</option>
                <option value="Ms.">Ms.</option>
                <option value="Mrs.">Mrs.</option>
                <option value="Miss.">Miss.</option>
                <option value="Dr.">Dr.</option>
                <option value="Prof.">Prof.</option>
              </select></td>
            <td></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_FNAME?>
              :</td>
            <td><input type="text" name="fname" id="fname"  class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_LNAME?>
              :</td>
            <td><input type="text" name="lname" id="lname"  class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_STR_ADDR?>
              :</td>
            <td><input type="text" name="str_addr" id="str_addr"  class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_CITY?>
              :</td>
            <td><input type="text" name="city"  id="city" class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_STATE?>
              :</td>
            <td><input type="text" name="state"  id="state" class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_ZIP?>
              :</td>
            <td><input type="text" name="zipcode"  id="zipcode" class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_COUNTRY?>
              :</td>
            <td><input type="text" name="country"  id="country" class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_PHONE?>
              :</td>
            <td><input type="text" name="phone"  id="phone" class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_FAX?>
              :</td>
            <td><input type="text" name="fax"  id="fax" class="textbox" /></td>
            <td></td>
          </tr>
          <tr>
            <td><?=BOOKING_DETAILS_EMAIL?>
              :</td>
            <td><input type="text" name="email"  id="email" class="textbox" /></td>
            <td  class="status"></td>
          </tr>
          <tr>
            <td valign="top"><?=BOOKING_DETAILS_PAYMENT_OPTION?>
              :</td>
            <td><?php
				$paymentGatewayDetails = $bsiCore->loadPaymentGateways();				
				foreach($paymentGatewayDetails as $key => $value){ 	
					echo '<input type="radio" name="payment_type" id="payment_type_'.$key.'" value="'.$key.'" />'.$value['name'].'<br />';
				}
				?></td>
            <td  class="status"><label for="payment_type" class="error">Please select payment method</label></td>
          </tr>
          <tr>
            <td valign="top" nowrap="nowrap"><?=BOOKING_DETAILS_ADDITIONAL_REQUEST?>
              :</td>
            <td colspan="2"><textarea name="message" rows="1" cols="1" class="textarea"></textarea></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2"><input type="checkbox" name="tos" id="tos" value="" />
              <?=BOOKING_DETAILS_AGREE_TEXT?>
              <a href="javascript: ;" onclick="javascript:myPopup2();">
              <?=BOOKING_DETAILS_TERMS_LINK?>
              .</a></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" value="<?=BOOKING_DETAILS_CHECKOUT_BTN?>" class="button" /></td>
            <td></td>
          </tr>
        </table>
      </form>
      </p>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- END content -->
<?php include("footer.php"); ?>
</body>
</html>