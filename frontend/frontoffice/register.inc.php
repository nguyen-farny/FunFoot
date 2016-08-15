<script>
$(function() {
	dialog_register = $( "#register-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Inscription": function() {
				var firstname = $('#register-firstname').val(); 
				var lastname = $('#register-lastname').val(); 
				var address = $('#register-address').val(); 
				var phonenumber = $('#register-phonenumber').val(); 
				var email = $('#register-email').val(); 
				var password = $('#register-password').val();
				
				//vérifications supplémentaires
				if (!validate_mail(email)) {
					alert('Adresse mail invalide');
					return false;
				}
				
				if (password != $("#register-c_password").val())
				{
					alert("Mots de passes differents");
					$("#register-c_password").css("border","2px solid red");
					$("#register-password").css("border","2px solid red");
					return false;
				}
				else
				{
					$("#register-c_password").css("border","1px solid #ccc");
					$("#register-password").css("border","1px solid #ccc");
				}
									
				// ajax 
				$.ajax({
					url: server_address + "backend/user/put.php",
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
							customAlert("Votre compte a bien été créé");
							dialog_register.dialog( "close" );
						}
						else 
							customError(data.message);
					},
					error: function(data) {
						customError("error");
					}
				});
			},
			Cancel: function() {
				dialog_register.dialog( "close" );
			}
		}
	});
});
</script>

<div id="register-form" title="Inscrivez-vous">
	<input type="text" id="register-firstname" placeholder="Prénom"><br>
	<input type="text" id="register-lastname" placeholder="Nom"><br>
	<input type="text" id="register-address" placeholder="Adresse"><br> 
	<input type="text" id="register-phonenumber" placeholder="Téléphone"><br>
	<input type="text" id="register-email" placeholder="Email"><br>
	<input type="password" id="register-password" placeholder="Mot de passe"><br>
	<input type="password" id="register-c_password" placeholder="Confirmer mot de passe"><br>
</div>