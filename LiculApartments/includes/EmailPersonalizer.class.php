<?php
class EmailPersonalizer
{
	public $errors = array();
	public $booking = null;
	
	public $terms = array(); 
	public $replacements = array();
	
	public function __construct($booking)
	{
		$this->booking = $booking;
		
		$this->terms = array(); 
		$this->replacements = array();
		
		$this->terms[] 		= "##CLIENT_FIRST_NAME##";				
		$this->terms[] 		= "##CLIENT_LAST_NAME##";		
		$this->terms[] 		= "##CLIENT_EMAIL##";
		$this->terms[] 		= "##BOOKING_ID##";
		$this->terms[] 		= "##INVOICE##";		
	}
	
	public function customizeEmailContents($emailContents)
	{	
		$this->errors = array();		
		if ($this->booking == null)
		{
			$this->errors[] = "Booking is NULL";
			return false;
		}
		else if (!($this->booking instanceof Booking))
		{
			$this->errors[] = "Booking variable is not a Booking object";
			return false;
		}
		
		$client = $this->booking->getClient();
		 
		$this->replacements = array();
		$this->replacements[] = $client->firstName;
		$this->replacements[] = $client->lastName;
		$this->replacements[] = $client->email;
		$this->replacements[] = $this->booking->id;
		$this->replacements[] = $this->booking->invoice;
		
		$emailContents = str_ireplace($this->terms, $this->replacements, $emailContents);
		return $emailContents;		
	}
}
?>