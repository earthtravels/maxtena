<?php
error_reporting(0);
include ("access.php");

if(isset($_POST['act_sbmt'])){
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->payment_gateway_post();
header("location:payment_gateway.php");
}

include ("header.php");
include("../includes/admin.class.php");
$payment_gateway_val=$bsiAdminMain->payment_gateway();
?>

	</td>
  </tr> 
  
  <tr>
    <td valign="top" >
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    
    
    <fieldset>
    <legend class="TitleBlue11pt">PAYMENT METHOD</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
    <tr><td class="TitleRed11pt">Enabled</td><td class="TitleRed11pt">Gateway</td><td class="TitleRed11pt">Title</td><td class="TitleRed11pt">Account Info</td></tr>
    <tr><td><input type="checkbox" value="pp" name="pp"  id="pp" <?=($payment_gateway_val['pp_enabled']) ? 'checked="checked"' : ''; ?> /></td><td>PayPal</td><td><input type="text" name="pp_title" id="pp_title" value="<?=$payment_gateway_val['pp_gateway_name']?>" size="30"/></td><td><input type="text" name="paypal_id" id="paypal_id" value="<?=$payment_gateway_val['pp_account']?>" size="40"/> (enter your PayPal Email.)</td></tr>
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td><input type="checkbox" value="2co" name="2co" id="2co" <?=($payment_gateway_val['co_enabled']) ? 'checked="checked"' : ''; ?> /></td><td>2Checkout</td><td><input type="text" name="2co_title" id="2co_title" value="<?=$payment_gateway_val['co_gateway_name']?>" size="30"/></td><td><input type="text" name="2co_id" id="2co_id"  value="<?=$payment_gateway_val['co_account']?>" size="40"/> (enter your 2checkout vendor id.)</td></tr>
    <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td><input type="checkbox" value="poa" name="poa" id="poa" <?=($payment_gateway_val['poa_enabled']) ? 'checked="checked"' : ''; ?> /></td><td>Manual</td><td><input type="text"  name="poa_title" id="poa_title" value="<?=$payment_gateway_val['poa_gateway_name']?>" size="30"/></td><td></td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>
    <tr><td><input type="checkbox" value="an" name="an" id="an" <?=($payment_gateway_val['an_enabled']) ? 'checked="checked"' : ''; ?> /></td><td>Authorize.Net</td><td><input type="text"  name="an_title" id="an_title" value="<?=$payment_gateway_val['an_gateway_name']?>" size="30"/></td><td>API Login ID:<input type="text" name="an_loginid" size="15" value="<?=$payment_gateway_val['an_login']?>" />&nbsp;&nbsp;&nbsp;Transaction Key:<input type="text" name="an_txnkey" size="20" value="<?=$payment_gateway_val['an_txnkey']?>" /></td></tr>
    
    
    </table>
    </fieldset><br/>
    <input type="hidden" name="act_sbmt" value="1" />
    <input  src="images/button_update.gif" name="SBMT_REG" type="image">

    </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
