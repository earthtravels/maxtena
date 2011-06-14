<?php
require_once ("SystemConfiguration.class.php");
require_once ("DateUtils.class.php");

class SearchEngine
{  	
	public $isEnabled = false;
    public $searchCritieria;
	
	public $errors = array();	
	
	public function __construct($critieria)
	{        
		global $systemConfiguration;
		
		$this->isEnabled = $systemConfiguration->isSearchEgineEnabled();		
		if ($critieria == null || !($critieria instanceof SearchCriteria))
		{
			$this->searchCritieria = new SearchCriteria();
		}
		else
		{
        	$this->searchCritieria = $critieria;
		}
	}
	
	public function runSearchForRoom($roomId)
	{		
		$this->errors = array();
		if (!is_numeric($roomId))
		{
			$this->setError("Room id is not a number");
			return null;
		}
		
		$searchResults = array();		
		$totalCapacity = $this->searchCritieria->adultsCount + $this->searchCritieria->childrenCount; 
		$searchSql="SELECT * FROM bsi_rooms WHERE capacity >= $totalCapacity AND id NOT IN (SELECT room_id FROM bsi_bookings WHERE ((start_date + INTERVAL 1 DAY) BETWEEN CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE) OR (end_date - INTERVAL 1 DAY) BETWEEN CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE) OR ((start_date + INTERVAL 1 DAY) < CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND (end_date - INTERVAL 1 DAY) > CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE)) OR  ((start_date + INTERVAL 1 DAY) > CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND (end_date - INTERVAL 1 DAY) < CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE))) AND is_deleted = 0) AND id = " . $roomId;
		$searchQuery = mysql_query($searchSql);
		if (!$searchQuery)
		{
			die("Error: "  . mysql_error());
		}		
		while ($searchRow = mysql_fetch_assoc($searchQuery))
		{
			$matchingRoom = Room::fetchFromParameters($searchRow);
			array_push($searchResults, $matchingRoom);			 
		}
		return $searchResults;
	}

	public function runSearch()
	{		
		$this->errors = array();
		$searchResults = array();		
		$totalCapacity = $this->searchCritieria->adultsCount + $this->searchCritieria->childrenCount; 
		$searchSql="SELECT * FROM bsi_rooms WHERE capacity >= $totalCapacity AND id NOT IN (SELECT room_id FROM bsi_bookings WHERE ((start_date + INTERVAL 1 DAY) BETWEEN CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE) OR (end_date - INTERVAL 1 DAY) BETWEEN CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE) OR ((start_date + INTERVAL 1 DAY) < CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND (end_date - INTERVAL 1 DAY) > CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE)) OR  ((start_date + INTERVAL 1 DAY) > CAST('" . $this->searchCritieria->checkInDate->formatMySql() . "' AS DATE) AND (end_date - INTERVAL 1 DAY) < CAST('" . $this->searchCritieria->checkOutDate->formatMySql() . "' AS DATE))) AND is_deleted = 0)";
		$searchQuery = mysql_query($searchSql);
		if (!$searchQuery)
		{
			die("Error: "  . mysql_error());
		}				
		while ($searchRow = mysql_fetch_assoc($searchQuery))
		{
			$matchingRoom = Room::fetchFromParameters($searchRow);
			array_push($searchResults, $matchingRoom);			 
		}
		return $searchResults;
	}	
	
	public function getNightCount()
	{
		return $this->getNightCountPrivate(false);
	}
	
	private function getNightCountPrivate($skipValidation = false)
	{
		if (!$skipValidation && !$this->isValid())
		{
			return false;
		}
		$checkin_date = getdate(strtotime(DateUtils::getMySqlDate($this->checkInDate, $this->dateFormat)));
		$checkout_date = getdate(strtotime(DateUtils::getMySqlDate($this->checkOutDate, $this->dateFormat)));
		$checkin_date_new = mktime( 12, 0, 0, $checkin_date['mon'], $checkin_date['mday'], $checkin_date['year']);
		$checkout_date_new = mktime( 12, 0, 0, $checkout_date['mon'], $checkout_date['mday'], $checkout_date['year']);
		return round(abs($checkin_date_new - $checkout_date_new) / 86400);
	}
		
	public function isValid()
	{		
		global $systemConfiguration;
		$this->errors = array();
		if (strlen(trim($this->dateFormat)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_DATE_FORMAT_INVALID);
			return false;
		}		
		if (strlen(trim($this->checkInDate)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_CHECK_IN_REQ);
			return false;
		}
		else if (!DateUtils::isValidDate($this->checkInDate, $systemConfiguration->getDateFormat()))
		{
			$this->setError(SEARCH_CRITERIA_CHECK_IN_INVALID);
			return false;
		}
		
		$currentDate = getdate(); 
		if (!DateUtils::getNumericDate($this->checkInDate, $systemConfiguration->getDateFormat()) < intval($currentDate['year'] . $currentDate['mon'] . $currentDate['mday']))
		{
			$this->setError(SEARCH_CRITERIA_CHECK_IN_BEFORE_TODAY);
			return false;
		}
		
		if (strlen(trim($this->checkOutDate)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_CHECK_OUT_REQ);
			return false;
		}
		else if (!DateUtils::isValidDate($this->checkOutDate, $systemConfiguration->getDateFormat()))
		{
			$this->setError(SEARCH_CRITERIA_CHECK_OUT_INVALID);
			return false;
		}
		
		if (intval(DateUtils::getNumericDate($this->checkInDate)) > intval(DateUtils::getNumericDate($this->checkOutDate)))
		{
			$this->setError(SEARCH_CRITERIA_IN_BEFORE_OUT);
			return false;			
		}
		else if ($this->getNightCountPrivate(true) < $systemConfiguration->getMinimumNightCount())
		{
			$this->setError(SEARCH_CRITERIA_MIN_BOOKING_PART_1 . $systemConfiguration->getMinimumNightCount() . SEARCH_CRITERIA_MIN_BOOKING_PART_2);
			return false;
		}
		
		if (strlen(trim($this->adultsCount)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_REQ);
			return false;
		}
		else if (!is_int($this->adultsCount))
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_INVALID);
			return false;
		}
		else if (intval($this->adultsCount) <= 0)
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_INVALID);
			return false;
		}
		
		if (strlen(trim($this->childrenCount)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_REQ);
			return false;
		}
		else if (!is_int($this->childrenCount))
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_INVALID);
			return false;
		}		
		
		return sizeof($this->errors) == 0;					
	}	
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}