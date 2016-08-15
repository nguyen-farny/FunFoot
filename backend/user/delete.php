<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../user.lib.php';

$sql = SQL::GetAccess($config);

$u = new User();
$u->id = isset($_GET['id']) ? $_GET['id'] : "";

if($u->Remove($sql))
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