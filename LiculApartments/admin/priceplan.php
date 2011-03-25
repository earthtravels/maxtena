<?php
include ("access.php");
if(isset($_REQUEST['rtype']))
	{
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	$rtype=$bsiCore->ClearInput($_REQUEST['rtype']);
	$start_dt=$bsiCore->ClearInput($_REQUEST['start_dt']);
	mysql_query("delete from bsi_priceplan where roomtype_id=".$rtype." and start_date='$start_dt'");
	header("location: priceplan.php");
	}
include ("header.php");
include("../includes/conf.class.php");


?>

	</td>
  </tr> 
  <tr>
    <td height="400" valign="top" align="left">
      <!--################################################# -->
    <table align="left" widtd="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>    
	<td align="left" valign="top" style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
    <?php
	if(isset($_REQUEST['error_code'])){
	$error_msg="<font color=\"red\"><b>Error: Please select correct dates. date slot already exist!</b></font><br><br>";
	echo $error_msg;
	}
	?>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr bgcolor="#666666" height="25"><td colspan="7" align="left" width="100%"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;Price Plan List</b></font>&nbsp;&nbsp;&nbsp;<input type="button" value='Add New Price Plan' class="lnk" onclick="javascript:window.location.href='add_edit_priceplan.php?rtype=0&start_dt=0'" align="right"></td></tr>
	</table>
    
<table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666" bordercolor="#666666">
<?php
	$rtype_sql=mysql_query("select * from bsi_roomtype");
	while($rtype_row=mysql_fetch_array($rtype_sql)){
	?>
    <tr bgcolor="#BEEBE6"><td colspan="9"><font color="#000000" face="Arial, Helvetica, sans-serif"><b><?=$rtype_row[1]?></b></font></td></tr>
  <tr bgcolor="#FFFFFF">
    <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="1">Start Date</font></b></td>
     <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="1">End Date</font></b></td>
     <?php
	 $sql_capacity=mysql_query("select * from  bsi_capacity");
     while($row_capacity=mysql_fetch_assoc($sql_capacity)){
	 ?>
    	<td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="1"><?=$row_capacity['title'].' ('.$row_capacity['capacity'].')'?></font></b></td>
     <? } ?>
   <td scope="col" align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="1">Extra Bed/Room</font></b></td>
	<td scope="col" class="bodytext_h">&nbsp;  </td>
   
  </tr>
  <?php
  $rs=mysql_query("SELECT  DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, default_plan FROM `bsi_priceplan` where `roomtype_id`=".$rtype_row[0]." group by `roomtype_id`,`start_date`,`end_date`");
  while($row=mysql_fetch_assoc($rs))
  {
  ?>
  <tr class=odd bgcolor="#f2eaeb">
  <?php
  if($row['default_plan']==true){
  ?>
  <td align="left"  colspan="2"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Regular Price</font></b></td>
  <? } else { ?>
    <td align="left" ><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="1"><?= $row['start_date'] ?></font></b></td>
    <td align="left"><b><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="1"><?= $row['end_date'] ?></font></b></td>
   <? } ?>
    <?php
	 $sql_capacity=mysql_query("select * from  bsi_capacity");
     while($row_capacity=mysql_fetch_assoc($sql_capacity)){
	 if($row['default_plan']==true){
	 $sql_price=mysql_query("select price, extrabed from bsi_priceplan where roomtype_id=".$rtype_row[0]." and default_plan=true and capacity_id=".$row_capacity['id']);
	 }else{
	 $sql_price=mysql_query("select price, extrabed from bsi_priceplan where roomtype_id=".$rtype_row[0]." and start_date='".$bsiCore->getMySqlDate($row['start_date'])."' and capacity_id=".$row_capacity['id']);
	 }
	 if(mysql_num_rows($sql_price)){
	 $row_price=mysql_fetch_assoc($sql_price);
	 $extrabedprice=$row_price['extrabed'];
	 ?>
	<td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?=$bsiCore->config['conf_currency_symbol']?><?= $row_price['price'] ?></font></td>
    <?php } else { ?>
	<td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">NA</font></td>    
    <?php } } 
	
	if($extrabedprice=="0.00"){
	?>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2">NA</font></td>  
    <?php } else { ?>
    <td align="left"><font color="#666666"  face="Verdana, Arial, Helvetica, sans-serif" size="2"><?=$bsiCore->config['conf_currency_symbol']?><?= $extrabedprice ?></font></td> 
    <?php } ?>
   
    <td align="left"><? if($row['default_plan']==false) { ?><a href="add_edit_priceplan.php?rtype=<?=$rtype_row[0]?>&start_dt=<?=$bsiCore->getMySqlDate($row['start_date'])?>" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;<a href="<?=$_SERVER['PHP_SELF']?>?rtype=<?=$rtype_row[0]?>&start_dt=<?=$bsiCore->getMySqlDate($row['start_date'])?>" class="lnk"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2">Delete</font></a><? } else { ?> <a href="add_edit_rtype.php?id=<?=$rtype_row[0]?>" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a> <? } ?></td>
  </tr>
  <?php
  }
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
