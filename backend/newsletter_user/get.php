<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../newsletter_user.lib.php';

$sql = SQL::GetAccess($config);
$subscribers = NewsletterUser::Get($sql);
echo json_encode($subscribers);

?>