<?php
require_once("./core/Application.php");

Application::build()->init();

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
	<title><?php echo Application::build()->getName() ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Author" content="Matschieu" />
	<link rel="icon" type="image/png" href="<?php echo Application::build()->getFavicon() ?>" />
	<link rel="stylesheet" type="text/css" href="./styles/default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./styles/custom.css" media="screen" />
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
	<!--Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- Application styles -->
	<link rel="stylesheet" type="text/css" href="./styles/default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo Application::build()->getCustomCss() ?>" media="screen" />
</head>

<body>

	<!-- HEADER -->
	<div id="header">
		<?php echo Application::build()->getHeader() ?>
	</div>

	<!-- MENU -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo Application::build()->getRootUrl() ?>" >
					<span class="glyphicon glyphicon-home"></span>
					<?php echo Translation::get('menu.root') ?>
				</a>
			</div>

			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li>
						<a href="#" data-toggle="modal" data-target="#folderTreeModal">
							<span class="glyphicon glyphicon-star"></span>
							<?php echo Translation::get('menu.folderTree') ?>
						</a>
					</li>
					<li>
						<a href="#" onclick="javascript:window.location.reload();">
							<span class="glyphicon glyphicon-refresh"></span>
							<?php echo Translation::get('menu.refresh') ?>
						</a>
					</li>
					<li>
						<a href="#" onclick="javascript:window.history.back();">
							<span class="glyphicon glyphicon-arrow-left"></span>
							<?php echo Translation::get('menu.back') ?>
						</a>
					</li>
					<li>
						<a href="#" onclick="javascript:window.history.forward();">
							<span class="glyphicon glyphicon-arrow-right"></span>
							<?php echo Translation::get('menu.next') ?>
						</a>
					</li>
					<?php if (Application::build()->getViewContext() == Application::VIEW_LIST) { ?>
					<li>
						<a href="<?php echo Application::build()->getChangeViewUrl(Application::VIEW_BLOCK) ?>">
							<span class="glyphicon glyphicon-th"></span>
							<?php echo Translation::get('menu.blockView') ?>
						</a>
					</li>
					<?php } else {?>
					<li>
						<a href="<?php echo Application::build()->getChangeViewUrl(Application::VIEW_LIST) ?>">
							<span class="glyphicon glyphicon-list"></span>
							<?php echo Translation::get('menu.listView') ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="glyphicon glyphicon-flag"></span>
							Language
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo Application::build()->getChangeLanguageUrl("en") ?>">
									<?php echo Translation::get('menu.english') ?>
									<?php if (Application::build()->getLanguageContext() === "en") { ?>
									<span class="glyphicon glyphicon-ok-circle"></span>
									<?php } ?>
								</a>
							</li>
							<li>
								<a href="<?php echo Application::build()->getChangeLanguageUrl("fr") ?>">
									<?php echo Translation::get('menu.french') ?>
									<?php if (Application::build()->getLanguageContext() === "fr") { ?>
									<span class="glyphicon glyphicon-ok-circle"></span>
									<?php } ?>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#" onclick="javascript:open(location, '_self').close(); return true;">
							<span class="glyphicon glyphicon-remove"></span>
							<?php echo Translation::get('menu.close') ?>
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
				<?php echo Translation::get('statebar.navigation') . $currentFolder->getLogicalPath() ?>
			</div>
		</div>

		<?php if (Application::build()->getViewContext() == Application::VIEW_LIST) { ?>

		<!-- LIST VIEW -->
		<div id="list" class="row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th><?php echo Translation::get('content.name') ?></th>
						<th><?php echo Translation::get('content.type') ?></th>
						<th><?php echo Translation::get('content.size') ?></th>
						<th><?php echo Translation::get('content.date') ?></th>
						<th><?php echo Translation::get('content.actions') ?></th>
					</tr>
				</thead>

				<!-- FOLDERS -->
				<?php foreach ($folders as $folder) { ?>
				<tr class="folder">
					<td class="icon">
						<span class="glyphicon <?php echo $folder->getGlyphicon() ?>"></span>
					</td>
					<td>
						<a href="<?php echo Application::build()->getChangeFolderUrl($folder) ?>">
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
						<a href="<?php echo Application::build()->getChangeFolderUrl($folder) ?>" title="<?php echo Translation::get('content.openFolder') ?>">
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
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>">
							<?php echo $file->getName() ?>
						</a>
					</td>
					<td>
						<span class="label label-info <?php echo $file->getExtension() != null ? $file->getExtension() : "noext" ?>">
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
						<a href="<?php echo $file->getUrl() ?>" target="_<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.openFile') ?>">
							<span class="glyphicon glyphicon-new-window"></span></a>
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.saveFile') ?>">
							<span class="glyphicon glyphicon-save"></span></a>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>

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
						<a href="<?php echo Application::build()->getChangeFolderUrl($folder) ?>">
							<?php echo $folder->getDisplayName() ?>
						</a><br />
						<p><?php echo FileSystem::convertSize($folder->getSize()) ?></p>
						<p><?php echo $folder->getDate() ?></p>
						<a href="<?php echo Application::build()->getChangeFolderUrl($folder) ?>" title="<?php echo Translation::get('content.openFolder') ?>">
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
						<span class="label label-info <?php echo $file->getExtension() != null ? $file->getExtension() : "noext" ?>">
							<?php echo $file->getExtension() ?>
						</span>
					</div>
					<div class="info col-md-offset-3">
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>">
							<?php echo $file->getName() ?>
						</a><br />
						<p><?php echo FileSystem::convertSize($file->getSize()) ?></p>
						<p><?php echo $file->getDate() ?></p>
						<a href="<?php echo $file->getUrl() ?>" target="_<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.openFile') ?>"
						    data-toggle="tooltip" data-placement="right">
							<span class="glyphicon glyphicon-new-window"></span></a>
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.saveFile') ?>">
							<span class="glyphicon glyphicon-save"></span></a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>

		<?php } ?>

		<?php if (count($folders) === 0 && count($files) === 0) { ?>
		<!-- EMPTY CONTENT MESSAGE -->
		<div id="noContent" class="row">
			<div class="col-md-12">
				<div class="alert alert-warning">
					<?php echo Translation::get('content.noContent') ?>
				</div>
			</div>
			<div class="col-md-offset-11">
				<a class="btn btn-default" href="#" onclick="javascript:window.history.back();">
					<span class="glyphicon glyphicon-arrow-left"></span>
					<?php echo Translation::get('menu.back') ?>
				</a>
			</div>
		</div>
		<?php } ?>

		<!-- BOTTOM STATE BAR -->
		<div id="statebarBottom" class="row bg-primary">
			<div class="col-md-12">
				<span class="glyphicon glyphicon-stats"></span>
				<?php echo $currentFolder->getFolderChildrenCount() . Translation::get('statebar.folders') ?>
				-
				<?php echo $currentFolder->getFileChildrenCount() . Translation::get('statebar.files') ?>
				-
				<?php echo FileSystem::convertSize($currentFolder->getChildrenSize()) ?>
			</div>
		</div>
	</div>

	<!-- FOOTER -->
	<div id="footer">
		<?php echo Application::build()->getFooter() ?>
	</div>

	<!-- Folder tree modal -->
	<div class="modal fade" id="folderTreeModal" tabindex="-1" role="dialog" aria-labelledby="folderTreeModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php echo Translation::get('modal.tree.title') ?></h4>
				</div>
				<div class="modal-body" style="max-height: 400px; overflow-y: scroll">

					<?php
					/**
					 *
					 * @param Folder $folder
					 * @param number $level
					 */
					function displayFolders(Folder $folder, $level = 0) {
						if (isset($folder) && $folder->getName() !== FileSystem::PARENT_FOLDER) {
							echo "<a href=\"" . Application::build()->getChangeFolderUrl($folder) . "\" class=\"list-group-item\" style=\"padding-left: " . (20 + $level * 20) . "px\">";
							echo "<span class=\"glyphicon " . $folder->getGlyphicon() . "\"></span> " . $folder->getName() . "</a>";
							if ($folder->getChildrenCount() > 0) {
								foreach ($folder->getFolderChildren() as $child) {
									displayFolders($child, $level + 1);
								}
							}
						}
					}

					displayFolders(FileSystem::getRootFolder());
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Translation::get('modal.close') ?></button>
				</div>
			</div>
		</div>
	</div>

</body>

</html>

<?php Application::build()->postLoad() ?>
