<?php 

session_start();

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';
require_once '../utils.lib.php';

$sql = SQL::GetAccess($config);
$basket = Order::GetBasket();
$basket->userId = isset($_GET['userId']) ? $_GET['userId'] : 0; 
$basket->amount = $basket->ComputeAmount($sql);
$basket->AddStatus(OrderStatus::$PendingPayment, new DateTime());

$validate = $basket->IsValidOrder($sql);

if($validate !== Order::$Valid) {
	// erreur
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid order (' . $validate . ')',
		'error_code' => $validate
	));
}
else if(!$basket->IsAvailableInStock($sql)) {
	// erreur
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Products not availables'
	));
}
else if($basket->Save($sql) === false) {
	// erreur de sauvegarde
		echo json_encode(array(
		'status' => 'error',
		'message' => 'Save failed'
	));
}
else {
	// ok !
	echo json_encode(array('status' => 'success'));
	SendNewOrderEmail(Order::GetOne($basket->id, $sql));
	Order::SaveBasket(new Order());
}


?>