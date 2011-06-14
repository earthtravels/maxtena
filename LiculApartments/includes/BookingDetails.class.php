<?php
//require_once ("SystemConfiguration.class.php");

class BookingDetails
{  		
	public static $log = null;
    public $searchCriteria = null;
	public $room = null;
	public $client = null;
	// 0 - ExtraService; 1 - quantity; 2 - total price
	public $extraServices = array();
	public $discountDeposit = null;
    public $promoCode = null;
    public $priceDetails = null;
	public $extraBedRequested = false;
	public $booking = null;
	public $paymentGateway = null;
	
	public $errors = array();	
	
	public function __construct()
	{					 
	}
	
	public static function clearExpiredBookings()
	{
		global $systemConfiguration;
		global $logger;
		
		$logger->LogDebug("Clearing expired bookings ...");
		$currentDate = new Date();
		$expiredBookingsSql = "SELECT booking_id FROM bsi_bookings WHERE payment_success = false AND booking_time <= (STR_TO_DATE('" . $currentDate->formatMySql() . "', '%Y-%m-%d')  - INTERVAL " . $systemConfiguration->getBookingExpirationTime() . " SECOND)";	

			
		// Delete expired bookings
		$expiredBookingsSqlQuery = mysql_query($expiredBookingsSql);
		if (!$expiredBookingsSqlQuery)
		{
			die('Error: ' . mysql_error());
		}
		while ($row = mysql_fetch_assoc($expiredBookingsSqlQuery))
		{			
			if (!mysql_query ("DELETE FROM bsi_bookings WHERE booking_id  = " . $row['booking_id']));
			{
				die('Error: ' . mysql_error());
			}			
		}
		mysql_free_result($expiredBookingsSqlQuery);				
	}
	
	public function isBookingStillAvailable()
	{
		$searchEngine = new SearchEngine($this->searchCriteria);
		$availableRooms = $searchEngine->runSearchForRoom($this->room->id);
		if ($availableRooms == null)
		{
			$this->errors = $searchEngine->errors;
			return false;
		}
		$roomCount = sizeof($availableRooms);
		return $roomCount > 0;
	}
	

	public function fetchExtraServices($params)
    {
        $this->extraServices = array();
		if (isset($params['extraServices']))
        {
        	$extraServices = $params['extraServices'];
			foreach($extraServices as $serviceId => $quantity)
            {
				if ($quantity > 0)
                {
					$extraService = ExtraService::fetchFromDb($serviceId);
					if ($extraService != null)
                    {
						$extraServiceDetails = array();
                        $extraServiceDetails[0] = $extraService;
                        $extraServiceDetails[1] = $quantity;
						$extraServiceDetails[2] = floatval($extraService) * intval($quantity);
						$this->extraServices[] = $extraServiceDetails;
                    }
                }
            }
        }
    }
		
