<?php

require_once 'product.lib.php';
require_once 'user.lib.php';

class OrderProduct
{
	var $quantity;
	var $productId;
	var $product;
	
    function __construct($quantity, $productId) 
	{
		$this->quantity = $quantity; 
		$this->productId = $productId; 
    }
	
	function Load($mysqli) 
	{
		$this->product = Product::GetOne($this->productId, $mysqli);
	}
}

class OrderStatus
{
	var $status;
	var $date;
		
    function __construct($status, $date) 
	{
		$this->status = $status; 
		$this->date = $date; 
    }
	
	static $PendingPayment = "PENDING_PAYMENT";
	static $Payed = "PAYED";
	static $Shipped = "SHIPPED";
	static $Delivered = "DELIVERED";
	static $Cancelled = "CANCELLED";
}

class Order
{
	// attributes
	var $id; 
	var $userId;
	var $user;
	var $amount; 
	var $statusList;
	var $productsList;

    function __construct($userId = 0) 
	{
		$this->statusList = array(); 
		$this->productsList = array(); 
		$this->userId = $userId;
    }
	
	function HasStatus($status, $date)
	{
		foreach($this->statusList as $os) 
		{
			if($os->date == $date && $os->status == $status)
				return true;
		}
		
		return false;
	}
	
	function FindProduct($productId)
	{
		foreach($this->productsList as $op) 
		{
			if($op->productId == $productId)
				return $op;
		}
		
		return null;
	}
	
	function AddStatus($status, $date)
	{
		if(!$this->HasStatus($status, $date))
			$this->statusList[] = new OrderStatus($status, $date);
	}
	
	function AddProduct($quantity, $productId)
	{
		$op = $this->FindProduct($productId);
		if($op == null)
			$this->productsList[] = new OrderProduct($quantity, $productId);
		else
			$op->quantity = $quantity;
	}
	
	function RemoveProduct($productId)
	{
		foreach($this->productsList as $k=>$op) 
		{
			if($op->productId == $productId)
				unset($this->productsList[$k]);
		}
		
		$this->productsList = array_values($this->productsList);
	}
	
	/*
		CRUD
	*/
	

	static function GetOne($id, $mysqli)
	{
		$o = new Order();

		$statement = $mysqli->prepare("SELECT id, user_Id, amount FROM `order` WHERE id = ?"); 	
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->bind_result($o->id, $o->userId, $o->amount);
		$statement->fetch();
		$statement->close();
		
		$statement = $mysqli->prepare("SELECT status, date FROM `order_status` WHERE order_id = ?"); 	
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->bind_result($status, $date);
		while($statement->fetch())
			$o->statusList[] = new OrderStatus($status, $date);
		
		$statement = $mysqli->prepare("SELECT product_id, quantity FROM `order_product` WHERE order_id = ?"); 	
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->bind_result($product_id, $quantity);
		while($statement->fetch())
			$o->productsList[] = new OrderProduct($quantity, $product_id);;
		
		// Load products details
		foreach($o->productsList as $op) 
			$op->Load($mysqli);
				
		// Load user details
		$o->user = User::GetOne($o->userId, $mysqli);

		return $o;	
	}
	
	static function Get($mysqli, $where = '1=1')
	{
		$buffer = array();
		$statement = $mysqli->prepare("
			SELECT 
				o.id, 
				o.user_Id, 
				o.amount, 
				os.status, 
				os.date, 
				op.product_id, 
				op.quantity  
			FROM 
			`order` o, 
			`order_status` os, 
			`order_product` op 
			WHERE o.id = os.order_id AND os.order_id = op.order_id AND " . $where
		); 	
		$statement->execute();
		$statement->bind_result($buffer['id'], $buffer['user_Id'],$buffer['amount'], $buffer['status'], $buffer['date'], $buffer['product_id'], $buffer['quantity']);
		$list = array();
		
		while ($statement->fetch()) 
		{
			if(!array_key_exists($buffer['id'], $list))
			{
				$list[$buffer['id']] = new Order();
				$list[$buffer['id']]->id = $buffer['id'];
				$list[$buffer['id']]->userId = $buffer['user_Id'];
				$list[$buffer['id']]->amount = $buffer['amount'];
			}
			
			$list[$buffer['id']]->AddStatus($buffer['status'], $buffer['date']);
			$list[$buffer['id']]->AddProduct($buffer['quantity'], $buffer['product_id']);
		}
	
		// Load details
		foreach($list as $o)
		{
			foreach($o->productsList as $op) 
				$op->Load($mysqli);
				
			$o->user = User::GetOne($o->userId, $mysqli);
		}
		
		return array_values($list);	
	}
	
