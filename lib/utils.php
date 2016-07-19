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

		$counterId = Option::get("rodzeta.yandexmetricgoals", "yandex_metrika_id");
		$targets = array();
		$i = 0;
		while (($row = fgetcsv($fcsv, 4000, "\t")) !== FALSE) {
			$i++;
			if ($i == 1) {
				continue;
			}
			$targets[] = '
				BX.bind(
					document.querySelector("' . addslashes(trim($row[0])) . '"),
					"' . trim($row[2]) . '",
					function () {
						yaCounter' . trim($counterId) . '.reachGoal("' . trim($row[1]) . '");
					}
				);
			';
		}
		fclose($fcsv);

		file_put_contents(
			$basePath . self::CACHE_NAME,
			'BX.ready(function () { ' . implode("\n", $targets)	. ' });'
		);
	}

	static function clearCache() {
		if (file_exists($_SERVER["DOCUMENT_ROOT"] . self::CACHE_NAME)) {
			unlink($_SERVER["DOCUMENT_ROOT"] . self::CACHE_NAME);
		}
	}

}
