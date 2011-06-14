<?php

class EmailSender
{	
	private $emailFrom 		= '';
	private $emailReplyTo 	= '';		
	
	public function __construct()
	{
		global $systemConfiguration;
		
		$this->emailFrom = $systemConfiguration->getHotelDetails()->getHotelEmail();
		$this->emailReplyTo = $this->emailFrom;
	}
	
	public function sendEmail($emailTo, $emailSubject, $emailBody)
	{		
		global $logger;
		$emailHeaders = $this->getHeaders();
				
		$logger->LogInfo(__METHOD__ . " Sending email ...");
		$logger->LogInfo("To: " . $emailTo);
		$logger->LogInfo("Subject: " . $emailSubject);
		$logger->LogInfo("Headers: " . $emailHeaders);
		$logger->LogInfo("Body: " . $emailBody);
				
		$returnCode = mail($emailTo, $emailSubject, $emailBody, $emailHeaders);
		if ($returnCode) 
		{			
			return true;
		}
		else 
		{
			$logger->LogError("Error sending email. Return code: " . $returnCode);
			return false;
		}
	}
	/**
	 * Enter description here ...
	 */
	private function getHeaders()
	{
		// To send HTML mail, the Content-type header must be set
		$emailHeaders  = 'MIME-Version: 1.0' . "\r\n";
		$emailHeaders .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
				
		// Additional headers
		$emailHeaders .= 'reply-to: '.$this->emailReplyTo.'' . "\r\n";
		$emailHeaders .= 'From: '.$this->emailFrom.'' . "\r\n";
		return $emailHeaders;
	}
	
	
	public function sendContactEmail($formData)
	{
		// To send HTML mail, the Content-type header must be set
		$emailHeaders = $this->getHeaders();

		$emailBody = "";
		$emailBody.= "<table>";
		foreach ($formData as $key => $value) 
		{
			$emailBody.= "<tr><td>" . htmlentities($key) . "</td><td>" . htmlentities($value) . "</td></tr>";   
		}
		$emailBody.= "</table>";	
		
		$returnCode = mail($this->emailFrom, "Website contact", $emailBody, $emailHeaders);		
		if ($returnCode) 
		{
			return true;
		}
		else 
		{
			$logger->LogError("Error sending email. Return code: " . $returnCode);
			return false;
		}
	}
}
?>