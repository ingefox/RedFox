<?php

return [
	'rules' => [
		'required' 			=> [
			'masculineA' 			=> 'Vous devez renseigner un {field}.',
			'masculineThe' 			=> 'Vous devez renseigner le {field}.',
			'feminineA' 			=> 'Vous devez renseigner une {field}.',
			'feminineThe' 			=> 'Vous devez renseigner la {field}.',
			'acceptMasculine' 		=> 'Vous devez accepter le {field}.',
			'acceptPlural' 			=> 'Vous devez accepter les {field}.',
			'acceptFeminine' 		=> 'Vous devez accepter la {field}.',
			'apostrophe' 			=> "Vous devez renseigner l'{field}."
		],
		'alpha_dash_space' 	=> [
			'masculine' 			=> 'Le {field} ne peut contenir que des lettres, des espaces et des tirets (-).',
			'feminine' 				=> 'La {field} ne peut contenir que des lettres, des espaces et des tirets (-).',
			'plural' 				=> 'Les {field} ne peuvent contenir que des lettres, des espaces et des tirets (-).',
		],
		'is_unique' 		=> [
			'masculine' 			=> 'Ce {field} est déjà utilisé.',
			'feminine' 				=> 'Cette {field} est déjà utilisée.',
			'apostrophe' 			=> "L'{field} est déjà utilisée.",
		],
		'numeric' 			=> [
			'masculine' 			=> 'Le {field} ne peut contenir que des chiffres.',
			'feminine' 				=> 'La {field} ne peut contenir que des chiffres.',
			'apostrophe' 			=> "L'{field} ne peut contenir que des chiffres.",
		],
		'valid' 			=> [
			'masculine' 			=> "Le {field} n'est pas valide.",
			'feminine' 				=> "La {field} n'est pas valide.",
			'apostrophe' 			=> "L'{field} n'est pas valide.",
		],
		'exact_length' 		=> [
			'masculine' 			=> "Le {field} doit avoir une taille exacte de {param} caractères.",
			'feminine' 				=> "La {field} doit avoir une taille exacte de {param} caractères.",
			'apostrophe' 			=> "L'{field} doit avoir une taille exacte de {param} caractères.",
		],
		'files' 			=> [
			'is_image' 				=> 'Votre fichier n\'est pas une image valide.',
			'max_size' 				=> 'Votre fichier doit avoir une taille inférieure à {0, number}mb',
		]
	],
];
