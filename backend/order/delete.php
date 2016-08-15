<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';

$sql = SQL::GetAccess($config);
if(isset($_GET['id'])) {
	$order = Order::GetOne($_GET['id'],$sql);
	if ($order->Remove($sql)) {
		echo json_encode(array(
			'status' => 'success'
		));
	}
	else {
		echo json_encode(array(
			'status' => 'error',
			'message' => 'Cancel failed'
		));
	}
}
else 
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Cancel aborted'
	));
}

?>