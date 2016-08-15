<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../product.lib.php';

$sql = SQL::GetAccess($config);

if(isset($_GET['id'])) {
	$product = Product::GetOne($_GET['id'],$sql);
	echo json_encode($product);
}
else if(isset($_GET['category'])) {
	$products = Product::Get($sql, "category='".$_GET['category']."'");
	echo json_encode($products);
}
else if(isset($_GET['latest'])) {
	$products = Product::Get($sql, "1=1 ORDER BY id DESC LIMIT 3");
	echo json_encode($products);
}
else {
	$products = Product::Get($sql);
	echo json_encode($products);
}

?>