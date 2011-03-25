<?php
error_reporting(0);
include ("access.php");
include ("header.php");
if(isset($_POST['sbt_lang'])){
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->langauge_setting();
}
$lang_sql=mysql_query("select * from bsi_language order by lang_order");
?>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){
   $('input[type="radio"]').change(function() {   
	  var the_value;
  		the_value = jQuery('#lang input:radio:checked').val();
  		//alert(the_value);
		//$('input[name=lang_'+the_value+']').attr('checked', true);

	//alert('sdf sdfasd');
}); 

});
</script>
	</td>
  </tr> 
  
  <tr>
    <td valign="top" >
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" id="lang">
    
    
    <fieldset>
    <legend class="TitleBlue11pt">LANGUAGE SETTING</legend>
    <table cellpadding="5" cellspacing="0" border="0" class="bodytext" width="500">
    <tr><td class="TitleRed11pt">Language</td><td class="TitleRed11pt">Default</td><td class="TitleRed11pt">Enabled</td><td class="TitleRed11pt">Order</td></tr>
    <?php
	while($lang_row=mysql_fetch_assoc($lang_sql)){
	if($lang_row['default']==true){
	?>  
    <tr bgcolor="#CCCCFF"><td><?=$lang_row['language']?></td><td><input type="radio" value="<?=$lang_row['lang_code']?>" name="lang_default" id="lang_default" checked="checked" /></td><td>Yes</td><td><input type="text" name="order_<?=$lang_row['lang_code']?>" value="<?=$lang_row['lang_order']?>" size="5" /></td></tr>
    <?php 
	}else{ 
	$lang_enabled=(($lang_row['status']==false) ? '' : 'checked="checked"')
	?>
	  <tr><td><?=$lang_row['language']?></td><td><input type="radio" value="<?=$lang_row['lang_code']?>" name="lang_default" /></td><td><input type="checkbox" value="<?=$lang_row['lang_code']?>" name="lang_<?=$lang_row['lang_code']?>" <?=$lang_enabled?> /></td><td><input type="text" name="order_<?=$lang_row['lang_code']?>" value="<?=$lang_row['lang_order']?>" size="5" /></td></tr>
	<?php } } ?>   
    </table>
    </fieldset><br/>
    <input  type="hidden" value="11" name="sbt_lang" />
    <input  src="images/button_update.gif" name="SBMT_REG" type="image" id="lang_submit">

    </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
