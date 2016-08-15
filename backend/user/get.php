<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../user.lib.php';

$sql = SQL::GetAccess($config);

if(isset($_GET['id'])) {
	$u = User::GetOne($_GET['id'],$sql);
	echo json_encode($u);
}
else 
{
	$users = User::Get($sql);
	echo json_encode($users);
}

?>