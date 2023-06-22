<?php

$menuItems = [];

switch (session()->get('roles'))
{
	case ROLE_ADMIN:
		$menuItems = [
				[
					'title' => 'Accueil',
					'link' => base_url(),
					'icon' => 'fas fa-home',
					'active' => !((current_url() !== base_url() . '/'))
				],
				[
					'title' => 'Gestion des utilisateurs',
					'link' => base_url('Users/manage'),
					'icon' => 'fas fa-users-cog',
					'active' => !((strpos(current_url(), base_url('Users/manage')) === false))
				]
		];
		break;
}

?>

<?php foreach($menuItems as $menuItem): ?>
	<a href="<?= $menuItem['link'] ?>" class="rf-menu-item">
		<div class="rf-menu-item-div <?= $menuItem['active'] ? 'rf-menu-item-active' : '' ?>">
			<div class="rf-menu-item-logo"><i class="<?= $menuItem['icon'] ?> rf-menu-item-logo"></i></div>
			<div class="rf-menu-item-title"><span><?= $menuItem['title'] ?></span></div>
		</div>
	</a>
<?php endforeach; ?>
