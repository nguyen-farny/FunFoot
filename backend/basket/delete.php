<?php 

session_start();

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';

if(isset($_GET['productId']))
{	
	$productId = intval($_GET['productId']);

	$basket = Order::GetBasket();
	$basket->RemoveProduct($productId);
	Order::SaveBasket($basket);
}

echo json_encode(Order::GetBasket());

?>