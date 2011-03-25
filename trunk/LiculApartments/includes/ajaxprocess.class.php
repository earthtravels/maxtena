<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/

class ajaxProcessor
{
	public $actionCode = 0;
	private $errorCode = 0;
	private $errorMsg = '';
	
	function ajaxProcessor() {		
		$this->setRequestParams();		
	}	
	
	private function setRequestParams() {		
		global $bsiCore;
		$this->setMyParamValue($this->actionCode, $bsiCore->ClearInput($_POST['actioncode']), 0, true);		
	}//end of function	
	
	private function setMyParamValue(&$membervariable, $paramvalue, $defaultvalue, $required = false){
		if($required){if(!isset($paramvalue)){$this->invalidRequest();}}
		if(isset($paramvalue)){$membervariable = $paramvalue;}else{$membervariable = $defaultvalue;}
	}//end of function	
	
	private function invalidRequest(){
		header('Location: booking-failure.php?error_code=9');
		die;
	}//end of function	
	
	/************************************************************************************
	 * Function for sending Error Message
	 * actioncode = unknown
	 ************************************************************************************/
	public function sendErrorMsg(){		
		$this->errorMsg = "unknown error";	
		echo json_encode(array("errorcode"=>99,"strmsg"=>$this->errorMsg));
	}//end of function	
	
	/************************************************************************************
	 * Function for fetch booking Status
	 * actioncode = 1
	 ************************************************************************************/	
	public function getBookingStatus() {		
		global $bsiCore;
		$this->errorCode = 0;
		$this->errorMsg = "";	
		$booking_id = $bsiCore->ClearInput($_POST['booking_id']);				
	
		$booking_sql=mysql_query("select client_id, DATE_FORMAT(booking_time, '".$bsiCore->userDateFormat."') AS booking_time, DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, is_deleted from bsi_bookings where booking_id ='".$booking_id."' and payment_success=true limit 1");
	
		if(mysql_num_rows($booking_sql)){	
			$booking_row=mysql_fetch_assoc($booking_sql);
			$client_info=mysql_fetch_assoc(mysql_query("select first_name, surname, title from bsi_clients where client_id=".$booking_row['client_id']));
			$strhtml="<h2>".INDEX_BOOKING_STATUS."</h2>";
			$strhtml.=' <p>
				  <table cellpadding="5" cellspacing="0" border="0" style="border:solid 1px #999999" width="100%">
				  <tr><td style="border:solid 1px #999999; font-weight:bold;" width="220">'.INDEX_STATUS_BOOKING_NUMBER.':</td><td style="border:solid 1px #999999">'.$booking_id.'</td></tr>
				  <tr><td style="border:solid 1px #999999; font-weight:bold;">'.INDEX_STATUS_BOOKING_DATE.':</td><td style="border:solid 1px #999999">'.$booking_row['booking_time'].'</td></tr>
				  <tr><td style="border:solid 1px #999999; font-weight:bold;">'.INDEX_STATUS_GUEST_NAME.':</td><td style="border:solid 1px #999999">'.$client_info['first_name'].' '.$client_info['surname'].'</td></tr>
				  <tr><td style="border:solid 1px #999999; font-weight:bold;">'.LEFT_CHECK_IN_DT.':</td><td style="border:solid 1px #999999">'.$booking_row['start_date'].'</td></tr>
				  <tr><td style="border:solid 1px #999999; font-weight:bold;">'.LEFT_CHECK_OUT_DT.':</td><td style="border:solid 1px #999999">'.$booking_row['end_date'].'</td></tr>';
				  
			if(!$booking_row['is_deleted']){
				if(time() < strtotime($bsiCore->getMySqlDate($booking_row['end_date']))){
					$strhtml.='<tr><td style="border:solid 1px #999999; font-weight:bold;">'.INDEX_STATUS_STATUS.':</td><td style="border:solid 1px #999999"><img src="graphics/checked.gif" border="0" align="absmiddle" /> '.INDEX_STATUS_CONFIRM.'</td></tr>';
				}else{
					$strhtml.='<tr><td style="border:solid 1px #999999; font-weight:bold;">Status:</td><td style="border:solid 1px #999999"><img src="graphics/checked.gif" border="0" align="absmiddle" /> '.INDEX_STATUS_COMPLETED.'</td></tr>';			
				}
			}else{
				$strhtml.='<tr><td style="border:solid 1px #999999; font-weight:bold;">Status:</td><td style="border:solid 1px #999999"><img src="graphics/unchecked.gif" border="0" align="absmiddle" /> '.INDEX_STATUS_CANCELLED.'</td></tr>';
			}
				 
			$strhtml.=' </table><br />';
			$room_info_sql=mysql_query("select b_room.room_no, b_rtype.type_name, b_room.capacity_id from bsi_reservation as b_res, bsi_room as b_room, bsi_roomtype as b_rtype where b_res.bookings_id=".$booking_id." and b_res.room_id=b_room.room_ID and b_res.room_type_id=b_rtype.roomtype_ID");
			$strhtml.='<table cellpadding="5" cellspacing="0" border="0" style="border:solid 1px #999999" width="100%">
			<tr><td style="border:solid 1px #999999; font-weight:bold;"  >'.BOOKING_DETAILS_ROOM_NUMBER.'</td><td style="border:solid 1px #999999; font-weight:bold;">'.BOOKING_DETAILS_ROOM_TYPE.'</td><td style="border:solid 1px #999999; font-weight:bold;">'.SEARCH_GUEST_PER_ROOM.'</td></tr>';
			
			while($room_info_row=mysql_fetch_row($room_info_sql)){
				$strhtml.='<tr><td style="border:solid 1px #999999">'.$room_info_row[0].'</td><td style="border:solid 1px #999999">'.$room_info_row[1].'</td><td style="border:solid 1px #999999">'.$room_info_row[2].'</td></tr>';
			}
		
			$strhtml.='</table></p>';
		
			echo json_encode(array("errorcode"=>$this->errorCode,"strhtml"=>base64_encode($strhtml)));							
		}else{
			$this->errorCode = 1;
			$this->errorMsg = "Sorry! Your booking number is incorrect! please enter correct booking number.";
			echo json_encode(array("errorcode"=>$this->errorCode,"strmsg"=>$this->errorMsg));	
		}	
	}//end of function	
	
