<?php
require_once ("db.conn.php");
require_once ("KLogger.class.php");
require_once ("LocalizedText.class.php");
require_once ("Date.class.php");
require_once ("SearchCriteria.class.php");
require_once ("SearchEngine.class.php");
require_once ("Room.class.php");
require_once ("RoomPricePlan.class.php");
require_once ("RoomImage.class.php");
require_once ("BookingDetails.class.php");
require_once ("PromoCode.class.php");
require_once ("MonthlyDiscountDeposit.class.php");
require_once ("Language.class.php");
require_once ("PriceDetails.class.php");
require_once ("ExtraService.class.php");
require_once ("MonthlyDiscountDeposit.class.php");
require_once ("Client.class.php");
require_once ("PaymentGateway.class.php");
require_once ("Booking.class.php");
require_once ("EmailContents.class.php");
require_once ("EmailPersonalizer.class.php");
require_once ("HotelDetails.class.php");
require_once ("PageContents.class.php");
require_once ("NewsletterSubscription.class.php");
require_once ("Content.class.php");
require_once ("NewsCategory.class.php");
require_once ("NewsPost.class.php");
require_once ("UploadImage.class.php");
require_once ("LocalizedCalendar.class.php");
require_once ("NewsSearchCriteria.class.php");
require_once ("GalleryImage.class.php");
require_once ("SliderImage.class.php");
require_once ("EmailSender.class.php");
require_once ("Faq.class.php");
require_once ("Utilities.class.php");



date_default_timezone_set("Europe/Zagreb");

class SystemConfiguration
{  	
	private $config = array();
	private static $hotelNameKey = "conf_hotel_name";
	private static $hotelAddressKey = "conf_hotel_streetaddr";
	private static $hotelCityKey = "conf_hotel_city";
	private static $hotelCountryKey = "conf_hotel_country";
	private static $hotelPhoneKey = "conf_hotel_phone";
	private static $hotelEmailKey = "conf_hotel_email";
	private static $hotelSiteKey = "conf_hotel_sitetitle";
	private static $hotelLogoTitleKey = "conf_hotel_logo_title";
	private static $hotelSiteDescriptionKey = "conf_hotel_sitedesc";
	private static $hotelSiteKeywordsKey = "conf_hotel_sitekeywords";	
	private static $minimumNightCountKey = "conf_min_night_booking";
	private static $disableSearchEngineKey = "conf_booking_turn_off";
	private static $timeZoneKey = "conf_hotel_timezone";
	private static $bookingExpirationKey = "conf_booking_exptime";
	private static $monthlyDiscountSchemeKey = "conf_enabled_discount";
	private static $monthlyDepositSchemeKey = "conf_enabled_deposit";
	private static $taxRateKey = "conf_tax_amount";
	private static $currencySymbolKey = "conf_currency_symbol";
	private static $currencyCodeKey = "conf_currency_code";
	private static $currencyBeforeAmountKey = "conf_currency_before_amount";
	private static $decimalSeparatorSymbolKey = "conf_decimal_symbol";
	private static $thousandSeparatorSymbolKey = "conf_thousand_symbol";
	private static $adminItemsPerPageKey = "conf_admin_items_per_page";
	private static $newsItemsPerPageKey = "conf_news_items_per_page";
	
