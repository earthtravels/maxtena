<?php
class adminAjaxProcessor
{
	public function sendErrorMsg(){		
		$this->errorMsg = "unknown error";	
		echo json_encode(array("errorcode"=>99,"strmsg"=>$this->errorMsg));
	}//end of function	
	
	
	public function getdefaultcapacity(){
			/**
		 * Global Ref: conf.class.php
		 **/
		global $bsiCore;
		$errorcode = 0;
		$strmsg = "";
		$roomtype_id = $bsiCore->ClearInput($_POST['roomtype_id']);			
		
		$sql1=mysql_query("select * from bsi_priceplan where default_plan=true and roomtype_id=".$roomtype_id);
		
		if(mysql_num_rows($sql1)){	
			$capacity_input_box='<table cellpadding="3" cellspacing="0" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">';
			while($row_capacity2=mysql_fetch_assoc($sql1)){
			$row_capacity=mysql_fetch_assoc(mysql_query("select * from  bsi_capacity where id=".$row_capacity2['capacity_id']));
			$capacity_input_box.='<tr><td>'.$row_capacity['title'].' ('.$row_capacity['capacity'].'):</td><td><input type="text" name="'.strtolower($row_capacity['title']).'" value="'.$row_capacity2['price'].'" size="5" /></td></tr>';
			$extraprice=$row_capacity2['extrabed'];
			}
			$extrabedprice_row=mysql_fetch_assoc(mysql_query("select extrabed from bsi_priceplan where roomtype_id=".$roomtype_id." and default_plan=true group by roomtype_id"));
			if($extrabedprice_row['extrabed'] != "0.00")
			$capacity_input_box.='<tr><td>Extra / Bed:</td><td><input type="text" name="extrabed" size="5" value="'.$extraprice.'" /></td></tr></table>';	
			else
			$capacity_input_box.='</table>';
				
			echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$capacity_input_box));	
									
		}else{
			$errorcode = 1;
			$strmsg = "Sorry! Room Type does not exist!";
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
		}
	
	}
	
	public function getroomtypecapacity(){
			/**
			 * Global Ref: conf.class.php
			 **/
			 global $bsiCore;
			$errorcode = 0;
			$strmsg = "";
			$roomtype_id = $bsiCore->ClearInput($_POST['roomtype_id']);			
			
			$sql1=mysql_query("select * from bsi_priceplan where default_plan=true and roomtype_id=".$roomtype_id);
			$row_extbed=mysql_fetch_assoc(mysql_query("SELECT distinct(`extrabed`) as extbed FROM `bsi_priceplan` WHERE `roomtype_id`=".$roomtype_id." and `default_plan`=true"));
			if(mysql_num_rows($sql1)){	
				$capacity_input_box='<select name="roomcapid">';
				while($row_capacity2=mysql_fetch_assoc($sql1)){
				$row_capacity=mysql_fetch_assoc(mysql_query("select * from  bsi_capacity where id=".$row_capacity2['capacity_id']));
				$capacity_input_box.='<option value="'.$row_capacity2['capacity_id'].'">'.$row_capacity['title'].' ('.$row_capacity['capacity'].')</option>';
				
				}
				$capacity_input_box.='</select>';		
				
				if($row_extbed['extbed']=='0.00'){
				$extrabed_input_box='<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">NA(for active enter price in room type)</span>';
				}else{
				$extrabed_input_box='<input type="checkbox" name="extrabed" />';
				}	
				echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$capacity_input_box,"strhtml1"=>$extrabed_input_box));	
										
			}else{
				$errorcode = 1;
				$strmsg = "Sorry! Room Type does not exist!";
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
			}
	}
	
	
	public function getroomnumbers(){
			/**
			 * Global Ref: conf.class.php
			 **/
			 global $bsiCore;
			$errorcode = 0;
			$strmsg = "";
			$roomtype_id = $bsiCore->ClearInput($_POST['roomtype_id']);			
			
			$sql1=mysql_query("select * from bsi_room where roomtype_id=".$roomtype_id);
			
			if(mysql_num_rows($sql1)){	
				$rooms_by_roomtype='<option value="0">Select RoomNumber</option>';
				while($row_rooms=mysql_fetch_assoc($sql1)){
				$row_capacity=mysql_fetch_assoc(mysql_query("select * from  bsi_capacity where id=".$row_rooms['capacity_id']));
				$rooms_by_roomtype.='<option value="'.$row_rooms['room_ID'].'">'.$row_rooms['room_no'].' ('.$row_capacity['title'].')</option>';		}		
				echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$rooms_by_roomtype));	
										
			}else{
				$errorcode = 1;
				$strmsg = "Sorry! no room found in this room type!";
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
			}
	}
	
	public function getdiscountdepoitenabled(){
			/**
			 * Global Ref: conf.class.php
			 **/
			 global $bsiCore;
			$errorcode = 0;
			$strmsg = "";
			
			if(isset($_POST['chk_discount'])){
			$chk_discount = $bsiCore->ClearInput($_POST['chk_discount']);
				if($chk_discount=='true'){
				mysql_query("update bsi_configure set conf_value='1' where conf_key='conf_enabled_discount'");
				$strhtml='<span style="color:#006600; font-weight:bold;">Monthly discount scheme enabled!</span>';
				echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$strhtml));	
				}else{
				mysql_query("update bsi_configure set conf_value='0' where conf_key='conf_enabled_discount'");
				$errorcode = 1;
				$strmsg = '<span style="color:#FF0000; font-weight:bold;">Monthly discount scheme disabled!</span>';
				echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$strmsg));	
				}
					
			}
			
			if(isset($_POST['chk_deposit'])){
			$chk_deposit = $bsiCore->ClearInput($_POST['chk_deposit']);
				if($chk_deposit=='true'){
				mysql_query("update bsi_configure set conf_value='1' where conf_key='conf_enabled_deposit'");
				$strhtml='<span style="color:#006600; font-weight:bold;">Monthly deposit scheme enabled!</span>';
				echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$strhtml));	
				}else{
				mysql_query("update bsi_configure set conf_value='0' where conf_key='conf_enabled_deposit'");
				$errorcode = 1;
				$strmsg = '<span style="color:#FF0000; font-weight:bold;">Monthly deposit scheme disabled!</span>';
				echo json_encode(array("errorcode"=>$errorcode,"strhtml"=>$strmsg));	
				}
			}		
	}
	
	public function getdiscountupdate(){
			/**
			 * Global Ref: conf.class.php
			 **/
			 global $bsiCore;
			$errorcode = 0;
			$strmsg = "";	
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_january'])."' where month_num=1");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_february'])."' where month_num=2");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_march'])."' where month_num=3");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_april'])."' where month_num=4");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_may'])."' where month_num=5");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_june'])."' where month_num=6");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_july'])."' where month_num=7");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_august'])."' where month_num=8");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_september'])."' where month_num=9");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_october'])."' where month_num=10");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_november'])."' where month_num=11");
			mysql_query("update bsi_deposit_discount set discount_percent='".$bsiCore->ClearInput($_POST['discount_december'])."' where month_num=12");
			
			$strmsg = '<span style="color:#006600; font-weight:bold;">Discount value updated!</span>';
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
	
	}
	
	public function getdepositupdate(){
			/**
			 * Global Ref: conf.class.php
			 **/
			 global $bsiCore;
			$errorcode = 0;
			$strmsg = "";	
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_january'])."' where month_num=1");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_february'])."' where month_num=2");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_march'])."' where month_num=3");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_april'])."' where month_num=4");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_may'])."' where month_num=5");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_june'])."' where month_num=6");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_july'])."' where month_num=7");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_august'])."' where month_num=8");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_september'])."' where month_num=9");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_october'])."' where month_num=10");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_november'])."' where month_num=11");
			mysql_query("update bsi_deposit_discount set deposit_percent='".$bsiCore->ClearInput($_POST['deposit_december'])."' where month_num=12");
			
			$strmsg = '<span style="color:#006600; font-weight:bold;">Deposit value updated!</span>';
			echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
			
	}
	
	
	public function addhotelextras(){
			global $bsiCore;
			/**
			 * Global Ref: conf.class.php
			 **/
			$errorcode = 0;
			$strmsg = "";
			$extras_title = $bsiCore->ClearInput($_POST['extras_title']);		
			$extras_price = $bsiCore->ClearInput($_POST['extras_price']);	
			
			$inst_success=mysql_query("insert into bsi_extras(description, fees) values('$extras_title', $extras_price)");
			
			if($inst_success){	
				$strmsg = "Your data successfully inserted!";		
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
										
			}else{
				$errorcode = 1;
				$strmsg = "Sorry! your data does not insert!";
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
			}
	}
	
	
	public function adddiscountcoupon(){
			 global $bsiCore;
			/**
			 * Global Ref: conf.class.php
			 **/
			$errorcode = 0;
			$strmsg = "";
			$coupon_code = $bsiCore->ClearInput($_POST['coupon_code']);		
			$discount_amt = $bsiCore->ClearInput($_POST['discount_amt']);	
			$min_amt = ($_POST['min_amt']=='')? '0.00' : $bsiCore->ClearInput($_POST['min_amt']);
			$exp_date = ($_POST['exp-date']=="")? 'null' : "'".$bsiCore->getMySqlDate($bsiCore->ClearInput($_POST['exp-date']))."'";
			$coupon_category = $bsiCore->ClearInput($_POST['coupon_category']);
			$cust_email = $bsiCore->ClearInput($_POST['cust_email']);
			$rad_discount_type = $bsiCore->ClearInput($_POST['rad_discount_type']);
			$chk_reusecoupon = ($_POST['chk_reusecoupon']=='undefined')? '0' : $bsiCore->ClearInput($_POST['chk_reusecoupon']);
			
			$inst_success=mysql_query("insert into bsi_promocode(promo_code, discount,min_amount,percentage,promo_category,customer_email,exp_date,reuse_promo) values('$coupon_code', $discount_amt, $min_amt, $rad_discount_type, $coupon_category, '$cust_email', $exp_date, $chk_reusecoupon)");
			
			if($inst_success){	
				$strmsg = "Your data successfully inserted!";	
				//$strmsg="insert into bsi_promocode(promo_code, discount,min_amount,percentage,promo_category,customer_email,exp_date,reuse_promo) values('$coupon_code', $discount_amt, $min_amt, $rad_discount_type, $coupon_category, '$cust_email', '$exp_date', $chk_reusecoupon)";	
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
										
			}else{
				$errorcode = 1;
				$strmsg = "Sorry! your data does not insert!";
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg));	
			}
	}
	
	
	public function blockroombyadmin(){
			 global $bsiCore;
			/**
			 * Global Ref: conf.class.php
			 **/
			$errorcode	= 0;
			$strmsg		= "";
			$blockId 	= time();
			$roomtypeid	= $bsiCore->ClearInput($_POST['roomtypeid']);	
			$roomid 	= $bsiCore->ClearInput($_POST['roomid']);	
			$block	 	= intval($bsiCore->ClearInput($_POST['block']));
			
			if($block > 0){
				$blockfrom	= $bsiCore->getMySqlDate($bsiCore->ClearInput($_POST['blockfrom']));	
				$blockto 	= $bsiCore->getMySqlDate($bsiCore->ClearInput($_POST['blockto']));	
				if($roomtypeid == "" || $roomid == "" || $blockfrom == "" || $blockto == ""){
					$strmsg = "Required inputs not supplied for blocking a room.";
				}else{
					$strsql = "
					SELECT count(rvs.room_id) AS idcount
					  FROM bsi_reservation rvs, bsi_bookings bks
					 WHERE     rvs.bookings_id = bks.booking_id
						   AND bks.is_deleted = FALSE
						   AND rvs.room_type_id = ".$roomtypeid."
						   AND rvs.room_id = ".$roomid."
						   AND (('".$blockfrom."' BETWEEN bks.start_date AND DATE_SUB(bks.end_date, INTERVAL 1 DAY))
								OR (DATE_SUB('".$blockto."', INTERVAL 1 DAY) BETWEEN bks.start_date AND DATE_SUB(bks.end_date, INTERVAL 1 DAY))
								OR (bks.start_date BETWEEN '".$blockfrom."' AND DATE_SUB('".$blockto."', INTERVAL 1 DAY))
								OR (DATE_SUB(bks.end_date, INTERVAL 1 DAY) BETWEEN '".$blockfrom."' AND DATE_SUB('".$blockto."', INTERVAL 1 DAY)))";				
						
					$sql = mysql_fetch_assoc(mysql_query($strsql));
					if($sql['idcount']){
						$strmsg = "Failed to block this room for input date range. Input date range must not be conflict with already booked/blocked date. Please enter correct date range.";			
					}else{				
						$sql2 = mysql_query("INSERT INTO bsi_bookings (booking_id, booking_time, start_date, end_date, payment_type, payment_success, is_block) values(".$blockId.", NOW(), '".$blockfrom."', '".$blockto."', 'block', true, true)");
						$sql3 = mysql_query("INSERT INTO bsi_reservation (bookings_id, room_id, room_type_id) values(".$blockId.", ".$roomid.", ".$roomtypeid.")");				
					}
				}
			}
			
			$tbldata = $this->getBlockedRoomHtml($roomtypeid, $roomid);
			
			
			if($strmsg == ""){	
				$strmsg = "Room successfully blocked.";		
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg, "tbldata"=>$tbldata));	
										
			}else{
				$errorcode = 1;	
				echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg, "tbldata"=>$tbldata));	
			}
	}
	
	public function unblockRoom(){
				 global $bsiCore;
				/**
				 * Global Ref: conf.class.php
				 **/
				$errorcode	= 0;
				$strmsg		= "";
				$bookingid 	= 0;	
				$roomtypeid	= 0;
				$roomid 	= 0;		
				if(isset($_POST['bookingid'])) $bookingid = $bsiCore->ClearInput($_POST['bookingid']);
				if(isset($_POST['roomtypeid'])) $roomtypeid = $bsiCore->ClearInput($_POST['roomtypeid']);
				if(isset($_POST['roomid'])) $roomid = $bsiCore->ClearInput($_POST['roomid']);
				
				if($bookingid != ""){	
					$sql1 = mysql_query("DELETE FROM bsi_bookings WHERE booking_id = ".$bookingid);	
					$sql2 = mysql_query("DELETE FROM bsi_reservation WHERE bookings_id = ".$bookingid);
					if($sql1 == 0 || $sql2 == 0) $strmsg.= "Error unblocking room for block id:".$bookingid;	
				}
					
				$tbldata = $this->getBlockedRoomHtml($roomtypeid, $roomid);	
				
				if($strmsg == ""){	
					echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg, "tbldata"=>$tbldata));									
				}else{
					$errorcode = 1;	
					echo json_encode(array("errorcode"=>$errorcode,"strmsg"=>$strmsg, "tbldata"=>$tbldata));	
				}
		}
		
	
	private function getBlockedRoomHtml($roomtypeid, $roomid){
			global $bsiCore;
			/**
			 * Global Ref: conf.class.php
			 **/
			$tbldata = "<tr><td class=\"TitleBlue11pt\">Block ID</td><td class=\"TitleBlue11pt\">Start Date</td><td class=\"TitleBlue11pt\">End Date</td><td></td></tr>"; 	
			$counters = 0;
			if($roomtypeid != "" && $roomid != "" ){
				$sql4 = mysql_query("SELECT boks.booking_id, DATE_FORMAT(boks.start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(boks.end_date, '".$bsiCore->userDateFormat."') AS end_date, DATEDIFF(boks.end_date, CURDATE()) AS block_date_count FROM bsi_bookings boks, bsi_reservation resv WHERE boks.booking_id = resv.bookings_id AND boks.is_block = TRUE AND resv.room_type_id = ".$roomtypeid." AND room_id = ".$roomid." ORDER BY block_date_count");		
				
				while($onerow = mysql_fetch_assoc($sql4)){
					$displink = "Un-Block";
					if(intval($onerow['block_date_count']) <= 0) $displink = "Delete";
					$tbldata.= "<tr><td class=\"bodytext\">".$onerow['booking_id']."</td><td  class=\"bodytext\">".$onerow['start_date']."</td><td  class=\"bodytext\">".$onerow['end_date']."</td><td><a href=\"javascript:;\" onclick=\"javascript:unblockRoom('".$onerow['booking_id']."', '".$roomtypeid."', '".$roomid."' );\" class=\"bodytext\">".$displink."</a></td></tr>";
					$counters++;
				}
				
				if($counters == 0){
					$tbldata.= "<tr><td class=\"bodytext\" colspan=\"4\">No block onformation found for this room.</td></tr>";
				}
			}else{
				$tbldata.= "<tr><td class=\"bodytext\" colspan=\"4\">No block onformation found for this room.</td></tr>";
			}	
			return $tbldata;	
	}
	
	public function booking_search(){
			global $bsiCore;
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
	}
	
	//**************************************************************
	public function block_xml_calendar(){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
		header("Cache-Control: no-cache, must-revalidate" ); 
		header("Pragma: no-cache" );
		header("Content-Type: text/xml; charset=utf-8");
		
		$room_number=$_GET['roomid'];
		$xml = '<?xml version="1.0" ?><response><content><![CDATA[';
		
		if($_GET['event'] != '') {
			$fields = explode("-",$_GET['event']);
			$result_booking = mysql_fetch_assoc(mysql_query("select * from bsi_bookings where booking_id=".$fields[0]));
			$result_client= mysql_fetch_assoc(mysql_query("select * from bsi_clients where client_id=".$result_booking['client_id']));
			$i = 0;
			
				$xml .= "<div id='event'";
				$xml .= " style='border-bottom:none'";
				$xml .= "><div class='heading'><div class='title'>".$result_client['title']." ".$result_client['first_name']." ".$result_client['surname']."</div><div class='posted'><b>Booking Period :</b>".$result_booking['start_date']." To ".$result_booking['end_date']."</div>";
				if($i == 0) $xml .= "<div class='back'><a href='javascript:navigate(".$fields[2].",".$fields[1].",\"\",".$room_number.")'>Return to calendar</a></div>";
				$xml .= "</div><div class='line'>More details <a href=\"booking_details.php?id=".base64_encode($result_booking['booking_id'])."\">click here</a></div><br /></div><br />";
		
		
			
		} else {
			$month = $_GET['month'];
			$year = $_GET['year'];
				
			if($month == '' && $year == '') { 
				$time = time();
				$month = date('n',$time);
				$year = date('Y',$time);
			}
			
			$date = getdate(mktime(0,0,0,$month,1,$year));
			$today = getdate();
			$hours = $today['hours'];
			$mins = $today['minutes'];
			$secs = $today['seconds'];
			
			if(strlen($hours)<2) $hours="0".$hours;
			if(strlen($mins)<2) $mins="0".$mins;
			if(strlen($secs)<2) $secs="0".$secs;
			
			$days=date("t",mktime(0,0,0,$month,1,$year));
			$start = $date['wday']+1;
			$name = $date['month'];
			$year2 = $date['year'];
			$offset = $days + $start - 1;
			 
			if($month==12) { 
				$next=1; 
				$nexty=$year + 1; 
			} else { 
				$next=$month + 1; 
				$nexty=$year; 
			}
			
			if($month==1) { 
				$prev=12; 
				$prevy=$year - 1; 
			} else { 
				$prev=$month - 1; 
				$prevy=$year; 
			}
			
			if($offset <= 28) $weeks=28; 
			elseif($offset > 35) $weeks = 42; 
			else $weeks = 35; 
			
			$xml .= "<table class='cal' cellpadding='0' cellspacing='1'>
					<tr>
						<td colspan='7' class='calhead'>
							<table>
							<tr>
								<td>
									<a href='javascript:navigate($prev,$prevy,\"\",".$room_number.")' style='border:none'><img src='images/calLeft.gif' alt='prev' /></a> <a href='javascript:navigate(\"\",\"\",\"\",".$room_number.")' style='border:none'><img src='images/calCenter.gif' alt='current' /></a> <a href='javascript:navigate($next,$nexty,\"\",".$room_number.")' style='border:none'><img src='images/calRight.gif' alt='next' /></a> <a href='javascript:void(0)' onClick='showJump(this,".$room_number.")' style='border:none'><img src='images/calDown.gif' alt='jump' /></a> 
								</td>
								<td align='right'>
									$name $year2
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr class='dayhead'>
						<td>Sun</td>
						<td>Mon</td>
						<td>Tue</td>
						<td>Wed</td>
						<td>Thu</td>
						<td>Fri</td>
						<td>Sat</td>
					</tr>";
			
			$col=1;
			$cur=1;
			$next=0;
			
			for($i=1;$i<=$weeks;$i++) { 
				if($next==3) $next=0;
				if($col==1) $xml.="\n<tr class='dayrow'>"; 
				
				$xml.="\t<td valign='top' onMouseOver=\"this.className='dayover'\" onMouseOut=\"this.className='dayout'\">";
			
				if($i <= ($days+($start-1)) && $i >= $start) {
					$xml.="<div class='day'><b";
			
					if(($cur==$today[mday]) && ($name==$today[month]) && ($year2==$today[year])) $xml.=" style='color:#C00'";
			
					$xml.=">$cur</b></div>";
					
					//$result = mysql_query("SELECT DATE_FORMAT(`date`,'%Y-%m-%e') FROM `events` WHERE MONTHNAME(`date`)='$name' AND DAYOFMONTH(`date`)=$cur AND YEAR(`date`)=$year2");
					$booked_dt=$year2.'-'.$month.'-'.$cur;
					$result=mysql_query("select bookings_id, is_block from bsi_reservation as rv, bsi_bookings as bk where rv.room_id=$room_number and rv.bookings_id = bk.booking_id and bk.is_deleted=false  and '$booked_dt' between bk.start_date and DATE_SUB(bk.end_date, INTERVAL 1 DAY) ");
					if(mysql_num_rows($result) > 0) {
						$row = mysql_fetch_row($result);
						if($row[1]==true){
						$xml.="<div ><a href='javascript:;' style=\"font-size:14px;; color:#FF0000\">Block</a></div>";			
						}else{ 
						$xml.="<div ><a href='javascript:navigate(\"\",\"\",\"".$row[0]."-".$booked_dt."\",\"".$room_number."\")' style=\"font-size:14px;; color:#129b07\">Booked</a></div>";		
						}
					}
					
					$xml.="\n\t</td>\n";
			
					$cur++; 
					$col++; 
					
				} else { 
					$xml.="&nbsp;\n\t</td>\n"; 
					$col++; 
				}  
					
				if($col==8) { 
					$xml.="\n</tr>\n"; 
					$col=1; 
				}
			}
			
			$xml.="</table>";
			  
		}
			
		$xml .= "]]></content></response>";
		echo $xml;
	}
	//************************************************************8


}
?>