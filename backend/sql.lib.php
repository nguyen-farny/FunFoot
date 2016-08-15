<?php

class SQL
{	
	static $mysqli;

	static function GetAccess($config)
	{
		if(!isset(SQL::$mysqli)) 
		{
			SQL::$mysqli= new mysqli(
				$config['host'], 
				$config['username'], 
				$config['password'], 
				$config['schema'] 
			);
			
			if (SQL::$mysqli->connect_error) {
				die("Connection failed: " . SQL::$mysqli->connect_error);
			}
		}
				
		return SQL::$mysqli;
	}
}

?>