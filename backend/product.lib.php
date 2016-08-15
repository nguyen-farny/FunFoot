<?php
class Product
{
	// attributes
	var $id; 
	var $name; 
	var $category; 
	var $price; 
	var $image; 
	var $image_1; 
	var $image_2; 
	var $image_3; 
	var $stock_quantity; 
	var $description;
		
    function __construct() 
	{
		$this->image = ""; 
		$this->image_1 = ""; 
		$this->image_2 = ""; 
		$this->image_3 = "";
    }
	
	/*
		CRUD
	*/
		
	static function GetOne($id, $mysqli)
	{		
		$p = new Product();

		$statement = $mysqli->prepare("SELECT id, name, category, price, image, stock_quantity, image_1, image_2, image_3, description FROM product WHERE id = ?"); 	
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->bind_result($p->id, $p->name, $p->category, $p->price, $p->image, $p->stock_quantity, $p->image_1, $p->image_2, $p->image_3, $p->description);
		$statement->fetch();
		
		return $p;
	}
	
	static function Get($mysqli, $where = '1=1')
	{		
		$statement = $mysqli->prepare("SELECT id, name, category, price, image, stock_quantity, image_1, image_2, image_3, description  FROM product WHERE " . $where); 	
		$statement->execute();
		$statement->bind_result($id, $name, $category, $price, $image, $stock_quantity, $image_1, $image_2, $image_3, $description);
		$list = array();
		
		while ($statement->fetch()) 
		{
			$p = new Product();
			$p->id = $id;
			$p->name = $name;
			$p->category = $category;
			$p->price = $price;
			$p->image = $image;
			$p->stock_quantity = $stock_quantity;
			$p->image_1 = $image_1;
			$p->image_2 = $image_2;
			$p->image_3 = $image_3;
			$p->description = $description;
			
			$list[] = $p; 
		}
	
		return $list;
	}
	
	function Save($mysqli, &$e)
	{
		$statement = null;
		
		if(isset($this->id) && $this->id != 0) 
		{
			// UPDATE
			if(!$statement = $mysqli->prepare("UPDATE product SET name=?, category=?, price=?, image=?, stock_quantity=?, image_1=?, image_2=?, image_3=?, description=? WHERE id=?"))
			{
				$e = "Update statement is wrong";
				return false;
			}
			$statement->bind_param('ssdsissssi', $this->name, $this->category, $this->price, $this->image, $this->stock_quantity, $this->image_1, $this->image_2, $this->image_3, $this->description, $this->id); 
		}
		else 
		{
			// INSERT
			if(!$statement = $mysqli->prepare("INSERT INTO product SET name=?, category=?, price=?, image=?, stock_quantity=?, image_1=?, image_2=?, image_3=?, description=?"))
			{
				$e = "Insert statement is wrong: " . $mysqli->error;
				return false;
			}
			$statement->bind_param('ssdsissss', $this->name, $this->category, $this->price, $this->image, $this->stock_quantity, $this->image_1, $this->image_2, $this->image_3, $this->description); 
		}
		
		if(!$statement->execute())
		{
			$e = "Exec failed: " . $mysqli->error;
			return false;
		}

		// we are in INSERT scenario : get the inserted id
		if(!isset($this->id) || $this->id == 0)
			$this->id = $mysqli->insert_id;
		
		$statement->close();
		
		$e = "id; " . $this->id;;
		return $this->id;
	}
	
	function Remove($mysqli)
	{
		$statement = null;
		
		if(!$statement = $mysqli->prepare("DELETE FROM product WHERE id=?"))
			return false;
		
		$statement->bind_param('i', $this->id); 
	
		if(!$statement->execute())
			return false;
		
		$statement->close();
		return true;
	}
}

?>