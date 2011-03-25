<?php
include ("access.php");
if(isset($_GET['edid'])){
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->del_hotel_coupon();
header("location: discount_coupon.php");
}
include ("header.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$hotel_coupon=$bsiAdminMain->hotel_coupon();
?>
<!-- jquery.datePicker.js -->
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../scripts/date.js"></script>
<script type="text/javascript" src="../scripts/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/datePicker.css">
<!-- page specific styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/date.css">
<!-- page specific scripts -->
<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = '<?=$bsiCore->config['conf_dateformat']?>';
$(function()
{
	
	$('.date-pick').datePicker()
	$('#exp-date').bind(
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
	
});
</script>
<script language="javascript">
$(document).ready(function() {
$('#td_cust_title').hide()
$('#td_cust_email').hide()
	$('#coupon_category').change(function() { 
		if($('#coupon_category').val()==3){
		$('#td_cust_title').show()
		$('#td_cust_email').show()
		}else{
		$('#td_cust_title').hide()
		$('#td_cust_email').hide()
		}	
	});
   //add extrass ***********************
   $('#coupon_sbmt').click(function() { 
   	  if($('#coupon_code').val() != "" && $('#discount_amt').val() != ""){
	       if($('#coupon_category').val()==3 && $('#cust_email').val() == ""){
		   		  alert("Customer email can't be blank!");
		   }else{
				  var querystr = 'actioncode=8&coupon_code='+$('#coupon_code').val()+'&discount_amt='+$('#discount_amt').val()+'&min_amt='+$('#min_amt').val()+'&exp-date='+$('#exp-date').val()+'&coupon_category='+$('#coupon_category').val()+'&cust_email='+$('#cust_email').val()+'&rad_discount_type='+$('input:radio[name=rad_discount_type]:checked').val()+'&chk_reusecoupon='+$('input:checkbox:checked').val();  	
				  //alert(querystr);
				  $.post("admin_ajax_processor.php", querystr, function(data){
						if(data.errorcode == 0){
							alert(data.strmsg);
							location.reload();
						}else{
							alert(data.strmsg);
						}		  
				  }, "json");
		   }
	  }else{
	  	  alert("Coupon code or discount amount can't be blank!");
	  }
   
   });
   //add extrass ***********************
});
</script>
</td>
</tr>

<tr>
  <td height="400" valign="top" align="left">
  <table cellpadding="5" cellspacing="0" border="0" class="bodytext"  style="border:solid 1px #999999;">
  <tr><td class="SubPageHead" align="center" style="border:solid 1px #999999;">Discount Coupon</td></tr>
  <tr><td style="border:solid 1px #999999;">
      <table cellpadding="5" cellspacing="0" border="0">
      <tr><td colspan="6" class="TitleBlue11pt" align="center">Add Discount Coupon</td></tr>
      <tr><td>Coupon Code:</td><td><input type="text" name="coupon_code" id="coupon_code" /></td><td>Discount Amount:</td><td colspan="3"><input type="text" name="discount_amt" id="discount_amt"  size="13"/> <input type="radio" name="rad_discount_type" id="rad_discount_type" value="1" checked="checked" />Persent &nbsp;&nbsp; <input type="radio" name="rad_discount_type" id="rad_discount_type" value="0" />Fixed</td></tr>
      <tr><td>Minimum Amount:</td><td><input type="text" name="min_amt" id="min_amt" size="13"/></td><td>Expiry date:</td><td><input name="exp-date" id="exp-date" class="date-pick"  readonly="readonly"  size="10"/></td><td colspan="2">Reuse Coupon per customer? <input type="checkbox" name="chk_reusecoupon"  id="chk_reusecoupon" value="1" /></td></tr>
      <tr><td>Coupon Allow for:</td><td><select name="coupon_category" id="coupon_category"><option value="1">All customer</option><option value="2">Existing all customer</option><option value="3">One selected customer</option></select></td><td id="td_cust_title">Customer Email:</td><td id="td_cust_email"><input type="text" name="cust_email" id="cust_email" /></td><td colspan="2"></td></tr>
     
      <tr><td colspan="6"><input type="submit" value="Submit" name="coupon_sbmt" id="coupon_sbmt" /> </td></tr>
      </table>
  </td></tr>
  <tr><td style="border:solid 1px #999999;">
      <table cellpadding="5" cellspacing="0" border="1">
      <tr><td class="TitleBlue11pt">Coupon Code</td><td class="TitleBlue11pt">Amount</td><td class="TitleBlue11pt">Minimum Booking</td><td class="TitleBlue11pt">Expires</td><td class="TitleBlue11pt">Customer Allow</td><td class="TitleBlue11pt">Reuse per customer</td><td></td></tr>
      <?=$hotel_coupon['hotel_coupon_view']?>
      </table>
  </td></tr>
  </table>
  
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>