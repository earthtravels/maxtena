<?php
class LocalizedText
{  	
	private $_textArray = array();
	private $_dbFieldPrefix = "";

	public $error = "";
	
	public function getText($languageCode)
	{
		$languageCode = trim(strtolower($languageCode));
		if (isset($this->_textArray[$languageCode]))
		{			
			return $this->_textArray[$languageCode];
		}
		$this->error = "Invalid language code " . $languageCode;
		return null;		
	}
	
	public function getFirstXCharacters($languageCode, $limit = null)
	{
		if ($limit == null || !is_numeric($limit))
		{
			return $this->getText($languageCode);
		}
		else 
		{
			$limit = intval($limit);
			$text = substr($this->getText($languageCode), 0, $limit);
			$position = strrpos($text, " ");
			if ($position === false)
			{
				$position = strlen($text);
			}
			$text = substr($text, 0, $position);
			return $text;			
		}
	}
	
	public function __construct($fieldPrefix)
	{
		$this->_dbFieldPrefix = trim(strtolower($fieldPrefix));		
	}

    public function getMySqlFields($isCommaBefore)
    {
    	$mySqlFields = "";
    	foreach ($this->_textArray as $languageCode => $text) 
    	{
	    	if ($isCommaBefore)
	    	{
	    		$mySqlFields.= ", " . $this->_dbFieldPrefix . $languageCode;	
	    	}    		
	    	else 
	    	{
	    		$mySqlFields.= $this->_dbFieldPrefix . $languageCode . ", ";
	    	}
    	}
    	return $mySqlFields;    	
    }
    
	public function getMySqlValues($isCommaBefore = true)
    {
    	$mySqlValues = "";
    	foreach ($this->_textArray as $languageCode => $text) 
    	{
	    	if ($isCommaBefore)
	    	{
	    		$mySqlValues.= ", '" . mysql_escape_string($text) . "'";	
	    	}    		
	    	else 
	    	{
	    		$mySqlValues.= "'" . mysql_escape_string($text) . "', ";
	    	}
    	}
    	return $mySqlValues;    	
    }
    
	public function getMySqlValuesForSet($isCommaBefore = true)
    {
    	$mySqlValues = "";
    	foreach ($this->_textArray as $languageCode => $text) 
    	{
	    	if ($isCommaBefore)
	    	{
	    		$mySqlValues.= ", ";	    			
	    	}    		
	    	$mySqlValues.= $this->_dbFieldPrefix . $languageCode . " = '" . mysql_escape_string($text) . "'";
	    	if (!$isCommaBefore)
	    	{
	    		$mySqlValues.= ", ";	    		
	    	}
    	}
    	return $mySqlValues;    	
    }
    
    public static function fetchFromParameters($params, $fieldPrefix)
    {
    	$localizedText = new LocalizedText($fieldPrefix);
    	foreach ($params as $key => $value)
        {
            if (preg_match('/' . $fieldPrefix . '[A-Za-z]{2}/', $key))
            {
                $languageCode = substr($key, -2);  
                $localizedText->_textArray[$languageCode] = trim($value);
            }
        }
        return $localizedText;
    } 

    public function areAnyValuesEmpty()
    {
    	foreach ($this->_textArray as $value) 
    	{
    		if (strlen(trim($value)) == 0)
    		{
    			return true;
    		}
    	}
    }
    
	public function areAllValuesEmpty()
    {
    	foreach ($this->_textArray as $value) 
    	{
    		if (strlen(trim($value)) != 0)
    		{
    			return false;
    		}
    	}
    	return true;
    }
}