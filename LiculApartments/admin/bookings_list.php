<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

BookingDetails::clearExpiredBookings();
$currentBookings = Booking::fetchFromDbCurrent();
$futureBookings = Booking::fetchFromDbFuture();
$pastBookings = Booking::fetchFromDbPast();

$defaultLanguage = Language::fetchDefaultLangauge();
include ("header.php");

function outputHeading()
{
?>
				<tr bgcolor="#747471">
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Id</font></b>
					</td>												
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Date</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Accommodation</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Client</font></b>
					</td>					
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Check In</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Check Out</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Amount</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Amount Paid</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Payment Type</font></b>
					</td>					
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Payment Status</font></b>
					</td>
					<td scope="col" align="center"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Actions</font></b>
					</td>					
				</tr>
<?php 	
}

function outputRows($type="current")
{
	global $systemConfiguration;
	$sql = "SELECT b.*, CAST(b.booking_time as date) as booking_date, cl.id as client_id, cl.first_name, cl.last_name, r.room_name, IF(pg.gateway_name_en IS NULL, 'None', pg.gateway_name_en) as gateway_name ";
	$sql.= "FROM bsi_bookings b ";
	$sql.= "	INNER JOIN bsi_clients cl ";
	$sql.= " 		ON b.client_id = cl.id ";
	$sql.= "	INNER JOIN bsi_rooms r ";
	$sql.= " 		ON b.room_id = r.id ";
	$sql.= "	LEFT OUTER JOIN bsi_payment_gateway pg";
	$sql.= " 		ON pg.id = b.payment_gateway_id ";
	if ($type == "current")
	{		
		$sql.= " WHERE CURDATE() BETWEEN start_date AND end_date AND is_deleted = 0 ORDER BY start_date";
	}
	else if ($type == "future")
	{
		$sql.= " WHERE CURDATE() < start_date AND is_deleted = 0 ORDER BY start_date";
	}
	else if ($type == "past")
	{
		$sql.= " WHERE CURDATE() > end_date AND is_deleted = 0 ORDER BY start_date";
	}
	else if ($type == "cancelled")
	{
		$sql.= " WHERE is_deleted = 1 ORDER BY start_date";
	}	
	$query = mysql_query($sql);
	if (!$query)
	{
		die ('Error message: ' . mysql_error() . 'Error number: ' || mysql_errno());
	} 
	
	if(mysql_num_rows($query) > 0)
	{
		while ($row = mysql_fetch_assoc($query)) 
		{	
			echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $row['booking_id'] . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $row['booking_date'] . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $row['room_name'] . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="clients_add_edit.php?id=' . $row['client_id'] . '">' . $row['first_name'] . ' ' . $row['last_name']  . '</a></font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $row['start_date'] . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $row['end_date'] . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $systemConfiguration->formatCurrency($row['total_cost']) . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $systemConfiguration->formatCurrency($row['payment_amount']) . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $row['gateway_name'] . '</font></td>' . "\n";		
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . ($row['payment_success'] == 1 ? '<font color="green"><b>Complete</b></font>' : '<font color="red"><b>Incomplete</b></font>') . '</font></td>' . "\n";
			echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
			echo '		<a href="invoice.php?booking_id=' . $row['booking_id']. '" target="_blank" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >View Invoice</font></a>' . "\n";
			if ($type == "current" || $type == "future")
			{				
				echo '<br /><br /><a href="bookings_cancel.php?id=' . $row['booking_id']. '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Cancel Booking</font></a>&nbsp;&nbsp;';			
			}
			echo '	</td>' ."\n";			
			echo "</tr>\n";
		}
	}
	else
	{
		echo '<tr><td colspan="11"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">There are no bookings in this category.</font></td></tr>' . "\n";
	}
}
?>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Current Bookings</legend>
	    	<table cellspacing="1" border="0" cellpadding="3">				
			<?php 
				outputHeading();
				outputRows("current");				
			?>
			</table>
    </fieldset>       
    <br />    
    <fieldset>
	    <legend class="TitleBlue11pt">Future Bookings</legend>
	    	<table cellspacing="1" border="0" cellpadding="3">				
			<?php 
				outputHeading();
				outputRows("future");				
			?>
			</table>
    </fieldset>
    <br />   
    <fieldset>
	    <legend class="TitleBlue11pt">Past Bookings</legend>
	    	<table cellspacing="1" border="0" cellpadding="3">				
			<?php 
				outputHeading();
				outputRows("past");				
			?>
			</table>
    </fieldset>
     <br />
    <fieldset>
	    <legend class="TitleBlue11pt">Cancelled Bookings</legend>
	    	<table cellspacing="1" border="0" cellpadding="3">				
			<?php 
				outputHeading();
				outputRows("cancelled");				
			?>
			</table>
    </fieldset>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
