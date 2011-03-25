<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/

class bsiBookingDetails
{
	public $guestsPerRoom = 0;			
	public $nightCount = 0;	
	public $checkInDate = '';
	public $checkOutDate = '';	
	public $totalRoomCount = 0;	
	public $discountPlans = array();	
	public $roomPrices = array();
	public $listHotelExtraService = array();
		
	private $selectedRooms = '';
	private $needExtraBed = '';
	private $mysqlCheckInDate = '';
	private $mysqlCheckOutDate = '';
	private $hotelExrtaServices = array();
	
	private $searchVars = array();
	private $detailVars	= array();

	function bsiBookingDetails() {	
		$this->setRequestParams();			
		$this->loadDiscountPlans();	
	}		
	
	private function setRequestParams() {	
		/**
		 * Global Ref: conf.class.php
		 **/
		global $bsiCore;	
		
		$this->setMyParamValue($this->guestsPerRoom, 'SESSION', 'sv_guestperroom', NULL, true);		
		$this->setMyParamValue($this->checkInDate, 'SESSION', 'sv_checkindate', NULL, true);
		$this->setMyParamValue($this->mysqlCheckInDate, 'SESSION', 'sv_mcheckindate', NULL, true);
		$this->setMyParamValue($this->checkOutDate, 'SESSION', 'sv_checkoutdate', NULL, true);
		$this->setMyParamValue($this->mysqlCheckOutDate, 'SESSION', 'sv_mcheckoutdate', NULL, true);
		$this->setMyParamValue($this->nightCount, 'SESSION', 'sv_nightcount', NULL, true);		
		$this->setMyParamValue($this->searchVars, 'SESSION', 'svars_details', NULL, true);
		
		$this->setMyParamValue($this->selectedRooms, 'POST_SPECIAL', 'svars_selectedrooms', NULL, true);		
		$selected = 0;
		foreach($this->selectedRooms as &$val){		
			$val = $bsiCore->ClearInput($val); if($val) $selected++;
		}			
		if($selected == 0) $this->invalidRequest(9);		
		
		$this->setMyParamValue($this->needExtraBed, 'POST_SPECIAL', 'svars_extrabed', NULL, false);	
		$this->setMyParamValue($this->hotelExrtaServices, 'POST_SPECIAL', 'extraservices', NULL, false);	
		if($this->hotelExrtaServices)$this->hotelExrtaServices = array_filter($this->hotelExrtaServices);				
	}
	
