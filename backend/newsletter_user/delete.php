<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../newsletter_user.lib.php';

$sql = SQL::GetAccess($config);

$nu = new NewsletterUser();
$nu->id = isset($_GET['id']) ? $_GET['id'] : "";

if($nu->Remove($sql))
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