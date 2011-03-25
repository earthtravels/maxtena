<?php
include ("access.php");
if(isset($_GET['edid'])){
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->del_hotel_extras();
header("location: hotel_extras.php");
}
include ("header.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$hotel_extras=$bsiAdminMain->hotel_extras();
?>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script language="javascript">
$(document).ready(function() {
   //add extrass ***********************
   $('#extra_sbmt').click(function() { 
   	  if($('#extras_title').val() != "" && $('#extras_price').val() != ""){
		  var querystr = "actioncode=7&extras_title="+$('#extras_title').val()+"&extras_price="+$('#extras_price').val(); 	
		  //alert(querystr);
		  $.post("admin_ajax_processor.php", querystr, function(data){
		  		if(data.errorcode == 0){
					alert(data.strmsg);
					location.reload();
				}else{
				    alert(data.strmsg);
				}		  
		  }, "json");
		  
	  }else{
	  	  alert("please enter all required data!");
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
  <tr><td class="SubPageHead" align="center" style="border:solid 1px #999999;">Hotel Extras</td></tr>
  <tr><td style="border:solid 1px #999999;">
      <table cellpadding="3" cellspacing="0" border="0">
      <tr><td colspan="2" class="TitleBlue11pt" align="center">Hotel Extras Add</td></tr>
      <tr><td>Title:</td><td><input type="text" name="extras_title" id="extras_title" size="45" /></td></tr>
      <tr><td>Price</td><td><input type="text" name="extras_price" id="extras_price" size="10" /></td></tr>
     
      <tr><td></td><td><input type="submit" value="Submit" name="extra_sbmt" id="extra_sbmt" /> </td></tr>
      </table>
  </td></tr>
  <tr><td style="border:solid 1px #999999;">
      <table cellpadding="3" cellspacing="0" border="0">
      <tr><td class="TitleBlue11pt">Title</td><td class="TitleBlue11pt">&nbsp;&nbsp;Price</td><td></td></tr>
      <?=$hotel_extras['hotel_extra_view']?>
      </table>
  </td></tr>
  </table>
  
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>