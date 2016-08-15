<?php include ('header.php'); ?>
		<div class="c_content">
			<div id="contact">
				<h2>Contact</h2>
					<input class="control renseignements" type="text" name="nom" id="nom" placeholder="Nom"/><br>	
					<input class="control renseignements" type="text" name="prenom" id="prenom" placeholder="Prénom"/><br>
					<input class="control renseignements" type="tel" name="telephone" id="telephone" placeholder="Numéro de téléphone"/><br>
					<input class="control renseignements" type="text" name="mail" id="mail" placeholder="ex:aaaa@exemple.fr"/><br>
					<textarea class="control" name="message" placeholder="Saisissez votre message ici" id="message">
					</textarea><br>
					<input type="submit" value="Envoyer" id="send"/>
			</div>
			<div class="c_image">
				<img src="images/paper-planes.jpg"/>
			</div>
		</div>
		<script>
		
		$('#send').click(function() {
        var mail = $('#mail').val();
			if (validate_mail(mail)) {
				$("#mail").css("border","1px solid #DEB887");
				$("#span_mail").html("");
				return true;
			}
			else {
				$("<span id='span_mail' class='c_spans' style='color:red'> ex: aaaa@exemple.fr</span>").insertAfter("#mail");
				$("#mail").css("border","2px solid red");
				return false;
			}
		});

		$('#send').click(function () {
		var tel = $('#telephone').val();
		
		if (validate_tel(tel)) {
			$("#telephone").css("border","1px solid #DEB887");
			$("#span_tel").html("");
			return true;
		}
		else {
			$("#telephone").css("border","2px solid red");
			$("<span id='span_tel' class='c_spans' style='color:red'> 10 chiffres</span>").insertAfter("#telephone");
			return false;
		}
		
		});
		
		function validate_tel(tel) {
		var filter = /^[0-9]{10}$/;
			if (filter.test(tel)) {
				return true;
			}
			else {
				return false;
			}
		};
		
		$("#send").click(function()
		{	
		<!--Vérif champs remplis-->
			if ($("#nom").val()=="")
			{
				$("#nom").css("border","2px solid red");
				$("<span id='span_nom' class='c_spans' style='color:red'> champs obligatoire </span>").insertAfter("#nom");
				 //return false;
			}
			else
			{
				$("#nom").css("border","1px solid #DEB887");
				$("#span_nom").html("");
			}
			
			if ($("#prenom").val()=="")
			{
				$("#prenom").css("border","2px solid red");
				$("<span id='span_prenom' class='c_spans' style='color:red'> champ obligatoire</span>").insertAfter("#prenom");
				// return false;
			}
			else
			{
				$("#prenom").css("border","1px solid #DEB887");
				$("#span_prenom").html("");
			}

			if ($("#message").val()=="")
			{
				$("#message").css("border","2px solid red");
				$("<span id='span_message' class='c_spans' style='color:red'> champ obligatoire</span>").insertAfter("#message");
				// return false;
			}
			else
			{
				$("#message").css("border","1px solid #DEB887");
				$("#span_message").html("");
			}
				
			
		});
		
		</script>
<?php include ('footer.php'); ?>