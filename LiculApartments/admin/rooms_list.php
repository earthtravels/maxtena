<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
$rooms = Room::fetchAllFromDb();
include ("header.php");

?>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Rooms / Apartments</legend>
	    	<table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Number</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Name</font></b>
					</td>							
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Extra Bed</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Capacity</font></b></td>
					<td scope="col" class="bodytext_h">&nbsp;</td>
				</tr>
			<?php 
					foreach ($rooms as $room) 
					{
						if (!($room instanceof Room))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($room->roomNumber) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($room->roomName) . '</font></td>' . "\n";																
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . ($room->hasExtraBed ? 'Yes' : 'No') . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($room->capacity) . '</font></td>' . "\n";						                                    
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '<a href="rooms_add_edit.php?id=' . $room->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;';
						echo '<a href="rooms_delete.php?id=' . $room->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>&nbsp;&nbsp;';
						echo '<a href="room_images_list.php?room_id=' . $room->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Gallery</font></a></td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($rooms) == 0)
					{
						echo '<tr><td colspan="5">No rooms are defined yet!</td></tr>' . "\n";
					}
			?>
			</table>
    </fieldset> 
    <table width="100%">
		<tr>
			<td align="center">
				<input src="images/button_add.png" name="SBMT_REG" type="image" onclick="javascript:window.location.href='rooms_add_edit.php'">
			</td>
		</tr>
	</table>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