	public static $timeZones = array('Kwajalein' => '(GMT-12:00) International Date Line West',
			'Pacific/Midway' => '(GMT-11:00) Midway Island',
			'Pacific/Samoa' => '(GMT-11:00) Samoa',
			'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
			'America/Anchorage' => '(GMT-09:00) Alaska',
			'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
			'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
			'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
			'America/Chihuahua' => '(GMT-07:00) Chihuahua',
			'America/Mazatlan' => '(GMT-07:00) Mazatlan',
			'America/Phoenix' => '(GMT-07:00) Arizona',
			'America/Regina' => '(GMT-06:00) Saskatchewan',
			'America/Tegucigalpa' => '(GMT-06:00) Central America',
			'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada)',
			'America/Mexico_City' => '(GMT-06:00) Mexico City',
			'America/Monterrey' => '(GMT-06:00) Monterrey',
			'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
			'America/Bogota' => '(GMT-05:00) Bogota',
			'America/Lima' => '(GMT-05:00) Lima',
			'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
			'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
			'America/Caracas' => '(GMT-04:30) Caracas',
			'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
			'America/Manaus' => '(GMT-04:00) Manaus',
			'America/Santiago' => '(GMT-04:00) Santiago',
			'America/La_Paz' => '(GMT-04:00) La Paz',
			'America/St_Johns' => '(GMT-03:30) Newfoundland',
			'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
			'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
			'America/Godthab' => '(GMT-03:00) Greenland',
			'America/Montevideo' => '(GMT-03:00) Montevideo',
			'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
			'Atlantic/Azores' => '(GMT-01:00) Azores',
			'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
			'Europe/Dublin' => '(GMT) Dublin',
			'Europe/Lisbon' => '(GMT) Lisbon',
			'Europe/London' => '(GMT) London',
			'Africa/Monrovia' => '(GMT) Monrovia',
			'Atlantic/Reykjavik' => '(GMT) Reykjavik',
			'Africa/Casablanca' => '(GMT) Casablanca',
			'Europe/Belgrade' => '(GMT+01:00) Belgrade',
			'Europe/Bratislava' => '(GMT+01:00) Bratislava',
			'Europe/Budapest' => '(GMT+01:00) Budapest',
			'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
			'Europe/Prague' => '(GMT+01:00) Prague',
			'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
			'Europe/Skopje' => '(GMT+01:00) Skopje',
			'Europe/Warsaw' => '(GMT+01:00) Warsaw',
			'Europe/Zagreb' => '(GMT+01:00) Zagreb',
			'Europe/Brussels' => '(GMT+01:00) Brussels',
			'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
			'Europe/Madrid' => '(GMT+01:00) Madrid',
			'Europe/Paris' => '(GMT+01:00) Paris',
			'Africa/Algiers' => '(GMT+01:00) West Central Africa',
			'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
			'Europe/Berlin' => '(GMT+01:00) Berlin',
			'Europe/Rome' => '(GMT+01:00) Rome',
			'Europe/Stockholm' => '(GMT+01:00) Stockholm',
			'Europe/Vienna' => '(GMT+01:00) Vienna',
			'Europe/Minsk' => '(GMT+02:00) Minsk',
			'Africa/Cairo' => '(GMT+02:00) Cairo',
			'Europe/Helsinki' => '(GMT+02:00) Helsinki',
			'Europe/Riga' => '(GMT+02:00) Riga',
			'Europe/Sofia' => '(GMT+02:00) Sofia',
			'Europe/Tallinn' => '(GMT+02:00) Tallinn',
			'Europe/Vilnius' => '(GMT+02:00) Vilnius',
			'Europe/Athens' => '(GMT+02:00) Athens',
			'Europe/Bucharest' => '(GMT+02:00) Bucharest',
			'Europe/Istanbul' => '(GMT+02:00) Istanbul',
			'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
			'Asia/Amman' => '(GMT+02:00) Amman',
			'Asia/Beirut' => '(GMT+02:00) Beirut',
			'Africa/Windhoek' => '(GMT+02:00) Windhoek',
			'Africa/Harare' => '(GMT+02:00) Harare',
			'Asia/Kuwait' => '(GMT+03:00) Kuwait',
			'Asia/Riyadh' => '(GMT+03:00) Riyadh',
			'Asia/Baghdad' => '(GMT+03:00) Baghdad',
			'Africa/Nairobi' => '(GMT+03:00) Nairobi',
			'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
			'Europe/Moscow' => '(GMT+03:00) Moscow',
			'Europe/Volgograd' => '(GMT+03:00) Volgograd',
			'Asia/Tehran' => '(GMT+03:30) Tehran',
			'Asia/Muscat' => '(GMT+04:00) Muscat',
			'Asia/Baku' => '(GMT+04:00) Baku',
			'Asia/Yerevan' => '(GMT+04:00) Yerevan',
			'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
			'Asia/Karachi' => '(GMT+05:00) Karachi',
			'Asia/Tashkent' => '(GMT+05:00) Tashkent',
			'Asia/Calcutta' => '(GMT+05:30) Calcutta',
			'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
			'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
			'Asia/Dhaka' => '(GMT+06:00) Dhaka',
			'Asia/Almaty' => '(GMT+06:00) Almaty',
			'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
			'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
			'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
			'Asia/Bangkok' => '(GMT+07:00) Bangkok',
			'Asia/Jakarta' => '(GMT+07:00) Jakarta',
			'Asia/Brunei' => '(GMT+08:00) Beijing',
			'Asia/Chongqing' => '(GMT+08:00) Chongqing',
			'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
			'Asia/Urumqi' => '(GMT+08:00) Urumqi',
			'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
			'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
			'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
			'Asia/Singapore' => '(GMT+08:00) Singapore',
			'Asia/Taipei' => '(GMT+08:00) Taipei',
			'Australia/Perth' => '(GMT+08:00) Perth',
			'Asia/Seoul' => '(GMT+09:00) Seoul',
			'Asia/Tokyo' => '(GMT+09:00) Tokyo',
			'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
			'Australia/Darwin' => '(GMT+09:30) Darwin',
			'Australia/Adelaide' => '(GMT+09:30) Adelaide',
			'Australia/Canberra' => '(GMT+10:00) Canberra',
			'Australia/Melbourne' => '(GMT+10:00) Melbourne',
			'Australia/Sydney' => '(GMT+10:00) Sydney',
			'Australia/Brisbane' => '(GMT+10:00) Brisbane',
			'Australia/Hobart' => '(GMT+10:00) Hobart',
			'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
			'Pacific/Guam' => '(GMT+10:00) Guam',
			'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
			'Asia/Magadan' => '(GMT+11:00) Magadan',
			'Pacific/Fiji' => '(GMT+12:00) Fiji',
			'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
			'Pacific/Auckland' => '(GMT+12:00) Auckland',
			'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa');
	
