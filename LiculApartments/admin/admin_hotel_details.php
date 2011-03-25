<?php
include ("access.php");
if(isset($_POST['sbt_details'])){
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->hotel_details_post();
header("location:admin_hotel_details.php");
}
include ("header.php");
include("../includes/conf.class.php");
?>
	</td>
  </tr> 
  
  <tr>
    <td valign="top" >
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <fieldset>
    <legend class="TitleBlue11pt">HOTEL DETAILS</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
    <tr><td>Hotel Name:</td><td><input type="text" name="hotel_name" size="50" value="<?=$bsiCore->config['conf_hotel_name']?>"/></td></tr>
    <tr><td>Street Address:</td><td><input type="text" name="str_addr" size="40" value="<?=$bsiCore->config['conf_hotel_streetaddr']?>"/></td></tr>
    <tr><td>City:</td><td><input type="text" name="city" size="30" value="<?=$bsiCore->config['conf_hotel_city']?>"/></td></tr>
    <tr><td>State:</td><td><input type="text" name="state" size="30" value="<?=$bsiCore->config['conf_hotel_state']?>"/></td></tr>
    <tr><td>Country:</td><td><input type="text" name="country" size="30" value="<?=$bsiCore->config['conf_hotel_country']?>"/></td></tr>
    <tr><td>Zip/Post code:</td><td><input type="text" name="zipcode" size="10" value="<?=$bsiCore->config['conf_hotel_zipcode']?>"/></td></tr>
    <tr><td>Phone:</td><td><input type="text" name="phone" size="15" value="<?=$bsiCore->config['conf_hotel_phone']?>"/></td></tr>
    <tr><td>Fax:</td><td><input type="text" name="fax" size="15" value="<?=$bsiCore->config['conf_hotel_fax']?>"/></td></tr>
    <tr><td>Email:</td><td><input type="text" name="email" size="30" value="<?=$bsiCore->config['conf_hotel_email']?>"/></td></tr>
    </table>
    </fieldset><br/>
   
     <input  type="hidden" value="11" name="sbt_details" />
    <input  src="images/button_update.gif" name="SBMT_REG" type="image">

    </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
