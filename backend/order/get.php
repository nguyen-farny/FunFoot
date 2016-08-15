<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';

$sql = SQL::GetAccess($config);
if(isset($_GET['id'])) {
	$order = Order::GetOne($_GET['id'],$sql);
	echo json_encode($order);
}
else if(isset($_GET['userId'])) {
	$orders = Order::Get($sql, 'user_Id = ' . $_GET['userId']);
	echo json_encode($orders);
}else {
	$orders = Order::Get($sql);
	echo json_encode($orders);
}

?>