<script>
	function SendForm(callback)
	{
		var $form = $('#edit form');
		var formdata = (window.FormData) ? new FormData($form[0]) : null;
		var data = (formdata !== null) ? formdata : $form.serialize();
		
		$('#edit #save').prop('disabled', true);
		
		$.ajax({
				url: server_address + "backend/product/put.php",
				type: "POST",
				data: data,
				contentType: false, // obligatoire pour de l'upload
				processData: false, // obligatoire pour de l'upload

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
		$("#edit #id").val(p.id);
		$("#edit #name").val(p.name);
		$("#edit #price").val(p.price);
		$("#edit #stock").val(p.stock_quantity);
		$("#edit #category").val(p.category);
		$("#edit #description").val(p.description);
		$("#edit #current-image").html(p.image);
		$("#edit #current-image1").html(p.image_1);
		$("#edit #current-image2").html(p.image_2);
		$("#edit #current-image3").html(p.image_3);
		
		// action on save
		$("#edit #save").unbind();
		$("#edit #save").click(function(e) {
			e.preventDefault();
			SendForm(Listing);
		});
	}
	
	function Remove(p) 
	{
		if(confirm('Etes vous sur de vouloir supprimer ' + p.name + ' ? '))
		{
			$.ajax({
			  url: server_address + "backend/product/delete.php?id=" + p.id,
			  context: document.body
			}).done(function(data) {
			  data = jQuery.parseJSON(data);
			  if(data.status == 'error')
				alert(data.message);
				
			  Listing();
			});
		}
	}

	function AddProduct(p) 
	{
		var pid = 'p_' + p.id;
		
		$('#products').append(
		'<tr class="product" id="'+pid+'">' +
		'<td>' + p.id + '</td>' +
		'<td>' + p.name + '</td>' +
		'<td>' + p.category + '</td>' +
		'<td>' + p.price + '</td>' +
		'<td>' + p.stock_quantity + '</td>' +
		'<td> <a href="#" class="edit"> Modifier </td>' +
		'<td> <a href="#" class="remove"> Supprimer </td>' +
		'</tr>'
		); 
			
		$('#'+pid+' .edit').click(function() { Edit(p); });
		$('#'+pid+' .remove').click(function() { Remove(p); });
	}

	function Listing()
	{
		// Show product table
		$(".content").css('display', 'none');
		$("#listing").css('display', 'block');
		
		// Remove old lines
		$("#products").empty();

		// Switch menu
		$("#submenu .current").removeClass('current');
		$("#submenu a[href$=list]").addClass('current');
		
		// enable save button
		$('#edit #save').prop('disabled', false);
		
		// load all products
		LoadProducts(AddProduct);
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
				<td>Nom</td>
				<td>Categorie</td>
				<td>Prix</td>
				<td>Stock</td>
				<td>Editer</td>
				<td>Supprimer</td>
			</tr>
		</thead>
		<tbody id="products"> 
		</tbody>
	</table>
</div>

<div id="edit" class="content">
	<form action="../../backend/product/put.php" method="POST" enctype="multipart/form-data" > 
	<input type="hidden" name="id" id="id"></input>
	<label for="name">Nom</label> <input type="text" name="name" id="name" placeholder="Nom"></input><br />
	<label for="price">Prix</label>  <input type="text" name="price" id="price" placeholder="Prix"></input><br />
	<label for="stock">Quantité en stock</label>  <input type="text" name="stock_quantity" id="stock" placeholder="Stock"></input><br />
	<label for="categoy">Catégorie</label>  <input type="text" name="category" id="category" placeholder="Categorie"></input><br />
	<textarea name="description" id="description" placeholder="Description"></textarea><br />
	
	<label for="image">Image principale</label> <span id="current-image"></span> <input type="file" name="image" accept="image/*" /><br />
	<label for="image-1">Image Annexe 1</label> <span id="current-image1"></span> <input type="file" name="image_1" accept="image/*" /><br />
	<label for="image-2">Image Annexe 2</label> <span id="current-image2"></span> <input type="file" name="image_2" accept="image/*" /><br />
	<label for="image-3">Image Annexe 3</label> <span id="current-image3"></span> <input type="file" name="image_3" accept="image/*" /><br />
	
	<button id="save">Sauvegarder</button>
	</form>
</div>