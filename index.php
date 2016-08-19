<?php
require_once("./core/Application.php");

Application::getInstance()->init();

$currentFolder = FileSystem::getCurrentFolder();

$folders = $currentFolder->getFolderChildren();
$files = $currentFolder->getFileChildren();

$element = 0;

/**
 *
 * @global int $element
 */
function displayRow() {
	global $element;

	if ($element % 6 === 0) {
		if ($element > 1) {
			echo '</div>' . PHP_EOL;
		}

		echo '<div class="row">' . PHP_EOL;
	}

	$element++;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<title><?php echo Application::getInstance()->getName() ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Author" content="Matschieu" />
	<link rel="icon" type="image/png" href="<?php echo Application::getInstance()->getFavicon() ?>" />
	<link rel="stylesheet" type="text/css" href="./styles/default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./styles/custom.css" media="screen" />

	<!--Bootstrap -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Application styles -->
	<link rel="stylesheet" type="text/css" href="./styles/default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Application::getInstance()->getCustomCss() ?>" media="screen" />
</head>

<body>

	<!-- HEADER -->
	<div id="header">
		<?php echo Application::getInstance()->getHeader() ?>
	</div>

	<!-- MENU -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo Application::getInstance()->getUrl() ?>" >
					<span class="glyphicon glyphicon-home"></span> Root
				</a>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li>
						<a href="#" onclick="javascript:window.location.reload();">
							<span class="glyphicon glyphicon-refresh"></span> Refresh
						</a>
					</li>
					<li>
						<a href="#" onclick="javascript:window.history.back();">
							<span class="glyphicon glyphicon-arrow-left"></span> Back
						</a>
					</li>
					<li>
						<a href="#" onclick="javascript:window.history.forward();">
							<span class="glyphicon glyphicon-arrow-right"></span> Next
						</a>
					</li>
					<?php if (Application::getInstance()->getViewContext() == Application::VIEW_LIST) { ?>
					<li>
						<a href="<?php echo Application::getInstance()->getParameterizedUrl($currentFolder, Application::VIEW_BLOCK) ?>">
							<span class="glyphicon glyphicon-th"></span> Block view
						</a>
					</li>
					<?php } else {?>
					<li>
						<a href="<?php echo Application::getInstance()->getParameterizedUrl($currentFolder, Application::VIEW_LIST) ?>">
							<span class="glyphicon glyphicon-list"></span> List view
						</a>
					</li>
					<?php } ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#" onclick="javascript:open(location, '_self').close(); return true;">
							<span class="glyphicon glyphicon-remove"></span> Close
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- FILE EXPLORER CONTENT -->
	<div id="content" class="container-fluid">

		<!-- TOP STATE BAR -->
		<div id="statebarTop" class="row bg-primary">
			<div class="col-md-12">
				<span class="glyphicon glyphicon-folder-open"></span>
				Navigation in <?php echo $currentFolder->getLogicalPath() ?>
			</div>
		</div>

		<?php if (Application::getInstance()->getViewContext() == Application::VIEW_LIST) { ?>

		<!-- LIST VIEW -->
		<table id="list" class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Type</th>
					<th>Size</th>
					<th>Date</th>
					<th>Actions</th>
				</tr>
			</thead>

			<!-- FOLDERS -->
			<?php foreach ($folders as $folder) { ?>
			<tr class="folder">
				<td class="icon">
					<span class="glyphicon <?php echo $folder->getGlyphicon() ?>"></span>
				</td>
				<td>
					<a href="<?php echo Application::getInstance()->getParameterizedUrl($folder) ?>">
						<?php echo $folder->getDisplayName() ?>
					</a>
				</td>
				<td></td>
				<td>
					<?php echo FileSystem::convertSize($folder->getSize()) ?>
				</td>
				<td>
					<?php echo $folder->getDate() ?>
				</td>
				<td>
					<a href="<?php echo Application::getInstance()->getParameterizedUrl($folder) ?>" title="Open folder">
							<span class="glyphicon glyphicon-circle-arrow-right"></span></a>
				</td>
			</tr>
			<?php } ?>

			<!--FILES -->
			<?php foreach ($files as $file) { ?>
			<tr class="file">
				<td class="icon">
					<span class="glyphicon <?php echo $file->getGlyphicon() ?>"></span>
				</td>
				<td>
					<a href="<?php echo $file->getUrl() ?>" target="_new">
						<?php echo $file->getName() ?>
					</a>
				</td>
				<td>
					<span class="label label-info <?php echo $file->getExtension() ?>">
						<?php echo $file->getExtension() ?>
					</span>
				</td>
				<td>
					<?php echo FileSystem::convertSize($file->getSize()) ?>
				</td>
				<td>
					<?php echo $file->getDate() ?>
				</td>
				<td>
					<a href="<?php echo $file->getUrl() ?>" target="_<?php echo $file->getName() ?>" title="Open file in new window">
						<span class="glyphicon glyphicon-new-window"></span></a>
					<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="Save file">
						<span class="glyphicon glyphicon-save"></span></a>
				</td>
			</tr>
			<?php } ?>
		</table>

		<?php } else { ?>

		<!-- BLOCK VIEW -->
		<div id="list" class="row">
			<!-- FOLDERS -->
			<?php foreach ($folders as $folder) { ?>
			<?php displayRow(); ?>
			<div class="folder col-md-2">
				<div class="row">
					<div class="col-md-3">
						<div class="icon">
							<span class="glyphicon <?php echo $folder->getGlyphicon() ?>"></span>
						</div>
					</div>
					<div class="info col-md-offset-3">
						<a href="<?php echo Application::getInstance()->getParameterizedUrl($folder) ?>">
							<?php echo $folder->getDisplayName() ?>
						</a><br />
						<p><?php echo FileSystem::convertSize($folder->getSize()) ?></p>
						<p><?php echo $folder->getDate() ?></p>
						<a href="<?php echo Application::getInstance()->getParameterizedUrl($folder) ?>" title="Open folder">
							<span class="glyphicon glyphicon-circle-arrow-right"></span></a>
					</div>
				</div>
			</div>
			<?php } ?>

			<!--FILES -->
			<?php foreach ($files as $file) { ?>
			<?php displayRow(); ?>
			<div class="file col-md-2">
				<div class="row">
					<div class="col-md-3">
						<div class="icon">
							<span class="glyphicon <?php echo $file->getGlyphicon() ?>"></span>
						</div>
						<span class="label label-info <?php echo $file->getExtension() ?>">
							<?php echo $file->getExtension() ?>
						</span>
					</div>
					<div class="info col-md-offset-3">
						<a href="<?php echo $file->getUrl() ?>" target="_new">
							<?php echo $file->getName() ?>
						</a><br />
						<p><?php echo FileSystem::convertSize($file->getSize()) ?></p>
						<p><?php echo $file->getDate() ?></p>
						<a href="<?php echo $file->getUrl() ?>" target="_<?php echo $file->getName() ?>" title="Open file in new window">
							<span class="glyphicon glyphicon-new-window"></span></a>
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="Save file">
							<span class="glyphicon glyphicon-save"></span></a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>

		<?php } ?>

		<!-- EMPTY CONTENT MESSAGE -->
		<?php if (count($folders) === 0 && count($files) === 0) { ?>
		<div id="noContent" class="row">
			<div class="col-md-12">
				<div class="alert alert-warning">
					No content to display
				</div>
			</div>
			<div class="col-md-offset-11">
				<a class="btn btn-default" href="#" onclick="javascript:window.history.back();">
					<span class="glyphicon glyphicon-arrow-left"></span> Back
				</a>
			</div>
		</div>
		<?php } ?>

		<!-- BOTTOM STATE BAR -->
		<div id="statebarBottom" class="row bg-primary">
			<div class="col-md-12">
				<span class="glyphicon glyphicon-stats"></span>
				<?php echo $currentFolder->getDisplayFoldersInfo() ?> -
				<?php echo $currentFolder->getDisplayFilesInfo() ?> -
				<?php echo FileSystem::convertSize($currentFolder->getChildrenSize()) ?>
			</div>
		</div>
	</div>

	<!-- FOOTER -->
	<div id="footer">
		<?php echo Application::getInstance()->getFooter() ?>
	</div>

</body>

</html>
