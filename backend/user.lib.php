<?php
class User 
{
	// attributes
	var $id; 
	var $firstname; 
	var $lastname; 
	var $phonenumber; 
	var $email; 
	var $password; 
	var $address; 
		
    function __construct() 
	{
	}
	
	/*
		CRUD
	*/
	
	static function GetOne($id, $mysqli)
	{	
		$u = new User();
	
		$statement = $mysqli->prepare("SELECT id, firstname, lastname,phonenumber, email, password, address FROM user WHERE id = ?"); 	
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->bind_result($u->id, $u->firstname, $u->lastname, $u->phonenumber, $u->email, $u->password, $u->address);
		$statement->fetch();		
	
		return $u;
	}	
	
	static function Login($email, $password, $mysqli)
	{	
		$u = new User();
	
		$statement = $mysqli->prepare("SELECT id, firstname, lastname, phonenumber, email, password, address FROM user WHERE email = ? AND password = ?"); 	
		$statement->bind_param('ss', $email, $password);
		$statement->execute();
		$statement->bind_result($u->id, $u->firstname, $u->lastname, $u->phonenumber, $u->email, $u->password, $u->addres);
		$statement->fetch();		
	
		return $u;
	}
	
	static function Get($mysqli)
	{		
		$statement = $mysqli->prepare("SELECT id, firstname, lastname,phonenumber, email, password, address FROM user"); 	
		$statement->execute();
		$statement->bind_result($id, $firstname, $lastname, $phonenumber, $email, $password, $address );
		$list = array();
		
		while ($statement->fetch()) 
		{
			$u = new User();
			$u->id = $id;
			$u->firstname = $firstname;
			$u->lastname = $lastname;
			$u->phonenumber = $phonenumber;
			$u->email = $email;
			$u->password = $password;
			$u->address = $address;
			$list[] = $u; 
		}
	
		return $list;
	}
	
	function Save($mysqli)
	{
		$statement = null;
		
		if(isset($this->id)) 
		{
			// UPDATE
			if(!$statement = $mysqli->prepare("UPDATE user SET firstname=?, lastname=?, phonenumber=?, email=?, password=?, address=? WHERE id=?"))
				return false;
			$statement->bind_param('ssssssi', $this->firstname, $this->lastname, $this->phonenumber, $this->email, $this->password, $this->address, $this->id); 
		}
		else 
		{
			// INSERT
			if(!$statement = $mysqli->prepare("INSERT INTO user SET firstname=?, lastname=?, phonenumber=?, email=?, password=?, address=?"))
				return false;
			$statement->bind_param('ssssss', $this->firstname, $this->lastname, $this->phonenumber, $this->email, $this->password, $this->address); 
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
		
		if(!$statement = $mysqli->prepare("DELETE FROM user WHERE id=?"))
			return false;
		
		$statement->bind_param('i', $this->id); 
	
		if(!$statement->execute())
			return false;
		
		$statement->close();
		return true;
	}
   	
}

?>