<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../utils.lib.php';
require_once '../order.lib.php';

$sql = SQL::GetAccess($config);

$o = new Order();
$o->userId = isset($_GET['userId']) ? $_GET['userId'] : 0;
$o->statusList[] = OrderStatus(OrderStatus::PendingPayment, new DateTime());

foreach($_GET['products'] as $product) 
	$o->productsList[] = OrderProduct($product['id'], $product['quantity']);

$o->amount = $o->ComputeAmount($sql);

if(!$o->IsValidOrder($sql)) {
	// erreur
	echo json_encode(array(
	'status' => 'error',
	'message' => 'Invalid order'
	));

}
else if(!$o->IsAvailableInStock($sql)) {
	// erreur
	echo json_encode(array(
	'status' => 'error',
	'message' => 'Products not availables'

}
else if(!$o->Save($sql)) {
	// erreur de sauvegarde
		echo json_encode(array(
		'status' => 'error',
		'message' => 'Save failed'
	));
}
else {
	// ok !
	echo json_encode(array('status' => 'success'));
	SendNewOrderEmail($o);
}

/*
//Test 
$o = new Order(1);
// $o->userId = 1;
$o->statusList[] = new OrderStatus(OrderStatus::$PendingPayment, new DateTime());
$o->productsList[] = new OrderProduct(5, 1);
$o->productsList[] = new OrderProduct(1, 3);
$o->amount = $o->ComputeAmount($sql);
// $isvalid = $o->IsValidOrder($sql);
// $isavailable = $o->IsAvailableInStock($sql);

// echo $o->amount; //  test: function ComputeAmount
// echo $isvalid;  //  test: function IsValidOrder
// echo $isavailable; // test: function IsAvailableInStock

$o->Save($sql);
*/

?>