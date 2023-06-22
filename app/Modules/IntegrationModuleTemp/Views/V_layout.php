<?php
/**
 * @var $content
 */
?>

<!doctype html>
<html lang="fr">

<head>

	<link rel="icon" href="<?= base_url('public/img/tab.png') ?>">

	<!-- CSS -->

	<link href="<?php echo base_url() ?>/public/css/minified.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>/public/css/tail.select/tail.select-default.min.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>/public/css/integration.css" rel="stylesheet">

	<!-- JS -->

	<script src="<?= base_url('/public/js/jquery-3.3.1.min.js') ?>"></script>
	<script src="<?= base_url('/public/js/popper.min.js') ?>"></script>
	<script src="<?= base_url('/public/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('/public/js/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('/public/js/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?= base_url('/public/js/vfs_fonts.js') ?>"></script>
	<script src="<?= base_url('/public/js/datatables.min.js') ?>"></script>
	<script src="<?= base_url('/public/js/moment-with-locales.js') ?>"></script>
	<script src="<?= base_url('/public/js/sha256.min.js') ?>"></script>
	<script src="https://kit.fontawesome.com/ec9f944071.js" crossorigin="anonymous"></script>
	<script src="<?= base_url('/public/js/minified.js') ?>"></script>

	<?= $script ?? '' ?>

	<title><?= $title ?? PROJECT_ID ?></title>
</head>

<body>

<!-- The Modal  -->
<div class="modal fade" id="RF_MODAL" tabindex="-1" role="dialog" aria-labelledby="RF_MODAL_LABEL"
	 aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" id="RF_MODAL_DIALOG">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="RF_MODAL_TITLE"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="RF_MODAL_BODY">
			</div>
		</div>
	</div>
</div>

<div class="row m-0 h-100" id="masterContainer">

	<!-- ============================================================ -->
	<!-- ============================================================ -->
	<!-- HEADER -->
	<!-- ============================================================ -->
	<!-- ============================================================ -->

	<?php if (!isset($hideMenu) || (!$hideMenu)): ?>
		<div class="global-header-wrapper">

			<!-- PROJECT NAME -->
			<div class="header-project-name"><?= PROJECT_ID ?></div>

			<!-- PAGE TITLE -->
			<div class="header-page-title"><?= $headerTitle ?? $title ?? '' ?></div>

			<!-- USER CONTROLS -->
			<div class="header-user-controls clickable" role="button" id="dropdownMenuLink" data-toggle="dropdown"
				 aria-haspopup="true" aria-expanded="false">

				<!-- Username + role -->
				<div class="header-username-container">
					<div class="header-username"><?= session()->get('firstname').' '.strtoupper(session()->get('lastname') ?? '') ?></div>
					<div class="header-role"><?= ROLES_ARRAY_STR[session()->get('roles')] ?? '' ?></div>
				</div>

				<!-- User profile picture -->
				<div class="header-user-profile-picture">
					<img src="<?= session()->get('avatar') ?? base_url(DEFAULT_AVATAR) ?>" onerror="this.src='<?= base_url(DEFAULT_AVATAR) ?>'" alt="">
				</div>
			</div>

			<!-- USER CONTROLS DROPDOWN MENU -->
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
				<?php if ((!isset($hideSidebar) || !$hideSidebar)):?>
					<a class="dropdown-item" href="<?= base_url('Users/editFullPage'); ?>"><i class="far fa-user-circle"></i>Mon compte</a>
				<?php endif;?>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="<?= base_url('Users/logout') ?>"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
			</div>
		</div>
	<?php else:?>
		<div class="global-header-wrapper-no-shadow" style="visibility: hidden">
		</div>
	<?php endif;?>

	<!-- ============================================================ -->
	<!-- ============================================================ -->
	<!-- CONTENT -->
	<!-- ============================================================ -->
	<!-- ============================================================ -->

	<div class="m-0 pr-0 pl-0 main-container ">

		<!-- MENU -->
		<?php if (session()->has('logged_in') && session()->get('logged_in') && (!isset($hideSidebar) || !$hideSidebar)): ?>
			<div class="rf-menu">
				<?= view(VIEW_MENU) ?>
				<a href="javascript:;" class="rf-menu-item" onclick="toggleMenu()">
					<div class="rf-menu-item-div">
						<i class="rf-menu-toggle rf-menu-item-logo fas fa-chevron-right"></i>
					</div>
				</a>
			</div>
		<?php endif;?>

		<!-- TOAST CONTAINER -->
		<div style="position: absolute; top: 100px; right: 40px" id="toast-container">
		</div>

		<!-- CONTENT -->
		<div class="rf-container">
			<?= $content; ?>
		</div>
	</div>

	<!-- ============================================================ -->
	<!-- ============================================================ -->
	<!-- FOOTER -->
	<!-- ============================================================ -->
	<!-- ============================================================ -->

	<div class="rf-footer">
		<div class="footer-home-link">
			<a href="#"><?= PROJECT_ID ?></a>
		</div>

		<div class="footer-env">
			PRÉ-PRODUCTION
		</div>

		<div class="footer-useful-links">
			<?php if (session()->get(SESSION_KEY_LOGGED_IN)): ?>
				<!-- Bug report -->
				<div class="footer-bug-report"><a href="#" onclick="openModal('<?= base_url('Mantis/displayIssueForm') ?>','Signaler un problème')" title="Signaler un problème"><i class="far fa-life-ring"></i></i></a></div>
			<?php endif; ?>

