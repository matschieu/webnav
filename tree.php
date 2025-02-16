<?php

use core\FileViewerApplication;
use core\FileSystem;

require_once("./core/autoload.php");

$app = FileViewerApplication::build();

?>

<?php foreach(FileSystem::getFlatTree($app->getRootFolder()) as $treeElement) { ?>
<a href="<?php echo $app->getUrlWithFolder($treeElement->getFolder()) ?>" class="list-group-item" style="padding-left: <?php echo (20 + $treeElement->getLevel() * 20) ?>px">
<span class="tree-folder-icon <?php echo $treeElement->getFolder()->getGlyphicon() ?>"></span> <?php echo $treeElement->getFolder()->getName() ?></a>
<?php } ?>