	public static $bookingExpirationTimes = array('120' => '2 minutes',
			'300' => '5 minutes',
			'600' => '10 minutes',
			'1200' => '20 minutes',
			'1800' => '30 minutes');
	
	public static $staticErrors = array();
	public $errors = array();
	
	public function __construct($fetchFromDb = true)
	{
		if ($fetchFromDb)
		{
			$selectQuery = mysql_query ("SELECT IFNULL(conf_key, false) AS conf_key, IFNULL(conf_value,false) AS conf_value FROM bsi_configure");
			if (!$selectQuery)
			{
				die ("Error: " . mysql_errno() . ". Message: " . mysql_error());
			}
			
			while ($currentRow = mysql_fetch_assoc($selectQuery))
			{
				if ($currentRow["conf_key"])
				{
					if ($currentRow["conf_value"])
					{
						$this->config[trim ($currentRow["conf_key"])] = trim($currentRow["conf_value"]);
					}
					else
					{
						$this->config[trim($currentRow["conf_key"])] = false;
					}
				}
			}
			mysql_free_result($selectQuery);
			
			date_default_timezone_set($this->config[SystemConfiguration::$timeZoneKey]);
		}
	}
	
	public function getHotelDetails()
	{
		$hotelDetails = HotelDetails::fetchFromParameters($this->config);
		return $hotelDetails;
	}
	
