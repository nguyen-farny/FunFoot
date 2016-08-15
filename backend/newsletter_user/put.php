<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../utils.lib.php';
require_once '../newsletter_user.lib.php';

$sql = SQL::GetAccess($config);

$nu = new NewsletterUser();
$nu->email = isset($_POST['email']) ? $_POST['email'] : "";

if(!IsValidEmail($nu->email))
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid email address'
	));
}
else if($nu->Save($sql))
{
	echo json_encode(array('status' => 'success'));
}
else
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Save failed'
	));
}

?>