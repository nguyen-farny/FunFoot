<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../order.lib.php';

$sql = SQL::GetAccess($config);
if(isset($_POST['id']) && isset($_POST['status'])) {
	$order = Order::GetOne($_POST['id'],$sql);
	$order->AddStatus($_POST['status'],new DateTime());

	if ($order->Save($sql)) {
		echo json_encode(array(
			'status' => 'success'
		));
	}
	else {
		echo json_encode(array(
			'status' => 'error',
			'message' => 'Change status failed'
		));
	}
}
else 
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Change status aborted: ' . $_POST['id']
	));
}

?>