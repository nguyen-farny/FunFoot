
		<script>
			function OnCancel(data, o)
			{
				if(data.status == 'success') {
					$('#'+o.id+' .status').html(TranslateStatus('CANCELLED'));
					$('#'+o.id+' .cancel_order').hide();
				}
				else
					alert(data.message);
			}
				
			function ShowOrder(o)
			{
				$( "#myaccount2-show .products" ).empty();

				o.productsList.forEach(function(op) {
					$( "#myaccount2-show .products" ).append(
						'<tr>' +
							'<td>'+op.product.name+'</td>' +
							'<td>'+op.quantity+'</td>' +
						'</tr>'
					);
				});
				
				dialog_detail.dialog( "open" );
			}
			
			function AddOrder(o)
			{
				var date = GetStatusDate(o.statusList, 'PENDING_PAYMENT');
				var status = TranslateStatus(GetCurrentStatus(o.statusList));

				$('#myorders_tbody').append(
					'<tr class="line" id="'+o.id+'">' +
					'<td><a href="#">' + date + '</a></td>' +
					'<td><a href="#">' + o.id + '</a></td>' +
					'<td><a href="#" class="status">' + status + '</a></td>' +
					'<td><a href="#">' + o.amount + '</a></td>' +
					'<td class="b_delete b_get_removed cancel_order"><a class="remove" href="#"><img src="images/garbage.png" class="prod_panier"/></a></td>'+
					'</tr>'
				);
				
				$('#'+o.id+' td').click(function() { ShowOrder(o); });
				$('#'+o.id+' .cancel_order').unbind();
				$('#'+o.id+' .cancel_order').click(function() { CancelOrder(o, OnCancel); });
				if( GetCurrentStatus(o.statusList) == 'CANCELLED' ) { $('#'+o.id+' .cancel_order').hide(); }
			}
				
			$( document ).ready(function() {
				LoadOrdersForUser(AddOrder); 
				
				dialog_detail = $( "#myaccount2-show" ).dialog({
					autoOpen: false,
					height: 300,
					width: 400,
					modal: true,
					buttons: {
					}
				});
			});
		</script>
	
		<div id="myaccount2" id="myorders">
			<h3>Mes Commandes</h3>
			<table class="myaccount2table">
				<thead>
					<tr class="b_part1">
						<td class="b_title">Date de la commande</td>
						<td class="b_title">#</td>
						<td class="b_title">Etat de la commande</td>
						<td class="b_title">€</td>
						<td class="b_title b_delete"></td>
					</tr>
				</thead>
				<tbody id="myorders_tbody">
				</tbody>
			</table>
		</div>		

		<div id="myaccount2-show" title="Détails de votre commande">
			<h3>Produits</h3>
			<table>
				<thead>
				<tr>
					<td  class="b_title">Nom</td>
					<td  class="b_title">Quantite</td>
				</tr>
				</thead>
				<tbody class="products">
				</tbody>
			</table>
		</div>