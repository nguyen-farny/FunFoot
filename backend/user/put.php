<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../utils.lib.php';
require_once '../user.lib.php';

$sql = SQL::GetAccess($config);

$u = new User();
$u->id = isset($_GET['userId']) ? $_GET['userId'] : null;
$u->firstname = isset($_POST['firstname']) ? $_POST['firstname'] : "";
$u->lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
$u->phonenumber = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : "";
$u->email = isset($_POST['email']) ? $_POST['email'] : "";
$u->password = isset($_POST['password']) ? $_POST['password'] : "";
$u->address = isset($_POST['address']) ? $_POST['address'] : "";


if(!IsValidPhone($u->phonenumber))
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid phone number'
	));
}
else if(!IsValidEmail($u->email))
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid email address'
	));
}
else if(!IsValidPass($u->password))
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Invalid password, it must have 8 characters mininum'
	));
}
else if($u->Save($sql))
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