	/************************************************************************************
	 * Function for fetch Customer Details
	 * actioncode = 2
	 ************************************************************************************/
	public function getCustomerDetails(){
		global $bsiCore;
		$this->errorCode = 0;
		$this->errorMsg = "";	
		
		$existing_email = $bsiCore->ClearInput($_POST['existing_email']);			
		$client_sql=mysql_query("select * from bsi_clients where email='".$existing_email."' limit 1");
		
		if(mysql_num_rows($client_sql)){	
			$client_row=mysql_fetch_assoc($client_sql);		
			$title_array=array("Mr.","Ms.","Mrs.","Miss.","Dr.","Prof.");
			$select_title='<select name="title" class="textbox3">';
			
			for($p=0; $p<6; $p++){
				if($title_array[$p]==$client_row['title']){
					$select_title.='<option value="'.$title_array[$p].'" selected="selected">'.$title_array[$p].'</option>';
				}else{
					$select_title.='<option value="'.$title_array[$p].'" >'.$title_array[$p].'</option>';
				}
			}
			
			$select_title.='</select>';
			
			echo json_encode(array("errorcode"=>$this->errorCode, "title"=>$select_title, "first_name"=>$client_row['first_name'], "surname"=>$client_row['surname'],"street_addr"=>$client_row['street_addr'],"city"=>$client_row['city'],"province"=>$client_row['province'],"zip"=>$client_row['zip'],"country"=>$client_row['country'],"phone"=>$client_row['phone'],"fax"=>$client_row['fax'],"email"=>$client_row['email'] ));	
									
		}else{
			$this->errorCode = 1;
			$this->errorMsg = BOOKING_DETAILS_EXISTING_CUSTOMER;
			echo json_encode(array("errorcode"=>$this->errorCode,"strmsg"=>base64_encode($this->errorMsg)));	
		}
	}//end of function	
	
	
	/************************************************************************************
	 * Function for sending Contact Us Email Message
	 * actioncode = 3
	 ************************************************************************************/
	public function sendContactMessage(){
		global $bsiCore;	
		$this->errorCode = 0;
		$this->errorMsg = "";	
		$bsiMail = new bsiMail();	
			
		$fullname	= $bsiCore->ClearInput($_POST['fullname']);		
		$email		= $bsiCore->ClearInput($_POST['email']);	
		$phone		= $bsiCore->ClearInput($_POST['phone']);	
		$subject	= $bsiCore->ClearInput($_POST['subject']);	
		$message	= $bsiCore->ClearInput($_POST['message']);				
		$email_subj = $bsiCore->config['conf_hotel_name']." Contact Form Message.";
		
		$email_body_contact="Name: ".$fullname."<br><br>Email: ".$email."<br><br>Phone: ".$phone."<br><br>Subject: ".$subject."<br><br>Message: ".$message;
		$retrunmsg = $bsiMail->sendEMail($bsiCore->config['conf_hotel_email'], $email_subj, $email_body_contact);
	
		if($retrunmsg){		   
			$strhtml="<p align='center'>".CONTACT_SEND_SUCCESS_MSG."</p>";	  
			echo json_encode(array("errorcode"=>$this->errorCode,"strhtml"=>base64_encode($strhtml)));							
		}else{
			$this->errorCode = 1;
			$this->errorMsg = CONTACT_SEND_FAILURE;
			echo json_encode(array("errorcode"=>$this->errorCode,"strmsg"=>base64_encode($this->errorMsg)));	
		}		
	}//end of function	
	
	
	/************************************************************************************
	 * Function for apply Discount
	 * actioncode = 4
	 ************************************************************************************/
	public function applyCouponDiscount(){		
		global $bsiCore;	
		$this->errorCode = 0;
		$this->errorMsg = "";	
		$orignalPrice = NULL;
		$advamtmodified = false;
		
		if(isset($_SESSION["dvars_roomprices"])){
			$orignalPrice = $_SESSION["dvars_roomprices"];
		}
		$discountedsubtotal = $orignalPrice['discountedsubtotal'];	//subtotal price after monthly discount
		$subtotal = $orignalPrice['subtotal'];	
		
		$discountcoupon = $bsiCore->ClearInput($_POST['discountcoupon']);		
		$clientemail = $bsiCore->ClearInput($_POST['clientemail']);	
		$discount = 0.00;
		$taxamount = 0.00;
		$grandtotal = 0.00;
		
		//calculate discount for a given coupon
		$discount = $this->getPromoDiscount($discountcoupon, $clientemail, $subtotal); 				
		$grandtotal = $discountedsubtotal - $discount;
		
		if($bsiCore->config['conf_tax_amount'] > 0){
			$taxamount = ($grandtotal * $bsiCore->config['conf_tax_amount'])/100;		
		}
		$grandtotal = $grandtotal + $taxamount;
		
		$advancepercentage = $orignalPrice['advancepercentage'];				
		$advanceamt = $grandtotal;
		if($bsiCore->config['conf_enabled_deposit'] && ($advancepercentage > 0 && $advancepercentage < 100)){
			$advamtmodified = true;
			$advanceamt = ($grandtotal * $advancepercentage)/100;
		}
		
		$fmdiscount		= number_format($discount, 2 , '.', ',');
		$fmtaxamount 	= number_format($taxamount, 2 , '.', ',');	
		$fmgrandtotal	= number_format($grandtotal, 2 , '.', ',');
		$fmadvamount	= number_format($advanceamt, 2 , '.', ',');
		
		if(isset($_SESSION["dvars_roomprices"]['discountcoupon'])){unset($_SESSION["dvars_roomprices"]['discountcoupon']);}
		if(isset($_SESSION["dvars_roomprices"]['coupondiscount'])){unset($_SESSION["dvars_roomprices"]['coupondiscount']);}
		if(isset($_SESSION["dvars_roomprices"]['coupontaxamount'])){unset($_SESSION["dvars_roomprices"]['coupontaxamount']);}	
		if(isset($_SESSION["dvars_roomprices"]['coupongrandtotal'])){unset($_SESSION["dvars_roomprices"]['coupongrandtotal']);}
		if(isset($_SESSION["dvars_roomprices"]['couponadvamount'])){unset($_SESSION["dvars_roomprices"]['couponadvamount']);}	
		
		if($this->errorCode){
			echo json_encode(array("errorcode"=>$this->errorCode,"strmsg"=>base64_encode($this->errorMsg),"fmdiscount"=>$fmdiscount,"fmtaxamount"=>$fmtaxamount,"fmgrandtotal"=>$fmgrandtotal,"fmadvamount"=>$fmadvamount,"advamtmodified"=>$advamtmodified));			
		}else{
			$_SESSION["dvars_roomprices"]['discountcoupon'] = $discountcoupon;
			$_SESSION["dvars_roomprices"]['coupondiscount'] = number_format($discount, 2 , '.', '');
			$_SESSION["dvars_roomprices"]['coupontaxamount'] = number_format($taxamount, 2 , '.', '');			
			$_SESSION["dvars_roomprices"]['coupongrandtotal'] = number_format($grandtotal, 2 , '.', '');
			$_SESSION["dvars_roomprices"]['couponadvamount'] = number_format($advanceamt, 2 , '.', '');
			
			echo json_encode(array("errorcode"=>$this->errorCode,"strmsg"=>base64_encode($this->errorMsg),"couponcode"=>$discountcoupon,"fmdiscount"=>$fmdiscount,"fmtaxamount"=>$fmtaxamount,"fmgrandtotal"=>$fmgrandtotal,"fmadvamount"=>$fmadvamount, "advamtmodified"=>$advamtmodified));							
		}		
	}//end of function	
	
