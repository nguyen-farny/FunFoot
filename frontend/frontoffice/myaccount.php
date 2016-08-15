<?php include ('header.php'); ?>
		<div id="myaccount">
			<a href="#myaccount1" id="myaccountInfo">Modifier l'information de mon compte</a>
			<a href="#myaccount2" id="myaccountOrder">Mes commandes</a>
		</div>
		
		<div id="myaccount1" >
			<h3>Mon compte</h3>
			<form>				
				<input type="text" id="firstname" placeholder="Prénom"><br>
				<input type="text" id="lastname" placeholder="Nom"><br>
				<input type="text" id="address" placeholder="Adresse"><br> 
				<input type="text" id="phonenumber" placeholder="Téléphone"><br>
				<input type="text" id="email" placeholder="Email"><br>
				<input type="password" id="password" placeholder="Mot de passe"><br>
				<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="submit">
					<span class="ui-button-text">Enregistrer</span>
				</button>
			</form> 
		</div>
		
		<?php include 'myorders.php'; ?>
		

	<script>
		
		$(document).ready(function(){
		
			$.ajax({
				url: server_address + "backend/user/get.php?id=" + getUserId(),
				context: document.body
			}).done(function(d) { 					
				var user = jQuery.parseJSON(d);
				$("#firstname").val(user.firstname); 
				$("#lastname").val(user.lastname); 
				$("#address").val(user.address); 
				$("#phonenumber").val(user.phonenumber); 
				$("#email").val(user.email); 
				$("#password").val(user.password); 
			});
			
			
			$("#submit").click(function(e){
				
				e.preventDefault();
				e.stopPropagation();
				
				
				var firstname = $('#firstname').val(); 
				var lastname = $('#lastname').val(); 
				var address = $('#address').val(); 
				var phonenumber = $('#phonenumber').val(); 
				var email = $('#email').val(); 
				var password = $('#password').val(); 
									
				// ajax 
				$.ajax({
					url: server_address + "backend/user/put.php?userId=" + getUserId(),
					type: "POST",
					data: {
						'firstname': firstname,
						'lastname' : lastname, 
						'address': address, 
						'phonenumber': phonenumber, 
						'email' : email, 
						'password': password
					},
					success: function(d) { 					
						var data = jQuery.parseJSON(d);
						if(data.status == 'success') 
						{
							customAlert("Modifications enregistrées"); //also show a success message 
						}
						else 
							customError(data.message);
					},
					error: function(data) {
						customError("error");
					}
				});
			});
			
			$('#myaccountOrder').click(function() {
				$('#myaccount2').css('display', 'block');
				$('#myaccount1').css('display', 'none');
			});	
			
			$('#myaccountInfo').click(function() {
				$('#myaccount2').css('display', 'none');
				$('#myaccount1').css('display', 'block');
			});	
		});			
			
	</script>		

<?php include ('footer.php'); ?>