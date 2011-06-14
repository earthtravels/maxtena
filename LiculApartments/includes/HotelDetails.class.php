<?php
require_once ("HotelDetails.class.php");

global $systemConfiguration;

class HotelDetails
{  	
	private $config = array();
	private static $hotelNameKey = "conf_hotel_name";
	private static $hotelAddressKey = "conf_hotel_streetaddr";
	private static $hotelCityKey = "conf_hotel_city";
	private static $hotelStateKey = "conf_hotel_state";	
	private static $hotelCountryKey = "conf_hotel_country";
	private static $hotelPhoneKey = "conf_hotel_phone";
	private static $hotelEmailKey = "conf_hotel_email";		
	
	public static $staticErrors = array();
	public $errors = array();
			
	public static function fetchFromParameters($params)
	{		
		$hotelDetailsObject = new HotelDetails(false);
		if (isset($params[HotelDetails::$hotelNameKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelNameKey] = trim($params[HotelDetails::$hotelNameKey]);
		}		
		if (isset($params[HotelDetails::$hotelAddressKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelAddressKey] = trim($params[HotelDetails::$hotelAddressKey]);
		}
		if (isset($params[HotelDetails::$hotelCityKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelCityKey] = trim($params[HotelDetails::$hotelCityKey]);
		}
		if (isset($params[HotelDetails::$hotelStateKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelStateKey] = trim($params[HotelDetails::$hotelStateKey]);
		}
		if (isset($params[HotelDetails::$hotelCountryKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelCountryKey] = trim($params[HotelDetails::$hotelCountryKey]);
		}
		if (isset($params[HotelDetails::$hotelPhoneKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelPhoneKey] = trim($params[HotelDetails::$hotelPhoneKey]);
		}
		if (isset($params[HotelDetails::$hotelEmailKey]))
		{
			$hotelDetailsObject->config[HotelDetails::$hotelEmailKey] = strtolower(trim($params[HotelDetails::$hotelEmailKey]));
		}			
		return $hotelDetailsObject;
	}
	
	public function isValid()
	{
		$this->errors = array();		
		if (strlen($this->getHotelAddress()) == 0)
		{
			$this->errors[] = "Hotel address must be specified";
		}
		if (strlen($this->getHotelCity()) == 0)
		{
			$this->errors[] = "Hotel city must be specified";
		}
		if (strlen($this->getHotelCountry()) == 0)
		{
			$this->errors[] = "Hotel country must be specified";
		}
		if (strlen($this->getHotelEmail()) == 0)
		{
			$this->errors[] = "Hotel email must be specified";
		}
		else if (!preg_match(Client::$EMAIL_REGEX, $this->getHotelEmail()))
		{
			$this->errors[] = "Hotel email is invalid";
		}
		if (strlen($this->getHotelName()) == 0)
		{
			$this->errors[] = "Hotel name must be specified";
		}
		if (strlen($this->getHotelPhone()) == 0)
		{
			$this->errors[] = "Hotel phone must be specified";
		}		
		return sizeof($this->errors) == 0;		
	}	
    
	public function getHotelName()
    {
        return $this->config[HotelDetails::$hotelNameKey];
    }
    
	public function getHotelAddress()
    {
        return $this->config[HotelDetails::$hotelAddressKey];
    }
    
	public function getHotelCity()
    {
        return $this->config[HotelDetails::$hotelCityKey];
    }
    
	public function getHotelState()
    {
        return $this->config[HotelDetails::$hotelStateKey];
    }
    
	public function getHotelCountry()
    {
        return $this->config[HotelDetails::$hotelCountryKey];
    }
    
	public function getHotelPhone()
    {
        return $this->config[HotelDetails::$hotelPhoneKey];
    }
    
	public function getHotelEmail()
    {
        return $this->config[HotelDetails::$hotelEmailKey];
    }
    
    public function save($isValidated = false)
    {
    	if (!$isValidated && !$this->isValid())
    	{
    		return false;
    	}    	
    	
    	$this->runUpdate(HotelDetails::$hotelNameKey, $this->getHotelName());
		$this->runUpdate(HotelDetails::$hotelAddressKey, $this->getHotelAddress());
		$this->runUpdate(HotelDetails::$hotelCityKey, $this->getHotelCity());
		$this->runUpdate(HotelDetails::$hotelStateKey, $this->getHotelState());
		$this->runUpdate(HotelDetails::$hotelCountryKey, $this->getHotelCountry());
		$this->runUpdate(HotelDetails::$hotelPhoneKey, $this->getHotelPhone());
		$this->runUpdate(HotelDetails::$hotelEmailKey, $this->getHotelEmail());		
		return true;    	
    }
    
    private function runUpdate($configKey, $value)
    {
    	$sql = "UPDATE bsi_configure SET conf_value='" . mysql_escape_string($value) . "' WHERE conf_key='" . $configKey . "'";
    	$query = mysql_query($sql);
    	if (!$query)
    	{
    		global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
    		die("Error: " . mysql_error());
    	}
    }
}