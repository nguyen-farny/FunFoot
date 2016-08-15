function LoadUser(callback, id)
{
	$.ajax({
	  url: server_address + "backend/user/get.php?id=" + id,
	  context: document.body
	}).done(function(data) {
	  callback(jQuery.parseJSON(data));
	});
}

function LoadUsers(callback)
{
	$.ajax({
	  url: server_address + "backend/user/get.php",
	  context: document.body
	}).done(function(data) {
	  var products = jQuery.parseJSON(data);
	  products.forEach(callback);
	});
}

function LoadNewsletterUsers(callback)
{
	$.ajax({
	  url: server_address + "backend/newsletter_user/get.php",
	  context: document.body
	}).done(function(data) {
	  var products = jQuery.parseJSON(data);
	  products.forEach(callback);
	});
}