<!--			<a href="#" onclick="unavailableFeature()">Mentions légales</a>-->
<!--			<a href="#" onclick="unavailableFeature()">CGV</a>-->
<!--			<a href="#" onclick="unavailableFeature()">Crédits</a>-->

<!--			<span class="footer-social-networks">-->
<!--					<a href="#" onclick="unavailableFeature()"><i class="fab fa-linkedin"></i></a>-->
<!--					<a href="#" onclick="unavailableFeature()"><i class="fab fa-facebook-square"></i></a>-->
<!--					<a href="#" onclick="unavailableFeature()"><i class="fab fa-instagram-square"></i></a>-->
<!--			</span>-->
		</div>
	</div>
</div>

<div class="loading-modal" style="z-index: 10000"></div>

</body>

</html>

<script>
	let menu 			= $('.rf-menu');
	const menuToggle 	= $('.rf-menu-toggle');

	// Set to "true" to prevent the loading animation displayed when sending ajax requests
	let disableLoadingAnimation = false;

	let toastIndex = 0;

	$(document).ready(function () {
		<?php
		if (session()->has('alert')){
			echo 'openModal("'.base_url('Users/alert').'","Notification",{"type" : "'.session()->get('alert')['type'].'", "msg" : "'.session()->get('alert')['msg'].'"},true);';
			session()->remove('alert');
		}
		?>

		// Set the initial state of the menu
		if (localStorage.getItem('menuExpanded') === 'true')
		{
			// Disabling the transition animation for the first toggle
			menu.addClass('rf-menu-no-animation');
			toggleMenu();
		}
	})

	/**
	 * Toggle the menu's expansion
	 */
	function toggleMenu()
	{
		menu.toggleClass('rf-menu-expanded');
		menuToggle.toggleClass('fa-chevron-left');
		menuToggle.toggleClass('fa-chevron-right');

		// Save the state of the menu in the local storage for the next page loading
		localStorage.setItem('menuExpanded', menu.hasClass('rf-menu-expanded'));

		// The 'rf-menu-no-animation' class is used to prevent the menu from animating when the page is loaded
		// It is removed after the first toggle
		if(menu.hasClass('rf-menu-no-animation'))
		{
			// A timeout is needed in order to prevent a subtle "jump" when the menu is expanded for the first time without animation
			setTimeout(function () {
				menu.removeClass('rf-menu-no-animation');
			}, 500);
		}
	}

	$(document).on({
		fetchStart: function() {
			if (!disableLoadingAnimation){
				$('body').addClass("loading");
			}
		},
		ajaxStart: function() {
			if (!disableLoadingAnimation){
				$('body').addClass("loading");
			}
		},
		ajaxStop: function() {
			$('body').removeClass("loading");
		}
	});
</script>
