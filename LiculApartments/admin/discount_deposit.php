<?php
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
if($bsiCore->config['conf_enabled_discount']==1)
$discount_check="checked";
else
$discount_check="";

if($bsiCore->config['conf_enabled_deposit']==1)
$deposit_check="checked";
else
$deposit_check="";
?>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
   if($('#chk_discount').attr('checked')==true){
   $('#enabled_discount').show();
   }else{
   $('#enabled_discount').hide();
   }
   
   if($('#chk_deposit').attr('checked')==true){
   $('#enabled_deposit').show();
   }else{
   $('#enabled_deposit').hide();
   }
   
   $('#update_msg').hide();
   $('#chk_discount').click(function() { 
			var querystr = 'actioncode=4&chk_discount='+$('#chk_discount').attr('checked'); 	
			//alert(querystr);
			$.post("admin_ajax_processor.php", querystr, function(data){						
				//alert("1");						 
				if(data.errorcode == 0){
				$('#enabled_discount').show();
				$('#update_msg').show();
				$('#update_msg').html(data.strhtml);
				}else{
				$('#enabled_discount').hide();
				$('#update_msg').show();
				$('#update_msg').html(data.strhtml);
				}
				
			}, "json");
	 });
	
	
	 $('#chk_deposit').click(function() { 
			var querystr = 'actioncode=4&chk_deposit='+$('#chk_deposit').attr('checked'); 	
			//alert(querystr);
			$.post("admin_ajax_processor.php", querystr, function(data){						
				//alert("1");						 
				if(data.errorcode == 0){
				$('#enabled_deposit').show();
				$('#update_msg').show();
				$('#update_msg').html(data.strhtml);
				}else{
				$('#enabled_deposit').hide();
				$('#update_msg').show();
				$('#update_msg').html(data.strhtml);
				}
				
			}, "json");
	 });
	 
	 $('#submit_discount').click(function() { 
	        var querystr = 'actioncode=5&discount_january='+$('#discount_january').val()+'&discount_february='+$('#discount_february').val()+'&discount_march='+$('#discount_march').val()+'&discount_april='+$('#discount_april').val()+'&discount_may='+$('#discount_may').val()+'&discount_june='+$('#discount_june').val()+'&discount_july='+$('#discount_july').val()+'&discount_august='+$('#discount_august').val()+'&discount_september='+$('#discount_september').val()+'&discount_october='+$('#discount_october').val()+'&discount_november='+$('#discount_november').val()+'&discount_december='+$('#discount_december').val(); 	
			//alert(querystr);
			$.post("admin_ajax_processor.php", querystr, function(data){
			   if(data.errorcode == 0){
			   $('#update_msg').show();
			   $('#update_msg').html(data.strmsg);
			   }
			}, "json");
	 });
	 
	 $('#submit_deposit').click(function() { 
	        var querystr = 'actioncode=6&deposit_january='+$('#deposit_january').val()+'&deposit_february='+$('#deposit_february').val()+'&deposit_march='+$('#deposit_march').val()+'&deposit_april='+$('#deposit_april').val()+'&deposit_may='+$('#deposit_may').val()+'&deposit_june='+$('#deposit_june').val()+'&deposit_july='+$('#deposit_july').val()+'&deposit_august='+$('#deposit_august').val()+'&deposit_september='+$('#deposit_september').val()+'&deposit_october='+$('#deposit_october').val()+'&deposit_november='+$('#deposit_november').val()+'&deposit_december='+$('#deposit_december').val(); 	
			//alert(querystr);
			$.post("admin_ajax_processor.php", querystr, function(data){
			   if(data.errorcode == 0){
			   $('#update_msg').show();
			   $('#update_msg').html(data.strmsg);
			   }
			}, "json");
	 });
	 
});
</script>
</td>
</tr>

<tr>
  <td height="400"  align="left" valign="top"><table cellpadding="5" cellspacing="0" border="0" class="bodytext" width="650" style="border:solid 1px #999999;">
      <tr>
        <td class="SubPageHead" align="center" style="border:solid 1px #999999;" width="50%">Monthly Discount Scheme</td>
        <td class="SubPageHead" align="center" style="border:solid 1px #999999;" width="50%">Monthly Deposit Scheme</td>
      </tr>
      <tr>
        <td style="border:solid 1px #999999;">Enabled Monthly discount scheme?
          <input type="checkbox" name="chk_discount" id="chk_discount" <?=$discount_check?> value="1"></td>
        <td style="border:solid 1px #999999;">Enabled Monthly deposit scheme?
          <input type="checkbox" name="chk_deposit" id="chk_deposit" <?=$deposit_check?> value="1"></td>
      </tr>
      <tr>
        <td style="border:solid 1px #999999;" >
        <table cellpadding="0" cellspacing="0" border="0">
        <tr><td id="enabled_discount">
        <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
            <?php
	$sql_discount=mysql_query("select * from bsi_deposit_discount");
	while($row_discount=mysql_fetch_assoc($sql_discount)){
	?>
            <tr>
              <td><?=$row_discount['month']?>
                :</td>
              <td><input type="text" name="discount_<?=strtolower($row_discount['month'])?>" id="discount_<?=strtolower($row_discount['month'])?>" size="5" value="<?=$row_discount['discount_percent']?>"/>
                % of Total Amount</td>
            </tr>
            <?php } ?>
            <tr>
              <td></td>
              <td><input value="1" src="images/button_update.gif" name="submit_discount" id="submit_discount" type="image"/></td>
            </tr>
          </table>
          </td></tr></table>
          </td>
        <td style="border:solid 1px #999999;">
        <table cellpadding="0" cellspacing="0" border="0">
        <tr><td id="enabled_deposit">
        <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
            <?php
	$sql_deposit=mysql_query("select * from bsi_deposit_discount");
	while($row_deposit=mysql_fetch_assoc($sql_deposit)){
	?>
            <tr>
              <td><?=$row_deposit['month']?>
                :</td>
              <td><input type="text" name="deposit_<?=strtolower($row_deposit['month'])?>" id="deposit_<?=strtolower($row_deposit['month'])?>"  size="5" value="<?=$row_deposit['deposit_percent']?>"/>
                % of Total Amount</td>
            </tr>
            <?php } ?>
            <tr>
              <td></td>
              <td><input value="1" src="images/button_update.gif" name="submit_deposit" id="submit_deposit" type="image"/></td>
            </tr>
          </table>
          </td></tr></table>
          </td>
      </tr>
      <tr><td colspan="2" id="update_msg" style="border:solid 1px #999999;"></td></tr>
    </table></td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>