	public static function fetchFromParameters($params)
	{		
		$configObject = new SystemConfiguration(false);		
		if (isset($_POST[SystemConfiguration::$hotelSiteKey]))
		{
			$configObject->config[SystemConfiguration::$hotelSiteKey] = trim($_POST[SystemConfiguration::$hotelSiteKey]);
		}
		if (isset($_POST[SystemConfiguration::$hotelLogoTitleKey]))
		{
			$configObject->config[SystemConfiguration::$hotelLogoTitleKey] = trim($_POST[SystemConfiguration::$hotelLogoTitleKey]);
		}
		if (isset($_POST[SystemConfiguration::$hotelSiteDescriptionKey]))
		{
			$configObject->config[SystemConfiguration::$hotelSiteDescriptionKey] = trim($_POST[SystemConfiguration::$hotelSiteDescriptionKey]);
		}
		if (isset($_POST[SystemConfiguration::$hotelSiteKeywordsKey]))
		{
			$configObject->config[SystemConfiguration::$hotelSiteKeywordsKey] = trim($_POST[SystemConfiguration::$hotelSiteKeywordsKey]);
		}
		if (isset($_POST[SystemConfiguration::$minimumNightCountKey]))
		{
			$configObject->config[SystemConfiguration::$minimumNightCountKey] = trim($_POST[SystemConfiguration::$minimumNightCountKey]);
		}
		if (isset($_POST[SystemConfiguration::$disableSearchEngineKey]))
		{
			$configObject->config[SystemConfiguration::$disableSearchEngineKey] = trim($_POST[SystemConfiguration::$disableSearchEngineKey]);
		}
		else
		{
			$configObject->config[SystemConfiguration::$disableSearchEngineKey] = 0;
		}
		if (isset($_POST[SystemConfiguration::$timeZoneKey]))
		{
			$configObject->config[SystemConfiguration::$timeZoneKey] = trim($_POST[SystemConfiguration::$timeZoneKey]);
		}
		if (isset($_POST[SystemConfiguration::$bookingExpirationKey]))
		{
			$configObject->config[SystemConfiguration::$bookingExpirationKey] = trim($_POST[SystemConfiguration::$bookingExpirationKey]);
		}
		if (isset($_POST[SystemConfiguration::$monthlyDiscountSchemeKey]))
		{
			$configObject->config[SystemConfiguration::$monthlyDiscountSchemeKey] = trim($_POST[SystemConfiguration::$monthlyDiscountSchemeKey]);
		}
		else
		{
			$configObject->config[SystemConfiguration::$monthlyDiscountSchemeKey] = 0;
		}
		if (isset($_POST[SystemConfiguration::$monthlyDepositSchemeKey]))
		{
			$configObject->config[SystemConfiguration::$monthlyDepositSchemeKey] = trim($_POST[SystemConfiguration::$monthlyDepositSchemeKey]);
		}
		else
		{
			$configObject->config[SystemConfiguration::$monthlyDepositSchemeKey] = 0;
		}
		if (isset($_POST[SystemConfiguration::$taxRateKey]))
		{
			$configObject->config[SystemConfiguration::$taxRateKey] = trim($_POST[SystemConfiguration::$taxRateKey]);
		}
		else
		{
			$configObject->config[SystemConfiguration::$taxRateKey] = 0;
		}
		if (isset($_POST[SystemConfiguration::$currencySymbolKey]))
		{
			$configObject->config[SystemConfiguration::$currencySymbolKey] = trim($_POST[SystemConfiguration::$currencySymbolKey]);
		}
		if (isset($_POST[SystemConfiguration::$currencyCodeKey]))
		{
			$configObject->config[SystemConfiguration::$currencyCodeKey] = strtoupper(trim($_POST[SystemConfiguration::$currencyCodeKey]));
		}
		if (isset($_POST[SystemConfiguration::$currencyBeforeAmountKey]))
		{
			$configObject->config[SystemConfiguration::$currencyBeforeAmountKey] = trim($_POST[SystemConfiguration::$currencyBeforeAmountKey]);
		}
		else
		{
			$configObject->config[SystemConfiguration::$currencyBeforeAmountKey] = 0;
		}
		if (isset($_POST[SystemConfiguration::$decimalSeparatorSymbolKey]))
		{
			$configObject->config[SystemConfiguration::$decimalSeparatorSymbolKey] = trim($_POST[SystemConfiguration::$decimalSeparatorSymbolKey]);
		}
		if (isset($_POST[SystemConfiguration::$thousandSeparatorSymbolKey]))
		{
			$configObject->config[SystemConfiguration::$thousandSeparatorSymbolKey] = trim($_POST[SystemConfiguration::$thousandSeparatorSymbolKey]);
		}
		if (isset($_POST[SystemConfiguration::$adminItemsPerPageKey]))
		{
			$configObject->config[SystemConfiguration::$adminItemsPerPageKey] = trim($_POST[SystemConfiguration::$adminItemsPerPageKey]);
		}
		if (isset($_POST[SystemConfiguration::$newsItemsPerPageKey]))
		{
			$configObject->config[SystemConfiguration::$newsItemsPerPageKey] = trim($_POST[SystemConfiguration::$newsItemsPerPageKey]);
		}		
		return $configObject;
	}
	
