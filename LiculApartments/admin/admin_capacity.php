<?php
include ("access.php");

	if(isset($_REQUEST['delid']))
	{
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->capacity_delete();
	header("location:".$_SERVER['PHP_SELF']);
	}
	
	if(isset($_POST['submit'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->capacity_addedit();
	header('Location:'.$_SERVER['PHP_SELF']);
	}
include ("header.php");
include("../includes/conf.class.php");
?>
<script language="javascript">
function capacity_delete(delid){
var answer = confirm ("Are you sure want to delete this capacity? Remember corresponding of this capacity all room and priceplan will be deleted forever. ")
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
    <?php 
	if(isset($_REQUEST['addedit'])){	
	$id=$bsiCore->ClearInput($_REQUEST['id']);
	
	if($id)	{
	$row_1=mysql_fetch_assoc(mysql_query("select * from bsi_capacity where id=$id"));
	}else{
	$row_1=NULL;
	}
	?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <input type="hidden"  name="id" value="<?=$id?>" />
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr bgcolor="#666666" height="25"><td colspan="7" align="left" width="100%"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;Add/Edit Capacity</b></font>&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
<table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666" bordercolor="#666666">

  <tr bgcolor="#FFFFFF">
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Capcity Title</font></b></td><td><input type="text" size="30" name="capacityTitle" value="<?=$row_1['title']?>"/></td>
     
  </tr>
   <tr bgcolor="#FFFFFF">
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">No of Adult</font></b></td>
   <?php  if($_REQUEST['id']){ ?>
     <td><?=$row_1['capacity']?></td>
   <?php }else{ ?>
   <td><input type="text" size="10" name="NoOfAdult" value="<?=$row_1['capacity']?>" /></td>
   <?php } ?>
  </tr>
  <tr><td></td><td><input type="submit" value="Submit" name="submit"/></td></tr>
</table>	</form>
    <?php } else {?>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr bgcolor="#666666" height="25"><td colspan="7" align="left" width="100%"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;Capacity List</b></font>&nbsp;&nbsp;&nbsp;<input type="button" value='Add New Capacity' class="lnk" onclick="javascript:window.location.href='<?=$_SERVER['PHP_SELF']?>?id=0&addedit=1'"  align="right"></td></tr>
	</table>
<table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666" bordercolor="#666666">

  <tr bgcolor="#FFFFFF">
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Capacity Title</font></b></td>
     <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">No of Adult</font></b></td>
     
    
          
   
	<td scope="col" class="bodytext_h">&nbsp;</td>
   
  </tr>
  <?php
  $rs=mysql_query("select * from bsi_capacity");
  while($row=mysql_fetch_array($rs))
  {
  ?>
  <tr class=odd bgcolor="#f2eaeb">
    <td align="left" ><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $row[1] ?></font></td>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $row[2] ?></font></td>
	
  
    <td align="left"><a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$row[0]?>&addedit=1" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;<a href="javascript:;" class="lnk" onclick="javascript:capacity_delete('<?=$row[0]?>');"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Delete</font></a></td>
  </tr>
  <?php  }  ?>
</table>

<?php } ?>
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
