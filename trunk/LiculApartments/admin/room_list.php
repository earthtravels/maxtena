<?php
include ("access.php");
	if(isset($_REQUEST['delid']))
	{
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	$delid=$bsiCore->ClearInput($_REQUEST['delid']);
	mysql_query("delete from bsi_room where room_ID=".$delid);
	header("location: room_list.php");
	}
include ("header.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
?>
<link href="css/pagination.css" rel="stylesheet" type="text/css" />
<?php
if(isset($_GET['page']))
$pagination=$bsiAdminMain->pagination_global('bsi_room',15,$_GET['page'],'room_list.php',3);
else
$pagination=$bsiAdminMain->pagination_global('bsi_room',15,0,'room_list.php',3);
?>


	</td>
  </tr> 
  <tr>
    <td height="400" valign="top" align="left">
      <!--################################################# -->
    <table align="left" widtd="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>    
	<td align="left" valign="top">
    <?php if(isset($_GET['error'])){ ?>
    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#FF0000; font-weight:bold"> Error: Room Number Already exist!</span><br />
    <?php } ?>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr bgcolor="#666666" height="25"><td colspan="7" align="left" width="100%"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;Room List</b></font>&nbsp;&nbsp;&nbsp;<input type="button" value='Add New Room' class="lnk" onclick="javascript:window.location.href='add_edit_room.php?id=0'" align="right"></td></tr>
	</table>
<table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666" bordercolor="#666666">

  <tr bgcolor="#FFFFFF">
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Room No#</font></b></td>
     <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Room Type</font></b></td>
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">No of Adult</font></b></td>
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">No of Child</font></b></td>
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Extra One Bed?</font></b></td>
          
   
	<td scope="col" class="bodytext_h">&nbsp;</td>
   
  </tr>
  <?php
 while($row =  mysql_fetch_array($pagination['pagination_return_sql']))
  {
  $room_type=mysql_fetch_row(mysql_query("select * from bsi_roomtype where roomtype_ID=".$row[1]));
  $capacity=mysql_fetch_row(mysql_query("select * from bsi_capacity where id=".$row[3]));
  
  $extrabed = ($row[5] == true) ? 'Yes' : 'No';
  ?>
  <tr class=odd bgcolor="#f2eaeb">
    <td align="left" ><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $row[2] ?></font></td>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $room_type[1] ?></font></td>
	<td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $capacity[1].' ('.$capacity[2].')' ?></font></td>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $row[4] ?></font></td>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $extrabed  ?></font></td>
  
    <td align="left"><a href="add_edit_room.php?id=<?=$row[0]?>" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;<a href="<?=$_SERVER['PHP_SELF']?>?delid=<?=$row[0]?>" class="lnk"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Delete</font></a></td>
  </tr>
  <?php
  }
  ?>
  <tr><td colspan="4"><?=$pagination['page_list']?></td></tr>
</table>

	</td>
  </tr>
</table><br />

 <!--################################################# -->
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