	public static function fetchFromParameters($params) 
	{
	    $searchCriteria = new BookingDetails();
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
	

    public function calculatePriceDetails($languageCode)
    {
        $this->errors = array();
        $priceDetails = new PriceDetails();

        global $systemConfiguration;

        // Get room and extra bed prices
        $roomPriceDetails = $this->room->getRoomAndBedPrice($this->searchCriteria->checkInDate, $this->searchCriteria->checkOutDate);
        if ($roomPriceDetails == null)
        {
            array_push($this->errors, $this->room->errors); 
            return false;
        }
        else if (sizeof($roomPriceDetails) != 2)
        {
            $this->setError("Invalid number of array elements returned: " . sizeof($roomPriceDetails));
            return false;
        }
        $priceDetails->roomPrice = floatval($roomPriceDetails[0]);
        $priceDetails->subtotalBeforeDiscounts = $priceDetails->roomPrice;
        if ($this->extraBedRequested)
        {
            $priceDetails->extraBedPrice = floatval($roomPriceDetails[1]);
            $priceDetails->subtotalBeforeDiscounts += $priceDetails->extraBedPrice;
        }

        // Add extra services
        foreach ($this->extraServices as $extraServiceDetails)
        {
            if (!($extraServiceDetails[0] instanceof ExtraService))
            {
                continue;
            }
			$extraService=$extraServiceDetails[0];
			$quantity=intval($extraServiceDetails[1]);
            $extraServicePrice = floatval($extraServiceDetails[2]);
            //$priceDetails->extraServicesPrice[] = array( 0 => $extraService->getName($languageCode), 1 => $quantity, 2 => $extraServicePrice);
            $priceDetails->subtotalBeforeDiscounts += $extraServicePrice;
        }
		$priceDetails->extraServicesDetail = $this->extraServices;

        // Initialize subototal after discounts
        $priceDetails->subtotalAfterDiscounts = $priceDetails->subtotalBeforeDiscounts;

        // Get monthly discount/deposit details
        $monthlyDiscountDeposit = null;
        if ($systemConfiguration->isMonthlyDiscountSchemeEnabled() || $systemConfiguration->isMonthlyDepositSchemeEnabled())
        {
            $monthlyDiscountDeposit = MonthlyDiscountDeposit::fetchFromDbForDate($this->searchCriteria->checkInDate);
            if ($monthlyDiscountDeposit == null)
            {
                array_push($this->errors, MonthlyDiscountDeposit::$staticErrors); 
                return false;
            }
        }

        // Apply monthly discounts
        if ($systemConfiguration->isMonthlyDiscountSchemeEnabled() && $monthlyDiscountDeposit->discountPercent > 0)
        {
            $priceDetails->monthlyDiscountPercent = $monthlyDiscountDeposit->discountPercent;
            $priceDetails->monthlyDiscount = round(min((floatval($monthlyDiscountDeposit->discountPercent * $priceDetails->subtotalAfterDiscounts / 100)), $priceDetails->subtotalAfterDiscounts), 2);
            $priceDetails->subtotalAfterDiscounts -= $priceDetails->monthlyDiscount;
        }

        // Apply promo discounts
        if ($this->promoCode != null)
        {
            $priceDetails->promoCode = $this->promoCode->promoCode;
            $priceDetails->promoDiscount = $this->promoCode->getDiscount($priceDetails->subtotalAfterDiscounts);
            $priceDetails->subtotalAfterDiscounts -= $priceDetails->promoDiscount;
        }

        // Initialize grand total
        $priceDetails->grandTotal = $priceDetails->subtotalAfterDiscounts;

        // Apply tax
        if ($systemConfiguration->getTaxRate() > 0)
        {
            $priceDetails->taxRate = $systemConfiguration->getTaxRate();
            $priceDetails->taxAmount = round($priceDetails->subtotalAfterDiscounts * $priceDetails->taxRate / 100, 2);
            $priceDetails->grandTotal = $priceDetails->subtotalAfterDiscounts + $priceDetails->taxAmount;
        }

        // Apply monthly deposit
        $priceDetails->totalDue = $priceDetails->grandTotal;
        if ($systemConfiguration->isMonthlyDepositSchemeEnabled() && $monthlyDiscountDeposit->depositPercent > 0)
        {
            $priceDetails->monthlyDepositPercent = $monthlyDiscountDeposit->depositPercent;
            $priceDetails->totalDue = round(floatval($monthlyDiscountDeposit->depositPercent * $priceDetails->grandTotal / 100), 2);
        }
        $this->priceDetails = $priceDetails;   
    }
	
	public function getNightCount()
	{
		return $this->checkOutDate->getInterval($this->checkInDate, "days");
	}	
	
		
	public function isValid()
	{		
		global $systemConfiguration;
		$this->errors = array();
		
		if ($this->client == null)
		{
			$this->setError("Client info is invalid");			
		}		
		else if (!$this->client->isValid())
		{
			$this->errors = $this->client->errors;			
		}
		
		if ($this->room == null)
		{
			$this->setError("Room is invalid");			
		}
		else if (!$this->room->isValid())
		{
			$this->errors = $this->room->errors;			
		}
		
		if ($this->searchCriteria == null)
		{
			$this->setError("Search criteria is invalid.");			
		}
		else if (!$this->searchCriteria->isValid())
		{
			$this->errors = $this->searchCriteria->errors;		
		}

		if ($this->priceDetails == null)
		{
			$this->setError("Price was not calculated.");		
		}
		return sizeof($this->errors) == 0;					
	}	
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
	
	public function generateBooking($languageCode)
	{
		$booking = new Booking();
		$booking->adultCount = $this->searchCriteria->adultsCount;
		$booking->childCount = $this->searchCriteria->childrenCount;
		$booking->clientId = $this->client->id;
		$booking->endDate = $this->searchCriteria->checkOutDate;
		$booking->paymentAmount = $this->priceDetails->totalDue;
		$booking->promoCode = ($this->promoCode == null ? "" : $this->promoCode->promoCode);
		$booking->roomId = $this->room->id;
		$booking->startDate = $this->searchCriteria->checkInDate;
		$booking->totalCost = $this->priceDetails->grandTotal;
		$booking->paymentGatewayId = $this->paymentGateway->id;
		$booking->languageCode = $languageCode;
		$this->booking = $booking;		
		return true;
	}
	
	public function saveBooking($languageCode)
	{
		global $logger;
		$logger->LogInfo("Generating booking ...");
		$this->generateBooking($languageCode);
		$logger->LogInfo("Saving booking ...");
		if (!$this->booking->save())
		{
			$logger->LogError("Error saving booking ...");
			$this->errors = $booking->errors;
			return false;
		}		
		return true;
	}
	
	public function generateInvoice($languageCode)
	{
		global $systemConfiguration;
		global $logger;
		$logger->LogInfo("Generating invoice ...");
		if ($this->booking == null)
		{
			$logger->LogError("Booking is not yet saved so invoice cannot be generated!");
			$this->setError("Booking is not yet saved");
			return false;
		}	
		$booking = $this->booking;
		$client = $booking->getClient();
		
		$invoiceHtml = "";
		$invoiceHtml ='<table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1">' . "\n";		
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="left" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="3">' . "\n";
		$invoiceHtml.='				' . INVOICE_BOOKING_DETAILS . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;">' ."\n";
		$invoiceHtml.='				' . INVOICE_BOOKING_NUMBER . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;" colspan="2">' . "\n";
		$invoiceHtml.='				' . $booking->id . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' ."\n";
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . INVOICE_GUEST_NAME . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;" colspan="2">' . "\n";
		$invoiceHtml.='				' . $this->client->firstName . ' ' . $this->client->lastName . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		
		$invoiceHtml.='		<tr height="8px;">' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;" colspan="3">' . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" width="33%">' . "\n";
		$invoiceHtml.='				' . INVOICE_CHECK_IN_DATE . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" width="33%">' . "\n";
		$invoiceHtml.='				' . INVOICE_CHECK_OUT_DATE . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" width="33%">' . "\n";
		$invoiceHtml.='				' .  INVOICE_TOTAL_NIGHTS . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="center" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . $this->searchCriteria->checkInDate->format("m/d/Y") . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . $this->searchCriteria->checkOutDate->format("m/d/Y") . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . $this->searchCriteria->getNightCount() . "\n";
		$invoiceHtml.='			</td>' . "\n";

		$invoiceHtml.='		<tr height="8px;">' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;" colspan="3">' . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
		$invoiceHtml.='				' . INVOICE_ROOM_NUMBER . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
		$invoiceHtml.='				' . INVOICE_ROOM_NAME . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
		$invoiceHtml.='				' . INVOICE_TOTAL_GUESTS . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="center" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . $this->room->roomNumber . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . $this->room->roomName . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="center" style="background:#ffffff;">' . "\n";
		$invoiceHtml.='				' . $this->searchCriteria->adultsCount . ' ' . ($this->searchCriteria->adultsCount > 1 ? INVOICE_ADULTS : INVOICE_ADULT);
		if ($this->searchCriteria->childrenCount > 0)
		{
			$invoiceHtml.=' + ' . $this->searchCriteria->childrenCount . ' ' . ($this->searchCriteria->childrenCount > 1 ? INVOICE_CHILDREN : INVOICE_CHILD) . "\n";
		}
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";

		$invoiceHtml.='		<tr height="8px;">' . "\n";
		$invoiceHtml.='			<td align="left" style="background:#ffffff;" colspan="3">' . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";

		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="left" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="3">' . "\n";
		$invoiceHtml.='				' . BOOKING_DETAILS_PRICE_DETAILS . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
			
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="right" style="background: #ffffff;" colspan="2">' . "\n";
		$invoiceHtml.='				' . BOOKING_DETAILS_ACCOMMODATION_TOTAL . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="right" style="background: #ffffff;" colspan="2">' . "\n";
		$invoiceHtml.='				' . $systemConfiguration->formatCurrency($this->priceDetails->roomPrice) . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		if ($this->extraBedRequested || sizeof($this->extraServices) > 0)
		{			
			if ($this->extraBedRequested)
			{
				$invoiceHtml.= '		<tr>' . "\n";
				$invoiceHtml.= '			<td colspan="2" align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				' . INVOICE_EXTRA_BED . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '			<td align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				' .  $systemConfiguration->formatCurrency($this->priceDetails->extraBedPrice) . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '		</tr>';		
			}
			
			
			foreach($this->priceDetails->extraServicesDetail as $extraServiceDetails)
			{
				$extraService = $extraServiceDetails[0];
				$quantity = $extraServiceDetails[1];
				$totalPrice = $extraServiceDetails[2];
				$invoiceHtml.= '		<tr>' . "\n";
				$invoiceHtml.= '			<td colspan="2" align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				' . $extraService->getName($languageCode) . ' (' . $quantity . ' x ' . $systemConfiguration->formatCurrency($extraService->price) . ')' . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '			<td align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				' . $systemConfiguration->formatCurrency($totalPrice) . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '		</tr>';	
			}				
		}
		
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="2">' . "\n";
		$invoiceHtml.='				' . BOOKING_DETAILS_SUBTOTAL . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
		$invoiceHtml.='				' . $systemConfiguration->formatCurrency($this->priceDetails->subtotalBeforeDiscounts) . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		
		if ($this->priceDetails->monthlyDiscount > 0 || $this->priceDetails->promoDiscount > 0)
		{
			if ($this->priceDetails->monthlyDiscount > 0)
			{
				$invoiceHtml.= '		<tr>' . "\n";
				$invoiceHtml.= '			<td colspan="2" align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				' . BOOKING_DETAILS_DISCOUNT . ' (' . $this->priceDetails->monthlyDiscountPercent . ' %)' . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '			<td align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				(- ' .  $systemConfiguration->formatCurrency($this->priceDetails->monthlyDiscount) . ')' . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '		</tr>';
			}
			
			if ($this->priceDetails->promoDiscount > 0)
			{			
				$invoiceHtml.= '		<tr>' . "\n";
				$invoiceHtml.= '			<td colspan="2" align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				' . BOOKING_DETAILS_CODE . ' (' . $this->priceDetails->promoCode . ')' . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '			<td align="right" style="background:#ffffff;">' . "\n";
				$invoiceHtml.= '				(- ' .  $systemConfiguration->formatCurrency($this->priceDetails->promoDiscount) . ')' . "\n";
				$invoiceHtml.= '			</td>' . "\n";
				$invoiceHtml.= '		</tr>';
	                                
			}
			$invoiceHtml.='		<tr>' . "\n";
			$invoiceHtml.='			<td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="2">' . "\n";
			$invoiceHtml.='				' . BOOKING_DETAILS_SUBTOTAL . "\n";
			$invoiceHtml.='			</td>' . "\n";
			$invoiceHtml.='			<td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
			$invoiceHtml.='				' . $systemConfiguration->formatCurrency($this->priceDetails->subtotalAfterDiscounts) . "\n";
			$invoiceHtml.='			</td>' . "\n";
			$invoiceHtml.='		</tr>' . "\n";
			
		}
		
		if ($this->priceDetails->taxAmount > 0)
		{
			$invoiceHtml.= '		<tr>' . "\n";
			$invoiceHtml.= '			<td colspan="2" align="right" style="background:#ffffff;">' . "\n";
			$invoiceHtml.= '				' . BOOKING_DETAILS_TAX . ' (' . $this->priceDetails->taxRate . '%)' . "\n";
			$invoiceHtml.= '			</td>' . "\n";
			$invoiceHtml.= '			<td align="right" style="background:#ffffff;">' . "\n";
			$invoiceHtml.= '				' .  $systemConfiguration->formatCurrency($this->priceDetails->taxAmount) . "\n";
			$invoiceHtml.= '			</td>' . "\n";
			$invoiceHtml.= '		</tr>';			
		}
		
		$invoiceHtml.='		<tr>' . "\n";
		$invoiceHtml.='			<td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="2">' . "\n";
		$invoiceHtml.='				' . BOOKING_DETAILS_GRAND_TOTAL . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='			<td align="right" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;">' . "\n";
		$invoiceHtml.='				' . $systemConfiguration->formatCurrency($this->priceDetails->grandTotal) . "\n";
		$invoiceHtml.='			</td>' . "\n";
		$invoiceHtml.='		</tr>' . "\n";
		$invoiceHtml.='	</table>' . "\n";
		return $invoiceHtml;
	}
	
	public function saveInvoice($languageCode)
	{		
		global $systemConfiguration;
		if ($this->booking == null)
		{
			$this->setError("Booking is not yet saved");
			return false;
		}	
		$booking = $this->booking;
		$client = $booking->getClient();		
			
		$booking->invoice = $this->generateInvoice($languageCode);
		if (!$booking->save())
		{
			die ("Error: " . $booking->errors[0]);
		}		
		return true;
	}
}