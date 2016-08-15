<?php include ('header.php'); ?>
		<script>
		function blink(f) {
			f.animate({opacity:0},200,"linear",function(){
			  $(this).animate({opacity:1},200);
			});
		}
		
		function HideLine(p) 
		{
			$('#'+p.id).css('display', 'none');
			LoadBasket(OnBasketReady);
		}
		
		function AddProduct(p, b)
		{
			$('#basket').append(
			'<tr class="b_part2" id="'+p.id+'">' +
			'<td class="b_prod">' + p.name + '</td>' +
			'<td class="b_price">' + p.price + '€</td>' +
			'<td class="b_quantity">' + b.quantity + '</td>' +
			'<td class="b_delete b_get_removed"> <a class="remove" href="#"><img src="images/garbage.png" class="prod_panier"/></a></td>'+
			'</tr>'
			);
			
			$('#'+p.id+' .remove').click(function() { DeleteFromBasket(p, HideLine) });
			
			$(".b_get_removed").hover(
				function () {
					$(this).css("background-color","#D80027");
					$(this).css("border","solid 1px #D80027");
					$(this).css("border-radius","4px"); 
					$( this ).find( "img" ).attr('src','images/garbage2.png');
				},
				function () {

					$(this).css("background-color","white");
					$(this).css("border","0px");
					$(this).css("border-radius","0px");
					$( this ).find( "img" ).attr('src','images/garbage.png');		
				}
			);
			$(".b_payer").hover(
				function () {
					$(this).css("background-color","#5A5E6B");
					$(this).css("border","solid 1px #5A5E6B");
					$(this).css("border-radius","4px"); 
					$( this ).find( "img" ).attr('src','images/piggy-bank2.png');
					$( this ).find( "a" ).css('color','white');
				},
				function () {

					$(this).css("background-color","white");
					$(this).css("border","0px");
					$(this).css("border-radius","0px"); 
					$( this ).find( "img" ).attr('src','images/piggy-bank.png');	
					$( this ).find( "a" ).css('color','#5A5E6B');
				}
			);
		}
		
		function AddBasketLine(b) 
		{
			LoadProduct(function(p) { AddProduct(p, b); }, b.productId);
		}
		
		function OnBasketReady(basket) 
		{
			$('#basket').empty();
			$('#amount').empty();
			basket.productsList.forEach(AddBasketLine);
			$('#amount').append(basket.amount);
		}
				
		function OnOrderConfirmed(data)
		{
			$('#step2').css('display', 'none');
			$('#step3').css('display', 'block');
			
			if(data.status == 'success') {
				$('#step3').append('Merci, votre commande a été bien enregistrée!');
			}			
			else {
				$('#step3').append(data.message);
			}
		}
		
		function isBasketEmpty()
		{
			return $('#basket').is(':empty');
		}
		
		function notValidCard()
		{
			if(($("#card_number").val() == '') || ($("#card_user_name").val() == '') || ($("#secureCode").val() == ''))
				return true; 
			else return false;
		}
		
		$( document ).ready(function() {
			LoadBasket(OnBasketReady);
			
			$('#goto_step2').click(function() {
				if(getUserId() == 0) {
					customAlert('Vous devez être connecté pour passer commande');					
					blink($("#menu-login"));
					blink($("#menu-register"));
				}
				else if(isBasketEmpty())
					customAlert('Le panier doit contenir au moins un produit');				
				else {
					$('#step1').css('display', 'none');
					$('#step2').css('display', 'block');
				}
			});	
			
			$('#cancel').click(function() {
				$('#step1').css('display', 'block');
				$('#step2').css('display', 'none');
			});	
			
			$('#goto_step3').click(function() {
				if(getUserId() == 0) {
					customAlert('Vous devez être connecté pour passer commande');					
					blink($("#menu-login"));
					blink($("#menu-register"));
				}
				else if(notValidCard())
					customAlert('Veuillez vérifier l\'information de votre carte');						
				else {
					ConfirmOrder(OnOrderConfirmed);
				}
			});
		});
		
		
		</script>
	</head>
	<body>
		
		<div id="step1" class="content">
			<table class="full_basket">
				<thead>
					<tr class="b_part1">
						<td class="b_title b_prod">Produit</td>
						<td class="b_title b_price">Prix</td>
						<td class="b_title b_quantity">Quantité</td>
						<td class="b_title b_delete"></td>
					</tr>
				</thead>
				<tbody id="basket">
				</tbody>
				<tfoot>
					<tr class="b_part3">				
					</tr>
				</tfoot>
			</table>
			<div  class="b_payment">
				<div class="b_montant" >
					<label for="amount">Total à payer </label>
					<span id="amount"></span> €
				</div>
				<div class="b_payer">
					<a href="#" id="goto_step2" ><img src="images/piggy-bank.png" class="prod_panier"/>Payer</a>
				</div>
			</div>		
		</div>
		
		
		<div id="step2" style="display: none;" class="content">
			<table class="b_info_payment">
				<tr>
					<td class="b_payment_title" colspan="2">
						<h3>Paiement par carte</h3>
					</td>
				</tr>
				<tr>
					<td class="b_gauche">
						<label for="card_number">Numéro de carte: </label>
					</td>
					<td class="b_droit">
						<input type="text" id="card_number">
					</td>
				</tr>
				<tr>
					<td class="b_gauche">
						<label for="card_number">Date d'expiration: </label>
					</td>
					<td class="b_droit">
						<select name='expireMM' id='expire'>
							<option value=''>Mois</option>
							<option value='01'>Janvier</option>
							<option value='02'>Février</option>
							<option value='03'>Mars</option>
							<option value='04'>Avril</option>
							<option value='05'>Mai</option>
							<option value='06'>Juin</option>
							<option value='07'>Juillet</option>
							<option value='08'>Août</option>
							<option value='09'>Septembre</option>
							<option value='10'>Octobre</option>
							<option value='11'>Novembre</option>
							<option value='12'>Décembre</option>
						</select> 
						<select name='expireYY' id='expireYY'>
							<option value=''>Année</option>
							<option value='16'>2016</option>
							<option value='17'>2017</option>
							<option value='18'>2018</option>
							<option value='19'>2019</option>
							<option value='20'>2020</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="b_gauche">
						<label for="card_number">Nom de titulaire de la carte: </label>
					</td>
					<td class="b_droit">
						<input type="text" id="card_user_name">
					</td> 
				</tr>
				<tr>
					<td class="b_gauche">
						<label for="card_number">Code de sécurité: </label>
					</td>
					<td class="b_droit">
						<input type="text" id="secureCode">
					</td>
				</tr>
				<tr>
					<td class="b_gauche b_cancel">
						<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="cancel">
							<span class="ui-button-text">Annuler</span>
						</button>
					</td>
					<td class="b_droit">
						<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="goto_step3">
							<span class="ui-button-text">Effectuer un paiement</span>
						</button>
					</td>
				</tr>
			</table>
		</div> 
		
		<div id="step3" class="content">
		</div>
<?php include ('footer.php'); ?>
