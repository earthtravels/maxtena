<?php
class Faq
{  
	public $id = 0;
	public $question = null;	
	public $answer = null;
	public $displayOrder = 0;
	
	private static $questionPrefix = "question_";
	private static $answerPrefix = "answer_";		
	
	public $errors = array();
	public static $staticErrors = array();

	public function __construct()
	{
		$this->question = new LocalizedText(Faq::$questionPrefix);
		$this->answer = new LocalizedText(Faq::$answerPrefix);
	}
	
	public static function fetchFromParameters($params) 
	{
		$faq = new Faq();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$faq->id = intval($params['id']);
		}
		$faq->question = LocalizedText::fetchFromParameters($params, Faq::$questionPrefix);
		$faq->answer = LocalizedText::fetchFromParameters($params, Faq::$answerPrefix);		
		if (isset($params['display_order']) && is_numeric($params['display_order']))
		{
			$faq->displayOrder = intval($params['display_order']);
		} 
		return $faq;
	}
	
	public static function fetchFromDb($id) 
	{
		global $logger;
		Faq::$staticErrors = array();
		if (!is_numeric($id))
		{
			$logger->LogError("Id " . $id . " is not numeric.");
			Faq::$staticErrors[] = "Id " . $id . " is not numeric.";
			return null;
		}
		
		$id = intval($id);
		$sql = "SELECT * FROM bsi_faqs WHERE id = $id";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$faq = Faq::fetchFromParameters($row);
			return $faq;			
		}
		else
		{
			$logger->LogError("No faq with id " . $id . " could be found.");			
			return null;
		}		
	}
	
	public static function fetchAllFromDb() 
	{
		global $logger;
		Faq::$staticErrors = array();		
		
		$faqs = array();
		$sql = "SELECT * FROM bsi_faqs ORDER BY display_order";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$faq = Faq::fetchFromParameters($row);
			$faqs[] = $faq;			
		}				
		return $faqs;
	}	

	public function isValid()
	{
		$this->errors = array();		
		if ($this->question->areAnyValuesEmpty())
		{
			$this->errors[] = "Question cannot be empty";
		}
		if ($this->answer->areAnyValuesEmpty())
		{
			$this->errors[] = "Answer cannot be empty";
		}
		return sizeof($this->errors) == 0;
	}
	
	public function save($isValidated = false)
	{
		global $logger;
		if (!$isValidated && !$this->isValid())
		{
			return false;
		}
		
		if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_faqs (";
			$sql.= $this->question->getMySqlFields(false);
			$sql.= $this->answer->getMySqlFields(false);
			$sql.= "display_order) VALUES (";
			$sql.= $this->question->getMySqlValues(false);
			$sql.= $this->answer->getMySqlValues(false);
			$sql.= $this->displayOrder;
			$sql.= ")";		  			  	
			if (!mysql_query($sql))
			{				
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			} 
			$this->id = mysql_insert_id();
		}
		else 
		{
			// Run UPDATE
			$sql = "UPDATE bsi_faqs SET ";
			$sql.= $this->question->getMySqlValuesForSet(false);
			$sql.= $this->answer->getMySqlValuesForSet(false);
			$sql.= "display_order = " . $this->displayOrder;						
		  	$sql.= " WHERE id = " . $this->id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			}							
		}	
		return true;	
	}
	
	public static function delete($id)	
	{
		global $logger;
		Faq::$staticErrors = array();
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_faqs WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			}
			return true;
		}
		else
		{
			Faq::$staticErrors[] = "Id is not numeric";
			return false;
		}
	}
}
?>