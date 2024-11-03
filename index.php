<?php

use core\FileViewerApplication;
use core\FileSystem;
use core\Translation;

require_once("./core/autoload.php");

$app = FileViewerApplication::build();

$currentFolder = $app->getCurrentFolder();
$folders = $currentFolder->getFolderChildren();
$files = $currentFolder->getFileChildren();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
	<title><?php echo $app->getName() ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Author" content="Matschieu" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="robots" content="noindex" />
	<link rel="icon" type="image/png" href="<?php echo $app->getFavicon() ?>" />
	<!--Bootstrap -->
	<link rel="stylesheet" type="text/css" href="./lib/bootstrap-5.2.3-dist/css/bootstrap.min.css" />
	<script src="./lib/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
	<!-- Glyphicons -->
	<!-- Icons list: https://fontawesome.com -->
	<link rel="stylesheet" type="text/css" href="./lib/fontawesome-6.6.0/css/fontawesome.min.css" />
	<link rel="stylesheet" type="text/css" href="./lib/fontawesome-6.6.0/css/solid.min.css" />
	<link rel="stylesheet" type="text/css" href="./lib/fontawesome-6.6.0/css/brands.min.css" />
	<!-- Country flags -->
	<link rel="stylesheet" type="text/css" href="./lib/flag-icons/flag-icons.min.css">
	<!-- Application styles -->
	<link rel="stylesheet" type="text/css" href="./styles/default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $app->getCustomCss() ?>" media="screen" />
	<script type="text/javascript">
		function filter(value = "") {
			var elements = document.getElementsByClassName("filename");
			console.log(elements);
			for (let element of elements) {
				var folder = element.closest(".folder");
				var file = element.closest(".file");
				var parent = null;

				if (folder != null) {
					parent = folder;
				} else if (file != null) {
					parent = file;
				} else {
					return;
				}

				parent.style.display = !element.innerText.includes(value) ? "none" : "";
			};
		}
		fetch('<?php echo $app->getUrl("tree.php") ?>')
			.then(response => response.text())
			.then(html => document.getElementById('folderTreeModalContent').innerHTML = html)
			.catch(error => console.error('Error:', error));
	</script>
</head>

