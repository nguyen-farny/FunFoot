function ConvertDate(str)
{
	var pattern = /^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
	var arrayDate = str.match(pattern);
	return new Date(arrayDate[1], arrayDate[2] - 1, arrayDate[3], arrayDate[4], arrayDate[5], arrayDate[6]);
}

function GetStatusDate(statusList, status)
{
	for(i = 0 ; i < statusList.length ; i++) 
	{
		if(statusList[i].status == status)
			return statusList[i].date;
	}
	
	return 0;
}

function GetCurrentStatus(statusList)
{
	var status = '';
	var date = 0;
	
	
	for(i = 0 ; i < statusList.length ; i++) 
	{
		var d = ConvertDate(statusList[i].date);
		
		if(d > date) 
		{
			date = d;
			status = statusList[i].status;
		}
	}
	
	return status;
}

function TranslateStatus(s)
{
	if(s == 'PENDING_PAYMENT')
		return "En attente de payement";
	if(s == 'CANCELLED')
		return "Annulée";
	if(s == 'PAYED')
		return "Payement reçu";
	if(s == 'SHIPPED')
		return "Commande expediée";
	if(s == 'DELIVERED')
		return "Livraison terminée";
	
	return s;
}

function LoadOrders(callback)
{
	$.ajax({
	  url: server_address + "backend/order/get.php",
	  context: document.body
	}).done(function(data) {
	  var orders = jQuery.parseJSON(data);
	  orders.forEach(callback);
	});
}

function LoadOrdersForUser(callback)
{
	$.ajax({
	  url: server_address + "backend/order/get.php?userId=" + getUserId(),
	  context: document.body
	}).done(function(data) {
	  var orders = jQuery.parseJSON(data);
	  orders.forEach(callback);
	});
}

function CancelOrder(o, callback)
{
	$.ajax({
	  url: server_address + "backend/order/delete.php?id=" + o.id,
	  context: document.body
	}).done(function(data) {
	  callback(jQuery.parseJSON(data), o);
	});
}