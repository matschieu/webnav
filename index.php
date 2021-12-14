<?php
require_once("./core/FileViewerApplication.php");

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
	<link rel="icon" type="image/png" href="img/favicon.png" />
	<link rel="icon" type="image/png" href="<?php echo $app->getFavicon() ?>" />
	<!--Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<!-- Glyphicons -->
	<!-- Icons list: https://useiconic.com/open -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha512-UyNhw5RNpQaCai2EdC+Js0QL4RlVmiq41DkmCJsRV3ZxipG2L0HhTqIf/H9Hp8ez2EnFlkBnjRGJU2stW3Lj+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- Application styles -->
	<link rel="stylesheet" type="text/css" href="./styles/default.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo $app->getCustomCss() ?>" media="screen" />
</head>

<body>
	<!-- HEADER -->
	<div id="header" class="p-1">
		<?php echo $app->getHeader() ?>
	</div>

	<!-- MENU -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="<?php echo $app->getRootUrl() ?>" >
				<span class="oi oi-home"></span>
				<?php echo Translation::get('menu.root') ?>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">>
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#folderTreeModal">
							<span class="oi oi-project"></span>
							<?php echo Translation::get('menu.folderTree') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo $app->getRefreshUrl() ?>">
							<span class="oi oi-reload"></span>
							<?php echo Translation::get('menu.refresh') ?>
						</a>
					</li>
					<!--
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="javascript:window.history.back();">
							<span class="oi oi-arrow-thick-left"></span>
							<?php echo Translation::get('menu.back') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="javascript:window.history.forward();">
							<span class="oi oi-arrow-thick-right"></span>
							<?php echo Translation::get('menu.next') ?>
						</a>
					</li>
					-->
					<li class="nav-item">
						<?php if ($app->getAppContext()->getShowHidden()) { ?>
						<a class="nav-link" href="<?php echo $app->getShowHiddenUrl(false) ?>">
							<span class="oi oi-lock-locked"></span>
							<?php echo Translation::get('menu.hideHiddenFiles') ?>
						</a>
						<?php } else {?>
						<a class="nav-link" href="<?php echo $app->getShowHiddenUrl(true) ?>">
							<span class="oi oi-lock-unlocked"></span>
							<?php echo Translation::get('menu.showHiddenFiles') ?>
						</a>
						<?php } ?>
					</li>
					<li class="nav-item">
						<?php if ($app->getAppContext()->getDisplayList()) { ?>
						<a class="nav-link" href="<?php echo $app->getDisplayBlockUrl() ?>">
							<span class="oi oi-grid-three-up"></span>
							<?php echo Translation::get('menu.blockView') ?>
						</a>
						<?php } else {?>
						<a class="nav-link" href="<?php echo $app->getDisplayListUrl() ?>">
							<span class="oi oi-list"></span>
							<?php echo Translation::get('menu.listView') ?>
						</a>
						<?php } ?>
					</li>
				</ul>
				<ul class="navbar-nav ms-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" class="dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<span class="oi oi-flag"></span>
							Language
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li>
								<a class="dropdown-item" href="<?php echo $app->getChangeLanguageUrl("en") ?>">
									<?php echo Translation::get('menu.english') ?>
									<?php if ($app->isSelectedLanguage("en")) { ?>
									<span class="oi oi-check"></span>
									<?php } ?>
								</a>
							</li>
							<li>
								<a class="dropdown-item" href="<?php echo $app->getChangeLanguageUrl("fr") ?>">
									<?php echo Translation::get('menu.french') ?>
									<?php if ($app->isSelectedLanguage("fr")) { ?>
									<span class="oi oi-check"></span>
									<?php } ?>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" onclick="javascript:open(location, '_self').close(); return true;">
							<span class="oi oi-x"></span>
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
		<div id="statebarTop" class="row bg-primary mb-4 p-1 text-white">
			<div class="col-md-12">
				<span class="oi oi-folder"></span>
				<?php echo Translation::get('statebar.navigation') . $currentFolder->getLogicalPath() ?>
			</div>
		</div>

		<?php if ($app->getAppContext()->getDisplayList()) { ?>

		<!-- LIST VIEW -->
		<div id="list">
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th><?php echo Translation::get('content.type') ?></th>
						<th><?php echo Translation::get('content.name') ?></th>
						<th><?php echo Translation::get('content.size') ?></th>
						<th><?php echo Translation::get('content.date') ?></th>
						<th><?php echo Translation::get('content.actions') ?></th>
					</tr>
				</thead>

				<!-- FOLDERS -->
				<?php foreach ($folders as $folder) { ?>
				<tr class="folder text-break">
					<td class="icon text-primary">
						<span class="oi <?php echo $folder->getGlyphicon() ?>"></span>
					</td>
					<td></td>
					<td>
						<a href="<?php echo $app->getChangeFolderUrl($folder) ?>">
							<?php echo $folder->getDisplayName() ?>
						</a>
					</td>
					<td>
						<?php echo FileSystem::convertSize($folder->getSize()) ?>
					</td>
					<td>
						<?php echo $folder->getDate() ?>
					</td>
					<td>
						<a href="<?php echo $app->getChangeFolderUrl($folder) ?>" title="<?php echo Translation::get('content.openFolder') ?>">
								<span class="oi oi-account-login p-2"></span></a>
					</td>
				</tr>
				<?php } ?>

				<!--FILES -->
				<?php foreach ($files as $file) { ?>
				<tr class="file text-break">
					<td class="icon text-primary">
						<span class="oi <?php echo $file->getGlyphicon() ?>"></span>
					</td>
					<td>
						<span class="badge bg-primary <?php echo $file->getExtension() != null ? $file->getExtension() : "noext" ?>">
							<?php echo $file->getExtension() ?>
						</span>
					</td>
					<td>
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>">
							<?php echo $file->getName() ?>
						</a>
					</td>
					<td>
						<?php echo FileSystem::convertSize($file->getSize()) ?>
					</td>
					<td>
						<?php echo $file->getDate() ?>
					</td>
					<td>
						<a href="<?php echo $file->getUrl() ?>" target="_<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.openFile') ?>">
							<span class="oi oi-external-link p-2"></span></a>
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.saveFile') ?>">
							<span class="oi oi-data-transfer-download p-2"></span></a>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>

		<?php } else { ?>

		<!-- BLOCK VIEW -->
		<div id="list">
			<!-- FOLDERS -->
			<?php foreach ($folders as $folder) { ?>
			<?php echo DisplayHelper::getRowOpening(); ?>
			<div class="folder col-md-2">
				<div class="row">
					<div class="type col-md-2 text-primary">
						<div class="icon">
							<span class="oi <?php echo $folder->getGlyphicon() ?>"></span>
						</div>
					</div>
					<div class="info col-md-10 text-break">
						<a href="<?php echo $app->getChangeFolderUrl($folder) ?>">
							<?php echo $folder->getDisplayName() ?>
						</a><br />
						<div><?php echo FileSystem::convertSize($folder->getSize()) ?></div>
						<div><?php echo $folder->getDate() ?></div>
						<a href="<?php echo $app->getChangeFolderUrl($folder) ?>" title="<?php echo Translation::get('content.openFolder') ?>">
							<span class="oi oi-account-login p-2"></span></a>
					</div>
				</div>
			</div>
			<?php echo DisplayHelper::getRowClosing(); ?>
			<?php } ?>

			<!--FILES -->
			<?php foreach ($files as $file) { ?>
			<?php echo DisplayHelper::getRowOpening(); ?>
			<div class="file col-md-2">
				<div class="row">
					<div class="type col-md-2 text-primary">
						<div class="icon">
							<span class="oi <?php echo $file->getGlyphicon() ?>"></span>
						</div>
						<span class="badge bg-primary mt-2 <?php echo $file->getExtension() != null ? $file->getExtension() : "noext" ?>">
							<?php echo $file->getExtension() ?>
						</span>
					</div>
					<div class="info col-md-10 text-break">
						<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>">
							<?php echo $file->getName() ?>
						</a><br />
						<div><?php echo FileSystem::convertSize($file->getSize()) ?></div>
						<div><?php echo $file->getDate() ?></div>
						<div>
							<a href="<?php echo $file->getUrl() ?>" target="_<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.openFile') ?>">
								<span class="oi oi-external-link p-2"></span></a>
							<a href="<?php echo $file->getUrl() ?>" download="<?php echo $file->getName() ?>" title="<?php echo Translation::get('content.saveFile') ?>">
								<span class="oi oi-data-transfer-download p-2"></span></a>
						</div>
					</div>
				</div>
			</div>
			<?php echo DisplayHelper::getRowClosing(); ?>
			<?php } ?>
			<?php echo DisplayHelper::getLastRowClosing(); ?>
		</div>

		<?php } ?>

		<?php if (count($folders) === 0 && count($files) === 0) { ?>
		<!-- EMPTY CONTENT MESSAGE -->
		<div id="noContent" class="row mt-3 ps-2 pe-2">
			<div class="col-md-12">
				<div class="alert alert-primary" role="alert">
					<?php echo Translation::get('content.noContent') ?>
				</div>
			</div>
			<div class="col-md-offset-11">
				<a class="btn btn-primary" href="#" onclick="javascript:window.history.back();">
					<span class="oi oi-arrow-thick-left"></span>
					<?php echo Translation::get('menu.back') ?>
				</a>
			</div>
		</div>
		<?php } ?>

		<!-- BOTTOM STATE BAR -->
		<div id="statebarBottom" class="row bg-primary mt-4 p-1 text-white">
			<div class="col-md-12">
				<span class="oi oi-graph"></span>
				<?php echo $currentFolder->getFolderChildrenCount() . Translation::get('statebar.folders') ?>
				-
				<?php echo $currentFolder->getFileChildrenCount() . Translation::get('statebar.files') ?>
				-
				<?php echo FileSystem::convertSize($currentFolder->getChildrenSize()) ?>
			</div>
		</div>
	</div>

	<!-- FOOTER -->
	<div id="footer" class="text-end p-1">
		<?php echo $app->getFooter() ?>
	</div>

	<!-- Folder tree modal -->
	<div class="modal fade" id="folderTreeModal" tabindex="-1" role="dialog" aria-labelledby="folderTreeModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><?php echo Translation::get('modal.tree.title') ?></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" style="max-height: 400px; overflow-y: scroll">
					<?php foreach($app->getRootFolder()->getFlatTree() as $treeElement) { ?>
					<a href="<?php echo $app->getChangeFolderUrl($treeElement->getFolder()) ?>" class="list-group-item" style="padding-left: <?php echo (20 + $treeElement->getLevel() * 20) ?>px">
					<span class="oi <?php echo $treeElement->getFolder()->getGlyphicon() ?>"></span> <?php echo $treeElement->getFolder()->getName() ?></a>
					<?php } ?>
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