<body>
	<div id="top" class="sticky-top bg-white">
		<!-- HEADER -->
		<div id="header">
			<?php include $app->getHeader() ?>
		</div>

		<!-- MENU -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container-fluid">
				<a class="navbar-brand" href="<?php echo $app->getRootUrl() ?>" >
					<span class="fa-solid fa-house"></span>
					<?php echo Translation::get('menu.root') ?>
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">>
					<ul class="navbar-nav me-auto">
						<li class="nav-item">
							<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#folderTreeModal">
								<span class="fa-solid fa-folder-tree"></span>
								<?php echo Translation::get('menu.folderTree') ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo $app->getRefreshUrl() ?>">
								<span class="fa-solid fa-rotate-right"></span>
								<?php echo Translation::get('menu.refresh') ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#" onclick="javascript:window.history.back();">
								<span class="fa-solid fa-left-long"></span>
								<?php echo Translation::get('menu.back') ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#" onclick="javascript:window.history.forward();">
								<span class="fa-solid fa-right-long"></span>
								<?php echo Translation::get('menu.next') ?>
							</a>
						</li>
						<li class="nav-item">
							<?php if ($app->getAppContext()->getShowHidden()) { ?>
							<a class="nav-link" href="<?php echo $app->getShowHiddenUrl(false) ?>">
								<span class="fa-solid fa-lock"></span>
								<?php echo Translation::get('menu.hideHiddenFiles') ?>
							</a>
							<?php } else {?>
							<a class="nav-link" href="<?php echo $app->getShowHiddenUrl(true) ?>">
								<span class="fa-solid fa-lock-open"></span>
								<?php echo Translation::get('menu.showHiddenFiles') ?>
							</a>
							<?php } ?>
						</li>
						<li class="nav-item">
							<?php if ($app->getAppContext()->getDisplayList()) { ?>
							<a class="nav-link" href="<?php echo $app->getDisplayBlockUrl() ?>">
								<span class="fa-solid fa-table-cells"></span>
								<?php echo Translation::get('menu.gridView') ?>
							</a>
							<?php } else {?>
							<a class="nav-link" href="<?php echo $app->getDisplayListUrl() ?>">
								<span class="fa-solid fa-list"></span>
								<?php echo Translation::get('menu.listView') ?>
							</a>
							<?php } ?>
						</li>
					</ul>
					<ul class="navbar-nav ms-auto">
						<form class="d-flex">
							<input id="filterfield" class="form-control bg-secondary text-white me-2" type="search" placeholder="<?php echo Translation::get('menu.filter') ?>" aria-label="Search" onkeyup="javascript:filter(this.value)" />
							<button class="btn btn-secondary" onclick="javascript:filter(); return false;"><?php echo Translation::get('menu.reset') ?></button>
						</form>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" class="dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<span class="fa-solid fa-flag"></span>
								<?php echo Translation::get('menu.language') ?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
								<li>
									<a class="dropdown-item" href="<?php echo $app->getChangeLanguageUrl("en") ?>">
										<i class="fi fi-gb"></i>
										<?php echo Translation::get('menu.english') ?>
										<?php if ($app->isSelectedLanguage("en")) { ?>
										<span class="fa-solid fa-check"></span>
										<?php } ?>
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?php echo $app->getChangeLanguageUrl("fr") ?>">
										<i class="fi fi-fr"></i>
										<?php echo Translation::get('menu.french') ?>
										<?php if ($app->isSelectedLanguage("fr")) { ?>
										<span class="fa-solid fa-check"></span>
										<?php } ?>
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?php echo $app->getChangeLanguageUrl("de") ?>">
										<i class="fi fi-de"></i>
										<?php echo Translation::get('menu.german') ?>
										<?php if ($app->isSelectedLanguage("de")) { ?>
										<span class="fa-solid fa-check"></span>
										<?php } ?>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#" onclick="javascript:open(location, '_self').close(); return true;">
								<span class="fa-solid fa-xmark"></span>
								<?php echo Translation::get('menu.close') ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- TOP STATE BAR -->
		<div id="statebarTop" class="bg-primary p-1 text-white">
			<span class="fa-solid fa-folder"></span>
			<?php echo Translation::get('statebar.navigation') ?>
			<?php echo $currentFolder->getLogicalPath() ?>
		</div>
	</div>

	<!-- FILE EXPLORER CONTENT -->
	<div id="content" class="container-fluid py-4 bg-white">
		<?php if ($app->getAppContext()->getDisplayList()) { ?>

		<!-- LIST VIEW -->
		<div id="list">
			<table class="table table-striped align-middle">
				<thead>
					<tr>
						<th></th>
						<th><?php echo Translation::get('content.name') ?></th>
						<th><?php echo Translation::get('content.type') ?></th>
						<th><?php echo Translation::get('content.size') ?></th>
						<th><?php echo Translation::get('content.date') ?></th>
					</tr>
				</thead>

				<!-- FOLDERS -->
				<?php foreach ($folders as $folder) { ?>
				<tr class="folder text-break">
					<td class="icon text-primary">
						<span class="<?php echo $folder->getGlyphicon() ?>"></span>
					</td>
					<td>
						<a href="<?php echo $app->getChangeFolderUrl($folder) ?>" title="<?php echo Translation::get('content.openFolder') ?>">
							<span class="filename"><?php echo $folder->getDisplayName() ?></span>
							<span class="fa-solid fa-right-to-bracket p-2"></span>
						</a>
					</td>
					<td></td>
					<td>
						<?php echo FileSystem::convertSize($folder->getSize()) ?>
					</td>
					<td>
						<?php echo $folder->getDate() ?>
					</td>
				</tr>
				<?php } ?>

				<!--FILES -->
				<?php foreach ($files as $file) { ?>
				<tr class="file text-break">
					<?php if (!$file->isImage()) { ?>
					<td class="icon text-primary">
						<span class="<?php echo $file->getGlyphicon() ?>"></span>
					</td>
					<?php } else { ?>
					<td class="preview text-primary">
						<img src="<?php echo $file->getUrl() ?>" class="logo img-fluid" />
					</td>
					<?php } ?>
					<td>
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.openFile') ?>">
							<span class="filename"><?php echo $file->getName() ?></span>
							<span class="fa-solid fa-up-right-from-square p-2"></span>
						</a>
					</td>
					<td>
						<div class="badge bg-primary <?php echo $file->getExtension() != null ? $file->getExtension() : "noext" ?>">
							<?php echo $file->getExtension() ?>
						</div>
					</td>
					<td>
						<?php echo FileSystem::convertSize($file->getSize()) ?>
					</td>
					<td>
						<?php echo $file->getDate() ?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>

		<?php } else { ?>

		<!-- BLOCK VIEW -->
		<div id="list">
			<!-- FOLDERS -->
			<div class="row">
				<?php foreach ($folders as $folder) { ?>
				<div class="folder col-md-2">
					<div class="row">
						<div class="type col-md-3 text-primary text-center">
							<div class="icon">
								<span class="<?php echo $folder->getGlyphicon() ?>"></span>
							</div>
						</div>
						<div class="info col-md-9 text-break">
							<a href="<?php echo $app->getChangeFolderUrl($folder) ?>" title="<?php echo Translation::get('content.openFolder') ?>">
								<span class="filename"><?php echo $folder->getDisplayName() ?></span>
								<span class="fa-solid fa-right-to-bracket p-2"></span>
							</a><br />
							<div><?php echo FileSystem::convertSize($folder->getSize()) ?></div>
							<div><?php echo $folder->getDate() ?></div>
						</div>
					</div>
				</div>
				<?php } ?>

				<!--FILES -->
				<?php foreach ($files as $file) { ?>
				<div class="file col-md-2">
					<div class="row">
						<div class="type col-md-3 text-primary text-center">
							<?php if (!$file->isImage()) { ?>
							<div class="icon">
								<span class="<?php echo $file->getGlyphicon() ?>"></span>
							</div>
							<?php } else { ?>
							<div class="preview">
								<img src="<?php echo $file->getUrl() ?>" class="logo img-fluid" />
							</div>
							<?php } ?>
							<div class="badge bg-primary mt-2 <?php echo $file->getExtension() != null ? $file->getExtension() : "noext" ?>">
								<?php echo $file->getExtension() ?>
							</div>
						</div>
						<div class="info col-md-9 text-break">
							<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.openFile') ?>">
								<span class="filename"><?php echo $file->getName() ?></span>
								<span class="fa-solid fa-up-right-from-square p-2"></span>
							</a><br />
							<div><?php echo FileSystem::convertSize($file->getSize()) ?></div>
							<div><?php echo $file->getDate() ?></div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>

		<?php } ?>

		<?php if (count($folders) === 0 && count($files) === 0) { ?>
		<!-- EMPTY CONTENT MESSAGE -->
		<div id="noContent" class="row mt-3 ps-2 pe-2">
			<div class="col-md-12">
				<div class="alert alert-primar class="logo img-fluid"y" role="alert">
					<?php echo Translation::get('content.noContent') ?>
				</div>
			</div>
			<div class="col-md-offset-11">
				<a class="btn btn-primary" href="#" onclick="javascript:window.history.back();">
					<span class="fa-solid fa-left-long"></span>
					<?php echo Translation::get('menu.back') ?>
				</a>
			</div>
		</div>
		<?php } ?>

	</div>

	<div id="bottom" class="sticky-bottom bg-white text-primary">
		<!-- BOTTOM STATE BAR -->
		<div id="statebarBottom" class="bg-primary p-1 text-white">
			<span class="fa-solid fa-chart-simple"></span>
			<?php echo $currentFolder->getFolderChildrenCount() ?>
			<?php echo Translation::get('statebar.folders') ?>
			-
			<?php echo $currentFolder->getFileChildrenCount() ?>
			<?php echo Translation::get('statebar.files') ?>
			-
			<?php echo FileSystem::convertSize($currentFolder->getChildrenSize()) ?>
		</div>
		<!-- FOOTER -->
		<div id="footer">
			<?php include $app->getFooter() ?>
		</div>
	</div>

	<!-- Folder tree modal -->
	<div class="modal fade" id="folderTreeModal" tabindex="-1" role="dialog" aria-labelledby="folderTreeModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="myModalLabel"><?php echo Translation::get('modal.tree.title') ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="folderTreeModalContent" class="modal-body" style="max-height: 400px; overflow-y: scroll">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden"><?php echo Translation::get('modal.tree.title') ?></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo Translation::get('modal.close') ?></button>
				</div>
			</div>
		</div>
	</div>
</body>

</html>

<?php $app->postLoad() ?>
