<script>
	function SendForm(callback)
	{
		var $form = $('#edit form');
		var formdata = (window.FormData) ? new FormData($form[0]) : null;
		var data = (formdata !== null) ? formdata : $form.serialize();
		
		$('#edit #save').prop('disabled', true);
		
		$.ajax({
				url: server_address + "backend/user/put.php",
				type: "POST",
				data: data,
				contentType: false,
				processData: false,
				success: function(d) {
					// alert(d);
				
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
	

	function Add()
	{
		// Hide listing && show form
		$(".content").css('display', 'none');
		$("#edit").css('display', 'block');
		
		// action on save
		$("#edit #save").unbind();
		$("#edit #save").click(function(e) {
			e.preventDefault();
			SendForm(Listing);
		});
	}
		
	function Edit(p)
	{
		// Hide listing && show form
		$(".content").css('display', 'none');
		$("#edit").css('display', 'block');
		
		// Fill values in form
		$("#edit #userId").val(p.id);
		$("#edit #firstname").val(p.firstname);
		$("#edit #lastname").val(p.lastname);
		$("#edit #address").val(p.address);
		$("#edit #email").val(p.email);
		$("#edit #phonenumber").val(p.phonenumber);
		$("#edit #password").val(p.password);
		
		// action on save
		$("#edit #save").unbind();
		$("#edit #save").click(function(e) {
			e.preventDefault();
			SendForm(Listing);
		});
	}
	
	function Remove(u) 
	{
		if(confirm('Etes vous sur de vouloir supprimer ' + u.firstname + ' ' + u.lastname + ' ? '))
		{
			$.ajax({
			  url: server_address + "backend/user/delete.php?id=" + u.id,
			  context: document.body
			}).done(function(data) {
			  data = jQuery.parseJSON(data);
			  if(data.status == 'error')
				alert(data.message);
				
			  Listing();
			});
		}
	}

	function AddUser(u) 
	{
		var uid = 'u_' + u.id;
		
		$('#users').append(
		'<tr class="user" id="'+uid+'">' +
		'<td>' + u.id + '</td>' +
		'<td>' + u.firstname + '</td>' +
		'<td>' + u.lastname + '</td>' +
		'<td>' + u.email + '</td>' +
		'<td>' + u.address + '</td>' +
		'<td>' + u.phonenumber + '</td>' +
		'<td> <a href="?page=orders&action=list&filter=USER&id=' + u.id + '" class="listOrder"> Suivi commandes </td>' +
		'<td> <a href="#" class="edit"> Modifier </td>' +
		'<td> <a href="#" class="remove"> Supprimer </td>' +
		'</tr>'
		); 
			
		$('#'+uid+' .edit').click(function() { Edit(u); });
		$('#'+uid+' .remove').click(function() { Remove(u); });
	}
	
	function Listing()
	{
		// Show product table
		$(".content").css('display', 'none');
		$("#listing").css('display', 'block');
		
		// Remove old lines
		$("#users").empty();

		// Switch menu
		$("#submenu .current").removeClass('current');
		$("#submenu a[href$=list]").addClass('current');
		
		// enable save button
		$('#edit #save').prop('disabled', false);
		
		// load all products
		LoadUsers(AddUser);
	}

	$( document ).ready(function() {
	<?php // call right javascript method
		switch($action) {
			case 'add':
				echo 'Add();';
				break;
			default:
				echo 'Listing();';
				break;
		}
	?>
	});

</script>

<div id="listing" class="content">
	<table>
		<thead>
			<tr>
				<td>Id</td>
				<td>Prénom</td>
				<td>Nom</td>
				<td>Email</td>
				<td>Adresse</td>
				<td>Téléphone</td>
				<td>Commandes</td>
				<td>Editer</td>
				<td>Supprimer</td>
			</tr>
		</thead>
		<tbody id="users"> 
		</tbody>
	</table>
</div>

<div id="edit" class="content">
	<form action="../../backend/product/put.php" method="POST" enctype="multipart/form-data" > 
	<input type="hidden" name="userId" id="userId"></input>
	<label for="firstname">Prénom</label> <input type="text" name="firstname" id="firstname" placeholder="Prénom"></input><br />
	<label for="lastname">Nom</label> <input type="text" name="lastname" id="lastname" placeholder="Nom"></input><br />
	<label for="address">Adresse</label>  <input type="text" name="address" id="address" placeholder="Adresse"></input><br />
	<label for="phonenumber">Téléphone</label>  <input type="text" name="phonenumber" id="phonenumber" placeholder="Téléphone"></input><br />
	<label for="email">Email</label>  <input type="text" name="email" id="email" placeholder="Email"></input><br />
	<label for="password">Mot de passe</label>  <input type="password" name="password" id="password" placeholder="Mot de passe"></input><br />
	
	<button id="save">Sauvegarder</button>
	</form>
</div>