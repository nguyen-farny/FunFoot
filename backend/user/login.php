<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../user.lib.php';

$sql = SQL::GetAccess($config);

$email = isset($_POST['email']) ? $_POST['email'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

$result = User::Login($email, $password, $sql);

echo json_encode($result);

?>