	public function isValid()
	{
		$this->errors = array();
		if ($this->getBookingExpirationTime() <= 0)
		{
			$this->errors[] = "Invalid booking expiration time";
		}
		if (strlen($this->getCurrencyCode()) !=  3)
		{
			$this->errors[] = "Currency code must be exactly 3 characters";
		}
		if (strlen($this->getCurrencySymbol()) == 0)
		{
			$this->errors[] = "Currency symbol must be specified";
		}
		if (strlen($this->getDecimalPoint()) != 1)
		{
			$this->errors[] = "Decimal point symbol must be exactly one character";
		}		
		if (strlen($this->getMinimumNightCount()) == 0)
		{
			$this->errors[] = "Minimum booking must be specified";
		}
		else if (!is_numeric($this->getMinimumNightCount()))
		{
			$this->errors[] = "Minimum booking is not a valid number";
		}
		if (strlen($this->getSiteDescription()) == 0)
		{
			$this->errors[] = "Site description must be specified";
		}
		if (strlen($this->getSiteKeywords()) == 0)
		{
			$this->errors[] = "Site keywords must be specified";
		}
		if (strlen($this->getSiteTitle()) == 0)
		{
			$this->errors[] = "Site title must be specified";
		}
		if (strlen($this->getTaxRate()) == 0)
		{
			$this->config[SystemConfiguration::$taxRateKey] = 0;			
		}
		else if ($this->getTaxRate() < 0 || $this->getTaxRate() > 100)
		{
			$this->errors[] = "Tax rate must be between 0 and 100";
		}
		if ($this->getTimeZone() == null || strlen($this->getTimeZone()->getName()) == 0)
		{
			$this->errors[] = "Time zone must be selected";
		}
		if ($this->getNewsItemsPerPage() < 2)
		{
			$this->errors[] = "At least 2 news per page are required";
		}
		if ($this->getAdminItemsPerPage() < 1)
		{
			$this->errors[] = "At least 1 item per page is required for admin items";
		}
		return sizeof($this->errors) == 0;		
	}
	
	public function assertReferer($pageName = null)
	{		
		global $logger;
		if(!isset($_SERVER['HTTP_REFERER']))
		{
			$logger->LogError('HTTP_REFERER is not set!');
			header('Location: booking-failure.php?error_code=9'); 
		}
		
		$referringAddress = $_SERVER['HTTP_REFERER'];
		$logger->LogDebug("Referring address is: $referringAddress");
		$pos = strpos($referringAddress, "?");
		if ($pos !== false)
		{
			$referringAddress = substr($referringAddress, 0, $pos);
			$logger->LogDebug("Referring address is: $referringAddress");
		}
			
		$address = $this->getSiteAddress();
		$logger->LogDebug("Site address is: $address");
		if ($address != null)
		{
			$address = $address . $pageName;
			$logger->LogDebug("Full site address is: $address");
			$match = strpos($referringAddress, $address);
			if ($match === false)
			{
				$logger->LogWarn("No match in referers!");
				$logger->LogWarn("Referer: $referringAddress");
				$logger->LogWarn("Address: $address");
				header('Location: booking-failure.php?error_code=9'); 
			}
		}
		else 
		{
			$logger->LogError('Site address is null!');
			header('Location: booking-failure.php?error_code=9');			
		}
	}
	
