<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/

class bsiSearch
{
	public $checkInDate = '';
    public $checkOutDate = '';	
	public $mysqlCheckInDate = '';
    public $mysqlCheckOutDate = '';
	public $guestsPerRoom = 0;
	public $childPerRoom = 0;
	public $extrabedPerRoom = false;
				
	public $nightCount = 0;	
	public $fullDateRange;
	public $roomType = array();	
	public $multiCapacity = array();
	public $searchCode = "SUCCESS";
	const SEARCH_CODE = "SUCCESS";
	
	function bsiSearch() {				
		$this->setRequestParams();
		$this->getNightCount();
		$this->checkSearchEngine();		
		
		if($this->searchCode == self::SEARCH_CODE){
			$this->loadMultiCapacity();			
			$this->loadRoomTypes();
			$this->fullDateRange = $this->getDateRangeArray($this->mysqlCheckInDate, $this->mysqlCheckOutDate);
			$this->setMySessionVars();
		}	
	}
	
	private function setRequestParams() {		
		global $bsiCore;
		$tmpVar = isset($_POST['check_in'])? $_POST['check_in'] : NULL;
		$this->setMyParamValue($this->checkInDate, $bsiCore->ClearInput($tmpVar), NULL, true);
		$tmpVar = isset($_POST['check_out'])? $_POST['check_out'] : NULL;
		$this->setMyParamValue($this->checkOutDate, $bsiCore->ClearInput($tmpVar), NULL, true);
		$tmpVar = isset($_POST['capacity'])? $_POST['capacity'] : 0;
		$this->setMyParamValue($this->guestsPerRoom, $bsiCore->ClearInput($tmpVar), 0, true);
		$tmpVar = isset($_POST['childcount'])? $_POST['childcount'] : 0;
		$this->setMyParamValue($this->childPerRoom, $bsiCore->ClearInput($tmpVar), 0, false);
		$tmpVar = isset($_POST['extrabed'])? true : false;
		$this->setMyParamValue($this->extrabedPerRoom, $tmpVar, false, false);				
		$this->mysqlCheckInDate = $bsiCore->getMySqlDate($this->checkInDate);	
		$this->mysqlCheckOutDate = $bsiCore->getMySqlDate($this->checkOutDate);				
	}
	
	private function setMyParamValue(&$membervariable, $paramvalue, $defaultvalue, $required = false){
		if($required){if(!isset($paramvalue)){$this->invalidRequest();}}
		if(isset($paramvalue)){$membervariable = $paramvalue;}else{$membervariable = $defaultvalue;}
	}
	
	private function setMySessionVars(){
		if(isset($_SESSION['sv_checkindate'])) unset($_SESSION['sv_checkindate']);
		if(isset($_SESSION['sv_checkoutdate'])) unset($_SESSION['sv_checkoutdate']);
		if(isset($_SESSION['sv_mcheckindate'])) unset($_SESSION['sv_mcheckindate']);
		if(isset($_SESSION['sv_mcheckoutdate'])) unset($_SESSION['sv_mcheckoutdate']);
		if(isset($_SESSION['sv_nightcount'])) unset($_SESSION['sv_nightcount']);
		if(isset($_SESSION['sv_guestperroom'])) unset($_SESSION['sv_guestperroom']);
		if(isset($_SESSION['sv_childcount'])) unset($_SESSION['sv_childcount']);
		if(isset($_SESSION['sv_extrabed'])) unset($_SESSION['sv_extrabed']);	
		
		$_SESSION['sv_checkindate'] = $this->checkInDate;
		$_SESSION['sv_checkoutdate'] = $this->checkOutDate;
		$_SESSION['sv_mcheckindate'] = $this->mysqlCheckInDate;
		$_SESSION['sv_mcheckoutdate'] = $this->mysqlCheckOutDate;
		$_SESSION['sv_nightcount'] = $this->nightCount;		
		$_SESSION['sv_guestperroom'] = $this->guestsPerRoom;	
		$_SESSION['sv_childcount'] = $this->childPerRoom;		
		$_SESSION['sv_extrabed'] = $this->extrabedPerRoom;		
		$_SESSION['svars_details'] = array();
	}
	
