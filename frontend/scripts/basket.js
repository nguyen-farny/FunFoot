
function AddToBasket(p)
{
	$.ajax({
	  url: server_address + "backend/basket/put.php?productId=" + p.id + "&add=1",
	  context: document.body
	}).done(function(data) {
		customAlert('Ajout de ' + p.name + ' au panier ');
	});
}

function DeleteFromBasket(p, callback)
{
	$.ajax({
	  url: server_address + "backend/basket/delete.php?productId=" + p.id,
	  context: document.body
	}).done(function(data) {
		callback(p);
	});
}

function LoadBasket(callback)
{
	$.ajax({
	  url: server_address + "backend/basket/get.php",
	  context: document.body
	}).done(function(data) {
	  callback(jQuery.parseJSON(data));
	});
}

function ConfirmOrder(callback)
{
	$.ajax({
	  url: server_address + "backend/basket/validate.php?userId=" + getUserId(),
	  context: document.body
	}).done(function(data) {
	  callback(jQuery.parseJSON(data));
	});
}