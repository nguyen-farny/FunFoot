<script>
function onLogin(u)
{
	if(u.id) {
		customAlert("Vous êtes bien connecté.");
		refreshMenu();
		dialog_login.dialog( "close" );
	}
	else {
		customError('Erreur de connexion');
	}
}		

$(function() {
	dialog_login = $( "#login-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Connexion": function() {
				var email = $('#email').val(); 
				var password = $('#password').val(); 
				login(email, password, onLogin);
			},
			Cancel: function() {
				dialog_login.dialog( "close" );
			}
		}
	});
});
</script>

<div id="login-form" title="Identifiez-vous">
	<input type="text" id="email" placeholder="Email"><br>
	<input type="password" id="password" placeholder="Mot de passe"><br>
</div>