	private function invalidRequest(){
		header('Location: booking-failure.php?error_code=9');
		die;
	}
	
	private function getNightCount() {		
		$checkin_date = getdate(strtotime($this->mysqlCheckInDate));
		$checkout_date = getdate(strtotime($this->mysqlCheckOutDate));
		$checkin_date_new = mktime( 12, 0, 0, $checkin_date['mon'], $checkin_date['mday'], $checkin_date['year']);
		$checkout_date_new = mktime( 12, 0, 0, $checkout_date['mon'], $checkout_date['mday'], $checkout_date['year']);
		$this->nightCount = round(abs($checkin_date_new - $checkout_date_new) / 86400);
	}
	
	/**
     * Takes two dates formatted as YYYY-MM-DD and 
	 * creates an inclusive array of the dates between the from date not the to date
     * @return array
     */	
	public function getDateRangeArray($startDate, $endDate) {	
		$date_arr = array();  
		$time_from = mktime(1,0,0,substr($startDate,5,2), substr($startDate,8,2),substr($startDate,0,4));
		$time_to = mktime(1,0,0,substr($endDate,5,2), substr($endDate,8,2),substr($endDate,0,4));		
		if ($time_to >= $time_from) {  
			while ($time_from < $time_to) {      
				array_push($date_arr, date('Y-m-d',$time_from));
				$time_from+= 86400; // add 24 hours
			}
		}  
		return $date_arr;
	}
	
	private function checkSearchEngine(){
		global $bsiCore;
		if(intval($bsiCore->config['conf_booking_turn_off']) > 0){
			$this->searchCode = "SEARCH_ENGINE_TURN_OFF";
			return 0;
		}
				
		$diffrow = mysql_fetch_assoc(mysql_query("SELECT DATEDIFF('".$this->mysqlCheckOutDate."', '".$this->mysqlCheckInDate."') AS INOUTDIFF"));
		$dateDiff = intval($diffrow['INOUTDIFF']);
		if($dateDiff < 0){
			$this->searchCode = "OUT_BEFORE_IN";
			return 0;
		}else if($dateDiff < intval($bsiCore->config['conf_min_night_booking'])){
			$this->searchCode = "NOT_MINNIMUM_NIGHT";
			return 0;
		}
		
		$userInputDate = strtotime($this->mysqlCheckInDate);
		$hotelDateTime = strtotime(date("Y-m-d"));
		$timezonediff =  ($userInputDate - $hotelDateTime);
		if($timezonediff < 0){
			$this->searchCode = "TIME_ZONE_MISMATCH";
			return 0;
		}		
	}
	
	private function loadRoomTypes() {			
		$sql = mysql_query("SELECT * FROM bsi_roomtype");
		while($currentrow = mysql_fetch_assoc($sql)){			
			array_push($this->roomType, array('rtid'=>$currentrow["roomtype_ID"], 'rtname'=>$currentrow["type_name"]));
		}
		mysql_free_result($sql);
	}	
	
	private function loadMultiCapacity() {			
		$sql = mysql_query("SELECT * FROM bsi_capacity WHERE capacity = ".$this->guestsPerRoom);
		while($currentrow = mysql_fetch_assoc($sql)){			
			$this->multiCapacity[$currentrow["id"]] = array('capval'=>$currentrow["capacity"],'captitle'=>$currentrow["title"]);
		}		
		mysql_free_result($sql);
	}
	
