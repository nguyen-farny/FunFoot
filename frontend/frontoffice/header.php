<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" ></code>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="shortcut icon" href="images/ball.ico">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/jssor.slider-20.mini.js"></script>
		<script src="js/interface-utils.js"></script>
		<script src="../scripts/utils.js"></script>
		<script src="../scripts/basket.js"></script>
		<script src="../scripts/orders.js"></script>
		<script src="../scripts/products.js"></script>
		
		
		<script>
		function refreshMenu()
		{
			if(getUserId() > 0) {
				$('#menu-login').css('display', 'none');
				$('#menu-register').css('display', 'none');
				$('#menu-logout').css('display', 'inline-block');
				$('#menu-account').css('display', 'inline-block');
				$('#menu-logout').width("15%");
				$('#menu-account').width("15%");
			}			
			else {
				$('#menu-login').css('display', 'inline-block');
				$('#menu-login').width("15%");
				$('#menu-register').css('display', 'inline-block');
				$('#menu-register').width("15%");
				$('#menu-logout').css('display', 'none');
				$('#menu-account').css('display', 'none');
			}
		}


		
		$( document ).ready(function() {
			refreshMenu();
						
			$('#menu-logout').click(function() {
				setCookie('userId', 0);
				refreshMenu();
				customAlert('Vous avez été déconnecté !');
			});
			
		    $( "#menu-login" ).click(function() {
			  dialog_login.dialog( "open" );
			});			
		    $( "#menu-register" ).click(function() {
			  dialog_register.dialog( "open" );
			});	
			$("#menu_widget").click(function(){
				$(".menu-icon").click();
			});					
		});
		
		
		
		/* Search bar */

		var resizeElements;

		$(document).ready(function() {

		  // Set up common variables
		  // --------------------------------------------------

		  var bar = ".recherche";
		  var input = bar + " input[type='text']";
		  var button = bar + " button[type='submit']";
		  var dropdown = bar + " .search_dropdown";
		  var dropdownLabel = dropdown + " > span";
		  var dropdownList = dropdown + " ul";
		  var dropdownListItems = dropdownList + " li";


		  // Set up common functions
		  // --------------------------------------------------

		  resizeElements = function() {
			var barWidth = $(bar).outerWidth();

			var labelWidth = $(dropdownLabel).outerWidth();
			$(dropdown).width(labelWidth);

			var dropdownWidth = $(dropdown).outerWidth();
			var buttonWidth	= $(button).outerWidth();
			var inputWidth = barWidth - dropdownWidth - buttonWidth;
			var inputWidthPercent = inputWidth / barWidth * 100 + "%";

			$(input).css({ 'margin-left': dropdownWidth, 'width': inputWidthPercent });
		  }

		  function dropdownOn() {
			$(dropdownList).fadeIn(25);
			$(dropdown).addClass("active");
		  }

		  function dropdownOff() {
			$(dropdownList).fadeOut(25);
			$(dropdown).removeClass("active");
		  }


		  // Initialize initial resize of initial elements 
		  // --------------------------------------------------
		  resizeElements();


		  // Toggle new dropdown menu on click
		  // --------------------------------------------------

		  $(dropdown).click(function(event) {
			if ($(dropdown).hasClass("active")) {
			  dropdownOff();
			} else {
			  dropdownOn();
			}

			event.stopPropagation();
			return false;
		  });

		  $("html").click(dropdownOff);


		  // Activate new dropdown option and show tray if applicable
		  // --------------------------------------------------

		  $(dropdownListItems).click(function() {
			$(this).siblings("li.selected").removeClass("selected");
			$(this).addClass("selected");

			// Focus the input
			$(this).parents("form.recherche:first").find("input[type=text]").focus();

			var labelText = $(this).text();
			$(dropdownLabel).text(labelText);

			resizeElements();

		  });


		  // Resize all elements when the window resizes
		  // --------------------------------------------------

		  $(window).resize(function() {
			resizeElements();
		  });
		  
			//Search all products in research bar
			//-------------------------------------------------------
			$("#search").click(function(){
				var currentPath = location.pathname.split('/').slice(-1)[0];
				if(currentPath != "listing.php") {
					// goto listing.php
					document.location.href = 'listing.php';
				}
			});
			
			// on focus la barre de recherche si on est sur la page des produits
			var currentPath = location.pathname.split('/').slice(-1)[0];
			if(currentPath == "listing.php") {
				$("#search").focus();
			}
		});
				 
		
		</script>
	</head>
	
	<body>
		<header>
			<div class="accueil_1">
				<table class="barre_recherche" >
					<tr>
						<td id="rech_loupe"><a href="#" id="the_search_btn"><img src="images/search.png" class="image_loupe"></a></td>
						<td id="rech_texte"><input id="search" placeholder="Recherche..." type="texte"></td>	
					</tr>
				</table>
				
				<div id="vide">
				</div>
				<div id="menu-register" class="user-link">
					<a href="#" class="liens_user">Créer un compte</a>
				</div>
				<div id="menu-login" class="user-link">
					<a href="#" class="liens_user">Connexion</a>
				</div>
				<div id="menu-logout" class="user-link">
					<a href="#" class="liens_user" id="menu-logout">Déconnexion</a>
				</div>
					<div id="menu-account" class="user-link">
					<a href='./myaccount.php' class="liens_user" id="menu-account">Mon compte</a>
				</div>
				<div class="menu_basket">	
					<a class='panier' href="basket.php">
						<img src="images/commerce.png" id='panier'/>
					</a>	
				</div>
			</div>
			<div class="accueil_2">
				<div class="logo_head">
					<a href="index.php">
						<img src="images/logo-horiz.png" id="logo"/> 
					</a>
				</div>
				<nav class='menu'>
					<ul class="sous_menu1">
						<li><a href='./index.php' class="link_home">Accueil</a></li>
						<li><a href='./listing.php' class="link_all">Tous nos produits</a></li>
						<li><a href='./listing.php#vetement' class="link_vet">Vêtements</a></li>
						<li><a href='./listing.php#chaussure' class="link_chaus">Chaussures</a></li>
						<li><a href='./listing.php#accessoire' class="link_acces">Accessoires</a></li>
					</ul>
					
					<div class="sous_menu2">
						<input type="checkbox" id="op"></input>
						<div class="lower">
						  <label class="menu-icon border-icon" for="op"></label>
						</div>
						<div class="overlay overlay-hugeinc">
							<label for="op"></label>
							<nav class="sous_ss_menu3">
								<ul>
									<li><a href='./index.php' class="link_home">Accueil</a></li>
									<li><a href='./listing.php' class="link_all">Tous nos produits</a></li>
									<li><a href='./listing.php#vetement' class="link_vet">Vêtements</a></li>
									<li><a href='./listing.php#chaussure' class="link_chaus">Chaussures</a></li>
									<li><a href='./listing.php#accessoire' class="link_acces">Accessoires</a></li>
								</ul>
							</nav>			
						</div>
					</div>
				</nav>	
			</div>
			<div class="menu_body">
				<img src="images/ball.png" id="menu_widget"/>
			</body>
		</header>
		
		
<?php include('login.inc.php'); ?>
<?php include('register.inc.php'); ?>


<div id="msg"></div>