	private function setMyParamValue(&$membervariable, $vartype, $param, $defaultvalue, $required = false){
		global $bsiCore;
		switch($vartype){
			case "POST": 
				if($required){if(!isset($_POST[$param])){$this->invalidRequest(9);} 
					else{$membervariable = $bsiCore->ClearInput($_POST[$param]);}}
				else{if(isset($_POST[$param])){$membervariable = $bsiCore->ClearInput($_POST[$param]);} 
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "POST_SPECIAL":
				if($required){if(!isset($_POST[$param])){$this->invalidRequest(9);}
					else{$membervariable = $_POST[$param];}}
				else{if(isset($_POST[$param])){$membervariable = $_POST[$param];}
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "GET":
				if($required){if(!isset($_GET[$param])){$this->invalidRequest(9);} 
					else{$membervariable = $bsiCore->ClearInput($_GET[$param]);}}
				else{if(isset($_GET[$param])){$membervariable = $bsiCore->ClearInput($_GET[$param]);} 
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "SESSION":
				if($required){if(!isset($_SESSION[$param])){$this->invalidRequest(9);} 
					else{$membervariable = $_SESSION[$param];}}
				else{if(isset($_SESSION[$param])){$membervariable = $_SESSION[$param];} 
					else{$membervariable = $defaultvalue;}}				
				break;	
			case "REQUEST":
				if($required){if(!isset($_REQUEST[$param])){$this->invalidRequest(9);}
					else{$membervariable = $bsiCore->ClearInput($_REQUEST[$param]);}}
				else{if(isset($_REQUEST[$param])){$membervariable = $bsiCore->ClearInput($_REQUEST[$param]);}
					else{$membervariable = $defaultvalue;}}				
				break;
			case "SERVER":
				if($required){if(!isset($_SERVER[$param])){$this->invalidRequest(9);}
					else{$membervariable = $_SERVER[$param];}}
				else{if(isset($_SERVER[$param])){$membervariable = $_SERVER[$param];}
					else{$membervariable = $defaultvalue;}}				
				break;	
					
		}		
	}	
	
	private function invalidRequest($errocode = 9){		
		header('Location: booking-failure.php?error_code='.$errocode.'');
		die;
	}
		
	private function loadDiscountPlans() {
		$month_num = intval(substr($this->mysqlCheckInDate, 5, 2)) ;
		$sql = mysql_query("SELECT * FROM bsi_deposit_discount WHERE month_num = ".$month_num);
		$this->discountPlans = mysql_fetch_assoc($sql);		
		mysql_free_result($sql);
	}
	
	private function gethotelExrtaServices(){		
		$extidlist = implode(",", array_keys($this->hotelExrtaServices));
		$sql = mysql_query("SELECT * FROM bsi_extras WHERE enabled = 1 AND extras_id IN(".$extidlist.")");
		
		while($currentrow = mysql_fetch_assoc($sql)){	
			$temptotalfees = 0.00;
			$tempdescription = "";
			$temptotalfees = number_format(($currentrow["fees"] * $this->hotelExrtaServices[$currentrow["extras_id"]]), 2, '.', '');
			$tempdescription = $currentrow["description"]." x <b>".$this->hotelExrtaServices[$currentrow["extras_id"]]."</b>";				
			array_push($this->listHotelExtraService, array('extraid'=>$currentrow["extras_id"],'description'=>$tempdescription, 'price'=>$temptotalfees));
				
			$this->roomPrices['totalhotelextraprices'] = $this->roomPrices['totalhotelextraprices'] + $temptotalfees;					
		}		
		mysql_free_result($sql);	
	}	
	
	public function generateBookingDetails() {
		/**
		 * Global Ref: conf.class.php
		 **/
		global $bsiCore;
		
		$result = array();
		$dvroomidsonly = "";
		$selectedRoomsCount = count($this->selectedRooms);	
		//$this->roomPrices['discountenabled'] = false;
		//$this->roomPrices['depositeenabled'] = false;		
		$this->roomPrices['totalextrabedcount'] = 0;
		$this->roomPrices['totalchildcount'] = 0;		
		$this->roomPrices['subtotal'] = 0.00;	
		$this->roomPrices['mothlydiscountpercent'] = 0.00;	
		$this->roomPrices['mothlydiscount'] = 0.00;	
		$this->roomPrices['discountedsubtotal'] = 0.00;	
		$this->roomPrices['totaltax'] = 0.00;			
		$this->roomPrices['grandtotal'] = 0.00;	
		$this->roomPrices['totalhotelextraprices'] = 0.00;
		$this->roomPrices['advancepercentage'] = 0.00;
		$this->roomPrices['advanceamount'] = 0.00;		
	
		$dvarsCtr = 0;
		for($i = 0; $i < $selectedRoomsCount; $i++){
			if($this->selectedRooms[$i] > 0){		
				$this->detailVars[$dvarsCtr] = $this->searchVars[$i]; //selected only			
								
				$tmpTotalPrice = 0;
				if($this->needExtraBed[$i] == "yes"){
					$tmpTotalPrice = $this->detailVars[$dvarsCtr]['roomprice'] + $this->detailVars[$dvarsCtr]['extrabedprice'];
					$this->detailVars[$dvarsCtr]['totalprice'] = $tmpTotalPrice;					
					$this->detailVars[$dvarsCtr]['needextrabed'] = "yes";					
				}else{
					$tmpTotalPrice = $this->detailVars[$dvarsCtr]['roomprice'];
					$this->detailVars[$dvarsCtr]['totalprice'] = $tmpTotalPrice;
					$this->detailVars[$dvarsCtr]['needextrabed'] = "no";
				}
				
				$tmpRoomCounter = 0;								
				foreach($this->detailVars[$dvarsCtr]['availablerooms'] as $availablerooms){	
					$this->roomPrices['subtotal'] = $this->roomPrices['subtotal'] + $tmpTotalPrice;	
					$dvroomidsonly.= $availablerooms['roomid'].",";													
					array_push($result, array('roomno'=>$availablerooms['roomno'], 'roomtype'=>$this->detailVars[$dvarsCtr]['roomtypename'], 'capacitytitle'=>$this->detailVars[$dvarsCtr]['capacitytitle'] ,'capacity'=>$this->guestsPerRoom, 'maxchild'=>$this->detailVars[$dvarsCtr]['maxchild'], 'extrabed'=> $this->needExtraBed[$i], 'grosstotal'=>$tmpTotalPrice));						
					if($this->detailVars[$dvarsCtr]['maxchild'] > 0){
						$this->roomPrices['totalchildcount'] = $this->roomPrices['totalchildcount'] + $this->detailVars[$dvarsCtr]['maxchild'];
					}
					if($this->detailVars[$dvarsCtr] == "yes"){
						$this->roomPrices['totalextrabedcount'] = $this->roomPrices['totalextrabedcount'] + 1;
					}
					$tmpRoomCounter++;	
					if($tmpRoomCounter == $this->selectedRooms[$i]){
						$tmpAvRmSize = count($this->detailVars[$dvarsCtr]['availablerooms']);
						for($akey = $tmpRoomCounter; $akey < $tmpAvRmSize; $akey++){
							unset($this->detailVars[$dvarsCtr]['availablerooms'][$akey]);
						}
						break;		
					}			
				}
				$dvarsCtr++;				
			}
		}
		
		
		if(isset( $_SESSION['dvars_details']))unset($_SESSION['dvars_details']);
		$_SESSION['dvars_details'] = $this->detailVars;
		
				
		if(isset($_SESSION['dv_roomidsonly']))unset($_SESSION['dv_roomidsonly']);
		$_SESSION['dv_roomidsonly'] = substr($dvroomidsonly, 0, -1);	
		$this->totalRoomCount =  count(explode(",", $_SESSION['dv_roomidsonly']));
		
		
		/* -------------------------------- calculate pricing ------------------------------------ */	
				
		if(count($this->hotelExrtaServices) > 0){
			$this->gethotelExrtaServices();
			$this->roomPrices['subtotal'] = $this->roomPrices['subtotal'] + $this->roomPrices['totalhotelextraprices'];			
		}
		
		if(isset($_SESSION['dvars_hotelextradetails']))unset($_SESSION['dvars_hotelextradetails']);
		$_SESSION['dvars_hotelextradetails'] = $this->listHotelExtraService;
		
			
		if($bsiCore->config['conf_enabled_discount'] && $this->discountPlans['discount_percent'] > 0){
			$this->roomPrices['mothlydiscountpercent'] = $this->discountPlans['discount_percent'];
			$this->roomPrices['mothlydiscount'] = ($this->roomPrices['subtotal'] * $this->discountPlans['discount_percent'])/100;			
		}
		$this->roomPrices['discountedsubtotal'] = $this->roomPrices['subtotal'] - $this->roomPrices['mothlydiscount'];
				
		if($bsiCore->config['conf_tax_amount'] > 0){ 
			$this->roomPrices['totaltax'] = ($this->roomPrices['discountedsubtotal'] * $bsiCore->config['conf_tax_amount'])/100;
		}
		
		$this->roomPrices['grandtotal'] = $this->roomPrices['discountedsubtotal'] + $this->roomPrices['totaltax'];		
		
		$this->roomPrices['advanceamount'] = $this->roomPrices['grandtotal'];
		if($bsiCore->config['conf_enabled_deposit']){
			$this->roomPrices['advancepercentage'] = $this->discountPlans['deposit_percent'];			
			if($this->roomPrices['advancepercentage'] > 0 && $this->roomPrices['advancepercentage'] < 100){
				$this->roomPrices['advanceamount'] = ($this->roomPrices['grandtotal'] * $this->roomPrices['advancepercentage'])/100;
			}
		}
		
		//format currencies round upto 2 decimal places		
		$this->roomPrices['subtotal'] = number_format($this->roomPrices['subtotal'], 2 , '.', '');	
		$this->roomPrices['mothlydiscountpercent'] = number_format($this->roomPrices['mothlydiscountpercent'], 2 , '.', '');	
		$this->roomPrices['mothlydiscount'] = number_format($this->roomPrices['mothlydiscount'], 2 , '.', '');	
		$this->roomPrices['discountedsubtotal'] = number_format($this->roomPrices['discountedsubtotal'], 2 , '.', '');	
		$this->roomPrices['totaltax'] = number_format($this->roomPrices['totaltax'], 2 , '.', '');			
		$this->roomPrices['grandtotal'] = number_format($this->roomPrices['grandtotal'], 2 , '.', '');	
		$this->roomPrices['totalhotelextraprices'] = number_format($this->roomPrices['totalhotelextraprices'], 2 , '.', '');
		$this->roomPrices['advancepercentage'] = number_format($this->roomPrices['advancepercentage'], 2 , '.', '');
		$this->roomPrices['advanceamount'] = number_format($this->roomPrices['advanceamount'], 2 , '.', '');
		
		if(isset($_SESSION['dvars_roomprices']))unset($_SESSION['dvars_roomprices']);
		$_SESSION['dvars_roomprices'] = $this->roomPrices;
		
		return $result;
	}	
}
?>