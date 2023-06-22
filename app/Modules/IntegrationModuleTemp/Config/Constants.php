<?php

// =====================================================================================================================
// =====================================================================================================================
// GLOBAL CONSTANTS
// =====================================================================================================================
// =====================================================================================================================

// Project name
const PROJECT_ID = 'RedFox';

// Logos
const PROJECT_LOGO = 'public/img/Logo Ingefox Round v2.png';
const PROJECT_LOGO_MAINTENANCE = 'img/logo-red.png';

// Default language
const LANGUAGE = 'french';

/**
 * Global theme color (primarily used for RedFox BackOffice)
 * @see https://getbootstrap.com/docs/4.0/utilities/colors/
 */
const THEME_COLOR = 'custom';

// Default avatar that will be used if the user has not set one
const DEFAULT_AVATAR = '/public/img/icons/avatar.svg';

// =====================================================================================================================
// =====================================================================================================================
// PUBLIC FILES REFERENCES
// =====================================================================================================================
// =====================================================================================================================

const PUBLIC_FOLDER_PATH = ROOTPATH.'public'.DIRECTORY_SEPARATOR;

const CSS_FILES = [
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'bootstrap.min.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'all.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'Montserrat.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'font-awesome.min.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.min.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'bootstrap-steps.min.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'gijgo.min.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'jquery-ui.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'datatables.min.css',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'ui'.DIRECTORY_SEPARATOR.'trumbowyg.min.css',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'table'.DIRECTORY_SEPARATOR.'ui'.DIRECTORY_SEPARATOR.'trumbowyg.min.css',
	PUBLIC_FOLDER_PATH.'css'.DIRECTORY_SEPARATOR.'RFStyle.css',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'colors'.DIRECTORY_SEPARATOR.'trumbowyg.colors.min.css',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'colors'.DIRECTORY_SEPARATOR.'ui'.DIRECTORY_SEPARATOR.'trumbowyg.colors.min.css',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'tail.select'.DIRECTORY_SEPARATOR.'tail.select-default.css',
];

const JS_FILES = [
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'jquery-3.3.1.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'jquery-clock-timepicker.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'popper.min.js',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'funct.js',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'jquery.caret.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'print.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'api.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'gijgo.js',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'jquery-ui.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'datepicker-fr.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'bootstrap.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'sha256.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'trumbowyg.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'cleanpaste'.DIRECTORY_SEPARATOR.'trumbowyg.cleanpaste.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'colors'.DIRECTORY_SEPARATOR.'trumbowyg.colors.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'trumbowyg.template.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'langs'.DIRECTORY_SEPARATOR.'fr.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'trumbowyg.upload.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'pasteimage'.DIRECTORY_SEPARATOR.'trumbowyg.pasteimage.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'resizimg'.DIRECTORY_SEPARATOR.'resizable-resolveconflict.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'fontsize'.DIRECTORY_SEPARATOR.'trumbowyg.fontsize.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'fontfamily'.DIRECTORY_SEPARATOR.'trumbowyg.fontfamily.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'table'.DIRECTORY_SEPARATOR.'trumbowyg.table.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'trumbowyg'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'resizimg'.DIRECTORY_SEPARATOR.'trumbowyg.resizimg.min.js',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'jquery-resizable'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'jquery-resizable.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'jquery.dataTables.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'ec9f944071.js',
	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'tail.select-full.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'pdfmake.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'vfs_fonts.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'datatables.min.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'moment-with-locales.js',
//	PUBLIC_FOLDER_PATH.'js'.DIRECTORY_SEPARATOR.'UraTable'.DIRECTORY_SEPARATOR.'UraTable.js',
];

// =====================================================================================================================
// =====================================================================================================================
// VIEW RELATED FILE REFERENCES
// =====================================================================================================================
// =====================================================================================================================

const HOME_PAGE = INTEGRATION_BASE_MODULE . '\Views\V_Home';
const LOGIN_PAGE = INTEGRATION_BASE_MODULE . '\Views\V_Login';
const REGISTER_PAGE = 'RFCore\Views\Users\V_UserRegisterForm';

// General menu view
const VIEW_MENU = INTEGRATION_BASE_MODULE . '\Views\V_Menu';

// Password reset related views
const FORM_FORGOTTEN_PWD_STEP_1 = 'RFCore\Views\Users\V_UserForgottenPwdStep1';
const FORM_FORGOTTEN_PWD_STEP_2 = 'RFCore\Views\Users\V_UserForgottenPwdStep2';
const FORM_FORGOTTEN_PWD_STEP_3 = 'RFCore\Views\Users\V_UserForgottenPwdStep3';
const FORM_FORGOTTEN_PWD_STEP_4 = 'RFCore\Views\Users\V_UserForgottenPwdStep4';

const FORM_NEW_PWD = INTEGRATION_BASE_MODULE.'\Views\Users\V_UserNewPwdForm';

// List of URLs accessible without being logged in (without an existing user session)
const AUTHORIZED_URLS_NO_SESSION = [
	LOGIN_PAGE,
	HOME_PAGE,
	REGISTER_PAGE,
	FORM_FORGOTTEN_PWD_STEP_3,
	FORM_FORGOTTEN_PWD_STEP_4,
	FORM_NEW_PWD,
];

// =====================================================================================================================
// =====================================================================================================================
// TOAST
// =====================================================================================================================
// =====================================================================================================================

// Toast type values
$index = 1;
define('TOAST_OK', $index++);
define('TOAST_ERROR', $index++);
define('TOAST_DEFAULT', $index++);

