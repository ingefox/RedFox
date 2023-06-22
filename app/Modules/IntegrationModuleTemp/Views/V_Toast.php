<?php
/**
 * @var string $type
 * @var string $icon
 * @var string $title
 * @var string $message
 * @var bool $autohide
 * @var int $toastIndex
 */

switch ($type)
{
	case TOAST_OK:
		$class = 'toast-ok';
		$img = base_url('/public/img/alerts/ok.svg');
		break;
	case TOAST_ERROR:
		$class = 'toast-error';
		$img = base_url('/public/img/alerts/error.svg');
		break;
	case TOAST_DEFAULT:
	default:
		$class = '';
		$img = base_url('/public/img/alerts/info.svg');
		break;
}

$autoHideSetting = 'false';

if (isset($autohide))
{
	$autoHideSetting = $autohide ? 'true':'false';
}

if (isset($icon))
{
	$img = $icon;
}

?>

<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="<?= $autoHideSetting ?>" id="toast<?= $toastIndex ?>">
	<div class="toast-header <?= $class ?>">
		<img src="<?= $img ?>" class="rounded mr-2" alt="">
		<strong style="margin-left: 5px"><?= $title ?></strong>
		<small></small>
		<button type="button" class="close" data-dismiss="toast" aria-label="Close" style="margin-left: auto">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="toast-body">
		<?= $message ?>
	</div>
</div>
