<?php
/**
 * @var string $link
 * @var string $email
 */
?>
<html lang="fr">
	<body>
		<div>
			Bienvenue sur Maison Pauillac Traiteur !
			<br><br>
			Vous recevez ce message car un nouveau compte a été créé dans notre système avec l'adresse email suivante : '<?php echo $email; ?>'.
			<br><br>
			Merci de cliquer sur le lien suivant pour activer votre compte et définir votre mot de passe :
			<br><br>
			<?php echo $link;?>
			<br><br>
			Si vous avez reçu ce message par erreur, vous pouvez l'ignorer en toute sécurité.
			<br><br>
			Pour toutes questions éventuelles, merci d'envoyer un email à : <?php echo EMAIL_SUPPORT;?>
			<br><br>
			Merci,
			<br>
			<?php echo INTEGRATION_EMAIL_SIGNATURE;?>
		</div>
	</body>
</html>
