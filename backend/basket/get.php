<?php 

session_start();

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';

$sql = SQL::GetAccess($config);

$basket = Order::GetBasket();
$basket->amount = $basket->ComputeAmount($sql);
echo json_encode($basket);

?>