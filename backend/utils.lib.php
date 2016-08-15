<?php

function IsValidEmail($email)
{
	return strlen($email) > 3 && strpos($email, '@') > 0;
}

function IsValidPass($password)
{
	return strlen($password) > 7;
}

function IsValidPhone($phonenumber)
{
	return is_numeric($phonenumber) && strlen($phonenumber) > 9;
}

function GetNewOrderEmailSubjectForAdmin($order)
{
	return "Nouvelle commande #" . $order->id . " (montant: " . $order->amount . " Euros)";
}

function GetNewOrderEmailSubjectForUser($order)
{
	return "Confirmation commande #" . $order->id;
}

function GetNewOrderEmailMessageForAdmin($order)
{
	$m  = "Bonjour, \n";
	$m .= "\n"; 
	$m .= "Vous avez une nouvelle commande:\n"; 
	$m .= "\n"; 
	$m .= "Numéro de commande: " . $order->id . "\n"; 
	$m .= "Montant: " . $order->amount . " Euros \n"; 
	$m .= "Listing produits: \n";
	foreach($order->productsList as $op)
		$m .= "- " . $op->quantity . " x " . $op->product->name . "\n";

	$m .= "\n";
	
	$m .= "Client: #" . $order->userId . "\n"; 
	$m .= "Nom: " . $order->user->lastname . "\n"; 
	$m .= "Prénom: " . $order->user->firstname . "\n"; 
	$m .= "Adresse: " . $order->user->address . "\n"; 
	$m .= "Téléphone: " . $order->user->phonenumber. "\n"; 
	$m .= "\n"; 
	
	return $m;
}

function GetNewOrderEmailMessageForUser($order)
{
	$m  = "Bonjour, \n";
	$m .= "\n"; 
	$m .= "Votre commande numéro " . $order->id . " est bien enregistrée.\n";
	$m .= "Pour rappel, voici le contenu de votre commande:\n"; 
	$m .= "\n"; 

	$m .= "Montant: " . $order->amount . " Euros \n"; 
	$m .= "Listing produits: \n";
	foreach($order->productsList as $op)
		$m .= "- " . $op->quantity . " x " . $op->product->name . "\n";

	$m .= "\n";
	$m .= "A bientôt sur FunFoot !\n";
		
	return $m;
}

function SendNewOrderEmail($order)
{	
	$headers = 'From: FunFoot <From: webmaster@funfoot.esy.es>' . "\r\n" .
		'Reply-To: contact@funfoot.esy.es' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();	
		
	$admin = 'nnp.cao@gmail.com'; 
	$user = $order->user->email; 

	mail($admin, GetNewOrderEmailSubjectForAdmin($order), GetNewOrderEmailMessageForAdmin($order), $headers);
	mail($user, GetNewOrderEmailSubjectForUser($order), GetNewOrderEmailMessageForUser($order), $headers);
}

?>