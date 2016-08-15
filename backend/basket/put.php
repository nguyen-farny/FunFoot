<?php 

session_start();

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';

if(isset($_GET['quantity']) && isset($_GET['productId']))
{	
	$quantity = intval($_GET['quantity']);
	$productId = intval($_GET['productId']);

	$basket = Order::GetBasket();
	$basket->AddProduct($quantity, $productId);
	
	Order::SaveBasket($basket);
}
else if(isset($_GET['add']) && isset($_GET['productId']))
{	
	$add = intval($_GET['add']);
	$productId = intval($_GET['productId']);

	$basket = Order::GetBasket();
	$p = $basket->FindProduct($productId);
	if($p == null)
		$basket->AddProduct($add, $productId);
	else 
		$p->quantity += $add;
	
	Order::SaveBasket($basket);
}

echo json_encode(Order::GetBasket());

?>