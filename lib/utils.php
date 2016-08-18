<?php
/***********************************************************************************************
 * rodzeta.yandexmetricgoals - Yandex Metrika targets assignements
 * Copyright 2016 Semenov Roman
 * MIT License
 ************************************************************************************************/

namespace Rodzeta\Yandexmetricgoals;

use \Bitrix\Main\Config\Option;

final class Utils {

	const CACHE_NAME = "/upload/cache.rodzeta.yandexmetricgoals.js";
	const SRC_NAME = "/upload/rodzeta.yandexmetricgoals.csv";

	static function createCache() {
		$basePath = $_SERVER["DOCUMENT_ROOT"];
		if (!file_exists($basePath . self::SRC_NAME)) {
			return;
		}
		$fcsv = fopen($basePath . self::SRC_NAME, "r");
		if ($fcsv === FALSE) {
			return;
		}

		$counterId = trim(Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_id"));
		$counterIdGoogleAnalytics = trim(Option::get("rodzeta.yandexmetricgoals", "google_analytics_id"));
		$targets = array();
		$i = 0;
		while (($row = fgetcsv($fcsv, 4000, "\t")) !== FALSE) {
			$i++;
			if ($i == 1) {
				continue;
			}
			if ($counterId != "" || $counterIdGoogleAnalytics != "") {
				$targets[] = '
					BX.bind(
						document.querySelector("' . addslashes(trim($row[0])) . '"),
						"' . trim($row[2]) . '",
						function () {
							' . ($counterId != ""? ('yaCounter' . $counterId . '.reachGoal("' . trim($row[1]) . '");') : "") . '
							' . ($counterIdGoogleAnalytics != ""? ('ga("send", "event", "' . trim($row[3]) . '", "' . trim($row[4]) . '");') : "") . '
						}
					);
				';
			}
		}
		fclose($fcsv);

		file_put_contents(
			$basePath . self::CACHE_NAME,
			count($targets)?
				('BX.ready(function () { ' . implode("\n", $targets)	. ' });') : ""
		);
	}

	static function clearCache() {
		if (file_exists($_SERVER["DOCUMENT_ROOT"] . self::CACHE_NAME)) {
			unlink($_SERVER["DOCUMENT_ROOT"] . self::CACHE_NAME);
		}
	}

}
