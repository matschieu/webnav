<?php

/**
 *
 * @author Matschieu
 */
class DisplayHelper {

	public static $element = 0;

	/**
	 *
	 * @return string
	 */
	public static function getRowOpening(): string {
		if (self::$element % 6 === 0) {
			return '<div class="row mt-3 mb-3">'.PHP_EOL;
		}
		return "";
	}

	/**
	 *
	 * @return string
	 */
	public static function getRowClosing(): string {
		if (++self::$element % 6 === 0) {
			return '</div>'.PHP_EOL;
		}
		return "";
	}

	/**
	 *
	 * @return string
	 */
	public static function getLastRowClosing(): string {
		if ((self::$element - 1) % 6 !== 0) {
			return '</div>'.PHP_EOL;
		}
		return "";
	}

	/**
	 *
	 * @param FileViewerApplication $app
	 * @param Folder $folder
	 * @param number $level
	 * @return string
	 */
	public static function getFolderList(FileViewerApplication $app, Folder $folder, int $level = 0): string {
		$html = "";

		if (isset($folder) && $folder->getName() !== FileSystem::PARENT_FOLDER) {
			$html .= "<a href=\"" . $app->getChangeFolderUrl($folder) . "\" class=\"list-group-item\" style=\"padding-left: " . (20 + $level * 20) . "px\">".PHP_EOL;
			$html .= "<span class=\"oi " . $folder->getGlyphicon() . "\"></span> " . $folder->getName() . "</a>".PHP_EOL;

			if ($folder->getChildrenCount() > 0) {
				foreach ($folder->getFolderChildren() as $child) {
					$html .= self::getFolderList($app, $child, $level + 1);
				}
			}
		}

		return $html;
	}


}
