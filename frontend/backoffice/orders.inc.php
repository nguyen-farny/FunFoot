<?php
	function GetFilter($filter)
	{
		if($filter == 'OPEN') { return 'FilterOpen'; }  
		else if($filter == 'CLOSED') { return 'FilterClosed'; }  
		else if($filter == 'USER') { return 'FilterByUser'; }
		else { return 'FilterNone'; } 
	}
?>

<script>
	function FilterNone(o) 
	{
		return false;
	}
		
	function FilterByUser(o)
	{
		var uid = <?php echo isset($_GET['id']) ? $_GET['id'] : 0; ?>;
		return o.userId != uid;
	}	
	
	function FilterOpen(o)
	{
		var s = GetCurrentStatus(o.statusList);
		return s == 'CANCELLED' || s == 'DELIVERED';
	}	
	
	function FilterClosed(o)
	{
		return !FilterOpen(o);
	}

	var filter = <?php echo GetFilter($filter); ?>;

	function SendForm(callback)
	{
		var $form = $('#edit form');
		var formdata = (window.FormData) ? new FormData($form[0]) : null;
		var data = (formdata !== null) ? formdata : $form.serialize();
		
		$('#edit #save').prop('disabled', true);
		
		$.ajax({
				url: server_address + "backend/order/put.php",
				type: "POST",
				data: data,
				contentType: false,
				processData: false,
				success: function(d) {
					var data = jQuery.parseJSON(d);
					if(data.status != 'success') 
						alert(data.message);
						
					callback();
				},
				error: function(data) {
					alert("Error");
					
					callback();
				}
		});
	}
			
	function Edit(o)
	{
		// Hide listing && show form
		$(".content").css('display', 'none');
		$("#edit").css('display', 'block');
		
		// Fill values in form
		$("#edit #id").val(o.id);
		$("#edit #userId").html(o.userId);
		$("#edit #lastname").html(o.user.lastname);
		$("#edit #firstname").html(o.user.firstname);
		$("#edit #address").html(o.user.address);
		$("#edit #phonenumber").html(o.user.phonenumber);
		
		$("#edit #status").empty();
		o.statusList.forEach(function(s) {
			$("#edit #status").append(
				'<tr>' +
					'<td>' + TranslateStatus(s.status) + '</td>' +
					'<td>' + ConvertDate(s.date) + '</td>' +
				'</tr>'
			); 
		});		
		
		$('#edit #PAYED').text(TranslateStatus('PAYED'));
		$('#edit #DELIVERED').text(TranslateStatus('DELIVERED'));
		$('#edit #SHIPPED').text(TranslateStatus('SHIPPED'));
		
		$("#edit #products").empty();
		o.productsList.forEach(function(p) {		
			$("#edit #products").append(
				'<tr>' +
					'<td>' + p.productId + '</td>' +
					'<td>' + p.product.name + '</td>' +
					'<td>' + p.product.stock_quantity + '</td>' +
					'<td>' + p.quantity + '</td>' +
				'</tr>'
			); 
		});

		
		// action on save
		$("#edit #save").unbind();
		$("#edit #save").click(function(e) {
			e.preventDefault();
			SendForm(Listing);
		});
	}
	
	function Remove(o) 
	{
		if(confirm('Etes vous sur de vouloir supprimer ' + o.id + ' ? '))
		{
			$.ajax({
			  url: server_address + "backend/order/delete.php?id=" + o.id,
			  context: document.body
			}).done(function(data) {
			  data = jQuery.parseJSON(data);
			  if(data.status == 'error')
				alert(data.message);
				
			  Listing();
			});
		}
	}

	function AddOrder(o, filter) 
	{
		var oid = 'o_' + o.id;
		
		if(filter(o))
			return;
			
		$('#orders').append(
		'<tr class="user" id="'+oid+'">' +
		'<td>' + o.id + '</td>' +
		'<td>' + GetStatusDate(o.statusList, 'PENDING_PAYMENT') + '</td>' +
		'<td>' + TranslateStatus(GetCurrentStatus(o.statusList)) + '</td>' +
		'<td>' + o.amount + '</td>' +
		'<td>' + o.userId + '</td>' +
		'<td> <a href="#" class="edit"> Infos </td>' +
		'<td> <a href="#" class="remove"> Supprimer </td>' +
		'</tr>'
		); 
			
		$('#'+oid+' .edit').click(function() { Edit(o); });
		$('#'+oid+' .remove').click(function() { Remove(o); });
	}


	function Listing()
	{
		// Show product table
		$(".content").css('display', 'none');
		$("#listing").css('display', 'block');
		
		// Remove old lines
		$("#orders").empty();
		
		// enable save button
		$('#edit #save').prop('disabled', false);
		
		// load all products
		LoadOrders(function(o) { AddOrder(o, filter); });
	}

	$( document ).ready(function() {
		Listing();
	});

</script>

<div id="listing" class="content">
	<table>
		<thead>
			<tr>
				<td>Id</td>
				<td>Date</td>
				<td>Statut</td>
				<td>Montant</td>
				<td>Utilisateur</td>
				<td>Détails</td>
				<td>Supprimer</td>
			</tr>
		</thead>
		<tbody id="orders"> 
		</tbody>
	</table>
</div>

<div id="edit" class="content">
	<form action="../../backend/order/put.php" method="POST" enctype="multipart/form-data" > 
	<input type="hidden" name="id" id="id"></input>
	
	<h3>Client</h3> 
	
	<div>
		Numéro client: <span id="userId"></span><br />
		<span id="firstname"></span><br />
		<span id="lastname"></span><br />
		<span id="address"></span><br />
		<span id="phonenumber"></span><br />
	</div>
	
	<h3>Statuts</h3>
	<table>
		<thead>
			<tr>
				<td>Statut</td>
				<td>Date</td>
			</tr>
		</thead>
		<tbody id="status"> 
		</tbody>
	</table>
	
	<h3>Produits</h3>
	<table>
		<thead>
			<tr>
				<td>Id</td>
				<td>Nom</td>
				<td>Quantité en stock</td>
				<td>Quantité commandée</td>
			</tr>
		</thead>
		<tbody id="products"> 
		</tbody>
	</table>
	
	<h3>Modification</h3>
	
	<select name="status" id="newStatus">
		<option id="PAYED" value="PAYED"></option>
		<option id="SHIPPED" value="SHIPPED"></option>
		<option id="DELIVERED" value="DELIVERED"></option>
	</select>
	
	<button id="save">Sauvegarder</button>
	</form>
</div>