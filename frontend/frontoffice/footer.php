	<script>
	$(document).ready(function(){
		if (getCookie('newsletter')=='non' || getCookie('newsletter')=='oui')
			$("#alert").hide();	
		$( "#menu-newsletter" ).click(function() {
			 $( "#alert" ).css("display", "block");
		});
	})
	</script>
	<?php include('register_newsletter.inc.php'); ?>
	<footer>
		<div id="menu-newsletter" class="newsletter">
			<a href="#" class="liens_user">S'inscrire à la newsletter</a>
		</div>
		<div class='liens_foot'>
			<a href='about.php'>FunFoot</a>
			<a href='contact.php'>Contact</a>
			<a href='cgv.php'>CGV</a>
		</div>
		<div class='social'>
			<a href=''><img src="images/fb.png"/></a>
			<a href=''><img src="images/tw.png" width='24px'/></a>
			<a href=''><img src="images/tumblr.png"/></a>
		</div>
	</footer>
	
</body>
</html>