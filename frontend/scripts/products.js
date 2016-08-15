function LoadProduct(callback, id)
{
	$.ajax({
	  url: server_address + "backend/product/get.php?id=" + id,
	  context: document.body
	}).done(function(data) {
	  callback(jQuery.parseJSON(data));
	});
}

function LoadProducts(callback)
{
	$.ajax({
	  url: server_address + "backend/product/get.php",
	  context: document.body
	}).done(function(data) {
	  var products = jQuery.parseJSON(data);
	  products.forEach(callback);
	});
}


function LoadLatestProducts(callback)
{
	$.ajax({
	  url: server_address + "backend/product/get.php?latest",
	  context: document.body
	}).done(function(data) {
	  var products = jQuery.parseJSON(data);
	  products.forEach(callback);
	});
}

function LoadProductsByCategory(callback, category)
{
	$.ajax({
	  url: server_address + "backend/product/get.php?category=" + category,
	  context: document.body
	}).done(function(data) {
	  var products = jQuery.parseJSON(data);
	  products.forEach(callback);
	});
}