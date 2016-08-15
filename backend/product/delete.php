<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../product.lib.php';

$sql = SQL::GetAccess($config);

$p = new Product();
$p->id = isset($_GET['id']) ? $_GET['id'] : "";

if($p->Remove($sql))
{
	echo json_encode(array('status' => 'success'));
}
else
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Remove failed'
	));
}

?>