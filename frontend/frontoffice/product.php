
<?php include ('header.php'); ?>
	<script src='js/jquery.elevateZoom-3.0.8.min.js'></script>	
		<script>		
		function AddProduct(p) 
		{
			var pid = 'p_' + p.id;
			var available = p.stock_quantity > 0 ? 'Disponible' : 'Indisponible';
			
			$('#product_detail').append(
			'<table class="d_gallery_prod">'+
				'<tr class="d_image_princ">'+
					'<td colspan="4">' + 
						'<a>'+
							'<img src="images/' + p.image + '" data-zoom-image="images/' + p.image + '" id ="image_princ"/>'+
						'</a>' + 
					'</td>'+
				'</tr>'+
				'<tr class="d_images_sec">'+
					'<td>' + 
						'<a >'+
							'<img src="images/' + p.image + '" data-zoom-image="images/' + p.image + '" id="image" class="d_images"/>' + 
						'</a>'+
					'</td>' +
					'<td>' + 
						'<a >'+
							'<img src="images/' + p.image_1 + '" data-zoom-image="images/' + p.image_1 + '" id="image1" class="d_images"/>' + 
						'</a>'+
					'</td>' +
					'<td>' + 
						'<a >'+
							 '<img src="images/' + p.image_2 + '" data-zoom-image="images/' + p.image_2 + '" id="image2" class="d_images"/>' + 
						'</a>'+
					'</td>' +
					'<td>' + 
						'<a >'+
							 '<img src="images/' + p.image_3 + '" data-zoom-image="images/' + p.image_3 + '" id="image3" class="d_images"/>' + 
						'</a>'+
					'</td>' +
				'</tr>'+
			'</table>'+
			'<table class="d_product" id="'+pid+'">' +
				'<tr class= "d_part1">'+
					'<td class="d_prod_name" colspan="2">'+
						'<h3>' + p.name + '</h3>' +
					'</td>'+
				'</tr>'+
				'<tr class= "d_part2">'+
					'<td class="d_prod_desc" colspan="2">'+
						p.description +
					'</td>'+
				'</tr>'+
				'<tr class= "d_part3">'+
					'<td class="d_prod_avail" colspan="2">' + 
						available +" " + p.price + '€' +
					'</td>'+		
				'</tr>'+
				'<tr class= "d_part4" >'+
					'<td class="type_taille">'+
						'<select class="taille" id="taille_id">'+
							//se rempli en fonction de la category
						'</select>'+
					'</td>'+
					'<td class="d_prod_addbasket">'+
						'<a class="d_addtobasket" href="#"><img src="images/shopping-bag.png" class="d_prod_panier"/> Ajouter au panier</a>' +
					'</td>'+
				'</tr>'+
			'</table>'
			
			
			);
			taille(p.category);
			if ($( document ).width()<="570")
				zoom_inner();
			else 
				zoom();
			$('.d_images').click(function() {
				$('#image_princ').attr('src', $(this).attr('src'));
				$("#image_princ").data("zoom-image", $(this).data("zoom-image"));
				$("#image_princ").attr("data-zoom-image", $(this).attr("data-zoom-image"));
				
				if ($( document ).width()<=570)
					zoom_inner();
				else 
					zoom();
			});
	
			$('#'+pid+' .d_addtobasket').click(function() { AddToBasket(p); });
			
			$(".d_prod_addbasket").hover(
				//souris dessus
				function () {
					$(this).css("background-color","#5A5E6B");
					$(this).css("border","solid 1px #5A5E6B");
					$(this).css("border-radius","4px"); 
					$( this ).find( "img" ).attr('src','images/shopping-bag2.png');
					$( this ).find( "a" ).css('color','white');
				},
				//comportement normal
				function () {
					$(this).css("background-color","white");
					$(this).css("border","0px");
					$(this).css("border-radius","0px");
					$( this ).find( "img" ).attr('src','images/shopping-bag.png');	
					$( this ).find( "a" ).css('color','#5A5E6B');					
				}
			);
		}
		
		$( document ).ready(function() {
			var id = window.location.hash.substring(1);
			LoadProduct(AddProduct, id);
		});
		
		
		function zoom(){			
			$('#image_princ').elevateZoom({ 
				zoomWindowPosition	: 1,
				scrollZoom 			: true,
				borderSize			: 1,
				borderColour 		: "rgb(136,136,136)",
				tintColour 			: "#e7e7e7",
				zoomWindowWidth 	: 350,
				zoomWindowHeight 	: 350,
				/* zoomWindowOffetx	: 10,
				zoomWindowOffety	: 65, */
				zoomWindowFadeIn	: 500,
				zoomWindowFadeOut	: 500,
				lensFadeIn			: 500,
				lensFadeOut			: 500, 
			});
		};
		function zoom_inner(){			
			$('#image_princ').elevateZoom({ 
				zoomType			: "inner",
				cursor				: "crosshair",
			});
		};
		
		//fonction choix taille en fct de la categorie de prod
		function taille(category){
			switch(category) {
				case "vetement":
					$( '<label for="taille_id">Taille </label>' ).insertBefore( ".taille" );
					$('.taille').append(
						'<option class="size" value="S">S</option>'+
						'<option class="size" value="M">M</option>'+
						'<option class="size" value="L">L</option>'+
						'<option class="size" value="XL">XL</option>'+
						'<option class="size" value="XXL">XXL</option>'
					);
					break;
				case "chaussure":
					$( '<label for="taille_id">Pointure </label>' ).insertBefore( ".taille" );
					var i;
					for (i=35; i<=50;++i){
						$('.taille').append(							
							'<option class="size" id="pointure_'+ i  +'" value="' + i + '">'+ i +'</option>'			
						);
					};
					break;			
				default:
					$(".type_taille").css("display", 'none');
					$(".d_prod_name").attr("colspan", '1');
					$(".d_prod_desc").attr("colspan", '1');
					$(".d_prod_avail").attr("colspan", '1');
				};
			};
			
			//adapter le zoom apres resize
			$(window).resize(function() {
			  
				if ($(document).width()<680)	
					zoom_inner();
				else 
					zoom();
			});
			
		
		</script>
		<div id="product_detail">
		
		</div>
		

	<?php include ('footer.php'); ?>
