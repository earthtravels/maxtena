<?php
include ("access.php");
include("../includes/db.conn.php"); 
include("../includes/conf.class.php");

$errorcode = 0;
$strmsg = "";
        $booking_id = $bsiCore->ClearInput($_POST['bookingid']);		
		$cancelled = $_POST['cancelled'];
		
		if(!$cancelled)
		$booking_sql=mysql_query("select booking_id, DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, total_cost, DATE_FORMAT(booking_time, '".$bsiCore->userDateFormat."') AS booking_time, payment_type, client_id, is_deleted from bsi_bookings where booking_id ='".$booking_id."' and payment_success=true and CURDATE() <= end_date and is_deleted=false and is_block=false");
		else
		$booking_sql=mysql_query("select booking_id, DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, total_cost, DATE_FORMAT(booking_time, '".$bsiCore->userDateFormat."') AS booking_time, payment_type, client_id, is_deleted from bsi_bookings where booking_id ='".$booking_id."' and payment_success=true and (CURDATE() > end_date OR is_deleted=true and is_block=false)");
		
		//$cont_query=1;
		if(mysql_num_rows($booking_sql)){	
			$strhtml=' <table widtd="74%" cellspacing="1" border="0" cellpadding="3" style="border:solid 1px #666666" bordercolor="#666666">
            <tr bgcolor="#FFFFFF">
              <td scope="col" align="left" class="TitleBlue11pt">Booking ID#</td>
              <td scope="col" align="left" class="TitleBlue11pt">Name</td>
              <td scope="col" align="left" class="TitleBlue11pt">Phone#</td>
			  
              <td scope="col" align="left" class="TitleBlue11pt">Check In</td>
              <td scope="col" align="left" class="TitleBlue11pt">Check Out</td>
              <td scope="col" align="left" class="TitleBlue11pt">Amount</td>';
			  
			  if(!$cancelled)
			  $strhtml.='<td scope="col" align="left" class="TitleBlue11pt">Payment Type</td>';
			  
              $strhtml.='<td scope="col" align="left" class="TitleBlue11pt">Booking Date</td>';
			  
			  if(!$cancelled)
			  $strhtml.='<td scope="col" align="left" class="TitleBlue11pt">Status</td>';
			  
              $strhtml.='<td scope="col" class="bodytext_h">&nbsp;</td></tr>';
			
		  
			  while($row=mysql_fetch_assoc($booking_sql))
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
			  case 'an':
			  $payment_method="Authorize.Net";
			  break;
			  }
			  
			  if($row['is_deleted'])
			  $b_status="<font color='RED'><b>CANCELLED</b></font>";
			  else
			   $b_status="<font color='green'><b>COMPLETED</b></font>";
			   
			   
			  $client_info=mysql_fetch_assoc(mysql_query("select first_name, surname, title, phone from bsi_clients where client_id=".$row['client_id']));
			
		    $strhtml.=' <tr class=odd bgcolor="#f2eaeb">
              <td align="left"  class="bodytext8pt">'.$row['booking_id'].'</td>
              <td align="left" class="bodytext8pt">'.$client_info['title'].' '. $client_info['first_name'].' '.$client_info['surname'].'</td>
              <td align="left" class="bodytext8pt">'.$client_info['phone'].'</td>
              <td align="left" class="bodytext8pt">'.$row['start_date'].'</td>
              <td align="left" class="bodytext8pt">'.$row['end_date'].'</td>
              <td align="left" class="bodytext8pt">'.$bsiCore->config['conf_currency_symbol'].$row['total_cost'].'</td>';
			  
			   if(!$cancelled)
               $strhtml.='<td align="left" class="bodytext8pt">'.$payment_method.'</td>';
			   
              $strhtml.='<td align="left" class="bodytext8pt">'.$row['booking_time'].'</td>';
			  
			  if($cancelled)
			  $strhtml.='<td align="left" class="bodytext8pt">'.$b_status.'</td>';
			  
              $strhtml.='<td align="left"><a  href="booking_details.php?id='.base64_encode($row['booking_id']).'" class="bodytext">View Details</a>&nbsp;||&nbsp;<a  href="javascript:;" onclick="javascript:myPopup2(\''.$row['booking_id'].'\');" class="bodytext">Print Invoice</a>&nbsp;||&nbsp;';
			  if($cancelled)
			  $strhtml.='<a href="javascript:;" onclick="javascript:booking_delete(\''.base64_encode($row['booking_id']).'\');" class="bodytext">Cancell Booking</a></td></tr>';
			  else
			  $strhtml.='<a href="javascript:;" onclick="javascript:booking_cancel(\''.base64_encode($row['booking_id']).'\');" class="bodytext">Cancell Booking</a></td></tr>';
			  
		  }
		  
		  $strhtml.='</table>';
	  
			echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$strhtml));							
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! Your entered booking number is incorrect! please enter correct booking number.";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
		}			
		
?>