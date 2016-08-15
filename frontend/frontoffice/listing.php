<?php include ('header.php'); ?>

<script>

$( document ).ready(function() 
{	
	var cleanUpNeeded = false;
	var category = window.location.hash.substring(1);

	$('.link_all').click(function() { refresh(""); });
	$('.link_vet').click(function() { refresh("vetement"); });
	$('.link_chaus').click(function() { refresh("chaussure"); });
	$('.link_acces').click(function() { refresh("accessoire"); });

	timer = 0;

	$("#search").keyup(function(){
		if (timer) {
			clearTimeout(timer);
		}
		timer = setTimeout(function() { refresh(""); }, 400); 
	});
	
	function AddProductIfMatchSearch(p)
	{
		if(cleanUpNeeded == true) 
		{
			cleanUpNeeded = false;
			$('#products').empty();
		}
		
		var val = $('#search').val().toLowerCase();
		
		if(val == "" || p.name.toLowerCase().indexOf(val) >= 0)
		{
			AddProduct(p);
		}
	}
	
	function refresh(category) 
	{
		cleanUpNeeded = true;
		
		if(category != "")
			LoadProductsByCategory(AddProductIfMatchSearch, category);
		else
			LoadProducts(AddProductIfMatchSearch);
	}
	
	refresh(category);
});



</script>

	
<div id="products" class="content"> 
</div>

<?php include ('footer.php'); ?>