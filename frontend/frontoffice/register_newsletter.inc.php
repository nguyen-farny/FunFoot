<script>
$(function(){	
		$("#validerButton").click(function(){
			var email = $('#mail').val();
			if (!validate_mail(email)) {
				alert('Adresse mail invalide');
				return false;
			}
			
			$.ajax({
				url: server_address + "backend/newsletter_user/put.php",
				type: "POST",
				data: {
					'email' : email
				},
				success: function(d) { 
					var data = jQuery.parseJSON(d);
					if(data.status == 'success') 
					{
						customAlert("Vous êtes désormais inscrit à la newsletter");
						dialog_register.dialog( "close" );
					}
					else 
						customError(data.message);
				},
				error: function(data) {
					customError("error");
				}
			});
			setCookie('newsletter', 'oui');
			$("#alert").hide();	
			
		});
		$("#retourButton").click(function(){
			$("#alert").hide();	
			setCookie('newsletter', 'non');
		});
		
		// $(window).resize(function() {		  
			// if ($(document).width()<570)	
				// $(".alert_gauche").css("display", 'none');
				// $(".alert_droite").attr("colspan", '2');
		// });
});


</script>

<div id="alert">
	<table id="alert2">
		<tr>
			<td class="alert_gauche">
				<img src="images/coupe.jpg"/>
			</td>
			<td class="alert_droite">
				<h3>Inscription à la newsletter</h3>
				<input type="text" id="mail" placeholder="Votre adresse email"/>
				<button id="validerButton">Valider</button>
				<button id="retourButton">Non merci</button>
			</td>
		</tr>
	</table>
</div>


