<?php

return [
	'contact' 				=> [
		'subject' 				=> 'Demande de contact',
		'success' 				=> 'Votre email a correctement été envoyé',
		'error' 				=> "Une erreur est survenue lors de l'envoi de votre email. Veuillez réessayer ultérieurement.",
		'inputs' 				=> [
				'lastName' 			=> 'Nom',
				'firstName' 		=> 'Prénom',
				'company' 			=> 'Société',
				'position' 			=> 'Position',
				'email' 			=> 'Adresse email',
				'phone' 			=> 'Téléphone',
				'message' 			=> 'Message',
		]
	],
	'emailConfirmation' 	=> [
		'title' 				=> 'Merci pour votre inscription',
		'titleError' 			=> 'Une erreur est survenue',
		'text' 					=> 'Votre inscription a bien été prise en compte.<br><br> Vous pourrez à tout moment modifier ces informations dans l’espace Mon compte.',
		'textError' 			=> "Une erreur interne est survenue. Le lien de confirmation que vous avez utilisé est peut-être invalide ou expiré. Si ce n'est pas la cas, merci de prendre contact avec un responsable."
	],
	'steps' 				=> [
		'3' 					=> [
				'title' 				=> 'Réinitialisation de mot de passe',
				'newPwd'				=> 'Nouveau mot de passe',
				'confirmation' 			=> 'Confirmation du mot de passe',
				'backToMainPage' 		=> "Retour à l'accueil",
				'passwordNotice' 		=> 'Votre mot de passe doit au moins contenir :',
				'passwordNoticeBis' 	=> 'Les caractères spéciaux sont autorisés et conseillés',
				'pwdCheckLength' 		=> '7 caractères',
				'pwdCheckUpper' 		=> 'Une lettre majuscule',
				'pwdCheckLower' 		=> 'Une lettre minuscule',
				'pwdCheckNumber' 		=> 'Un chiffre',
				'pwdNotSecure' 			=> 'Votre mot de passe ne respecte pas les critères de sécurité minimum.',
				'pwdNotMatching' 		=> "Votre mot de passe de confirmation ne correspond pas à votre mot de passe.",
		]
	]
];
