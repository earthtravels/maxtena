<?php
class LocalizedCalendar
{
	private static $months = array(
		"en" => array(	1 => "January",
						2 => "February",
						3 => "March",
						4 => "April",
						5 => "May",
						6 => "June",
						7 => "July",
						8 => "August",
						9 => "September",
						10 => "October",
						11 => "November",
						12 => "December"										
					),
		"hr" => array(	1 => "Sije&#269;anj",
						2 => "Velja&#269;a",
						3 => "O&#382;ujak",
						4 => "Travanj",
						5 => "Svibanj",
						6 => "Lipanj",
						7 => "Srpanj",
						8 => "Kolovoz",
						9 => "Rujan",
						10 => "Listopad",
						11 => "Studeni",
						12 => "Prosinac"										
					),
		"de" => array(	1 => "Januar",
						2 => "Februar",
						3 => "M&auml;rz",
						4 => "April",
						5 => "Mai",
						6 => "Juni",
						7 => "Juli",
						8 => "August",
						9 => "September",
						10 => "Oktober",
						11 => "November",
						12 => "Dezember"										
					),
		"it" => array(	1 => "Gennaio",
						2 => "Febbraio",
						3 => "Marzo",
						4 => "Aprile",
						5 => "Maggio",
						6 => "Giugno",
						7 => "Luglio",
						8 => "Agosto",
						9 => "Settembre",
						10 => "Ottobre",
						11 => "Novembre",
						12 => "Dicembre"										
					)			
	);
	
	public static function getMonthName($languageCode, $monthNumber)
	{
		$languageCode = strtolower(trim($languageCode));
		$monthNumber = intval($monthNumber);
		return LocalizedCalendar::$months[$languageCode][$monthNumber];
	}
}