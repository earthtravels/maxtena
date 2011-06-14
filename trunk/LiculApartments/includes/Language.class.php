<?php
//include_once ("SystemConfiguration.class.php");

class Language
{ 
	public $id = 0;
	public $languageName = "";
	public $languageCode = "";
	public $fileName = "";
	public $isActive = false;
	public $isDefault = false;
	public $displayOrder = 0;
	
	public $errors = array();
	public static $staticErrors = array();
	
	public function __construct()
	{
	}
	
	public static function fetchFromParameters($params)
	{
		$language = new Language();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$language->id = intval($params['id']);
		}
		if (isset($params['language_name']))
		{
			$language->languageName = $params['language_name'];
		}
		if (isset($params['language_code']))
		{
			$language->languageCode = $params['language_code'];
		}
		if (isset($params['language_file_name']))
		{
			$language->fileName = $params['language_file_name'];
		}
		if (isset($params['is_active']))
		{
			$language->isActive = intval($params['is_active']) == 1;
		}
		if (isset($params['is_default']))
		{
			$language->isDefault = intval($params['is_default']) == 1;
		}
		if (isset($params['display_order']))
		{
			$language->displayOrder = intval($params['display_order']);
		}
		return $language;
	}
	
	public static function fetchAllFromDb()
	{
		$languages = array();
		$sql = "SELECT * FROM bsi_language ORDER BY display_order ASC, is_default DESC";
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$language = Language::fetchFromParameters($row);
			$languages[] = $language;
		}
		mysql_free_result($query);
		return $languages;
	}
	
	public static function fetchAllFromDbActive()
	{
		$languages = array();
		$sql = "SELECT * FROM bsi_language WHERE is_active = 1 ORDER BY display_order ASC, is_default DESC";
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$language = Language::fetchFromParameters($row);
			$languages[] = $language;
		}
		mysql_free_result($query);
		return $languages;
	}
	
	public static function fetchDefaultLangauge()
	{		
		$language = null;
		$sql = "SELECT * FROM bsi_language WHERE is_default = 1";
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$language = Language::fetchFromParameters($row);						
		}
		mysql_free_result($query);
		return $language;
	}
	
	public static function fetchForCodeOrDefault($languageCode)
	{		
		$sql = "SELECT * FROM bsi_language WHERE language_code = '" . mysql_escape_string(strtolower(trim($languageCode))) . "'";
		$query = mysql_query($sql);
		if (! $query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$language = Language::fetchFromParameters($row);
			return $language;
		}
		mysql_free_result($query);		
		
		return Language::fetchDefaultLangauge();		
	}
			
	public function isValid()
	{
		$this->errors = array();
		if (strlen(trim($this->id)) == 0)
		{
			$this->setError("Language id cannot be empty");
		}		
		if (strlen(trim($this->languageName)) == 0)
		{
			$this->setError("Language name cannot be empty");
		}
		if (strlen(trim($this->languageCode)) == 0)
		{
			$this->setError("Language code cannot be empty");
		}
		if (! is_int($this->displayOrder))
		{
			$this->setError("Display order is invalid");
		}		
		if (sizeof($this->errors) == 0)
		{
			$sql = "SELECT * FROM bsi_language WHERE language_code = '" . mysql_escape_string($this->languageCode) . "' AND id != " . $this->id;
			$query = mysql_query($sql);
			if (! $query)
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			}
			if ($row = mysql_fetch_assoc($query))
			{
				$this->setError("Language with code " . $this->languageCode . " already exists");						
			}
			mysql_free_result($query);
		}
		return sizeof($this->errors) == 0;
	}
	
	public function save()
	{
		$this->errors = array();
		if (! $this->isValid())
		{
			return false;
		}		
		
		$this->languageCode = strtolower(trim($this->languageCode));
		if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_language (language_name, language_code, language_file_name, is_active, is_default, display_order) VALUES (";
			$sql.= "'" . mysql_escape_string($this->languageName) . "', ";
			$sql.= "'" . mysql_escape_string($this->languageCode) . "', ";
			$sql.= "'" . mysql_escape_string($this->fileName) . "', ";
			$sql.= ($this->isActive ? "1" : "0") . ", ";
			$sql.= ($this->isDefault ? "1" : "0") . ", ";
			$sql.= $this->displayOrder;
			$sql.= ")";
			$query = mysql_query($sql);
			if (! $query)
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}
			$this->id = mysql_insert_id();			
		}
		else
		{
			// Run UPDATE
			$sql = "UPDATE bsi_language SET ";
			$sql.= "language_name = '" . mysql_escape_string($this->languageName) . "', ";
			$sql.= "language_code = '" . mysql_escape_string($this->languageCode) . "', ";
			$sql.= "language_file_name = '" . mysql_escape_string($this->fileName) . "', ";
			$sql.= "is_active = " . ($this->isActive ? "1" : "0") . ", ";
			$sql.= "is_default = " . ($this->isDefault ? "1" : "0") . ", ";
			$sql.= "display_order = " . $this->displayOrder;
			$sql.= " WHERE id = " . $this->id;
			$query = mysql_query($sql);
			if (! $query)
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}			
		}
		return true;
	}
	
	public static function delete($id)
	{
		$this->errors = array();
		$rowsAffected = 0;
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_language WHERE id = " . $id;
			$query = mysql_query($sql);
			if (!$query)
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}
			$rowsAffected = mysql_affected_rows($query);
		}
		return $rowsAffected;
	}
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}