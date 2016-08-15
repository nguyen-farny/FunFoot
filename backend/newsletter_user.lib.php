<?php
class NewsletterUser // implements JsonSerializable
{
	// attributes
	var $id; 
	var $email; 
	
    function __construct() 
	{
    }
	
	/*
		CRUD
	*/

	static function Get($mysqli)
	{		
		$statement = $mysqli->prepare("SELECT id, email FROM newsletter_user"); 	
		$statement->execute();
		$statement->bind_result($id, $email);
		$list = array();
		
		while ($statement->fetch()) 
		{
			$nu = new NewsletterUser();
			$nu->id = $id;
			$nu->email = $email;
			$list[] = $nu; 
		}
	
		return $list;
	}
	
	function Save($mysqli)
	{
		$statement = null;
		
		if(isset($this->id)) 
		{
			// UPDATE
			if(!$statement = $mysqli->prepare("UPDATE newsletter_user SET email=? WHERE id=?"))
				return false;
			$statement->bind_param('si', $this->email, $this->id); 
		}
		else 
		{
			// INSERT
			if(!$statement = $mysqli->prepare("INSERT INTO newsletter_user SET email=?"))
				return false;
			$statement->bind_param('s', $this->email); 
		}
		
		if(!$statement->execute())
			return false;

		// we are in INSERT scenario : get the inserted id
		if(!isset($this->id))
			$this->id = $mysqli->insert_id;
		
		$statement->close();
		
		return $this->id;
	}
	
	function Remove($mysqli)
	{
		$statement = null;
		
		if(!$statement = $mysqli->prepare("DELETE FROM newsletter_user WHERE id=?"))
			return false;
		
		$statement->bind_param('i', $this->id); 
	
		if(!$statement->execute())
			return false;
		
		$statement->close();
		return true;
	}
}
?>