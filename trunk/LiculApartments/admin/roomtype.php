<?php
include ("access.php");

	if(isset($_REQUEST['delid']))
	{
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->roomtype_delete();
	header("location: roomtype.php");
	}
include ("header.php");
include("../includes/conf.class.php");
?>
<script language="javascript">
function roomtype_delete(delid){
var answer = confirm ("Are you sure want to delete this room type? Remember corresponding of this room type all room and priceplan will be deleted forever. ")
if (answer)
window.location="<?=$_SERVER['PHP_SELF']?>?delid="+delid
}
</script>
	</td>
  </tr> 
  <tr>
    <td height="400" valign="top" align="left">
      <!--################################################# -->
    <table align="left" widtd="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>    
	<td align="left" valign="top">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr bgcolor="#666666" height="25"><td colspan="7" align="left" width="100%"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;RoomType List with Regular Price</b></font>&nbsp;&nbsp;&nbsp;<input type="button" value='Add New RoomType' class="lnk" onclick="javascript:window.location.href='add_edit_rtype.php?id=0'" align="right"></td></tr>
	</table>
<table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666; font-size:12px;" bordercolor="#666666">

  <tr bgcolor="#FFFFFF">
    
     <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" >RoomType Name</font></b></td>   
          
   <?php
	 $sql_capacity=mysql_query("select * from  bsi_capacity");
     while($row_capacity=mysql_fetch_assoc($sql_capacity)){
	 ?>
    	<td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" ><?=$row_capacity['title'].' ('.$row_capacity['capacity'].')'?></font></b></td>
     <? } ?>
     <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" >Extra Bed</font></b></td>
	<td scope="col" class="bodytext_h">&nbsp;</td>
   
  </tr>
  <?php
  $rs=mysql_query("select * from bsi_roomtype");
  while($row=mysql_fetch_array($rs))
  {
  ?>
  <tr class=odd bgcolor="#f2eaeb">
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif"><?= $row[1] ?></font></td>
	<?php
	 $sql_capacity=mysql_query("select * from  bsi_capacity");
	 
	
     while($row_capacity=mysql_fetch_assoc($sql_capacity)){
		 
		 $sql_price=mysql_query("select price, extrabed from bsi_priceplan where roomtype_id=".$row[0]." and default_plan=true and capacity_id=".$row_capacity['id']);
		 
		 if(mysql_num_rows($sql_price)){
		 $row_price=mysql_fetch_assoc($sql_price);
		 ?>
		<td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" ><?=$bsiCore->config['conf_currency_symbol']?><?= $row_price['price'] ?></font></td>
		<?php } else { 
		?>
		<td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" >NA</font></td>    
		<?php } 
	
	} 
	$extrabedprice_row=mysql_fetch_assoc(mysql_query("select extrabed from bsi_priceplan where roomtype_id=".$row[0]." and default_plan=true group by roomtype_id"));
	?>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" ><?php if($extrabedprice_row['extrabed'] != "0.00") { echo $bsiCore->config['conf_currency_symbol'].$extrabedprice_row['extrabed']; } else { echo "NA";}?> </font></td> 
    
  
    <td align="left"><a href="add_edit_rtype.php?id=<?=$row[0]?>" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;<a href="javascript:;" class="lnk" onclick="javascript:roomtype_delete('<?=$row[0]?>');"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Delete</font></a></td>
  </tr>
  <?php
  }
  ?>
</table>

	</td>
  </tr>
</table>
 <!--################################################# -->
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
