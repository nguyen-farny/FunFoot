function customAlert(text)
{
	$('#msg').html(text).fadeIn('slow');
	
	setTimeout(function() {
		$('#msg').fadeOut('fast');
	}, 7*1000);
}

function customError(text)
{
	alert(text);
}

function AddProduct(p) 
{
	var pid = 'p_' + p.id;
	var available = p.stock_quantity > 0 ? 'Disponible' : 'Indisponible';
	
	$('#products').append(
	'<table class="product" id="'+pid+'">' +
		'<tr class="tr_part1">'+
			'<td class="prod_pic" colspan="3">' + 
				'<a href="product.php#'+p.id+'">' +
					'<img src="images/' + p.image + '" class="img_prod" id="img"/>' +
				'</a>' + 
			'</td>' +
		'</tr>' +
		'<tr class="tr_part2">' +
			'<td class="prod_name" colspan="3"><h3>' + p.name + '</h3></td>'+ 
		'</tr>'+
		'<tr class="tr_part3">' + 
			'<td class="prod_categ" colspan="3">'+
				p.category + 
			'</td>'+
		'</tr>'+
		'<tr class="tr_part4">'+
			'<td class="prod_avail">' + available + '</td>' +
			'<td class="prod_price">' + p.price + '€ </td>' +	
			'<td class="prod_addbasket"> <a class="addtobasket" href="#"><img src="images/shopping-bag.png" class="prod_panier"/></a></td>' +
		'</tr>'+
	'</table>'
	); 
		
	$('#'+pid+' .addtobasket').click(function() { AddToBasket(p); });
	$(".prod_addbasket").hover(
		function () {
			$(this).css("background-color","#5A5E6B");
			$(this).css("border","solid 1px #5A5E6B");
			$(this).css("border-radius","4px"); 
			$( this ).find( "img" ).attr('src','images/shopping-bag2.png');
		},
		function () {

			$(this).css("background-color","white");
			$(this).css("border","0px");
			$(this).css("border-radius","0px");
			$( this ).find( "img" ).attr('src','images/shopping-bag.png');		
		}
	);		
}
