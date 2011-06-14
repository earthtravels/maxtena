<?php
require_once ("SystemConfiguration.class.php");

class SearchCriteria
{  		
	public $checkInDate;
	public $checkOutDate;
	public $adultsCount = 0;
	public $childrenCount = 0;	
	
	public $errors = array();	
	
	public function __construct()
	{
		$checkInDate = new Date();	
		$checkOutDate = new Date();	
	}
	
	public function getMySqlCheckInDate()
	{
		return DateUtils::getMySqlDate($this->checkInDate, $this->dateFormat);
	}  
	
	public function getMySqlCheckOutDate()
	{
		return DateUtils::getMySqlDate($this->checkInDate, $this->dateFormat);
	}
	
	public static function fetchFromParameters($params) 
	{
	    $searchCriteria = new SearchCriteria();
		if (isset($params['check_in']))
		{
			$searchCriteria->checkInDate = Date::parse($params['check_in']);
		}
		if (isset($params['check_out']))
		{
			$searchCriteria->checkOutDate = Date::parse($params['check_out']);
		}		
		if (isset($params['adults']))
		{
			$searchCriteria->adultsCount = intval($params['adults']);
		}		
		if (isset($params['children']))
		{
			$searchCriteria->childrenCount = intval($params['children']);
		}
		return $searchCriteria;			
	}	
	
	public function getNightCount()
	{
		return $this->checkOutDate->getInterval($this->checkInDate, "days");
	}	

	public function isValid()
	{		
		global $systemConfiguration;
		$currentDate = new Date();
		$currentDate->setTime(0, 0, 0);
		$this->errors = array();
						
		if ($this->checkInDate == null)
		{
			$this->setError(SEARCH_CRITERIA_CHECK_IN_INVALID);			
		}
		if ($this->checkOutDate == null)
		{
			$this->setError(SEARCH_CRITERIA_CHECK_OUT_INVALID);			
		}
		if ($this->checkOutDate != null && $this->checkInDate != null && $this->checkInDate->compareTo($this->checkOutDate) >= 1)
		{			
			$this->setError(SEARCH_CRITERIA_IN_BEFORE_OUT);			
		}
		if ($this->checkInDate != null && $this->checkInDate->compareTo($currentDate) < 0)
		{			
			$this->setError(SEARCH_CRITERIA_CHECK_IN_BEFORE_TODAY);			
		}
		if ($this->checkOutDate != null && $this->checkInDate != null && $this->getNightCount() < $systemConfiguration->getMinimumNightCount())
		{
			$this->setError(SEARCH_CRITERIA_MIN_BOOKING_PART_1 . $systemConfiguration->getMinimumNightCount() . SEARCH_CRITERIA_MIN_BOOKING_PART_2);			
		}
		if (strlen(trim($this->adultsCount)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_REQ);			
		}
		if (!is_int($this->adultsCount))
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_INVALID);			
		}
		if (intval($this->adultsCount) <= 0)
		{
			$this->setError(SEARCH_CRITERIA_ADULTS_INVALID);			
		}		
		if (strlen(trim($this->childrenCount)) == 0)
		{
			$this->setError(SEARCH_CRITERIA_CHILDREN_REQ);			
		}
		if (!is_int($this->childrenCount))
		{
			$this->setError(SEARCH_CRITERIA_CHILDREN_INVALID);			
		}
		
		return sizeof($this->errors) == 0;					
	}	
	
	private function setError($errorMessage)
	{
		$this->errors[sizeof($this->errors)] = $errorMessage;
	}
}