	public function getDateFormat()
	{
		return $this->config[SystemConfiguration::$dateFormatKey]; 
	}	
	
	public function getMinimumNightCount()
	{
		$nightCount = intval($this->config[SystemConfiguration::$minimumNightCountKey]); 
		return ($nightCount > 0 ? $nightCount : 1); 
	}
	
	public function isSearchEgineEnabled()
	{
		return intval($this->config[SystemConfiguration::$disableSearchEngineKey]) == 0; 
	}
	
	public function getTimeZone()
	{
		$tz = new DateTimeZone($this->config[SystemConfiguration::$timeZoneKey]);		
		return $tz;				 
	}

    public function getSiteAddress()
    {
        $hostInfo = pathinfo ($_SERVER["PHP_SELF"]);
		if ($hostInfo['dirname'] == chr (92))
			$url = "http://" . $_SERVER['SERVER_NAME'] . "/";
		else
			$url = "http://" . $_SERVER['SERVER_NAME'] . $hostInfo['dirname'] . "/";
		
		if (Utilities::stringEndsWith($url, "//"))
		{
			$url = Utilities::stringLastReplace("//", "/", $url);
		}
		return $url;	
    }
    
    public function getBookingExpirationTime()
    {
	return intval($this->config[SystemConfiguration::$bookingExpirationKey]);										   
    }

    public function isMonthlyDiscountSchemeEnabled()
    {
        return intval($this->config[SystemConfiguration::$monthlyDiscountSchemeKey]) == 1;
    }
    
	public function setIsMonthlyDiscountSchemeEnabled($value)
    {
        $this->config[SystemConfiguration::$monthlyDiscountSchemeKey] = intval($value) == 1;
    }

    public function isMonthlyDepositSchemeEnabled()
    {
        return intval($this->config[SystemConfiguration::$monthlyDepositSchemeKey]) == 1;
    }
    
	public function setIsMonthlyDepositSchemeEnabled($value)
    {
        $this->config[SystemConfiguration::$monthlyDepositSchemeKey] = intval($value) == 1;
    }

    public function getTaxRate()
    {
        return floatval($this->config[SystemConfiguration::$taxRateKey]);
    }
	
	public function getCurrencySymbol()
    {
        return $this->config[SystemConfiguration::$currencySymbolKey];
    }

	public function getCurrencyCode()
    {
        return $this->config[SystemConfiguration::$currencyCodeKey];
    }
    
	public function istCurrencyBeforeAmount()
    {    	
        return intval($this->config[SystemConfiguration::$currencyBeforeAmountKey]) == 1;
    }    
	
	public function getDecimalPoint()
    {
        return $this->config[SystemConfiguration::$decimalSeparatorSymbolKey];
    }
	
	public function getThousandSeparator()
    {
        return $this->config[SystemConfiguration::$thousandSeparatorSymbolKey];
    }
    	    
	public function getSiteTitle()
    {
        return $this->config[SystemConfiguration::$hotelSiteKey];
    }
    
	public function getLogoTitle()
    {
        return $this->config[SystemConfiguration::$hotelLogoTitleKey];
    }
    
	public function getSiteDescription()
    {
        return $this->config[SystemConfiguration::$hotelSiteDescriptionKey];
    }
    
