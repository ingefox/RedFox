<!doctype html>
<html lang="fr">

<head>

	<link rel="icon" href="<?= base_url('public/img/tab.svg') ?>">

	<!-- CSS -->

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		  integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!--	<link href="--><?php //echo base_url() ?><!--/public/css/bootstrap.min.css" rel="stylesheet">-->
	<link href="<?php echo base_url() ?>/public/css/all.css" rel="stylesheet">
	<!--	<script src="--><?php //echo base_url() ?><!--/public/js/all.js"></script>-->
	<!--	<link rel="icon" href="--><?php //echo base_url('public/img/icon.png') ?><!--">-->

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
		  rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<link href="<?php echo base_url() ?>/public/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>/public/css/bootstrap-steps.min.css" rel="stylesheet">
	<link href="<?php echo base_url() ?>/public/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<link rel="stylesheet" type="text/css"
		  href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.css"/>

	<link rel="stylesheet" href="<?php echo base_url() ?>/public/js/trumbowyg/dist/ui/trumbowyg.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css">

	<link href="<?php echo base_url() ?>/public/css/RFStyle.css?v=2.1" rel="stylesheet">

	<!-- JS -->

	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/jquery-clock-timepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
			integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
			crossorigin="anonymous"></script>

	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/funct.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/jquery.caret.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/print.min.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<script src="<?php echo base_url() ?>/public/js/gijgo.js" type="text/javascript"></script>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/datepicker-fr.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
			integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
			crossorigin="anonymous"></script>

	<!--	<script type="text/javascript" src="--><?php //echo base_url() ?><!--/public/js/popper.min.js"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"
			integrity="sha256-cVdRFpfbdE04SloqhkavI/PJBWCr+TuyQP3WkLKaiYo=" crossorigin="anonymous"></script>

	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/trumbowyg/dist/trumbowyg.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css">
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/template/trumbowyg.template.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/langs/fr.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/upload/trumbowyg.upload.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css">
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/pasteimage/trumbowyg.pasteimage.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/resizimg/resizable-resolveconflict.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/table/trumbowyg.table.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/jquery-resizable/dist/jquery-resizable.min.js"></script>
	<script src="<?php echo base_url() ?>/public/js/trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min.js"></script>

	<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
	<!--	<script src="https://js.stripe.com/v3/"></script>-->

	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://kit.fontawesome.com/ec9f944071.js" crossorigin="anonymous"></script>

	<link href="<?= base_url('public/css/tail.select/tail.select-default.css') ?>" rel="stylesheet">
	<script type="text/javascript" src="<?= base_url('public/js/tail.select-full.min.js') ?>"></script>

	<!--	DATATABLES -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript"
			src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sp-1.0.1/sl-1.3.1/datatables.min.js"></script>
	<!--	DATATABLES -->
	<script type="text/javascript" src="<?php echo base_url() ?>/public/js/moment-with-locales.js"></script>

	<?php if (isset($script)) echo $script; ?>

	<title><?= isset($title) ? $title : PROJECT_ID ?></title>
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
	<div class="global-header-wrapper">
		<div class="col">
			<img class="global-header-logo" src="<?= base_url('/public/'.PROJECT_LOGO) ?>"
				 alt="Logo LC-Solution">
		</div>
		<div class="col">
			<span class="page-header-title">Maintenance</span>
		</div>
		<div class="col global-header-right">
		</div>
	</div>


	<div class="m-0 pr-0 pl-0 main-container ">
		<div class="rf-container">
			<!-- Content -->
			<div class="page-content">
				<div class="col-6 offset-3 d-flex bg-accent-20 justify-content-around align-items-center px-3 py-3">
					<img src="<?= base_url('/public/img/icons/info.png') ?>" alt="Icône" height="60px">
					<p class="col-10 font-italic text-muted">Le site LC-Solution est temporairement en maintenance.
						<br><br>Merci de réessayer ultérieurement.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="loading-modal" style="z-index: 10000"></div>


</body>

</html>

<script>
	var menuExpanded = false;
	var menuToggle = $('.rf-menu-toggle');
	var containerInitialWidth;
</script>
