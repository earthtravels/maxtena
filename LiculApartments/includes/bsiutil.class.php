<?php
/**
* @package BSI
* @version $revision 2.0 $
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/

class bsiRoomTariff
{	
	public $roomTypes = array();	
	public $capacities = array();
	private $outputFormat = ''; 
		
	function bsiRoomTariff(){
		/**
		 * Global Ref: conf.class.php
		 **/
		global $bsiCore;	
			
		$format = $bsiCore->config['conf_dateformat'];
		//default set to mm/dd/yyyy 
		$this->outputFormat = isset($format) ? $this->getUserDateFormat($format) : '%m/%d/%Y';			
		$this->loadRoomTypes();	
		$this->loadCapacity();
	
	}
	
	private function getUserDateFormat($inputDtFormat){		
		$dtformatter = array('dd'=>'%d', 'mm'=>'%m', 'yyyy'=>'%Y', 'yy'=>'%y');		
		$dtformat = split("[/.-]", $inputDtFormat);
		$dtseparator = ($dtformat[0] === 'yyyy')? substr($inputDtFormat, 4, 1) : substr($inputDtFormat, 2, 1);
		return $dtformatter[$dtformat[0]].$dtseparator.$dtformatter[$dtformat[1]].$dtseparator.$dtformatter[$dtformat[2]];	
	}
	
	private function loadRoomTypes(){
		$sql = mysql_query("SELECT * FROM bsi_roomtype");		
		while($currentRow = mysql_fetch_assoc($sql)){	
			$this->roomTypes[$currentRow["roomtype_ID"]] = $currentRow["type_name"];				
		}		
		mysql_free_result($sql);
	}
	
	private function loadCapacity(){
		$sql = mysql_query("SELECT * FROM bsi_capacity ORDER BY capacity");
		while($currentRow = mysql_fetch_assoc($sql)){					
			$this->capacities[$currentRow["id"]] = array('capacity'=>$currentRow["capacity"], 'title'=>$currentRow["title"]); 	
		}	
		mysql_free_result($sql); 
	}
		
	public function loadPricePaln($roomtypeid=0, $isdefault = 1){
	//echo $roomtypeid. ', '.$isdefault.",".$this->outputFormat."<br>";
		//$sql = mysql_query("SELECT roomtype_id, capacity_id, DATE_FORMAT(start_date, '".$this->outputFormat."') AS start_date , DATE_FORMAT(end_date, '".$this->outputFormat."') AS end_date, price, extrabed, default_plan FROM bsi_priceplan WHERE roomtype_id = ".$roomtypeid." AND default_plan = ".$isdefault." AND IFNULL(end_date, CURDATE()+1) > CURDATE() ORDER BY start_date, capacity_id");	
		$sql = mysql_query("SELECT roomtype_id, capacity_id, DATE_FORMAT(start_date, '".$this->outputFormat."') AS start_date , DATE_FORMAT(end_date, '".$this->outputFormat."') AS end_date, price, extrabed, default_plan FROM bsi_priceplan WHERE roomtype_id = ".$roomtypeid." AND default_plan = ".$isdefault." AND (end_date IS NULL or end_date > CURDATE()) ORDER BY start_date, capacity_id");	
		$pricePlans = array();	
		while($currentRow = mysql_fetch_assoc($sql)){	
			array_push($pricePlans, 
				array(
					'capacity'=>$currentRow["capacity_id"],
					'startdate'=>$currentRow["start_date"], 
					'enddate'=>$currentRow["end_date"], 
					'price'=>$currentRow["price"], 
					'extrabed'=>$currentRow["extrabed"],				
					'default'=>$currentRow["default_plan"]));
		}		
		mysql_free_result($sql);	
		return $pricePlans;
	}	
}
?>