	public function getSiteKeywords()
    {
        return $this->config[SystemConfiguration::$hotelSiteKeywordsKey];
    }
    
	public function getAdminItemsPerPage()
    {
        return max(intval($this->config[SystemConfiguration::$adminItemsPerPageKey]), 1);
    }
    
	public function getNewsItemsPerPage()
    {
        return intval($this->config[SystemConfiguration::$newsItemsPerPageKey]);
    }
	
	public function formatCurrency($amount)
    {
		$amount = floatval($amount);
		if ($this->istCurrencyBeforeAmount())
		{
			return $this->getCurrencySymbol() . number_format($amount, 2 , $this->getDecimalPoint(), $this->getThousandSeparator());	
		}
		return number_format($amount, 2 , $this->getDecimalPoint(), $this->getThousandSeparator()) . $this->getCurrencySymbol();
		        
    }    
    
    
    public function save($isValidated = false)
    {
    	if (!$isValidated && !$this->isValid())
    	{
    		return false;
    	}    	
    	    	
		$this->runUpdate(SystemConfiguration::$hotelSiteKey, $this->getSiteTitle());
		$this->runUpdate(SystemConfiguration::$hotelLogoTitleKey, $this->getLogoTitle());
		$this->runUpdate(SystemConfiguration::$hotelSiteDescriptionKey, $this->getSiteDescription());
		$this->runUpdate(SystemConfiguration::$hotelSiteKeywordsKey, $this->getSiteKeywords());	
		$this->runUpdate(SystemConfiguration::$minimumNightCountKey, $this->getMinimumNightCount());
		$this->runUpdate(SystemConfiguration::$disableSearchEngineKey, $this->isSearchEgineEnabled() ? "0" : "1");
		$this->runUpdate(SystemConfiguration::$timeZoneKey, $this->getTimeZone()->getName());
		$this->runUpdate(SystemConfiguration::$bookingExpirationKey, $this->getBookingExpirationTime());
		$this->runUpdate(SystemConfiguration::$monthlyDiscountSchemeKey, $this->isMonthlyDiscountSchemeEnabled() ? "1" : "0");
		$this->runUpdate(SystemConfiguration::$monthlyDepositSchemeKey, $this->isMonthlyDepositSchemeEnabled() ? "1" : "0");
		$this->runUpdate(SystemConfiguration::$taxRateKey, $this->getTaxRate());
		$this->runUpdate(SystemConfiguration::$currencySymbolKey, $this->getCurrencySymbol());
		$this->runUpdate(SystemConfiguration::$currencyCodeKey, $this->getCurrencyCode());
		$this->runUpdate(SystemConfiguration::$currencyBeforeAmountKey, $this->istCurrencyBeforeAmount() ? "1" : "0");
		$this->runUpdate(SystemConfiguration::$decimalSeparatorSymbolKey, $this->getDecimalPoint());
		$this->runUpdate(SystemConfiguration::$thousandSeparatorSymbolKey, $this->getThousandSeparator());
		$this->runUpdate(SystemConfiguration::$adminItemsPerPageKey, $this->getAdminItemsPerPage());
		$this->runUpdate(SystemConfiguration::$newsItemsPerPageKey, $this->getNewsItemsPerPage());
		return true;    	
    }
    
    private function runUpdate($configKey, $value)
    {
    	$sql = "UPDATE bsi_configure SET conf_value='" . mysql_escape_string($value) . "' WHERE conf_key='" . $configKey . "'";
    	$query = mysql_query($sql);
    	if (!$query)
    	{    		
    		die("Error: " . mysql_error());
    	}
    }
    
}
global $systemConfiguration; 
$systemConfiguration = new SystemConfiguration();

$loggingDirectory = "./logs/";
$currentDirectory = getcwd();
if (Utilities::stringEndsWith($currentDirectory, "admin"))
{
	$loggingDirectory = "../logs/";
}

global $logger;
$logger = new KLogger($loggingDirectory, KLogger::INFO);
?>