<?php
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;

include ("header.php");
?>

</td>
</tr>
<tr>
	<td valign="top" align="left" width="100%">
	<!--################################################# -->
		<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		        <td align="left" valign="top" style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
		            <table cellpadding="0" cellspacing="0" width="100%">
		                <tr bgcolor="#666666">
		                    <td align="left" valign="middle" width="100%" style="font-size: 22px;">
		                        <font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;Price Plan List</b></font>&nbsp;&nbsp;&nbsp;
		                        <input type="image" value="Add New Price Plan" src="images/button_add.png"  name='SBMT_REG' onclick="javascript:window.location.href='room_price_add_edit.php'" />		                        
		                    </td>
		                </tr>
		            </table>
		            <table width="100%" cellspacing="1" border="0" cellpadding="3" style="border: solid 1px #666666">			            
		            <?php
		            	$selectRoomPricesSql = "SELECT r.room_name, r.room_number, rp.id, DATE_FORMAT(rp.start_date, '%m/%d/%Y') as start_date, DATE_FORMAT(rp.end_date, '%m/%d/%Y') as end_date, rp.price, rp.extrabed, rp.default_plan, rp.room_id FROM bsi_rooms r, bsi_room_price rp WHERE r.id = rp.room_id ORDER by r.id, rp.default_plan DESC, rp.start_date";
						$selectRoomPricesQuery=mysql_query($selectRoomPricesSql);
						$currentRoom = "";							
						$previousRoom = "";
						while ($roomPricePlan = mysql_fetch_assoc($selectRoomPricesQuery))
						{
							$currentRoom = $roomPricePlan['room_name'] . " (" . $roomPricePlan['room_number'] . ")";
							if ($currentRoom != $previousRoom)
							{
								$previousRoom = $currentRoom;
					?>
								<tr bgcolor="#BEEBE6">
				                    <td colspan="5">
				                        <font color="#000000" face="Arial, Helvetica, sans-serif" size="2"><b><?= $currentRoom ?></b></font>
				                    </td>
			                	</tr>
			                	<tr bgcolor="#FFFFFF">			                    
				                    <td scope="col" align="left">
				                        <b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">Start Date</font></b>
				                    </td>
				                    <td scope="col" align="left">
				                        <b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">End Date</font></b>
				                    </td>
				                    <td scope="col" align="left">
				                        <b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">Price</font></b>
				                    </td>
				                    <td scope="col" align="left">
				                        <b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">Extra Bed Price</font></b>
				                    </td>			                    
				                    <td scope="col" class="bodytext_h">
				                        &nbsp;
				                    </td>
				                </tr>			                	
					<?php	 
							}
					?>
							<tr class="odd" bgcolor="#f2eaeb">
					<?php 
							
							if ($roomPricePlan['default_plan'] == 1)
							{	
								//$links = '<a href="rooms_add_edit.php?id=' . $roomPrice['room_id'] . '" class="action_link">Edit</a>';							
								$links = '<a href="rooms_add_edit.php?id=' . $roomPricePlan['room_id'] . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>';
					?>
								<td align="left" colspan="2"><b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="1">Regular Price</font></b></td>																
					<?php 							
							}
							else
							{
								$links = '<a href="room_price_add_edit.php?id=' . $roomPricePlan['id'] . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>';
								$links = $links . '&nbsp;&nbsp;<a href="room_price_delete.php?id=' . $roomPricePlan['id'] . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" onclick = "if (! confirm(\'Are you sure?\')) { return false; }">Delete</font></a>';
					?>
								<td align="left">
		                        	<b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="1"><?= $roomPricePlan['start_date'] ?></font></b>
			                    </td>
			                    <td align="left">
			                        <b><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="1"><?= $roomPricePlan['end_date'] ?></font></b>
			                    </td>					
					<?php 
								
							}
					?>
								<td align="left">
			                        <font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $systemConfiguration->formatCurrency($roomPricePlan['price']) ?></font>
			                    </td>
			                    <td align="left">
			                        <font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2"><?= $systemConfiguration->formatCurrency($roomPricePlan['extrabed']) ?></font>
			                    </td>
			                    <td align="left">
			                    	<?= $links ?>
			                    </td>			                    
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
<?php
include ("footer.php");
?>
</table>
</body>
</html>
