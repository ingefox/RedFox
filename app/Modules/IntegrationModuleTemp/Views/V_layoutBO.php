<!doctype html>
<html lang="fr">
    <head>
        <link href="<?php echo base_url()?>/public/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/public/css/all.css" rel="stylesheet">

        <script type="text/javascript" src="<?php echo base_url()?>/public/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>/public/js/popper.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>/public/js/bootstrap.min.js"></script>

        <link href="<?php echo base_url()?>/public/css/dataTables.bootstrap4.min.css" rel="stylesheet">

        <script type="text/javascript" src="<?php echo base_url()?>/public/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>/public/js/dataTables.bootstrap4.min.js"></script>

		<script src="<?php echo base_url()?>/public/js/gijgo.js" type="text/javascript"></script>
		<link href="<?php echo base_url()?>/public/css/gijgo.min.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="<?php echo base_url()?>/public/js/funct.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>/public/js/jquery.caret.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>/public/js/print.min.js"></script>

		<link href="<?php echo base_url()?>/public/css/RFStyle.css" rel="stylesheet">

        <?php if(isset($script)) echo $script;?>

        <title><?php if(isset($title)) echo $title; else echo PROJECT_ID;?></title>

    </head>
    <body>

	<!-- The Modal  -->
	<div class="modal fade" id="RF_MODAL" tabindex="-1" role="dialog" aria-labelledby="RF_MODAL_LABEL"
		 aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-<?php echo THEME_COLOR; ?>">
        <a class="navbar-brand" href="<?php echo base_url()."/RF-BackOffice";?>">
            <img src="<?php echo base_url()."/public/".PROJECT_LOGO;?>" height="30" class="d-inline-block align-top" alt="">
            <?php echo PROJECT_ID; ?> - BACKOFFICE</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url()."/RF-BackOffice";?>">Accueil <span class="sr-only">(current)</span></a>
                </li>
                <?php
                    if (session()->get('logged_in_redfox')){
                        echo "<li class=\"nav-item dropdown\">
                        <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                            Admin BO
                        </a>
                        <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">
                            <a class=\"dropdown-item\" href=\"".base_url("RF-BackOffice/ModuleManager")."\">Gestion des modules</a>
                            <a class=\"dropdown-item\" href=\"".base_url("RF-BackOffice/ManageUserBO")."\">Gestion des utilisateurs BO</a>
                            <a class=\"dropdown-item\" href=\"".base_url("RF-BackOffice/ManageAPI")."\">Gestion des API</a>
                        </div>
                    </li>";
                        echo "<li class=\"nav-item dropdown\">
						<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
							Modules Manager
						</a>
						<div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">
							<a class=\"dropdown-item\" href=\"" . base_url("RF-BackOffice/ModuleManager") . "\">Gestion des Modules</a>
							<a class=\"dropdown-item\" href=\"" . base_url("RF-BackOffice/ModuleDownloader") . "\">Téléchargement de modules</a>
						</div>
						</li>";
                        echo '<li class="nav-item">
                        <a class="nav-link" href="'.base_url("RF-BackOffice/ManageDatabase").'" role=\"button\" aria-expanded=\"false\">
                            Database Manager
                        </a></li>';
                    }

                    echo $menu ?? '';
                ?>
            </ul>
            <?php if (session()->get('logged_in_redfox')){
                echo "<span class=\"navbar-text\"> <a href=\"#\"><b>".session()->get('email')."</b></a> |&nbsp;</span><span class=\"navbar-text\"><a href=\"".base_url("RF-BackOffice/BOUsers/logout")."\">Déconnexion <i class=\"fas fa-sign-out-alt text-white\"></i></a></span>";
            }
            else echo "<span class=\"navbar-text\"><a href=".base_url()."RF-BackOffice".">Connexion <i class=\"fas fa-sign-in-alt text-white\"></i></a></span>";
            ?>

        </div>
    </nav>

	<div class="container justify-content-center shadow bg-white rounded">
        <!-- Content -->
        <?php /** @var mixed $content */ echo $content; ?>
    </div>

    </body>

</html>
