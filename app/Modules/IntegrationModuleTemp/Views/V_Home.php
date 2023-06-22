<div class="page-content">
	<div class="row justify-content-center" >
		<div class="col text-center">
			<h1 class="headline text-accent" style="font-size: 80px"><i class="fas fa-home"></i></h1>
			<br>
			<h1 class="headline text-accent">Bienvenue sur <strong><?= PROJECT_ID ?></strong></h1>
			<br>
			<span style="font-weight: initial">Vous êtes sur l'interface <strong><?= ROLES_ARRAY_STR[session()->get('roles')];?></strong></span>
		</div>
	</div>
</div>
