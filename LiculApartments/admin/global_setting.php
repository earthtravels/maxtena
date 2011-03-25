<?php
include ("access.php");
if(isset($_POST['act_sbmt'])){
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->global_setting_post();
header("location:global_setting.php");
}
include ("header.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$global_setting=$bsiAdminMain->global_setting();
?>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){
<?php  if($bsiCore->config['conf_smtp_mail']=='true'){ ?>
$('#smtp_info').show()
<?php } else { ?>
$('#smtp_info').hide()
<?php } ?>
$('#email_send_by').click(function() { 

	if($('#email_send_by').val()=='true'){
	$('#smtp_info').show()
	}else{
	$('#smtp_info').hide()
	}
	
	

}, "json");

});
</script>
	</td>
  </tr> 
  
  <tr>
    <td valign="top" >
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <fieldset>
    <legend class="TitleBlue11pt">SEO DETAILS</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
    <tr><td>Website Title:</td><td><input type="text" name="title" size="50" value="<?=$bsiCore->config['conf_hotel_sitetitle']?>"/></td></tr>
    <tr><td valign="top">Website Description:</td><td><textarea cols="40" rows="3" name="desc"><?=$bsiCore->config['conf_hotel_sitedesc']?></textarea></td></tr>
    <tr><td valign="top">Website Keyword:</td><td><textarea cols="40" rows="3" name="keywords"><?=$bsiCore->config['conf_hotel_sitekeywords']?></textarea></td></tr>
    </table>
    </fieldset><br/>
    
    <fieldset>
    <legend class="TitleBlue11pt">EMAIL SETTING</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
    <tr><td>Email Send By:</td><td>
    <select name="email_send_by" id="email_send_by">
    <?php
	if($bsiCore->config['conf_smtp_mail']=='true'){
	?>
    <option value="false" >PHP Mail</option>
    <option value="true" selected>SMTP Authentication Mail</option>
    <?php } else { ?>
    <option value="false" selected>PHP Mail</option>
    <option value="true">SMTP Authentication Mail</option>
    <?php } ?>
    </select>
    </td></tr>
    <tr><td></td><td id="smtp_info">
        <table cellpadding="3" cellspacing="0" border="0" class="bodytext">
        <tr><td>SMTP Host</td><td><input type="text" name="smtphost" value="<?=$bsiCore->config['conf_smtp_host']?>"/></td></tr>
        <tr><td>SMTP Port</td><td><input type="text" name="smtpport" value="<?=$bsiCore->config['conf_smtp_port']?>"/></td></tr>
        <tr><td>SMTP Username</td><td><input type="text" name="smtpuser" value="<?=$bsiCore->config['conf_smtp_username']?>"/></td></tr>
        <tr><td>SMTP Password</td><td><input type="password" name="smtppass" value="<?=$bsiCore->config['conf_smtp_password']?>"/></td></tr>
        </table>
    </td>
    </table>
    </fieldset><br/>
    
    <fieldset>
    <legend class="TitleBlue11pt">CURRENCY SETTING</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
    <tr><td>Currency Code:</td><td><input type="text" name="currency_code" value="<?=$bsiCore->config['conf_currency_code']?>" size="10"/></td></tr>
    <tr><td>Currency Symbol:</td><td><input type="text" name="currency_symbol" value="<?=$bsiCore->config['conf_currency_symbol']?>" size="4"/></td></tr>
    </table>
    </fieldset><br/>
    
    <fieldset>
    <legend class="TitleBlue11pt">OTHERS SETTING</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
     <tr><td>Booking Engine:</td><td>
    <select name="booking_turn"><?=$global_setting['select_booking_turn']?></select></td></tr>
    <tr><td>Hotel Timezone:</td><td>
    <select name="timezone"><?=$global_setting['select_timezone']?></select></td></tr>
    <tr><td>Minimum Booking:</td><td>
    <select name="minbooking"><?=$global_setting['select_min_booking']?></select> Night(s)</td></tr>
    <tr><td>Date Format:</td><td>
    <select name="date_format"><?=$global_setting['select_dt_format']?></select></td></tr>
    <tr><td nowrap="nowrap">Room Lock Time:</td><td>
    <select name="room_lock"><?=$global_setting['select_room_lock']?></select> <span style="font-size:10px">Note: Duration for customer selected Room(s) will be lock when checkout redirect to payment gateway.</span></td></tr>
    <tr><td>Tax:</td><td><input type="text" name="tax" size="6" value="<?=$bsiCore->config['conf_tax_amount']?>" />%</td></tr>
    </table>
    </fieldset><br />
    <input type="hidden" name="act_sbmt" value="1" />
    <input  src="images/button_update.gif" name="SBMT_REG" type="image">

    </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