// Default delay in ms before a toast is hidden
const TOAST_DEFAULT_DELAY = 5000;
const TOAST_REFRESHING_DELAY = 5;

// =====================================================================================================================
// =====================================================================================================================
// USER ROLES
// =====================================================================================================================
// =====================================================================================================================

// Roles binary value
const ROLE_USER = 1;
const ROLE_ADMIN = ROLE_USER << 1;

// Roles associated labels
const ROLES_ARRAY_STR = [
	ROLE_USER                   => 'Utilisateur',           // 1
	ROLE_ADMIN                  => 'Administrateur',        // 2
];

// =====================================================================================================================
// =====================================================================================================================
// COMFOX + EMAILS RELATED CONSTANTS
// =====================================================================================================================
// =====================================================================================================================

// Indicates to methods using the ComFox module if it is available
const COMFOX_AVAILABLE = FALSE;

// Route used for sending account verification emails
const ROUTE_VERIF_EMAIL_ACCOUNT = 'userVerifAccount';

// Registration confirmation email content
const INTEGRATION_REGISTER_EMAIL_TITLE = PROJECT_ID . ': Validation de votre compte';
const INTEGRATION_REGISTER_EMAIL_MESSAGE = '<html lang="fr"><body>Bonjour<br/>Merci de cliquer sur le lien suivant pour valider votre compte :<br/>';

// Default signature used for emails
const INTEGRATION_EMAIL_SIGNATURE = 'L\'équipe ' . PROJECT_ID;

// Support contact email address
const EMAIL_SUPPORT = 'projet@ingefox.com'; // TODO - Update before production deployment

// Password reset related constants
const INTEGRATION_RENEW_PWD_USER_REQUEST = 1;
const INTEGRATION_RENEW_PWD_MANAGER_REQUEST = INTEGRATION_RENEW_PWD_USER_REQUEST + 1;

// Password reset + update routes
const ROUTE_UPDATE_PWD_ACCOUNT = 'Users/forgottenPassword';
const ROUTE_NEW_PWD_ACCOUNT = 'Users/newPassword';

// Forgotten password emails content
const INTEGRATION_UPDATE_PWD_EMAIL_TITLE = PROJECT_ID . ' : Mot de passe oublié';
const INTEGRATION_UPDATE_PWD_EMAIL_MESSAGE = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserForgottenPwdEmail';

// User account registration / activation emails content
const INTEGRATION_NEW_PWD_EMAIL_TITLE = PROJECT_ID . ' : Inscription';
const INTEGRATION_NEW_PWD_EMAIL_MESSAGE = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserActivationEmail';

// User account creation confirmation emails content (new account)
const INTEGRATION_UPDATE_PWD_EMAIL_TITLE_NEW_USER = PROJECT_ID . ' : Nouveau Compte';
const INTEGRATION_UPDATE_PWD_EMAIL_MESSAGE_NEW_USER = '<html lang="fr"><body>Bonjour<br/>Merci de cliquer sur le lien suivant pour valider votre nouveau compte ' . PROJECT_ID . ' :<br/>';

// Authentication action used in verification emails
const AUTH_ACTION_EMAIL_VERIFY = 'verifyEmail';
const AUTH_ACTION_RESET_PASSWORD = 'resetPassword';
const AUTH_ACTION_ = '';

// =====================================================================================================================
// =====================================================================================================================
// USER SESSIONS RELATED CONSTANTS
// =====================================================================================================================
// =====================================================================================================================

const SESSION_REMEMBER_ME   = 'SESSION_REMEMBER_ME';

// =====================================================================================================================
// =====================================================================================================================
// STATUS CODE
// =====================================================================================================================
// =====================================================================================================================

const SC_INTEGRATION_START = SC_REDFOX_MAX_VALUE + 1;
const SC_INTEGRATION_ERROR = SC_INTEGRATION_START + 1;

// User related errors status codes
const SC_INTEGRATION_USER_ALREADY_EXIST = SC_INTEGRATION_ERROR + 1;
const SC_INTEGRATION_USER_UNKNOWN = SC_INTEGRATION_USER_ALREADY_EXIST + 1;
const SC_INTEGRATION_USER_UPDATE_ERROR = SC_INTEGRATION_USER_UNKNOWN + 1;
const SC_INTEGRATION_USER_DISABLE = SC_INTEGRATION_USER_UPDATE_ERROR + 1;

// Email related errors status codes
const SC_INTEGRATION_EMAIL_SEND_ERROR = SC_INTEGRATION_USER_DISABLE + 1;
const SC_INTEGRATION_CHECK_BAD_TOKEN = SC_INTEGRATION_EMAIL_SEND_ERROR + 1;
const SC_INTEGRATION_DB_UPDATE_PROBLEM = SC_INTEGRATION_CHECK_BAD_TOKEN + 1;

// =====================================================================================================================
// =====================================================================================================================
// EXTERNAL API / LIBRARIES
// =====================================================================================================================
// =====================================================================================================================

// ReCaptcha
// @see https://www.google.com/recaptcha/admin/
const RECAPTCHA_PUBLIC_KEY = ''; // TODO - Set value
const RECAPTCHA_SECRET_KEY = ''; // TODO - Set value

// Mantis
const INT_MANTIS_PROJECT_ID = null;
const INT_MANTIS_FEATURE_REFERENCES = [
	'Utilisateurs' => [
		'Création de compte',
		'Suppression de compte',
		'Modification de compte',
		'Liste des utilisateurs',
		'Autre',
	]
];
