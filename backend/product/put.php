<?php 

require_once '../config.php';
require_once '../sql.lib.php';
require_once '../utils.lib.php';
require_once '../product.lib.php';

$sql = SQL::GetAccess($config);

$p = isset($_POST['id']) ? Product::GetOne($_POST['id'], $sql) : new Product();

$p->name = isset($_POST['name']) ? $_POST['name'] : $p->name;
$p->category = isset($_POST['category']) ? $_POST['category'] : $p->category;
$p->price = isset($_POST['price']) ? $_POST['price'] : $p->price;
$p->image = isset($_POST['image']) ? $_POST['image'] : $p->image;
$p->image_1 = isset($_POST['image_1']) ? $_POST['image_1'] : $p->image_1;
$p->image_2 = isset($_POST['image_2']) ? $_POST['image_2'] : $p->image_2;
$p->image_3 = isset($_POST['image_3']) ? $_POST['image_3'] : $p->image_3;
$p->stock_quantity = isset($_POST['stock_quantity']) ? $_POST['stock_quantity'] : $p->stock_quantity ;
$p->description = isset($_POST['description']) ? $_POST['description'] : $p->description;

function upload($p, $name) 
{
	$uploaddir = '../../frontend/frontoffice/images/';
	$uploadfile = $uploaddir . basename($_FILES[$name]['name']);

	if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
		$p->$name = basename($_FILES[$name]['name']);
	}
	
	return $p;
}

upload($p,'image');
upload($p,'image_1');
upload($p,'image_2');
upload($p,'image_3');

if($p->Save($sql, $e) > 0)
{
	echo json_encode(array('status' => 'success'));
}
else
{
	echo json_encode(array(
		'status' => 'error',
		'message' => 'Save failed : ' . $e
	));
}

?>