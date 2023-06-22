<?php

return [
	'manage' 		=> [
		'title'					=> 'Gestion des utilisateurs'
	],
	'delete' 		=> [
		'success' 				=> 'Utilisateur supprimé',
		'notFound' 				=> 'Aucun utilisateur trouvé',
	],
	'edit' 			=> [
		'title' 				=> 'Mes coordonnées',
		'userForm' 				=> 'Fiche utilisateur',
		'confirmationText' 		=> 'Les informations ont été correctement enregistrées.',
		'messages'				=> [
			'success' 			=> 'Utilisateur modifié',
			'error' 			=> 'Erreur lors de la modification de l\'utilisateur',
		]
	],
	'register' 		=> [
		'title' 				=> 'Inscription',
		'password' 				=> 'Mot de passe',
		'errorTitle' 			=> 'Erreur',
		'errorText' 			=> 'Une erreur interne est survenue, merci de réessayer ultérieurement. ',
		'confirmationTitle' 	=> 'Confirmation de l’e-mail',
		'confirmationText' 		=> 'Un e-mail de confirmation viens de vous être envoyé. Merci de cliquer sur le lien qu’il contient afin de valider votre adresse e-mail. ',
		'notice' 				=> '* Ces champs sont obligatoires',
		'pwdNotMatching' 		=> "Votre mot de passe de confirmation ne correspond pas à votre mot de passe.",
		'phoneNotValid' 		=> "Le numéro de téléphone n'est pas valide.",
		'messages'				=> [
			'success' 			=> 'Utilisateur créé',
			'error' 			=> 'Erreur lors de la création de l\'utilisateur',
		]
	],
	'fields' 		=> [
		'id' 					=> 'ID',
		'firstname' 			=> 'Prénom',
		'lastname' 				=> 'Nom',
		'email' 				=> 'Adresse e-mail',
		'phone' 				=> 'Numéro de téléphone',
		'roles' 				=> 'Rôles',
		'password' 				=> 'Mot de passe',
		'passwordConf' 			=> 'Confirmation du mot de passe',
		'CGUValidated1' 		=> "J'ai lu et j'accepte les ",
		'CGUValidated2' 		=> "Conditions Générales d'Utilisation",
	]
];