	public function getHotelExtras(){
		$hotelExtras = array();
		$sql = mysql_query("SELECT * FROM bsi_extras WHERE enabled = 1");
		while($currentrow = mysql_fetch_assoc($sql)){			
			array_push($hotelExtras, array('extraid'=>$currentrow["extras_id"],'description'=>$currentrow["description"], 'price'=>$currentrow["fees"]));
		}
		mysql_free_result($sql);
		return $hotelExtras;		
	}	
	
	
	public function getAvailableRooms($roomTypeId, $roomTypeName, $capcityid){
		/**
		 * Global Ref: conf.class.php
		 **/
		global $bsiCore;		
		$currency_symbol = $bsiCore->config['conf_currency_symbol'];		
		$searchresult = array('roomtypeid'=>$roomTypeId, 'roomtypename'=>$roomTypeName, 'capacityid'=>$capcityid, 'capacitytitle'=>$this->multiCapacity[$capcityid]['captitle'], 'maxchild'=>$this->childPerRoom);
		$room_count = 0;
		$dropdown_html = '<option value="0" selected="selected">0</option>';
		
		$price_details_html = '';
		$total_price_amount = 0;
		$calculated_extraprice = 0;
		$extraSearchParam = "";
		
		if($this->childPerRoom > 0){
			$extraSearchParam.= " AND rm.no_of_child = ".$this->childPerRoom." ";
		}
		if($this->extrabedPerRoom){
			$extraSearchParam.= " AND rm.extra_bed > 0 ";		
		}
		
		
		$searchsql = "		
		SELECT rm.room_ID, rm.room_no
		  FROM bsi_room rm
		 WHERE rm.roomtype_id = ".$roomTypeId."
			   AND rm.capacity_id = ".$capcityid."".$extraSearchParam."
			   AND rm.room_id NOT IN
					  (SELECT resv.room_id
						 FROM bsi_reservation resv, bsi_bookings boks
						WHERE     boks.is_deleted = FALSE
							  AND resv.bookings_id = boks.booking_id
							  AND resv.room_type_id = ".$roomTypeId."
							  AND (('".$this->mysqlCheckInDate."' BETWEEN boks.start_date AND DATE_SUB(boks.end_date, INTERVAL 1 DAY))
							   OR (DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) BETWEEN boks.start_date AND DATE_SUB(boks.end_date, INTERVAL 1 DAY))
							   OR (boks.start_date BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY))
							   OR (DATE_SUB(boks.end_date, INTERVAL 1 DAY) BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY))))";
        
        
                 
		
		/*$searchsql = "SELECT rm.room_ID, rm.room_no from bsi_room rm WHERE rm.roomtype_id=".$roomTypeId." AND rm.capacity_id=".$capcityid."".$extraSearchParam." AND rm.room_id NOT IN (SELECT rvs.room_id FROM bsi_reservation rvs, bsi_bookings bks WHERE bks.is_deleted = false AND ((bks.start_date BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY )) OR (DATE_SUB(bks.end_date, INTERVAL 1 DAY ) BETWEEN '".$this->mysqlCheckInDate."' AND  '".$this->mysqlCheckOutDate."') OR ((bks.start_date < '".$this->mysqlCheckInDate."') AND (DATE_SUB(bks.end_date, INTERVAL 1 DAY ) > DATE_SUB('".$this->mysqlCheckOutDate."' , INTERVAL 1 DAY )))) AND rvs.bookings_id = bks.booking_id AND rvs.room_type_id = '".$roomTypeId."')";
		*/
		//echo $searchsql."<br>";
		
		$sql = mysql_query($searchsql);
		$tmpctr = 1;
		$searchresult['availablerooms'] = array();
		while($currentrow = mysql_fetch_assoc($sql)){				
			$dropdown_html.= '<option value="'.$tmpctr.'">'.$tmpctr.'</option>';
			array_push($searchresult['availablerooms'], array('roomid'=>$currentrow["room_ID"], 'roomno'=>$currentrow["room_no"]));
			$tmpctr++;
		}
		
		mysql_free_result($sql);
		
		if($tmpctr > 1){
			$pricesql = "SELECT * FROM bsi_priceplan WHERE roomtype_id = ".$roomTypeId." AND capacity_id = ".$capcityid." AND (('".$this->mysqlCheckInDate."' BETWEEN start_date AND end_date) OR (DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) BETWEEN start_date AND end_date) OR start_date = '".$this->mysqlCheckInDate."' OR end_date = '".$this->mysqlCheckInDate."' OR start_date = DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) OR end_date = DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) OR default_plan =1) ORDER BY start_date";
			
			//echo $pricesql."<br>";
			$psql = mysql_query($pricesql);
			$arr_default_price_plan = array();
			$arr_custom_price_plan = array();
				
			while($currentrow = mysql_fetch_assoc($psql)){
				if($currentrow["default_plan"] == 1){							
					array_push($arr_default_price_plan, array("nights" => $this->nightCount, "price" => $currentrow["price"], "extraprice"=> $currentrow["extrabed"]));
					
				}else{
					
					$date_range_start = $currentrow["start_date"];
					$date_range_end = $currentrow["end_date"];
					if(strtotime($date_range_start) <= strtotime($this->mysqlCheckInDate)){
						$date_range_start = $this->mysqlCheckInDate;						
					}
					if(strtotime($date_range_end) >= strtotime($this->mysqlCheckOutDate)){
						$date_range_end = $this->mysqlCheckOutDate;
					}
					if(($date_range_start == $this->mysqlCheckInDate) && ($date_range_end == $this->mysqlCheckOutDate)){
						array_push($arr_custom_price_plan, array("nights" => $this->nightCount, "price" => $currentrow["price"], "extraprice"=> $currentrow["extrabed"]));						
					}else{
						$remaining_date_range = $this->getDateRangeArray($date_range_start, $date_range_end);										
						$remaining_nights = count(array_intersect($this->fullDateRange, $remaining_date_range));
						if($remaining_nights){
							array_push($arr_custom_price_plan, array("nights" => $remaining_nights, "price" => $currentrow["price"], "extraprice"=> $currentrow["extrabed"]));
						}						
					}						
				}				
			}				
			
			$night_count_at_customprice = 0;	
			$searchresult['prices'] = array();	
			
			foreach($arr_custom_price_plan as $custom_price_plan){
				$calculated_price = $custom_price_plan["nights"] * $custom_price_plan["price"];
				$calculated_extraprice = $calculated_extraprice + ($custom_price_plan["nights"] * $custom_price_plan["extraprice"]);				
				$total_price_amount = $total_price_amount + $calculated_price;
				$night_count_at_customprice = $night_count_at_customprice + $custom_price_plan["nights"];
				$price_details_html.= '<tr><td align="right">'.$custom_price_plan["nights"].' '.SEARCH_NIGHTS.'</td><td align="center"> x </td><td align="right">'.$currency_symbol.number_format($custom_price_plan["price"], 2 , '.', ',').'</td><td align="center"> = <td align="right">'.$currency_symbol.number_format($calculated_price,  2 , '.', ','). '</td></tr>';					
				
				array_push($searchresult['prices'], array('night'=>$custom_price_plan["nights"], 'price'=>$custom_price_plan["price"], 'extraprice'=>$custom_price_plan["extraprice"]));
			}
	
			$night_count_at_defaultprice = $this->nightCount - $night_count_at_customprice;
			if($night_count_at_defaultprice > 0){	
				foreach($arr_default_price_plan as $default_price_plan){								
					$calculated_price = $night_count_at_defaultprice * $default_price_plan["price"];
					$calculated_extraprice = $calculated_extraprice + ($night_count_at_defaultprice * $default_price_plan["extraprice"]);					
					$total_price_amount = $total_price_amount + $calculated_price;
					$price_details_html .= '<tr><td align="right">'.$night_count_at_defaultprice.' '.SEARCH_NIGHTS.'</td><td align="center"> x </td><td align="right">'.$currency_symbol.number_format($default_price_plan["price"], 2 , '.', ',').'</td><td align="center"> = <td align="right">'.$currency_symbol.number_format($calculated_price, 2 , '.', ','). '</td></tr>';									
					
					array_push($searchresult['prices'], array('night'=>$night_count_at_defaultprice, 'price'=>$default_price_plan["price"], 'extraprice'=>$default_price_plan["extraprice"]));
				}
			}	
		}
		$searchresult['roomprice'] = $total_price_amount;
		$searchresult['extrabedprice'] = $calculated_extraprice;				
		if($tmpctr > 1) array_push($_SESSION['svars_details'], $searchresult);
		unset($searchresult);
		
		return array(
		'roomcnt' => $tmpctr-1,		
		'roomdropdown' => $dropdown_html,
		'pricedetails' => $price_details_html,		
		'totalprice' => $total_price_amount,
		'extraprice' => $calculated_extraprice);
	}
}
?>
