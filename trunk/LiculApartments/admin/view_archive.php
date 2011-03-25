<?php
include ("access.php");

if(isset($_REQUEST['delid'])){
include("../includes/db.conn.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
$bsiAdminMain->booking_cencel_delete(2);
header("location:view_archive.php");
}


include ("header.php");
include("../includes/conf.class.php");
include("../includes/admin.class.php");
?>
<link href="css/pagination.css" rel="stylesheet" type="text/css" />
<?php
if(isset($_GET['page']))
$pagination=$bsiAdminMain->pagination_global('bsi_bookings',15,$_GET['page'],'view_archive.php',2);
else
$pagination=$bsiAdminMain->pagination_global('bsi_bookings',15,0,'view_archive.php',2);
?>

<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript">
function myPopup2(booking_id)
{
var width = 730;
var height = 650;
var left = (screen.width - width)/2;
var top = (screen.height - height)/2;
var url='print_invoice.php?bid='+booking_id;
var params = 'width='+width+', height='+height;
params += ', top='+top+', left='+left;
params += ', directories=no';
params += ', location=no';
params += ', menubar=no';
params += ', resizable=no';
params += ', scrollbars=yes';
params += ', status=no';
params += ', toolbar=no';
newwin=window.open(url,'Chat', params);
if (window.focus) {newwin.focus()}
return false;
}


$(document).ready(function() {
  $('#btn_booking_search').click(function() { 
  $('#booking_search_wait').html("<img src='images/ajax-loader_3.gif' border='0' >")
			var querystr = 'actioncode=11&cancelled=1&bookingid='+$('#bookingid').val(); 	
			$.post("admin_ajax_processor.php", querystr, function(data){						
				//alert("1");						 
				if(data.errorcode == 0){
					 $('#booking_lst').html(data.strhtml)
					 $('#booking_search_wait').html("");
				}else{
				 alert(data.strmsg);
				 $('#booking_search_wait').html("");
				}
				
			}, "json");

  });
});


function booking_delete(delid){
var answer = confirm ("Are you sure want to delete booking? Remember once booking deleted, it will be deleted forever from your database.")
if (answer)
window.location="view_archive.php?delid="+delid
}
</script>
</td>
</tr>

<tr>
  <td height="400" valign="top" align="left"><!--################################################# -->
    <table align="left" widtd="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top">
		<?php
		 // $rs=mysql_query("select * from bsi_bookings where payment_success=true and (CURDATE() > end_date OR is_deleted=true) order by start_date desc");
	    ?>
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr bgcolor="#666666" height="25">
              <td colspan="7" align="left" ><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><b>&nbsp;Booking List</b></font>&nbsp;&nbsp;&nbsp;</td><td align="right"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif">Enter Booking ID:</font> <input type="text" style="width:70px" id="bookingid" />&nbsp;&nbsp;<img src="images/button_search.gif" border="0" align="absmiddle" style="cursor:pointer; cursor:hand;" id="btn_booking_search" />&nbsp;</td><td align="center" id="booking_search_wait" width="25" valign="middle"></td>
            </tr>
          </table>
          <div id="booking_lst">
          <table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666" bordercolor="#666666">
            <tr bgcolor="#FFFFFF">
              <td scope="col" align="left" class="TitleBlue11pt">Booking ID#</td>
              <td scope="col" align="left" class="TitleBlue11pt">Name</td>
              <td scope="col" align="left" class="TitleBlue11pt">Phone#</td>
              <td scope="col" align="left" class="TitleBlue11pt">Check In</td>
              <td scope="col" align="left" class="TitleBlue11pt">Check Out</td>
              <td scope="col" align="left" class="TitleBlue11pt">Amount</td>              
              <td scope="col" align="left" class="TitleBlue11pt">Booking Date</td>
              <td scope="col" align="left" class="TitleBlue11pt">Status</td>
              <td scope="col" class="bodytext_h">&nbsp;</td>
            </tr>
            <?php
			  while($row =  mysql_fetch_assoc($pagination['pagination_return_sql']))
			  {
			  switch($row['payment_type']){
			  case 'poa':
			  $payment_method="Manual: Pay In Arrival";
			  break;
			  case 'pp':
			  $payment_method="PayPal";
			  break;
			  case '2co':
			  $payment_method="2Checkout";
			  break;
			  case 'admin':
			  $payment_method="Hotel Administrator";
			  break;
			  }
			  if($row['is_deleted'])
			  $b_status="<font color='RED'><b>CANCELLED</b></font>";
			  else
			   $b_status="<font color='green'><b>COMPLETED</b></font>";
			  
			  $client_info=mysql_fetch_assoc(mysql_query("select first_name, surname, title, phone from bsi_clients where client_id=".$row['client_id']));
			?>
            <tr class=odd bgcolor="#f2eaeb">
              <td align="left"  class="bodytext8pt"><?= $row['booking_id'] ?></td>
              <td align="left" class="bodytext8pt"><?= $client_info['title']." ". $client_info['first_name']." ".$client_info['surname'] ?></td>
              <td align="left" class="bodytext8pt"><?= $client_info['phone'] ?></td>
              <td align="left" class="bodytext8pt"><?= $row['start_date'] ?></td>
              <td align="left" class="bodytext8pt"><?= $row['end_date'] ?></td>
              <td align="left" class="bodytext8pt"><?=$bsiCore->config['conf_currency_symbol'].$row['total_cost'] ?></td>
              <td align="left" class="bodytext8pt"><?= $row['booking_time'] ?></td>
              <td align="left" class="bodytext8pt"><?= $b_status ?></td>
              <td align="left"><a  href="booking_details.php?id=<?=base64_encode($row['booking_id'])?>" class="bodytext">View Details</a>&nbsp;||&nbsp;<a  href="javascript:;" onclick="javascript:myPopup2('<?=$row['booking_id']?>');" class="bodytext">Print Invoice</a>&nbsp;||&nbsp;<a href="javascript:;" onclick="javascript:booking_delete('<?=base64_encode($row['booking_id'])?>');" class="bodytext">Delete forever</a></td>
            </tr>
            <?php  }  ?>
  <tr><td colspan="9" align="center"><?=$pagination['page_list']?></td></tr>
          </table>
          </div>
          </td>
      </tr>
    </table>
    <!--################################################# -->
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>