	function Remove($mysqli)
	{
		// insert into status 'CANCELLED'
		$this->AddStatus(OrderStatus::$Cancelled,new DateTime());
		return $this->Save($mysqli);
	}
	
	function SaveStatus($status, $mysqli)
	{
		if(!$statement = $mysqli->prepare("INSERT INTO order_status SET order_id=?, status=?, date=?"))
			return false;
				
		$dateStr = $status->date->format("Y-m-d H:i:s");
		$statement->bind_param('iss', $this->id, $status->status, $dateStr); 
		if(!$statement->execute())
			return false;
		$statement->close();
			
		return true;
	}

	function SaveProduct($op, $mysqli)
	{
		// Save Into order_product
		if(!$statement = $mysqli->prepare("INSERT INTO order_product SET order_id=?, product_id=?, quantity=?"))
			return false;
		
		$statement->bind_param('iii', $this->id, $op->productId, $op->quantity); 
		if(!$statement->execute())
			return false;
		$statement->close();
		
		// Decrement stock
		$product = Product::GetOne($op->productId, $mysqli);
		$product->stock_quantity -= $op->quantity;
		$e = "";
		if(!$product->Save($mysqli, $e))
			return false;
		
		return true;
	}
	
	function Update($mysqli) 
	{
		$originalOrder = Order::GetOne($this->id, $mysqli); 
		
		foreach($this->statusList as $os)
		{			
			if(!$originalOrder->HasStatus($os->status, $os->date)) 
			{
				if(!$this->SaveStatus($os, $mysqli))
					return false;
			}
		}
		
		return true;
	}
	
	function Create($mysqli) 
	{
		// Handle Order
		if(!$statement = $mysqli->prepare("INSERT INTO `order` SET amount=?, user_id=?"))
			return false;
			
		$statement->bind_param('di', $this->amount, $this->userId); 
		
		if(!$statement->execute())
			return false;
		
		$this->id = $mysqli->insert_id;
		$statement->close();
		
		// Handle OrderStatus
		foreach($this->statusList as $status)
			if(!$this->SaveStatus($status, $mysqli))
				return false;

		// Handle OrderProduct
		foreach($this->productsList as $product)
			if(!$this->SaveProduct($product, $mysqli))
				return false;
		
		
		return true;
	}
	
	function Save($sql)
	{
		if($this->id)
			return $this->Update($sql);
		else
			return $this->Create($sql);
	}
	
	function ComputeAmount($sql)
	{
		$amount = 0;
		
		foreach($this->productsList as $prod)
		{
			$product = Product::GetOne($prod->productId, $sql);
			$amount += ($product->price)*($prod->quantity);
		}
		
		return $amount;
	}
	
	function IsAvailableInStock($sql)
	{
		foreach($this->productsList as $prod)
		{
			$product = Product::GetOne($prod->productId, $sql);
			if($prod->quantity > $product->stock_quantity)
				return false; 
		}
		
		return true;
	}
	
	static $Valid = 0;
	static $InvalidQuantity = 'InvalidQuantity';
	static $NotInStock = 'NotInStock';
	static $InvalidUser = 'InvalidUser';
	static $InvalidStatus = 'InvalidStatus';
	
	function IsValidOrder($sql)
	{
		foreach($this->productsList as $prod)
			if($prod->quantity == 0)
				return Order::$InvalidQuantity; 			
		
		$user = User::GetOne($this->userId, $sql);
		
		if(sizeof($this->productsList) == 0)
			return Order::$InvalidQuantity; 			
		if(!$this->IsAvailableInStock($sql))
			return Order::$NotInStock;
		if(!$user->id)
			return Order::$InvalidUser;
		if($this->statusList == "" || sizeof($this->statusList) == 0)
			return Order::$InvalidStatus;
	
		return Order::$Valid; 
	}
	
	static function GetBasket() 
	{
		if(!array_key_exists('basket', $_SESSION)) {
			$_SESSION['basket'] = serialize(new Order());
		}
		
		return unserialize($_SESSION['basket']);
	}
	
	static function SaveBasket($basket)
	{
		$_SESSION['basket'] = serialize($basket);
	}
}

?>