	private function getPromoDiscount($couponCode, $clientEmail, $subTotalAmount = 0.00){	
		$discountAmount = 0.00;
			
		if($couponCode == ""){
			$this->errorCode = 1;
			$this->errorMsg = BOOKING_DETAILS_VALID_COUPON;
			return $discountAmount;
		}
		
		if($clientEmail == ""){
			$this->errorCode = 1;
			$this->errorMsg = BOOKING_DETAILS_FILL_EMAIL;
			return $discountAmount;
		}
		
		$promo = mysql_fetch_assoc(mysql_query("SELECT * FROM bsi_promocode WHERE promo_code = '".$couponCode."' AND (exp_date IS NULL OR exp_date >= CURDATE())"));		
		
		if(!$promo){
			$this->errorCode = 1;
			$this->errorMsg = BOOKING_DETAILS_EXPIRED_COUPON;
			return $discountAmount;
		}
		
		$alreadyUsed = mysql_num_rows(mysql_query("SELECT bok.discount_coupon FROM bsi_bookings bok, bsi_clients clt WHERE bok.client_id = clt.client_id AND clt.email = '".$clientEmail."' AND bok.discount_coupon = '".$couponCode."'"));
		
		if($alreadyUsed > 0 && $promo['reuse_promo'] == 0){
			$this->errorCode = 1;
			$this->errorMsg = BOOKING_DETAILS_DISCOUNT_COUPON.":".$couponCode." ".BOOKING_DETAILS_ALREADY_USE;
			return $discountAmount;
		}				
		
		if($promo["promo_category"] == 2){			
			$existingclient = mysql_fetch_assoc(mysql_query("SELECT * FROM bsi_clients WHERE email = '".$clientEmail."' AND existing_client = 1"));
			if(!$existingclient){
				$this->errorCode = 1;
				$this->errorMsg = BOOKING_DETAILS_NOT_CUSTOMER;
				return $discountAmount;
			}
		}
		
		if($promo["promo_category"] == 3){
			if($clientEmail != $promo["customer_email"]){
				$this->errorCode = 1;
				$this->errorMsg = BOOKING_DETAILS_EXPIRED_COUPON;
				return $discountAmount;
			}
		}
		
		if($subTotalAmount < $promo['min_amount']){
			$this->errorCode = 1;
			$this->errorMsg = BOOKING_DETAILS_NOT_VALID_PART1." ".$subTotalAmount.". ".BOOKING_DETAILS_NOT_VALID_PART2." ".$promo['min_amount'].".";
			return $discountAmount;
		}
		
		if($promo['percentage'] == 1){
			if($promo['discount'] > 0){
				$discountAmount = ($subTotalAmount * $promo["discount"])/100;
			}						
		}else{
			$discountAmount = $promo["discount"];
		}
		
		return $discountAmount;				
	} //end of function	
} //end of class
?>
