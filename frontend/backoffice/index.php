<?php 
	$page = (isset($_GET['page'])) ? $_GET['page'] : 'products';
	$action = (isset($_GET['action'])) ? $_GET['action'] : 'list';
	$filter = (isset($_GET['filter'])) ? $_GET['filter'] : '';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" type="text/css">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="../scripts/utils.js"></script>
		<script src="../scripts/orders.js"></script>
		<script src="../scripts/users.js"></script>
		<script src="../scripts/basket.js"></script>
		<script src="../scripts/products.js"></script>
	</head>
	
	<body>
		<nav>
			<a href="?page=products&action=<?php echo $action ?>" <?php if($page=='products') echo 'class="current"' ?>>Gestion des produits</a>
			<a href="?page=orders&action=<?php echo $action ?>" <?php if($page=='orders') echo 'class="current"' ?>>Gestion des commandes</a>
			<a href="?page=users&action=<?php echo $action ?>" <?php if($page=='users') echo 'class="current"' ?>>Gestion des utilisateurs</a>
			<a href="?page=newsletters&action=<?php echo $action ?>" <?php if($page=='newsletters') echo 'class="current"' ?>>Gestion newsletter</a>
		</nav>
		
		<?php if($page != 'orders') { ?>
		<nav id="submenu">
			<a href="?page=<?php echo $page ?>&action=list" <?php if($action=='list') echo 'class="current"' ?>>Lister</a>
			<a href="?page=<?php echo $page ?>&action=add" <?php if($action=='add') echo 'class="current"' ?>>Ajouter</a>
		</nav>
		<?php } else { ?>		
		<nav id="submenu">
			<a href="?page=<?php echo $page ?>&action=list" <?php if($filter!='OPEN' && $filter!='CLOSED') echo 'class="current"' ?>>Toutes les commandes</a>
			<a href="?page=<?php echo $page ?>&action=list&filter=OPEN" <?php if($filter=='OPEN') echo 'class="current"' ?>>Commandes en cours</a>
			<a href="?page=<?php echo $page ?>&action=list&filter=CLOSED" <?php if($filter=='CLOSED') echo 'class="current"' ?>>Commandes terminées</a>
		</nav>
		<?php } ?>		
		
		<?php 			
			switch($page) {
				case 'orders':
					include('orders.inc.php');
					break;
				case 'users':
					include('users.inc.php');
					break;
				case 'newsletters':
					include('newsletters.inc.php');
					break;
				default:
					include('products.inc.php');
					break;

			}
		?>
		
	</body>
</html>