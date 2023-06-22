<?php
/**
 * @var string $link
 * @var string $email
 */
?>
<html lang="fr">
	<body>
		<div>
			Vous recevez ce message car vous (ou quelqu'un d'autre) a demandé une réinitialisation du mot de passe du compte '<?php echo $email; ?>'.
			<br><br>
			Merci de cliquer sur le lien suivant pour réinitialiser votre mot de passe :
			<br><br>
			<?php echo $link;?>
			<br><br>
			Si vous avez reçu ce message par erreur, vous pouvez simplement l'ignorer.
			<br><br>
			Pour toutes questions éventuelles, merci d'envoyer un email à : <?php echo EMAIL_SUPPORT;?>
			<br><br>
			Merci,
			<br>
			<?php echo INTEGRATION_EMAIL_SIGNATURE;?>
		</div>
	</body>
</html>
